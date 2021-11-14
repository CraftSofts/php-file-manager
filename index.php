<?php
require_once('inc/core.php');
printHeader();
$self = 'index.php';
$dir = $_REQUEST['dir'];
if(!empty($dir)) {
	$url_dir = '&dir='.$dir.'';
    chdir($dir);
    $nav_dir = str_replace('//','/',$dir);
} else {
	$nav_dir = dirname(__DIR__);
	chdir(dirname(__DIR__));
}
$nav_dir = str_replace('\\', '/', $nav_dir);
$files = glob('{,.}[!.,!..]*',GLOB_MARK|GLOB_BRACE);
sort($files);
$icons = array('aif'=>'audiotrack','cda'=>'audiotrack','mid'=>'audiotrack','mp3'=>'audiotrack','mpa'=>'audiotrack','ogg'=>'audiotrack','wav'=>'audiotrack','wma'=>'audiotrack','wpl'=>'audiotrack','7z'=>'archive','arj'=>'archive','deb'=>'archive','pkg'=>'archive','rar'=>'archive','rpm'=>'archive','targz'=>'archive','z'=>'archive','zip'=>'archive','tar'=>'archive','fnt'=>'font_download','fon'=>'font_download','otf'=>'font_download','ttf'=>'font_download','ai'=>'image','bmp'=>'image','gif'=>'image','ico'=>'image','jpeg'=>'image','jpg'=>'image','png'=>'image','ps'=>'image','psd'=>'image','svg'=>'image','tif'=>'image','tiff'=>'image','asp'=>'description','aspx'=>'description','cer'=>'description','cfm'=>'description','cgi'=>'description','pl'=>'description','css'=>'description','htm'=>'description','html'=>'description','js'=>'description','jsp'=>'description','part'=>'description','php'=>'description','py'=>'description','rss'=>'description','xhtml'=>'description','c'=>'description','class'=>'description','cpp'=>'description','cs'=>'description','h'=>'description','java'=>'description','pl'=>'description','sh'=>'description','swift'=>'description','vb'=>'description','txt'=>'description','bat'=>'description','cgi'=>'description','pl'=>'description','py'=>'description','xml'=>'description','sql'=>'description','log'=>'description','3g2'=>'movie','3gp'=>'movie','avi'=>'movie','flv'=>'movie','h264'=>'movie','m4v'=>'movie','mkv'=>'movie','mov'=>'movie','mp4'=>'movie','mpg'=>'movie','mpeg'=>'movie','rm'=>'movie','swf'=>'movie','vob'=>'movie','webm'=>'movie','apk'=>'android');

$dirs = explode('/',$nav_dir);
if(strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0){
$dirs = array_filter($dirs);
} else {
	if(!empty($_REQUEST['dir'])) {
		 $lengh = sizeof($dirs) -1 ;
         unset($dirs[$lengh]); 
	}
}
$total_dir = count($dirs);
for ($i = 0; $i < $total_dir; $i++) {
	if(!isWindows()) {
		if($i!=$total_dir) {
			$full_path .= ''.$dirs[$i].'/';
			$dir_nav .= '<a href="'.$self.'?dir='.$full_path.'">'.$dirs[$i].'</a> <span class="grey-text">/ </span>
		';
		}
	} else {
		$full_path .= ''.$dirs[$i].'/';
		$dir_nav .= '<a href="'.$self.'?dir='.$full_path.'">'.$dirs[$i].'</a> <span class="grey-text">/ </span>
		';
	}
}
$dir_nav = str_replace('<span class="grey-text">/ </span> <span class="grey-text">/ </span>', '<span class="grey-text">/ </span>', $dir_nav);
echo '<div class="bold-text green-text large-icon"><i class="material-icons large-icon">folder_open</i> '.$dir_nav.'</div>';
$total_files = count($files);
if(empty($total_files)) {
	echo '<div class="no-files"><i class="material-icons large-icon medium">info_outline</i><br/>No files here</div>';
} else {
$total_pages   = ceil($total_files/PER_PAGE);
$page          = $_REQUEST['p']; //
if(!isset($page)) { $page=1; }
if($page < 0 || $page > $total_pages) $page = 1;
$offset        = ($page-1)*PER_PAGE;
$files_filter  = array_slice($files, $offset,PER_PAGE);

foreach ($files_filter as $file) {
	$wdir = getcwd();
	$wdir = str_replace('\\', '/', $wdir);
	$filename = ''.$wdir.'/'.$file.'';
	$filename = str_replace('//', '/', $filename);
	$filename = str_replace('\\', '/', $filename);
	if(is_dir($file)) {
		$file = str_replace('\\', '', $file);
	echo '<div class="file_list"><a href="'.$self.'?dir='.$filename.'"><span id="'.basename($file).'"><span class="amber-text"><i class="material-icons">folder</i></span> '.$file.'</a><span class="right"><a href="javascript:void(0)" class="tooltipped"  data-position="left" data-tooltip="More" onclick="getInfo(\''.$filename.'\')"><i class="material-icons">build</i></a><a href="javascript:void(0)" id="'.basename($file).'_tip" class="tooltipped"  data-position="left" data-tooltip="Select '.$file.'" onclick="selectFile(\''.$filename.'\',\''.basename($file).'\')"><i class="material-icons" id="'.basename($file).'_icon">check_box_outline_blank</i></a></span></span></div>
	';
	} else {
	$file_info = pathinfo($file);
	$ext = $file_info['extension'];
	if(array_key_exists($ext,$icons)) {
		echo '<div class="file_list"><a href="javascript:void(0)" onclick="getInfo(\''.$filename.'\')"><i class="material-icons">'.$icons[''.$ext.''].'</i> <span id="'.basename($file).'">'.$file.'</span></a><span class="right"><a href="javascript:void(0)" class="tooltipped"  data-position="left" data-tooltip="More" onclick="getInfo(\''.$filename.'\')"><i class="material-icons">build</i></a><a href="javascript:void(0)" id="'.basename($file).'_tip" class="tooltipped"  data-position="left" data-tooltip="Select '.$file.'" onclick="selectFile(\''.$filename.'\',\''.basename($file).'\')"><i class="material-icons" id="'.basename($file).'_icon">check_box_outline_blank</i></a></span></div>
		';
	} else {
		echo '<div class="file_list"><a href="javascript:void(0)" onclick="getInfo(\''.$filename.'\')"><i class="material-icons">insert_drive_file</i> <span id="'.basename($file).'">'.$file.'</span></a><span class="right"><a href="javascript:void(0)" class="tooltipped"  data-position="left" data-tooltip="More" onclick="getInfo(\''.$filename.'\')"><i class="material-icons">build</i></a><a href="javascript:void(0)" id="'.basename($file).'_tip" class="tooltipped"  data-position="left" data-tooltip="Select '.$file.'" onclick="selectFile(\''.$filename.'\',\''.basename($file).'\')"><i class="material-icons" id="'.basename($file).'_icon">check_box_outline_blank</i></a></span></div>
		';

	}
}
}
}

if($total_pages > 1){
	echo '<ul class="pagination center">';
   if($page != 1){
      echo '<li class="'.COLOR.' space"><a href="'.$self.'?p='.($page-1).$url_dir.'">&lt;</a> </li>';
      }
            for ($x = 1; $x < $total_pages+1; $x++) {
            	if ($x==$page) {
            		echo '<li class="'.COLOR.' lighten-3 space"> <a href="javascript:void(0)" disabled>'.$x.'</a> </li>';
            	} else {
            		echo '<li class="'.COLOR.' space"> <a href="'.$self.'?p='.$x.$url_dir.'">'.$x.'</a> </li>';
            	}
   }
   if($page != $total_pages){
      echo '<li class="'.COLOR.' space"><a href="'.$self.'?p='.($page+1).$url_dir.'">&gt;</a></li>';
   }
   echo '</ul>';
} else {
	echo '<br/>';
}
echo '<div id="result"></div>
<div class="fixed-action-btn"><a class="btn-floating btn-large '.COLOR.'" onclick="openModal(\'create_modal\')"><i class="large material-icons">add</i></a></div>
	<div id="checked_action"><div class="row valign-wrapper"><div class="col s8"><select id="batch">
      <option value="delete">Delete</option>
      <option value="copy">Copy</option>
      <option value="move">Move</option>
      <option value="compress">Compress</option>
    </select>
    <label for="batch">Select action</label></div>
	<div class="col s4 center"><button class="btn waves-effect waves-light" type="submit" onclick="batchFileAction(document.getElementById(\'batch\').value)">Okay</button></div></div></div>
  <div id="create_modal" class="modal">
    <div class="modal-content">
    <span class="right red-text modal-close"><i class="material-icons">cancel</i></span>
      <h4>Create New</h4>
      <p>
       <p>
      <label>
        <input name="ftype" id="ftype" type="radio" value="file" checked />
        <span>File</span>
      </label>
    </p>
    <p>
      <label>
        <input name="ftype" id="ftype" type="radio" name="dir" value="dir"/>
        <span>Directory</span>
      </label>
    </p>
    <div class="input-field">
          <input id="fname" type="text" name="fname">
          <label for="fname">Name</label>
        </div>
      <div class="space"><a href="upload.php?dir='.$dir.'"><i class="material-icons">file_upload</i> Upload a file</a></div>
      </p>
      </div>
    <div class="modal-footer">
      <button class="btn waves-effect waves-light green" onclick="createFile(document.getElementById(\'fname\').value,\'ftype\')">Create</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-green btn orange">Cancel</a>
    </div>
  </div>
  <div id="delete_files_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Delete multiple files</h4><p><div class="bold-text red-text">Are you sure to delete the selected files?</div><i class="material-icons">info</i> This action can\'t be undone!</p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="deleteFiles(\'confirm\')">Yes</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">No</a></div>
  </div></div>
  <div id="copy_files_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Copy files</h4><p>
  <div class="input-field"><input id="copy_dir" type="text" name="copy_dir" value="'.$dir.'"><label for="copy_dir">Target Directory</label></div>
  </p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="copyFiles(document.getElementById(\'copy_dir\').value)">Copy</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">Cancel</a></div>
  </div></div>
  <div id="move_files_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Move files</h4><p>
  <div class="input-field"><input id="move_dir" type="text" name="move_dir" value="'.$dir.'"><label for="move_dir">Target Directory</label></div>
  </p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="moveFiles(document.getElementById(\'move_dir\').value)">Move</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">Cancel</a></div>
  </div></div>
   <div id="zip_files_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Compress files</h4><p>
  <div class="input-field"><input id="zip_dir" type="text" name="zip_dir" value="archive.zip"><label for="zip_dir">File name</label></div>
  </p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="zipFiles(document.getElementById(\'zip_dir\').value)">Compress</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">Cancel</a></div>
  </div></div>';
$js = '<script>
var selection = [];
function selectFile(name,div) {
	var iconDiv = document.getElementById(div);
	var icon = div + "_icon";
	var tip = div + "_tip";
	if (selection.indexOf(name) !== -1) {
		var index = selection.indexOf(name);
		while (index > -1) {
			selection.splice(index, 1);
			index = selection.indexOf(selection);
			document.getElementById(icon).innerText = "check_box_outline_blank";
			document.getElementById(tip).setAttribute("data-tooltip", "Select " + div); 
		}
		iconDiv.className = " ";
		checkedAction();
		} else {
			selection[selection.length] = name;
			iconDiv.className = "green-text bold-text";
			checkedAction();
			document.getElementById(icon).innerText = "check_box";
			document.getElementById(tip).setAttribute("data-tooltip", "Unelect " + div);
		}
}
function checkedAction () {
	if(selection.length > 0) {
		document.getElementById("checked_action").style.display = "block";
	} else {
		document.getElementById("checked_action").style.display = "none";
	}
}
function openModal(name) {
var elems = document.getElementById(name);
	var instances = M.Modal.init(elems);
	instances.open();
}
function closeModal(name) {
var elems = document.getElementById(name);
	var instances = M.Modal.init(elems);
	if(instances.isOpen) {
		instances.close();
	}
}
function getInfo(name) {
	var processing =  \'<div class="center bold-text">Please wait...</div>\';
	dynamicResponse("result",processing,"info","name",name,"yes");
}
function createFile(name,type) {
	var processing =  \'<div class="center bold-text">Please wait...</div>\';
	var radios = document.getElementsByName(type);
	for (var i = 0, length = radios.length; i < length; i++) {
  		if (radios[i].checked) {
    	var type = radios[i].value;
    	break;
    	}
    }
	var dir = "'.$dir.'";
	var values = name + "&type=" + type + "&dir=" + dir;
	dynamicResponse("result",processing,"create","name",values,"yes");
}
function copyFile(name,target) {
	if(target) {
		var ntarget = name + "&target=" + target;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"copy","name",ntarget,"yes");
	} else {
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"copy","name",name,"yes");
	}
}
function copyFiles(target) {
	if(target) {
		closeModal("copy_files_modal");
		var nstring = selection.toString() + "&target=" + target;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"copy_files","name",nstring,"yes");
	} else {
		openModal("copy_files_modal");
	}
}
function moveFile(name,target) {
	if(target) {
		var ntarget = name + "&target=" + target;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"move","name",ntarget,"yes");
	} else {
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"move","name",name,"yes");
	}
}
function moveFiles(target) {
	if(target) {
		closeModal("move_files_modal");
		var nstring = selection.toString() + "&target=" + target;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"move_files","name",nstring,"yes");
	} else {
		openModal("move_files_modal");
	}
}
function deleteFile(name,confirm) {
	if(confirm) {
		var nconfirm = name + "&confirm=" + confirm;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"delete","name",nconfirm,"yes");
	} else {
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"delete","name",name,"yes");
	}
}
function deleteFiles(confirm) {
	if(confirm) {
		closeModal("delete_files_modal");
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"delete_files","name",selection.toString(),"yes");
	} else {
		openModal("delete_files_modal");
	}
}
function renameFile(name,newname) {
	if(newname) {
		var nname = name + "&dir='.$dir.'&target=" + newname;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"rename","name",nname,"yes");
	} else {
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"rename","name",name,"yes");
	}
}
function zipFiles(target) {
	if(target) {
		closeModal("zip_files_modal");
		var nstring = selection.toString() + "&target='.$dir.'/" + target;
		var processing =  \'<div class="center bold-text">Please wait...</div>\';
		dynamicResponse("result",processing,"zip_files","name",nstring,"yes");
	} else {
		openModal("zip_files_modal");
	}
}
function batchFileAction(action) {
	//dynamicResponseJson
	if (action == \'delete\') {
			deleteFiles();
		} else if (action == \'copy\') {
			copyFiles();
		} else if (action == \'move\') {
			moveFiles();
		} else if (action == \'compress\') {
			zipFiles();
		}
}
</script>';
printFooter($js);
?>