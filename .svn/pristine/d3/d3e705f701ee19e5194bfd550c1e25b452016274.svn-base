<?php 

	$code 		= ( isset($_GET['c']) ? $_GET['c'] : '404' );
	$message	= ( isset($_GET['m']) ? $_GET['m'] : 'Something is wrong...' );
	$uri		= ( isset($_GET['u']) ? $_GET['u'] : 'unknown uri...' );
	
	//var_dump($_SERVER);
	
	$tmp = $_SERVER['REQUEST_URI'];
	$home = mb_substr($tmp, 0, strpos($tmp,'error.php') );

?>

<h1>Something went wrong</h1>
<h2><?php echo $code;?></h2>
<h3><?php echo $message;?></h2>
<h3><?php echo $uri;?></h2>

<a href="<?php echo $home ?>">Goto Home Page</a>

<?php 
	exit();

/* End of error.php */