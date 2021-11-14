<?php
// this file will perform actions and send reaponses dynamicly without reloading the target page
include(''.__DIR__.'/inc/core.php');
// initialize the class and hold it to a variable for future usage
$file_manager = new FileManager;
// get values from http request
$do = $_REQUEST['do'];
$name = $_REQUEST['name'];
$file = $_REQUEST['file'];
$dir = $_REQUEST['dir'];
$target = $_REQUEST['target'];
$confirm = $_REQUEST['confirm'];
$type = $_REQUEST['type'];
$perm = $_REQUEST['perm'];

// if recieved a request for showing fle/dir info
if($do=='info'){
	if ($file_manager->checkFile($name)) {
$info = $file_manager->fileInfo($name);
if($info['isDir']) { $type = 'Directory'; } else { $type = 'File'; }
echo '<div id="info_modal" class="modal">
    <div class="modal-content">
    <span class="right red-text modal-close"><i class="material-icons">cancel</i></span>
      <h4>'.$type.' Info</h4>
      <p>
      <i class="material-icons">font_download</i> Name: '.$info['name'].'<br/>
      <i class="material-icons">pie_chart</i> Size: '.$info['size'].'<br/>
      <i class="material-icons">history</i> Modified: '.date('d/m/Y g:i:s A').'<br/>
      <i class="material-icons">security</i> Permissions: '.$info['perm_text'].' ('.$info['perm'].')<br/>
      <div class ="bold-text"><i class="material-icons">tune</i> Actions</div><div class="space">';
      if($file_manager->isZip($info['ext']) || $file_manager->isImage($info['ext']) || $file_manager->isText($info['ext']) || $file_manager->isVideo($info['ext'])) {
      	echo '<a class="btn waves-effect waves-light blue" href="file.php?open='.$name.'"><i class="material-icons">launch</i> Open</a>';
      }
echo '<button class="btn waves-effect waves-light green" onclick="copyFile(\''.$name.'\')"><i class="material-icons">content_copy</i> Copy</button><button class="btn waves-effect waves-light lime" onclick="moveFile(\''.$name.'\')"><i class="material-icons">open_with</i> Move</button><button class="btn waves-effect waves-light orange" onclick="renameFile(\''.$name.'\')"><i class="material-icons">keyboard</i> Rename</button><button class="btn waves-effect waves-light red" onclick="deleteFile(\''.$name.'\')"><i class="material-icons">delete_forever</i> Delete</button>';
	if($info['isDir']) {

	} else {
		echo '<a class="btn waves-effect waves-light light-blue" href="download.php?file='.$name.'"><i class="material-icons">file_download</i> Download</a> ';
	}
echo '</div></p>
  </div>
  <script>
  openModal("info_modal");
  </script>';
} else {
	echo '<div class="red-text">Not found!</div>';
}
// handle request for coping a single file
} elseif($do=='copy'){
	if(empty($target)) {
		echo '<div id="copy_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Copy</h4><p><div class="grey-text">Source</div><div class="input-field"><input type="text" value="'.$name.'" disabled/></div><div class="input-field"><div class="grey-text">Target Directory</div><input type="text" id="target" name="target" value="'.dirname($name).'/" required autofocus/></div></p> <div class="modal-footer"><button class="btn waves-effect waves-light" onclick="copyFile(\''.$name.'\',document.getElementById(\'target\').value)">Copy</button></div></div>
		<script>
		openModal("copy_modal");
		</script>';
	} else {
		if(is_dir($name)) {
		// copy
			if($file_manager->copyDir($name, $target)) {
				echo '<script>
  				showToast("Copied Succesfuly!");
       			 </script>';
   			} else {
   				echo '<script>
  				showToast("Failed to copy!");
       			</script>';
   			}
		} else {
			if($file_manager->copyFile($name, $target)) {
				echo '<script>
  				showToast("Copied Succesfuly!");
       			 </script>';
   			} else {
   				echo '<script>
  				showToast("Failed to copy!");
       			</script>';
			}
		}
	}
// handle request for moving a single file
} elseif($do=='move') {
	if(empty($target)) {
		echo '<div id="move_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Move</h4><p><div class="grey-text">Source</div><div class="input-field"><input type="text" value="'.$name.'" disabled/></div><div class="input-field"><div class="grey-text">Target Directory</div><input type="text" id="target" name="target" value="'.dirname($name).'/" required autofocus/></div></p> <div class="modal-footer"><button class="btn waves-effect waves-light" onclick="moveFile(\''.$name.'\',document.getElementById(\'target\').value)">Move</button></div></div>
		<script>
		openModal("move_modal");
		</script>';
	} else {
			if($file_manager->moveFile($name, $target)) {
				echo '<script>
  				showToast("Moved Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
   			} else {
   				echo '<script>
  				showToast("Failed to move!");
       			</script>';
			}
		}
// handle request for deleting a single file
} elseif($do=='delete') {
	if(empty($confirm)) {
		if(is_dir($name)) {
			echo '<div id="delete_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Delete</h4><p><div class="bold-text red-text">Are you sure to delete the following directory and everything inside it?</div><i class="material-icons amber-text">folder</i> Directory: '.$name.'</p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="deleteFile(\''.$name.'\',\'confirm\')">Yes</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">No</a></div></div>
		<script>
		openModal("delete_modal");
		</script>';
		} else {
			echo '<div id="delete_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Delete</h4><p><div class="bold-text red-text">Are you sure to delete the following file?</div><i class="material-icons">insert_drive_file</i> File: '.$name.'</p> <div class="modal-footer"><button class="btn waves-effect waves-light red" onclick="deleteFile(\''.$name.'\',\'confirm\')">Yes</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">No</a></div></div>
		<script>
		openModal("delete_modal");
		</script>';
		}
	} else {
		if(is_dir($name)) {
			if($file_manager->deleteDir($name)) {
				echo '<script>
  				showToast("Deleted Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
   			} else {
   				echo '<script>
  				showToast("Failed to delete!");
       			</script>';
			}
		} else {
			if($file_manager->deleteFile($name)) {
				echo '<script>
  				showToast("Deleted Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
   			} else {
   				echo '<script>
  				showToast("Failed to delete!");
       			</script>';
			}
		}
	}
// handle request for deleting multiple files
} elseif($do=='delete_files') {
	echo '<script>
	';
	$name = explode(',',$name);
	foreach($name as $name) {
		if(is_dir($name)) {
			if($file_manager->deleteDir($name)) {
				echo 'showToast("Deleted '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to delete '.$name.'!");';
			}
		} else {
			if($file_manager->deleteFile($name)) {
				echo 'showToast("Deleted '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to delete '.$name.'!");';
			}
		}
	}
	echo '
	setTimeout(function(){ location.reload(); }, 2000);
	</script>';
// handle request for coping multiple files
} elseif($do=='copy_files') {
	echo '<script>
	';
	$name = explode(',',$name);
	foreach($name as $name) {
		if(is_dir($name)) {
			if($file_manager->copyDir($name,$target)) {
				echo 'showToast("Copied '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to copy '.$name.'!");';
			} //echo 'alert("'.$name.' is a dir");';
		} else {
			if($file_manager->copyFile($name,$target)) {
				echo 'showToast("Copied '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to copy '.$name.'!");';
			}
		}
	}
	echo '
	setTimeout(function(){ location.reload(); }, 2000);
	</script>';
// handle request for moving multiple files
} elseif($do=='move_files') {
	echo '<script>
	';
	$name = explode(',',$name);
	foreach($name as $name) {
		if(is_dir($name)) {
			if($file_manager->moveFile($name,$target)) {
				echo 'showToast("Moved '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to move '.$name.'!");';
			} //echo 'alert("'.$name.' is a dir");';
		} else {
			if($file_manager->moveFile($name,$target)) {
				echo 'showToast("Moved '.$name.' Succesfuly!");';
   			} else {
   				echo 'showToast("Failed to move '.$name.'!");';
			}
		}
	}
	echo '
	setTimeout(function(){ location.reload(); }, 2000);
	</script>';
// handle request for compressing multiple files to a zip file
} elseif($do=='zip_files') {
	$name = explode(',',$name);
	if($file_manager->createZip($target,$name)) {
		echo '<script>
		showToast("'.basename($target).' created Succesfuly!");
  		setTimeout(function(){ location.reload(); }, 2000);
  		</script>';
   	} else {
   		echo '<script>
		showToast("Failed to create '.basename($target).'!");
  		</script>';
	}
// handle request for creating a new file
} elseif($do=='create') {
	$dir = $file_manager->addSlash($dir);
	$name = $dir.$name;
	if($type=='dir') {
		if($file_manager->createDir($name)) {
			echo '<script>
  				showToast("Directory created Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
		} else {
			echo '<script>
  				showToast("Failed to create directory!");
       			</script>';
		}
	} else {
		if($file_manager->createFile($name)) {
			echo '<script>
  				showToast("File created Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
		} else {
			echo '<script>
  				showToast("Failed to create file!");
       			</script>';
		}
	}
// handle request for chmodding (change permission) a file
} elseif ($do=='chmod') {
	if(empty($perm)) {
		echo '<div id="chmod_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Change Permission</h4><p>
			<form name="chmod_form">
			<div class="row valign-wrapper"><div class="col s6 bold-text">Permissions<br/><input type="text" name="t_total" id="t_total" value="'.$file_manager->filePermissions($name).'" onKeyUp="octalchange()"></div>
			<div class="col s6 bold-text">Permissions Details<br/><input type="text" name="sym_total" READONLY></div></div>
			Read:
			<div class="row">
			<div class="input-field col s4"><p><label><input type="checkbox" name="owner4" value="4" onclick="calc_chmod()"><span>Owner</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="group4" value="4" onclick="calc_chmod()"><span>Group</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="other4" value="4" onclick="calc_chmod()"><span>Other</span></label></p></div>
			</div>
			Write:
			<div class="row">
			<div class="input-field col s4"><p><label><input type="checkbox" name="owner2" value="2" onclick="calc_chmod()"><span>Owner</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="group2" value="2" onclick="calc_chmod()"><span>Group</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="other2" value="2" onclick="calc_chmod()"><span>Other</span></label></p></div>
			</div>
			Execute:
			<div class="row">
			<div class="input-field col s4"><p><label><input type="checkbox" name="owner1" value="1" onclick="calc_chmod()"><span>Owner</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="group1" value="1" onclick="calc_chmod()"><span>Group</span></label></p></div>
			<div class="input-field col s4"><p><label><input type="checkbox" name="other1" value="1" onclick="calc_chmod()"><span>Other</span></label></p></div>
			</div>
			</form>
			</p>
			<div class="modal-footer"><button class="btn waves-effect waves-light blue" onclick="chmodFile(\''.$name.'\',document.getElementById(\'t_total\').value)">Save</button> <a href="javascript:void(0)" class="modal-close waves-effect waves-light btn green">Cancel</a></div></div>
		<script>
		openModal("chmod_modal");
		setTimeout(function(){ octalchange(); }, 100);
		</script>';
	} else {
		if($file_manager->changePermisssion($name,$perm)) {
			print_r($_REQUEST);
			echo '<script>
  				showToast("Permission changed Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
		} else {
			echo '<script>
  				showToast("Failed to change permission!'.$name.'");
       			</script>';
		}
	}
// rename any file or dir
} elseif($do=='rename') {
	if(empty($target)) {
		echo '<div id="rename_modal" class="modal"><div class="modal-content"><span class="right red-text modal-close"><i class="material-icons">cancel</i></span><h4>Rename</h4><p><div class="grey-text">Original Name</div><div class="input-field"><input type="text" value="'.basename($name).'" disabled/></div><div class="input-field"><div class="grey-text">New Name</div><input type="text" id="target" name="target" required autofocus/></div></p> <div class="modal-footer"><button class="btn waves-effect waves-light" onclick="renameFile(\''.$name.'\',document.getElementById(\'target\').value)">Rename</button></div></div>
		<script>
		openModal("rename_modal");
		</script>';
	} else {
			$dir = $file_manager->addSlash($dir);
			$target = $dir.$target;
			if($file_manager->changeName($name, $target)) {
				echo '<script>
  				showToast("Name changed Succesfuly!");
  				setTimeout(function(){ location.reload(); }, 2000);
       			</script>';
   			} else {
   				echo '<script>
  				showToast("Failed to rename!");
       			</script>';
			}
		}
} else {

}
?>