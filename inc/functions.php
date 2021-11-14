<?php
//required funcions
function printHeader($title = '')
{
    if (empty($title)) {
        $title = 'File Manager';
    }
    echo '<!DOCTYPE html>
		<html lang="en-US">
		<head>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="viewport" content="width=device-width, initial-scale=1"/>
			<meta name="msapplication-tap-highlight" content="no"/>
			<title>' . $title . '</title>
			<link href="assets/material-icons.css" rel="stylesheet">
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
				h1, h2, h3, h4,  h5, h6, .h2 { font-size: 160% !important; color: ' . COLOR . ' !important; }
				.material-icons { vertical-align: middle !important; }
				.large-icon { font-size:  2rem; }
				.bold-text { font-weight: bold !important; }
				.logo { font-size: 200%; font-weight: bold; }
				footer.a { color: white; }
				.space {margin: 2px; }
				.btn {margin: 2px !important; }
				.no-files { position: fixed; top: 50%; left: 50%; color: grey; font-size: 150%; text-align: center; transform: translate(-50%, -50%); }
				#checked_action, #show_div, #edit_div { display: none; }
				.file_list:nth-child(odd) { background-color: #eeeeee; }
				.file_list:nth-child(even), .info { background-color: #fafafa; }
				.file_list:hover { background-color: #e0e0e0; cursor: pointer; font-weight: bold; }
				.code { background-color: #eeeeee; overflow: auto; padding: 6px; }
				.info { background-color: #e0f2f1 !important; border: 1px solid #00bcd4; padding: 4px; margin: 3px; border-radius: 5px; }
				.modal-content, .modal-footer { background-color: ' . HEX . ' !important; }
				#toast { visibility: hidden; min-width: 250px; margin-left: -125px; background-color: #333; color: #fff; text-align: center; border-radius: 2px; padding: 16px; position: fixed; z-index: 1; left: 50%; bottom: 30px; }
				#toast.show {
				  visibility: visible;
				  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
				  animation: fadein 0.5s, fadeout 0.5s 2.5s;
				}
				@-webkit-keyframes fadein {
				  from {bottom: 0; opacity: 0;}
				  to {bottom: 30px; opacity: 1;}
				}
				@keyframes fadein {
				  from {bottom: 0; opacity: 0;}
				  to {bottom: 30px; opacity: 1;}
				}
				@-webkit-keyframes fadeout {
				  from {bottom: 30px; opacity: 1;}
				  to {bottom: 0; opacity: 0;}
				}
				@keyframes fadeout {
				  from {bottom: 30px; opacity: 1;}
				  to {bottom: 0; opacity: 0;}
				}
				footer.a { color: white !important; }
				body { display: flex; min-height: 100vh; flex-direction: column; }
				main { flex: 1 0 auto; }
			</style>
		</head>
	<body>
	<header>
		<nav class="center ' . COLOR . ' logo">MS File Manager</nav>
	</header>
	<main>
		<div class="container">';
}

// function to detect os
function isWindows()
{
    if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0) {
        return true;
    } else {
        return false;
    }
}
// function to secure any page from unauthorized access
function secureAccess()
{
    // the file where settings are saved
    $file = '' . __DIR__ . '/settings.dat';
    if (!file_exists($file)) {
        // the file doesn't exists, redirect user to setup page. user must complete setup first.
        header('Location: setup.php');
    } else {
        // the file exists, we are good to go
        $lines = file($file); // get lines from the setting file
        $password = trim($lines[0]); // 1st line is the password saved during setup
        $per_page = trim($lines[1]); // 2nd line is a number which will be used to show files per page as of it's value
        define('PER_PAGE', $per_page); // now we set the file per page value globally
        if (!isset($_SESSION['fm_pass'])) {
            $_SESSION['msg'] = '<i class="material-icons">info</i></span> You are not logged in! Please login to continue.';
            if (basename($_SERVER['PHP_SELF']) != 'login.php') {header("Location: login.php");
                $_SESSION['url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";}
        }
    }
}

function printFooter($extra = '')
{
    echo '<div id="toast"></div>
	<div id="about_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>About</h4>
      <p>
      <div class="info"><i class="material-icons">info</i> This software is made to manage files of any web server which supports PHP. You can enjoy full featured Graphical User Interface based file manager with material design. This software was made as a part of hobby.</div>
      <div class="info"><i class="material-icons">person</i> Meraj-Ul Islam</div>
      <div class="info"><i class="material-icons">attach_money</i> Free (for using only)</div>
      <div class="info"><i class="material-icons">web</i> <a href="https://www.merajbd.com" target="_blank">www.merajbd.com</a></div>
      <div class="info"><i class="material-icons">extension</i> <a href="https://github.com/Ne-Lexa/php-zip" target="_blank">php-zip</a> | <a href="https://materializecss.com/" target="_blank">Materialize CSS</a></div>
       <div class="info"><i class="material-icons">functions</i> Features:<ul>
       <li>&#9889; Dynamic and Responsive</li>
       <li>&#9889; Batch operations (Copy/Move/Delete/Compress)</li>
       <li>&#9889; Easy and Simple UI</li>
       <li>&#9889; Easy Navigations</li>
       <li>&#9889; Secured with password login</li>
       <li>&#9889; Settings can be changed via GUI</li>
       <li>&#9889; File Create/Edit/Delete</li>
       <li>&#9889; Directory Create/Delete</li>
       <li>&#9889; View/Extract/Create ZIP arcihves</li>
       <li>&#9889; View Text/Image/Video</li>
       <li>&#9889; File Create/Edit</li>
       <li>&#9889; Rename Files/Dirs</li>
       <li>&#9889; File Upload (Local/URL)</li>
       </ul></div>
      </p>
    </div>
    <div class="modal-footer">
      <a href="javascript:void(0)" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
	</div>
	</main>
	<footer>
		<nav class="center ' . COLOR . ' bold-text"><a href="index.php">Home</a> | <a href="settings.php">Settings</a> | <a href="javascript:vpoid(0)" onclick="showAbout()" >About</a> | <a href="contact.php">Contact</a> | <a href="logout.php">Logout</a></nav>
	</footer>
		<script src="assets/materialize.min.js"></script>
		<script>
		 M.AutoInit();
		function dynamicResponse(resultDiv,processing,action,paramName,value,parseJs) {
		if(processing) {
		document.getElementById(resultDiv).processing;
		}
		var xmlhttp= window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
		var resp = this.responseText;
		document.getElementById(resultDiv).innerHTML = resp;
		if(parseJs) {
		parseScript(resp);
		}
		}
		}
		var values = paramName + "=" + value;
		xmlhttp.open("POST","dynamic.php?do=" + action, true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send(values);
		}
		function parseScript(strcode) {
		var scripts = new Array();
		while(strcode.indexOf("<script") > -1 || strcode.indexOf("</script") > -1) {
		var s = strcode.indexOf("<script");
		var s_e = strcode.indexOf(">", s);
		var e = strcode.indexOf("</script", s);
		var e_e = strcode.indexOf(">", e);
		scripts.push(strcode.substring(s_e+1, e));
		strcode = strcode.substring(0, s) + strcode.substring(e_e+1);
		}
		for(var i=0; i<scripts.length; i++) {
		try {
		eval(scripts[i]);
		}
		catch(ex) {
		alert("Error while executing");
		}
		}
		}
		function showAbout() {
		var elems = document.getElementById("about_modal");
		var instances = M.Modal.init(elems);
		instances.open();
		}
		function showToast(txt) {
  		var x = document.getElementById("toast");
  		x.innerText = txt;
  		x.className = "show";
  		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  		}
		</script>';
    // if there is any extra code for footer included by page, we include it
    if (!empty($extra)) {
        echo $extra;
    }

    echo '</body>
		</html>';
}
