<?php

$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='viewUncontroled';
$pageLocation='3_logIn';
$pageTitle='Please LogIn';
//$availableForUnregistered='1';

$wo = new WOOOF();

if (isset($_GET['error']))
{
    if ($_GET['error']=='1')
    {
        $errorText='ERROR: Invalid credentials.';
    }
}else
{
    $errorText='<br/>';
}

$content = '<div class="span16">
            <h1>Login</h1>
            <hr/>
            <div class="alert alert-block alert-error fade in" style="width: 600px;  padding:5px 10px;">
                        <p style="text-align:center; font-size:16px;"><b>'. $errorText .'</b></p> 
                </div>
            
            <div class="well well-large" style="width: 600px; padding:10px;">
            <form method="POST" id="loginForm" action="doTheLogIn.php">
           <label style="font-size:18px; margin-bottom:5px;">Enter your credentials</label><br/>
                <label>UserName</label><br/>
                <input type="text" name="username" value="" autocomplete="no"><br/><br/>

                <label>PassWord</label><br/>
                <input type="password" name="password" value="" autocomplete="no">

                <br/>
                <input class="btn btn-medium" type="submit" name="submit" value="LogIn" ></form>

            </form>
            </div>
            </div>
            
';



require 'template.php';
?>