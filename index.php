<?php
	ob_start();
	chdir(dirname($_SERVER['SCRIPT_FILENAME']));
	session_start();

	$pass = trim(htmlspecialchars(htmlspecialchars_decode($_POST['pass'], ENT_NOQUOTES), ENT_NOQUOTES));
	$numb = trim(htmlspecialchars(htmlspecialchars_decode($_GET['numb'], ENT_NOQUOTES), ENT_NOQUOTES));

	include('config.php');

	$mysqli = new mysqli($config['server'], $config['username'], $config['password'], $config['database']);

	if ( empty($pass) && empty($_SESSION['login']) ) {
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex">
		<title>YOURLS simple - login</title>
	</head>
	<body>
		<h1>YOURLS simple - login</h1>
		<form method="post">
			<label for="pass">Password: </label>
			<input type="password" id="pass" name="pass" size="12" maxlength="12" ></input>
			<input type="submit" value="Login"></input></span>
		</form>
	</body>
</html>
<?php
	}
	if ( !empty($pass) ) {
		if ( md5($pass) == $config['htpass'] ) { $_SESSION['login'] = 'logged'; }
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: index.php");
		header("Connection: close");
		exit();
	}
	if ( !empty($_SESSION['login']) ) {
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex">
		<title>YOURLS simple</title>
	</head>
	<body>
		<h1>YOURLS simple</h1>
	</body>
</html>
<?php
	}
?>
