<?php
	ob_start();
	chdir(dirname($_SERVER['SCRIPT_FILENAME']));
	session_start();

	include('config.php');

	$mysqli = new mysqli($config['server'], $config['username'], $config['password'], $config['database']);

	$pass = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_POST['pass'], ENT_NOQUOTES), ENT_NOQUOTES)));
	$numb = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_GET['numb'], ENT_NOQUOTES), ENT_NOQUOTES)));

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
		<table>
		<tr>
			<th>Keyword</th>
			<th>URL</th>
			<th>Title</th>
			<th>Timestap</th>
			<th>IP</th>
			<th>Clicks</th>
		</tr>
<?php
	if($result = $mysqli->query('SELECT * FROM yourls_url;')) {
		while($obj = $result->fetch_object()) {
?>
			<tr>
				<td><?php echo $obj->keyword; ?></td>
				<td><?php echo $obj->url; ?></td>
				<td><?php echo $obj->title; ?></td>
				<td><?php echo $obj->timestamp; ?></td>
				<td><?php echo $obj->ip; ?></td>
				<td><?php echo $obj->clicks; ?></td>
			</tr>
<?php
		}
	}
?>
		</table>
	</body>
</html>
<?php
	}
?>
