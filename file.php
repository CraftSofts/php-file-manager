<?php
include(''.__DIR__.'/inc/core.php');
$file = $_REQUEST['open'];
if(!file_exists($file)) {
	$title = 'File not found';
	$file_name = $title;
} else {
	$name = basename($file);
	$title = ''.basename($file).' - File Manager';
	$file_name = 'File Info';
}
function getUrl ($path) {
  if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) { $protocol = "https://"; } else { "http://"; }
  	$protocol = 'http://';
    $file_url = str_replace($_SERVER['DOCUMENT_ROOT'], '/', $path);
    $file_url = $protocol.$_SERVER['HTTP_HOST'].str_replace('//', '/', $file_url);
    return $file_url;
}
printHeader($title);
if(!file_exists($file)) {
?>
<p class="card-panel red-text"><i class="material-icons">error</i> Sorry, the requested file wasn't found on the server!</p>
<?php
} else {
	$file_manager = new FileManager;
	$ext = pathinfo($file, PATHINFO_EXTENSION);

$info = $file_manager->fileInfo($file);
echo '<h4>'.$file_name.'</h4>
      <p>
      <i class="material-icons orange-text">font_download</i> Name: '.$info['name'].'<br/>
      <i class="material-icons orange-text">pie_chart</i> Size: '.$info['size'].'<br/>
      <i class="material-icons orange-text">history</i> Modified: '.date('d/m/Y g:i:s A').'<br/>
      <i class="material-icons orange-text">security</i> Permissions: '.$info['perm_text'].' ('.$info['perm'].')<br/>
      <i class="material-icons orange-text">file_download</i> Download: <a href="download.php?file='.$file.'">'.basename($file).'</a><br/>
      <i class="material-icons orange-text">link</i> Visit: <a href="'.getUrl($file).'" target="_blank">'.getUrl($file).'</a><br/>
      </p>';

	if($file_manager->isZip($ext)) {
    if(empty($_POST['path'])) {
		$file_list = $file_manager->openZip($file);
?>
<h4>Additional Actions</h4>
<form method="post" action="file.php?open=<?=$file;?>">
	<div class="input-field">
          <input id="path" type="text" name="path" value="<?=dirname($file);?>">
          <label for="path">Extract to</label>
  </div>
    <button class="btn waves-effect waves-light green" type="submit">Extract</button> <a class="waves-effect waves-light btn modal-trigger" href="#view">Show Contents</a>
</form>
  <div id="view" class="modal">
    <div class="modal-content">
      <h4>Contents</h4>
      <p><?php
      foreach ($file_list as $file) {
      	echo '<i class="material-icons">insert_drive_file</i> '.$file.'<br/>
      	';
      }
      ?></p>
    </div>
    <div class="modal-footer">
      <a href="javascript:void(0)" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
<?php
    
    } else {
      if($file_manager->extractZip($file,$_POST['path'])){
        echo '<p class="card-panel green-text"><i class="material-icons">check_circle</i> File has been extracted successfuly!</p>';
      } else {
        echo '<p class="card-panel red-text"><i class="material-icons">error</i> Sorry, the file couldn\'t be extracted!</p>';
      }
    }
	} elseif ($file_manager->isText($ext)) {
    $content_edit  = $file_manager->readFileContents($file);
    $content_show  = $file_manager->showFileContents($file);
    echo '<h4>Additional Actions</h4>';
    if(empty($_POST['content'])) {
?>
<button class="btn waves-effect waves-light green" id="edit_button">Edit File</button> <button class="btn waves-effect waves-light blue" id="show_button">Show Contents</button>
<form actio="file.php?open=<?=$file;?>" method="post">
  <div id="edit_div"><h4>Edit <?=basename($file);?></h4>
  <div class="input-field"><textarea name="content" class="materialize-textarea" spellcheck="false"><?=$content_edit;?></textarea><label for="content">Contents</label></div>
  <button class="btn waves-effect waves-light green" type="submit">Save</button>
</form>
</div>
<div id="show_div"><h4>Contents of <?=basename($file);?></h4><div class="code"><?php
if($ext=='php') {
  $content_show = highlight_string($content_show, true);
}
echo $content_show;
?></div></div>
<?php
} else {
 if ($file_manager->editFile($file,$_POST['content'])) {
  echo '<p class="card-panel green-text"><i class="material-icons">check_circle</i> File has been saved successfuly!</p>';
 } else {
  echo '<p class="card-panel red-text"><i class="material-icons">error</i> Sorry, the file couldn\'t be saved!</p>';
 }
}
  } elseif ($file_manager->isVideo($ext)) {
echo '<h4>Preview Video</h4>
<video class="responsive-video" controls>
    <source src="'.getUrl($file).'" type="video/'.$ext.'">
  </video>';

  } elseif ($file_manager->isImage($ext)) {
echo '<h4>Preview Image</h4>
 <div class="center"><img class="materialboxed responsive-img" data-caption="'.basename($file).'" src="'.getUrl($file).'"></div>';

  } else {
    echo '<div class="bold-text red-text"><i class="material-icons">error</i> Sorry, you visited a wrong page!</div>';
  }
}
echo '<br/>';
$js = '<script>
document.getElementById("edit_button").onclick = function() {toggleDiv("edit_div")};
document.getElementById("show_button").onclick = function() {toggleDiv("show_div")};
function toggleDiv(name) {
 var x = document.getElementById(name);
 var style = window.getComputedStyle(x);
  if (style.display == "none") {
    x.style.display = "block";
    if(name == "show_div") {
      document.getElementById("show_button").innerText = "Hide Contents";
    }
  } else {
    x.style.display = "none";
    if(name == "show_div") {
      document.getElementById("show_button").innerText = "Show Contents";
    }
  }
}
</script>';
printFooter($js);
?>