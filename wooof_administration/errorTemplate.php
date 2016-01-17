<?php
$wo = $this;

$addressItems[0]='1';
$addressItems[1]='';

require_once 'adminMenu.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>WOOOF DB Manager</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="ui-darkness/jquery-ui.css" />
      <link rel="stylesheet" type="text/css" href="css/admin.css">
      <script src="jquery-1.9.1.js"></script>
      <script src="jquery-ui.js"></script>
      <script>
       $(function() 
       {
            $("#navmenu-h li,#navmenu-v li").hover( function() { $(this).addClass("iehover"); }, function() { $(this).removeClass("iehover"); } );
       });
      </script>
    </head>
<body>

<footer id="version">WOOOF &nbsp; <?php echo $GLOBALS['WOOOF_VERSION']; ?> &nbsp; &copy; Ioannis Loukeris 1997 - <?php echo date('Y'); ?></footer>

 <!--page-->
<div id="page">

    <!--header-->
    <div id="header">
        <h1>Administration of <?php echo $GLOBALS['siteName'];?></h1>
      <!--header-->
    </div>
            
<!--menuHolder-->
    <div id="menuHolder">
      <?php echo $menuOutput?>        
      <!--menuHolder-->
    </div>

<div id="deleteWarning">
  <p><?php echo $errorInput; ?></p>
            
            </div>

</body>
</html>
