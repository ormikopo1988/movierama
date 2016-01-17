<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$obj = new stdObject();
$obj->status = 'OK';
$obj->errorNumber = '1000';
$obj->errorDescription = 'Success.';

function showErrorAndTerminate($errorNumber, $errorDescription)
{
	global $obj;
	
	$obj->status = 'Error';
	$obj->errorNumber = $errorNumber;
	$obj->errorDescription = $errorDescription;
	echo json_encode($obj);
	exit;
}

if (!isset($_POST['action']))
{
	showErrorAndTerminate('2001', 'No action requested.');
}elseif (
			$_POST['action'] == 'wsRead' || 
			$_POST['action'] == 'wsUpdate' || 
			$_POST['action'] == 'wsDelete' || 
			$_POST['action'] == 'wsInsert' &&
			(
				!isset($_POST['__address']) || 
				$_POST['__address']==''
			)
		)
{
	showErrorAndTerminate('2002', 'Address required to perform this specific action');
}elseif (
			(
				!isset($_POST['wsSessionIdentifier']) || 
				$_POST['wsSessionIdentifier']=''
			) &&
			$_POST['action']!='wsLogin'
		)
{
	showErrorAndTerminate('2003', 'Not valid session supplied.');
}

$requestedAction = 'viewUncontroled';
$pageLocation = '3_webService';

$wo = new WOOOF();

if ($_POST['action']=='wsLogin')
{
	$loginResult = FALSE;
	
	$rowForTest = $this->db->getRowByColumn('__users', 'loginName', $wo->cleanUserInput($_POST['username']));
	
	if (isset($rowForTest['id']))
	{
		$hash = $wo->getPasswordHash($_POST['password'], $rowForTest['id']);
		
		$result = $this->db->query('select * from __users where binary loginName=\''. $wo->cleanUserInput($rowForTest['loginName']) .'\' and binary loginPass=\''. $hash .'\'');
		if (mysqli_num_rows($result))
		{
			$userRow = $this->db->fetchAssoc($result);
			$userRow['loginPass']='not your business, really !';
			
			$goOn = FALSE;
			
			do
			{
				$sid = 'ws'. WOOOF::randomString(38);
				$new_sid_result=$this->db->query("select * from __sessions where sessionId='". $sid ."'");
				if (!mysqli_num_rows($new_sid_result)) $goOn = TRUE;
			}while (!$goOn);
			
			$result = $this->db->query("insert into __sessions (userId,sessionId,loginDateTime,lastAction,loginIP,active) values ('$uid','$sid','". $this->dateTime ."','". $this->dateTime ."','". $this->cleanUserInput($_SERVER["REMOTE_ADDR"]) ."','1')");
			
			if ($result===FALSE)
			{
				showErrorAndTerminate('2005', 'Failed to insert new session in the data base for user `'. $userData['loginName'] .'`. Potential security breach!');
			}
			
			$obj->wsSessionIdentifier = $sid;
			$loginResult = TRUE;	
		}
	}
	 
	if ($loginResult===FALSE)
	{
		showErrorAndTerminate('2004', 'Wrong credentials supplied.Login failure');
	}
	
}else
{//sessionId - userId - loginDateTime - loginIP - active - lastAction
	$sessionData = $wo->db->getRowByColumn('__sessions', 'sessionId', $_POST['wsSessionIdentifier']);
	if (isset($sessionData['sessionId']) && $sessionData['sessionId']==$_POST['wsSessionIdentifier'])
	{
		$wsUserData = $wo->db->getRow('__users', $sessionData['userId']);
		if (isset($wsUserData['id']))
		{
			$permitions = $wo->db->getSecurityPermitionsForLocationAndUser($_POST['__address'], $wsUserData['id']);
			if ($permitions[$_POST['action']]==TRUE)
			{
				if ($_POST['action']=='wsRead')
				{
				
				}elseif ($_POST['action']=='wsUpdate')
				{
						
				}elseif ($_POST['action']=='wsInsert')
				{
						
				}elseif ($_POST['action']=='wsDelete')
				{
						
				}
			}else
			{
				showErrorAndTerminate('2005', 'Security check failed. You probably don\'t have the needed permitions.');
			}
		}else 
		{
			showErrorAndTerminate('2003', 'Not valid session supplied.');
		}
	}else
	{
		showErrorAndTerminate('2003', 'Not valid session supplied.');
	}
}

json_encode($obj);
exit;
?>