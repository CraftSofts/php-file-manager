<?php
include(''.__DIR__.'/inc/core.php');
printHeader('Upload - File Manager');
$dir = $_REQUEST['dir'];
	//if nothing  is submitted
?>
<h4>Local File Upload</h4>
<form action="" method="post" enctype="multipart/form-data">
	<div class="file-field input-field">
      <div class="btn">
        <span>File</span>
        <input type="file" placeholder="Select file to upload" name="file" id="file">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
<input type="hidden" name="dir" value="<?=$dir;?>">
<button type="submit" class="btn waves-effect waves-light green" name="local_upload">Upload</button>
</form>
<h4>Remote File Upload</h4>
<form method="post" action="">
<div class="input-field"><input name="url" type="url" id="url"/><label for="url">File URL</label></div>
<div class="input-field"><input name="name" type="text" id="name" value=" "/><label for="name">File Name</label></div>
<input type="hidden" value="<?=$dir?>" name="dir"/>
<button type="submit" class="btn waves-effect waves-light green" name="url_upload">Upload</button>
</form>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	print_r($_POST);
	// if form submitted
	echo '<h4>File Upload</h4>';
	if(isset($_POST['local_upload'])) {
		// local file upladed
		$target_dir = $dir;
		if(empty($target_dir)) $target_dir = getcwd().'/';
			$target_file = $target_dir . basename($_FILES["file"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if (file_exists($target_file)) {
			echo "<div class=\"red-text bold-text\"><i class=\"material-icons\">error</i> Sorry, file already exists</div>";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			echo "<div class=\"red-text bold-text\"><i class=\"material-icons\">error</i> Sorry, your file was not uploaded</div>";
		} else {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		    echo "<div class=\"green-text bold-text\"><i class=\"material-icons\">check_circle</i> The file ". basename( $_FILES["file"]["name"]). " has been uploaded</div>";
			} else {
		    echo "<div class=\"red-text bold-text\"><i class=\"material-icons\">error</i> Sorry, there was an error uploading your file</div>";
		 	}
		}
	} elseif(isset($_POST['url_upload'])) {
		// remote file uploded via url
		$url = $_POST['url']; 
		$ch = curl_init($url);
		$file_name = basename($url); 
		$save_file_loc = $dir . $file_name; 
		if(file_exists($save_file_loc)) {
			echo "<div class=\"red-text bold-text\"><i class=\"material-icons\">error</i> Sorry, file already exists! Try to give a different to this file name in file name input.</div>";
		} else {
			$fp = fopen($save_file_loc, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_exec($ch); 
			curl_close($ch); 
			fclose($fp); 
			echo "<div class=\"green-text bold-text\"><i class=\"material-icons\">check_circle</i> The file ".basename($url)." has been uploaded</div>";
		}
	 } else {
	 	echo "<div class=\"red-text bold-text\"><i class=\"material-icons\">error</i> Sorry, Something went wrong!</div>";
	 	print_r($_POST);
	 }
}
echo "<br/>";
$js = '<script>
document.getElementById("url").addEventListener("keyup", getFileName);
document.getElementById("url").addEventListener("blur", getFileName);
function getFileName() {
	var url = document.getElementById("url").value;
	var filename = url.substring(url.lastIndexOf("/")+1);
	if(filename) {
	document.getElementById("name").value = filename;
	}
}
</script>';
printFooter($js);
?>
