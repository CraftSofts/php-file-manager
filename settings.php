<?php
// this page updates settings
include(''.__DIR__.'/inc/core.php');
printHeader('Settings - File Manager');
$file = ''.getcwd().'/inc/settings.dat';
$err = $_GET['error'];
$suc = $_GET['success'];
if(!empty($_POST['confirm'])) {
if(empty($_POST['password'])) {
$msg = '<div class="red-text bold-text"><i class="material-icons">error</i> Password can\'t be empty!</div>';
} elseif(empty($_POST['per_page'])) {
$msg = '<div class="red-text bold-text"><i class="material-icons">error</i> Files per page can\'t be empty!!</div>';
}elseif(!ctype_digit($_POST['per_page'])) {
$msg = '<div class="red-text bold-text"><i class="material-icons">error</i> Files per page input must be number (10 - 100)!</div>';
} else {
	$content = "".md5(trim($_POST['password']))."\n";
	$content .= trim($_POST['per_page']);
	$openfile = fopen($file, "w+");
	fwrite($openfile, $content);
	fclose($openfile);
	$msg = '<div class="green-text"><i class="material-icons">check_circle</i> Settings updated succesfully! You need to login again.</div>';
	$logout_confirm = 1;
}
}
?>
<h4>File Manager Settings</h4>
<?php
if(!empty($msg)) {
 echo $msg;
}
 if(!empty($logout_confirm)) {
 logout();
 }else {
?>
<p><i class="material-icons">info</i> You can update you preferences here</p>
<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
<div class="input-field"><div class="grey-text">New Password</div><input type="password" name="password" required autofocus/></div>
<div class="input-field"><div class="grey-text">Files Per Page</div><input type="number" name="per_page" required/></div>
<input type="hidden" name="confirm" value="yes">
<button type="submit" class="btn waves-effect waves-light green">Save</button>
</form>
<br/>
<?php
}
printFooter();
?>