<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$__showMenu = false;
$requestedAction='viewUncontroled';
$pageLocation='3_logIn';
$pageTitle='Please LogIn';
$availableForUnregistered='1';

$wo = new WOOOF();

if (isset($_GET['error']))
{
    if ($_GET['error']=='1')
    {
    	/*
        $errorText='<div class="alert-error">
             <p>Λάθος κωδικός ή όνομα χρήστη. Παρακαλώ προσπαθήστε ξανά!</p> 
             </div>';
        */
        $errorText='<div class="alert-error">
             <p>Wrong credentials. Please try again!</p> 
             </div>';
    }
}else
{
    $errorText='<br/>';
}

$content = '<div class="welcome">
        
            <section class="credentials">
            
            <h1>WELCOME</h1>
            
            '. $errorText .'
            
            <div class="well">
            <form method="POST" id="loginForm" action="doTheLogIn.php">
            <label>Enter your credentials</label>
            
            <div class="insertion"><label>UserName</label></div>
            <div><input type="text" name="username" value="" autocomplete="no"></div>
            
            <div class="insertion"><label>PassWord</label></div>
            <div><input type="password" name="password" value="" autocomplete="no"></div>
            
            <div class="insertionbtn"><input  type="submit" name="submit" value="LogIn" ></div>
              </form>
            </div>
            
            </section>
            
            <!--welcome--> 
            </div>
';



require 'template.php';
?>