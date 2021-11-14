<?php
// this page collects data from user and creates settings files
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$file = '' . getcwd() . '/inc/settings.dat';
if (file_exists($file)) {
    header('Location: index.php');
    exit();
}

$err = $_GET['error'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['password'])) {
        $err[] = '<div class="red-text bold-text"><i class="material-icons">error</i> Password can\'t be empty!</div>';
    }
    if (empty($_POST['per_page'])) {
        $err[] = '<div class="red-text bold-text"><i class="material-icons">error</i> Files per page can\'t be empty!!</div>';
    } else {
        if (!ctype_digit($_POST['per_page'])) {
            $err[] = '<div class="red-text bold-text"><i class="material-icons">error</i> Files per page must be a number (10 - 100)!</div>';
        } else {
            if (strlen($_POST['per_page']) > 3) {
                $err[] = '<div class="red-text bold-text"><i class="material-icons">error</i> Files per page input must be number (10 - 100)!</div>';
            }
        }
    }

    if (empty($err)) {
        $content = "" . password_hash(trim($_POST['password']), PASSWORD_DEFAULT) . "\n";
        $content .= trim($_POST['per_page']);
        $openfile = fopen($file, "w+");
        fwrite($openfile, $content);
        fclose($openfile);
        header('Location: index.php');
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
<title>Setup - File Manager</title>
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
	h1, h2, h3, h4,  h5, h6, .h2 { font-size: 160% !important; color: #b0bec5 !important; }
	.material-icons { vertical-align: middle !important; }
	.bold-text { font-weight: bold !important; }
	.logo { font-size: 200%; font-weight: bold; }
	footer.a { color: white; }
</style>
</head>
<body>
<header>
	<nav class="center blue-grey logo">MS File Manager</nav>
</header>
<main>
<div class="container">
<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
<h4>Welcome</h4>
<p><i class="material-icons red-text">favorite</i> Thanks for trying this software. This software is free for personal use. However, you can't redistribute, host, sell or modify this software. I had to give a lot of time and effort to make this. So please let me take the credit I deserve. If you want to share this with someone else, please refer to the official download link.</p>
<p><i class="material-icons brown-text">free_breakfast</i> If you like this project and want me to provide future updates, please buy me a coffee. Just mail me from the contact page (look at the footer). It's also okay if you can't <i class="material-icons orange-text">sentiment_very_satisfied</i>.</p>
<p><i class="material-icons green-text">live_help</i> To complete setup, give a password and a value of how many files you want to be listed per page in the below form. You can change those settings later from settings page. If you ever forget the password, please delete the file under <span class="bold-text">/inc/settings.dat</span> to reset the password. then this page will be shown again and you will be able to setup again.</p>
<h4>Setup File Manager</h4>
<?php
if (!empty($err)) {
    if ($err == '1') {
        echo '';
    } elseif ($err == '2') {
        echo '';
    } elseif ($err == '3') {
        echo '';
    }
}
?>
<div class="input-field"><div class="grey-text">New Password</div><input type="password" name="password" required autofocus/></div>
<div class="input-field"><div class="grey-text">Files Per Page</div><input type="number" name="per_page" required/></div>
<button type="submit" class="btn waves-effect waves-light green">Save</button>
</form>
</div>
<br/>
</main>
</main>
<footer>
	<nav class="center blue-grey">No Copyright</nav>
</footer>
<script src="assets/materialize.min.js"></script>
<script>
	 M.AutoInit();
</script>
</body>
</html>