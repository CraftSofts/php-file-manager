<?php
require_once 'inc/core.php';
$url = $_SESSION['url'];
$msg = $_SESSION['msg'];
$file = 'inc/settings.dat';
if (!file_exists($file)) {
    // the file doesn't exists, redirect user to setup page. user must complete setup.
    header('Location: setup.php');
    exit();
}
// check if session already started or not
if (isset($_SESSION['fm_pass'])) {
	print_r($_SESSION);
	header('Location: index.php');
    exit(); // stop the code from executing further
}
// the file exists, we are good to go
$lines = file($file); // get lines from the setting file
//print_r($lines);
$password = trim($lines[0]); // 1st line is the password saved during setup
$user_pass = trim($_POST['password']);
if (!empty($_POST['password'])) {
    // if user tried to login
    if (!password_verify($user_pass,$password)) {
        // password is wrong
        $msg = '<div class="red-text"><i class="material-icons">error</i></span> The password is wrong. Please try again.</div></br/>';
    } else {
        //start secure session
        $_SESSION['fm_pass'] = $user_pass;
        unset($_SESSION['url']);
        unset($_SESSION['msg']);
		if (!empty($url)) {
            header('Location: ' . $url . '');
        } else {
            header('Location: index.php');
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Login - File Manager</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="assets/materialize.min.css" rel="stylesheet"/>
	<link rel="apple-touch-icon" sizes="180x180" href="assets/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon-16x16.png">
	<link rel="manifest" href="assets/icons/site.webmanifest">
	<link rel="mask-icon" href="assets/icons/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="assets/icons/favicon.ico">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-config" content="assets/icons/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<style>
		.center { text-align: center; }
		h1, h2, h3, h4,  h5, h6, .h2 { font-size: 160% !important; color: <?=COLOR;?> !important; }
		.material-icons { vertical-align: middle !important; }
		.bold-text { font-weight: bold !important; }
		.logo { font-size: 200%; font-weight: bold; }
		footer.a { color: white !important; }
		body { display: flex; min-height: 100vh; flex-direction: column; }
		main { flex: 1 0 auto; }
	</style>
</head>
<body>
	<header>
		<nav class="center <?=COLOR;?> logo">MS File Manager</nav>
	</header>
	<main>
		<div class="container"><h4>Login</h4>
		<?php if (empty($msg)) {echo '<div class="<?=COLOR;?>-text"><i class="material-icons">info</i></span> Please login to aceess the file manager. You will be kept logged in for 1 day after entering the correct password.</div></br/>';} else {echo '<div class="<?=COLOR;?>-text"> ' . $msg . '</div></br/>';}?>
		<form method="post" action="login.php"><div class="input-field"><input type="password" name="password" required autofocus><label for="password">Password</label></div><button type="submit" class="btn waves-effect waves-light green">Login</button></form><br/>
		</div>
		<br/>
	</main>
	<footer>
		<nav class="center <?=COLOR;?>"><a href="index.php">Home</a></nav>
	</footer>
	<script src="assets/materialize.min.js"></script>
	<script>
		M.AutoInit();
	</script>
</body>
</html>