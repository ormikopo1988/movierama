<?php 
	global $wo, $tpl, $timers;
	//foreach($tpl as $aKey=>$aVal) { ${'_tpl_'.$aKey}= $aVal; }
	extract( $tpl, EXTR_PREFIX_ALL, '_tpl' );	//  an underscore is added at the end of prefix :-(
?>
<html>

<head>
        <meta charset="utf-8">
        <title><?php echo $_tpl_browserTitle ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Les styles -->
        <link rel="stylesheet" type="text/css" href="css/MyFontsWebfontsKit.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-fileupload.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap.file-input.js"></script>
        
</head>

<body>
	Generic
	<div class="wrap">
  		<div class="container">
  			<div class="span16">
  

  <?php 
  	if ( isset($_tpl_contentTop) and $_tpl_contentTop != '' ) {
		echo $_tpl_contentTop;
  	}
  ?>
  <?php 
  	if ( isset($_tpl_errorMessage) and $_tpl_errorMessage != '' ) {
  		echo '<h3>Errors</h3><span style="font-size:14px;color:red;">' . $_tpl_errorMessage . "</span><br><br>"; 
  	}
  ?>
  <?php 
	if ( isset($_tpl_message) and $_tpl_message != '' ) {
		echo '<h3>Messages</h3><span style="font-size:14px;color:green;">' . $_tpl_message . "</span><br><br>"; 
  	}
  ?>
  
  <?php 
	if ( isset($_tpl_content) and $_tpl_content != '' ) {
		echo $_tpl_content; 
  	}
  ?>

			</div>  
		</div>
	</div>
  
  <?php 
		// Show Timers
		// on screen and in debug file
		if ( !$wo->isProductionEnv ) {
			echo '<pre><code>TIMERS<br>';
			Timer::showTimers($timers);
			echo '</code></pre>';
		}
				
		$wo->debugTimers($timers);
  ?>
  
</body>

</html>
<?php exit; 