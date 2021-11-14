<?php
/*************************************************
 * Micro Upload
 *
 * Version: 0.1
 * Date: 2006-10-27
 *
 * Usage:
 * Set the uploadLocation variable to the directory
 * where you want to store the uploaded files.
 * Use the version which is relevenat to your server OS.
 *
 ****************************************************/

//Windows way
$uploadLocation = $dir;
//Unix, Linux way
//$uploadLocation = "\tmp";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
   <title>MicroPing domain status checker</title>
   <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="main">
      <div id="caption">UPLOAD FILE</div>
      <div id="icon">&nbsp;</div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="fileForm" id="fileForm" enctype="multipart/form-data">
        File to upload:<center>
        <table>
          <tr><td><input name="upfile" type="file" size="36"></td></tr>
          <tr><td align="center"><br/><input class="text" type="submit" name="submitBtn" value="Upload"></td></tr>
        </table></center>  
      </form>
<?php    
    if (isset($_POST['submitBtn'])){

?>
      <div id="caption">RESULT</div>
      <div id="icon2">&nbsp;</div>
      <div id="result">
        <table width="100%">
<?php

$target_path = $uploadLocation . basename( $_FILES['upfile']['name']);

if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
    echo "The file: ".  basename( $_FILES['upfile']['name']).
    " has been uploaded!";
} else{
    echo "There was an error uploading the file, please try again!";
}

?>
        </table>
     </div>
<?php            
    }
?>
    <div>
</body>   