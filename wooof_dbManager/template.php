<?php 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($extraHeadScripts)) {  $extraHeadScripts='';
}

?><html>
    <head>
        <title>WOOOF DB Manager</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="css/dbManager.css"/>
		<script src="js/jquery-1.11.3.min.js"></script>
		<!--script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script-->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="css/bootstrap.3.3.4.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="css/bootstrap-theme.3.3.4.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="css/bootstrap.3.3.4.min.js"></script>
		<script src="js/dbManager.js"></script>
		<?php if ( isset($extraHeadScripts) ) { echo $extraHeadScripts; } ?>
	</head>
    <body bgcolor="#000000" class="normalTextWhite">
        Current dataBase: <span class="largeTextOrange">
        <?php echo $wo->db->getDataBaseName() . '@' . $wo->db->getDataBaseHost() . '</span>' . '&nbsp;&nbsp;MetaData Version: [' . WOOOF_MetaData::$version . ']' ; ?></span><br/>
    
    <?php //echo $content; ?>

	<?php 
  		if ( isset($tpl['contentTop']) and $tpl['contentTop'] != '' ) {
			echo $tpl['contentTop'];
  		}
  	?>
  	<?php 
  		if ( isset($tpl['errorMessage']) and $tpl['errorMessage'] != '' ) {
  			echo '<h3>Errors</h3><span style="font-size:14px;color:red;">' . $tpl['errorMessage'] . "</span><br><br>"; 
  		}
  	?>
  	<?php 
		if ( isset($tpl['message']) and $tpl['message'] != '' ) {
			echo '<h3>Messages</h3><span style="font-size:14px;color:green;">' . $tpl['message'] . "</span><br><br>"; 
  		}
  	?>
  
  	<?php 
		if ( isset($content) and $content != '' ) {
			echo $content; 
  		}
  ?>

    <script>
	    $( document ).ready(function() {
	    	$('[data-toggle="tooltip"]').tooltip();

			<?php if ( isset($extraDocReadyJS) ) { echo $extraDocReadyJS; } ?>
	    	
	    });
    </script>
  

</body>
</html><?php exit;?>