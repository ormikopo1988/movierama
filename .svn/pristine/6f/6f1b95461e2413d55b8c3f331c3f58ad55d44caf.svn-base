<?php
    // =================================================
    // Example AltoRouter index (front controller) file
    // =================================================

    require_once '../setup.inc.php';

    $wo = new WOOOF(true, null, null, false);
  	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
    $timers = array();
    
    /*
    spl_autoload_register(
      function ( $className ) 
      {
        global $wooofConfigOptions;
        $filename = $wooofConfigOptions['siteBasePath'] . 'classes/' . $className . '.php';
        if ( file_exists($filename) ) {
      	 require( $filename );
        }
      }
    );
    */

    // AltoRouter.php shoud be present in classes/ or wooof_classes/ or wooof_classes/thirdParty 
    $router = new AltoRouter();
    $router->setBasePath( substr( $wo->getConfigurationFor('siteBaseURL') . $wo->getConfigurationFor('publicSite'), 0, -1 ) );
    
    // should provide $wo
    $router->map('GET', '/form', function() use($wo) { 
      return RegistrationControllers::showForm($wo);
    });
    
    
    // but it's ok if not provided as well
    $router->map(
        'GET', 
        '/view/[:appId]/[:reason]', 
        function($appId, $reason) { 
            return RegistrationControllers::viewApplication($appId, $reason);
        }
        , 'viewApp'
    ); 

    // the start/home    
    $router->map(
      'GET', 
      '/', 
      function() use($wo) { 
          $wo->debug( 'Hello World');
          echo "Hello !!";
      }
      , 'home'
    ); 
    

    /*
    $router->get('GET', '/', function() use($app) { 
        $subRequest = Request::create('/form', 'GET');
        return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }); 
    
    */
    
    /*
    echo $router->generate('viewApp', array('appId' => 'fgder4rer', 'reason' => 'someAction') );
    //die();
    */
    
    /*
    $router->map('GET|POST','/', 'home#index', 'home');
    $router->map('GET','/users/', array('c' => 'UserController', 'a' => 'ListAction'));
    $router->map('GET','/users/[i:id]', 'users#show', 'users_show');
    $router->map('POST','/users/[i:id]/[delete|update:action]', 'usersController#doAction', 'users_do');
    */
    
    $router->run();
    ?>
    
    <!--
    <h1>AltoRouter</h1>
    
    <h3>Current request: </h3>
    <pre>
    	Target: <?php //var_dump($match['target']); ?>
    	Params: <?php //var_dump($match['params']); ?>
    	Name: 	<?php //var_dump($match['name']); ?>
    </pre>
    
    <h3>Try these requests: </h3>
    <p><a href="<?php //echo $router->generate('home'); ?>">GET <?php //echo $router->generate('home'); ?></a></p>
    <p><a href="<?php //echo $router->generate('users_show', array('id' => 5)); ?>">GET <?php //echo $router->generate('users_show', array('id' => 5)); ?></a></p>
    <p><form action="<?php //echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?>" method="post"><button type="submit"><?php //echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?></button></form></p>
    -->
