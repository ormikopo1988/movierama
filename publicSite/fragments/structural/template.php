<?php 
	global $timers, $userData, $availableForUnregistered, $extraHeadScripts;
	extract( $tpl, EXTR_PREFIX_ALL, '_tpl' );
	$isProductionEnv = $wo->isProductionEnv;
	wooofTimerStop('controller');
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $_tpl_browserTitle; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <!-- link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'-->
        
        <!-- include font awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        
        <link href="<?php echo $wo->assetsURL;?>css/fonts.css" rel="stylesheet">
        
        <!-- CSS -->
        
        <link rel="stylesheet" href="<?php echo $wo->assetsURL;?>css/movierama.css">
        
        <link rel="stylesheet" href="<?php echo $wo->assetsURL;?>css/react-select.css">

        <link rel="stylesheet" href="<?php echo $wo->assetsURL;?>css/react-datepicker.css">
        
        
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <?php //if (!isset($availableForUnregistered) ) { $availableForUnregistered = '0'; } ?>
        
        <?php if (isset($_tpl_extraHeadScripts)) echo $_tpl_extraHeadScripts; ?>
        
        <!-- include app's bundle (including react.js, ... ) -->
        <!-- For access from other machines/mobiles use a PROD config (preferably with minimal bundle.js)  -->
        <script type="text/javascript" src="<?php echo !$isProductionEnv ? 'http://localhost:8080/' : $wo->assetsURL.'js/bundle/'; ?>bundle.js"></script>
        <script>
            MOVIERAMA.globals.systemImgURL  = '<?php echo $wo->assetsURL."img/" ?>';
            MOVIERAMA.globals.imgURL        = '<?php echo $wo->imagesURL; ?>';
            MOVIERAMA.globals.assetsURL     = '<?php echo $wo->assetsURL; ?>';
            MOVIERAMA.globals.userId        = '<?php echo $wo->app->userId; ?>';
            MOVIERAMA.globals.startURL		= '<?php echo $wo->configuration['siteURLStart']; ?>';
        </script>
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>
	</head>
    <body>
        <div id="movierama-header"></div>
        <br/>
        
        <div id="top-messages" class="container" ></div>
        <br/>
        
        <div class="wrap"> 
            <div class="container-fluid mainSection">
            	<div class="row">
	                <div class="col-xs-12 col-sm-12 col-md-12">
		                <?php 
		                    if ( isset($_tpl_content) and $_tpl_content != '' ) 
		                    {
		                        echo $_tpl_content; 
		                    }
		                ?>
	                </div>
                </div>
            </div>  
        </div>

        <!--<div id="timers">
	        <?php 
	        	///*
	        	$_logLink = '<a href="'.$wo->assetsURL.'../wooof_administration/tailLog.php?session='.$wo->sid.'" target="_blank">Log</a>';
	        	echo "<pre><code>Timers $_logLink<br>";
	        	wooofTimersShow();
	        	echo "</code></pre>";
	        	//*/
			?>
        </div>-->
        
        
        <div id="movierama-footer"></div>
        
        <?php 
        	$userData = json_encode( VO_ProfileData::getMainInfo($wo, $wo->app->userId) );
        	$messages = json_encode(VO_SessionMessages::getMessages($wo));
        ?>
        
        <script>
        	ReactDOM.render(
            	React.createElement(
            		SiteHeaderBox, 
            		{ userData: <?php echo $userData ?> }
            	), 
            	document.getElementById('movierama-header')
            );

			ReactDOM.render(
				React.createElement(
					MessagesBoxComp, 
					{ allMessages:  <?php echo $messages; ?>}
				), 
				document.getElementById('top-messages')
			);
    				
			ReactDOM.render(
				React.createElement(
					SiteFooterBox, 
					{ userData: <?php echo $userData ?> }
				), 
				document.getElementById('movierama-footer')
			);
        </script>
    </body>
</html>
