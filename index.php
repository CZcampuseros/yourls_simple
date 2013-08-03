<?php
	ob_start();
	chdir(dirname($_SERVER['SCRIPT_FILENAME']));
	session_start();

	include('config.php');

	if ( $config['server'] && $config['username'] && $config['password'] && $config['database'] ) {
		$mysqli = new mysqli($config['server'], $config['username'], $config['password'], $config['database']);
	}

	$pass = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_POST['pass'], ENT_NOQUOTES), ENT_NOQUOTES)));
	$rows = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_GET['rows'], ENT_NOQUOTES), ENT_NOQUOTES)));
	if ( empty($rows) ) { $rows = 500; }

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
		<style>
			td { padding: 5px 10px; }
			h1 { margin: 0px 25px; }
			h1,form { display: inline-block; }
			.title { max-width: 150px; }
			tr:hover { background-color: #ffd; }
		</style>
	</head>
	<body>
		<h1>YOURLS simple</h1>
		<form method="get">
			<label for="rows">Number of rows: </label>
			<input type="text" id="rows" name="rows" size="12" maxlength="12" value="<?php echo $rows; ?>"></input>
			<input type="submit" value="Login"></input>
		</form>
		<table>
			<tr>
				<th class="keyword">Keyword</th>
				<th class="url">URL</th>
				<th class="title">Title</th>
				<th class="timestamp">Timestap</th>
				<th class="ip">IP</th>
				<th class="clicks">Clicks</th>
			</tr>
<?php
	if($result = $mysqli->query('SELECT * FROM yourls_url ORDER BY `yourls_url`.`timestamp` DESC LIMIT 0,'.$rows.';')) {
		while($obj = $result->fetch_object()) {
?>
			<tr>
				<td class="keyword"><?php echo $obj->keyword; ?></td>
				<td class="url"><?php echo $obj->url; ?></td>
				<td class="title"><?php echo $obj->title; ?></td>
				<td class="timestamp"><?php echo $obj->timestamp; ?></td>
				<td class="ip"><?php echo $obj->ip; ?></td>
				<td class="clicks"><?php echo $obj->clicks; ?></td>
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
