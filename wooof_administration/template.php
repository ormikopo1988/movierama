<?php 
require_once 'adminMenu.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>WOOOF / Administration of <?php echo $wo->getConfigurationFor('siteName'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400italic,700italic,400,700&subset=latin,greek-ext' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="ui-darkness/jquery-ui.css" />
      	<link rel="stylesheet" type="text/css" href="css/admin.css">
      	<script src="jquery-1.9.1.js"></script>
      	<script src="jquery-ui.js"></script>
      	<script src="jquery.bpopup.min.js"></script>
      	<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>

	      <script>
	       $(function() 
	       {
	            $("#navmenu-h li,#navmenu-v li").hover( function() { $(this).addClass("iehover"); }, function() { $(this).removeClass("iehover"); } );
	       });
	        function confirmDelete(url)
	        {
	          $('#titleModal').html('Πρόκειται να γίνει μη αναστρέψιμη διαγραφή!')
	          $('#textModal').html('Σίγουρα θέλετε να προχωρήσετε στην διαγραφή του αντικειμένου; Η κίνηση αυτή δεν αντιστρέφεται...');
	          window.confirmedURLToGo = url;
	          $('#modal').addClass('redBorder');
	          $('#modal').bPopup();
	        }
	        function closePopup()
	        {
	          $('#modal').bPopup().close();
	        }
	        function popUpConfirmed()
	        {
	          window.location = window.confirmedURLToGo;
	        }
	        <?php if(isset($extraScripts)) { echo $extraScripts; } ?>
	      </script>
    </head>
<body>

<footer id="version">WOOOF &nbsp; <?php echo $WOOOF_VERSION; ?> &nbsp; &copy; Ioannis Loukeris 1997 - <?php echo date('Y'); ?>, Antonis Papantonakis 2015  Licensed under GPL v2.0 </footer>

 <!--page-->
<div id="page">

    <!--header-->
    <div id="header">
        <h1>Administration of <?php echo $wo->getConfigurationFor('siteName'); ?></h1>
      <!--header-->
    </div>
            
<!--menuHolder-->
    <?php

    if (!isset($__showMenu) || $__showMenu != false)
    {
      echo '<div id="menuHolder">
          '. $menuOutput .'       
      <!--menuHolder-->
    </div>
';
    }?>
    
        
  <?php echo $content;?>
        
   
  
  <div id="emptyspace"></div>

<!--page-->
</div>
  <div id="modal" style="display: none;">
        
        <span class="titleModal" id="titleModal">Αν θες δίνε και Tίτλο Modal Window </span><br />
        <div id="textModal">Κείμενο μηνύματος βνα ωσ χψβ αξηβσω ξαηβσ χξηαβσ χξηαβχ ηξα κξασ κξαβν σ</div><br />
        <input class="modalButton" type="button" name="button" value="No" onClick="javascript:closePopup();"/>
        <input class="modalButton" type="button" name="button" value="Yes" onClick="javascript:popUpConfirmed();"/>        
     </div>
</body>
</html><?php exit;?>