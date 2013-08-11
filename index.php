<?php
	ob_start();
	chdir(dirname($_SERVER['SCRIPT_FILENAME']));
	session_start();

	include('config.php');

	if ( !empty($config['server']) && !empty($config['username']) && !empty($config['password']) && !empty($config['database']) ) {
		$mysqli = new mysqli($config['server'], $config['username'], $config['password'], $config['database']);

		$pass = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_POST['pass'], ENT_NOQUOTES), ENT_NOQUOTES)));
		$rows = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_GET['rows'], ENT_NOQUOTES), ENT_NOQUOTES)));
		$column = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_GET['column'], ENT_NOQUOTES), ENT_NOQUOTES)));
		$search = $mysqli->real_escape_string(trim(htmlspecialchars(htmlspecialchars_decode($_GET['search'], ENT_NOQUOTES), ENT_NOQUOTES)));

		if ( empty($column) ) { $column = 'url'; }
		if ( empty($rows) ) { $rows = 15; }
	}

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
			.url { max-width: 250px; overflow: auto; }
			.title { max-width: 150px; overflow: auto; }
			.ip { min-width: 110px; max-width: 125px; overflow: auto; }
			.keyword { min-width: 60px; }
			.timestamp { min-width: 150px; }
			tr:hover { background-color: #ffd; }
		</style>
	</head>
	<body>
		<h1>YOURLS simple</h1>
		<form method="get">
			<label for="rows">Rows: </label>
			<input type="text" id="rows" name="rows" size="6" maxlength="12" value="<?php echo $rows; ?>"></input>
			<label for="search">Search: </label>
			<input type="text" id="search" name="search" size="32" value="<?php echo $search; ?>"></input>
			<select name="column">
				<option value="url" <?php if ( $column == 'url' ) { echo 'selected="selected"'; } ?>>URL</option>
				<option value="keyword" <?php if ( $column == 'keyword' ) { echo 'selected="selected"'; } ?>>Keyword</option>
				<option value="title" <?php if ( $column == 'title' ) { echo 'selected="selected"'; } ?>>Title</option>
				<option value="ip" <?php if ( $column == 'ip' ) { echo 'selected="selected"'; } ?>>IP</option>
			</select>
			<input type="submit" value="OK"></input>
		</form>
		<form method="get">
			<input type="submit" value="Clean"></input>
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
	if ( !empty($search) ) {
		$where = 'WHERE `'.$column."` regexp '".$search."'";
	}
	if($result = $mysqli->query('SELECT * FROM '.$config['prefix'].'url '.$where.' ORDER BY `'.$config['prefix'].'url`.`timestamp` DESC LIMIT 0,'.$rows.';')) {
		while($obj = $result->fetch_object()) {
?>
			<tr>
				<td class="keyword"><?php echo $config['yourlsurl'].$obj->keyword; ?> (<a href="<?php echo $config['yourlsurl'].$obj->keyword; ?>">link</a>)</td>
				<td class="url"><?php echo $obj->url; ?> (<a href="<?php echo $obj->url; ?>">link</a>)</td>
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
