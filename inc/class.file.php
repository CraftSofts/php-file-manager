<?php

/**
Class for managing files and directories
**/
class FileManager
{

    public function createDir($name)
    {
        if (file_exists($name)) {
            return false;
        } else {
            if (mkdir($name)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkFile($name)
    {
        if (file_exists($name)) {
            return true;
        } else {
            return false;
        }
    }

    public function createFile($name, $content = '')
    {
        if (file_exists($name)) {
            return false;
        } else {
            $openfile = fopen($name, "w+");
            fwrite($openfile, $content);
            fclose($openfile);
            return true;
        }
    }

    public function deleteDir($name)
    {
        foreach (glob($name) as $file) {
            if (is_dir($file)) {
                $this->deleteDir("$file/*");
                rmdir($file);
            } else {
                unlink($file);
            }
        }
        return true;
    }

    public function deleteFile($name)
    {
        if (unlink($name)) {
            return true;
        } else {
            return false;
        }
    }

    public function copyDir($source, $destination)
    {
        $destination = $this->addSlash($destination);
        $dir = opendir($source);
        @mkdir($destination);
        while ($file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    $this->copyDir($source . '/' . $file, $destination . '/' . $file);
                } else {
                    copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
        }
        closedir($dir);
        return true;
    }

    public function copyFile($source, $destination)
    {
        $destination = $this->addSlash($destination);
        $destination = $destination . basename($source);
        if (copy($source, $destination)) {
            return true;
        } else {
            return false;
        }
    }

    public function changeName($old, $new)
    {
        if (rename($old, $new)) {
            return true;
        } else {
            return false;
        }
    }

    public function moveFile($source, $destination)
    {
        $destination = $this->addSlash($destination);
        $destination = $destination . basename($source);
        if (rename($source, $destination)) {
            return true;
        } else {
            return false;
        }
    }

    public function filePermissions($name, $num = true)
    {
        if ($num == true) {
            return substr(sprintf('%o', fileperms($name)), -4);
        } else {
            $perms = fileperms($name);
            switch ($perms & 0xF000) {
                case 0xC000:
                    $info = 's';
                    break;
                case 0xA000:
                    $info = 'l';
                    break;
                case 0x8000:
                    $info = 'r';
                    break;
                case 0x6000:
                    $info = 'b';
                    break;
                case 0x4000:
                    $info = 'd';
                    break;
                case 0x2000:
                    $info = 'c';
                    break;
                case 0x1000:
                    $info = 'p';
                    break;
                default:
                    $info = 'u';
            }
            $info .= (($perms & 0x0100) ? 'r' : '-');
            $info .= (($perms & 0x0080) ? 'w' : '-');
            $info .= (($perms & 0x0040) ?
                (($perms & 0x0800) ? 's' : 'x') :
                (($perms & 0x0800) ? 'S' : '-'));
            $info .= (($perms & 0x0020) ? 'r' : '-');
            $info .= (($perms & 0x0010) ? 'w' : '-');
            $info .= (($perms & 0x0008) ?
                (($perms & 0x0400) ? 's' : 'x') :
                (($perms & 0x0400) ? 'S' : '-'));
            $info .= (($perms & 0x0004) ? 'r' : '-');
            $info .= (($perms & 0x0002) ? 'w' : '-');
            $info .= (($perms & 0x0001) ?
                (($perms & 0x0200) ? 't' : 'x') :
                (($perms & 0x0200) ? 'T' : '-'));
            return $info;
        }
    }

    public function fileInfo($file)
    {
        $stat = stat($file);
        $info['ext'] = pathinfo($file, PATHINFO_EXTENSION);
        $info['name'] = basename($file);
        $info['date'] = $stat['ctime'];
        $info['size'] = $this->formatSizeUnits($stat['size']);
        $info['isDir'] = is_dir($file);
        $info['perm'] = $this->filePermissions($file);
        $info['perm_text'] = $this->filePermissions($file, false);
        return $info;
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function readFileContents($file)
    {
        if (file_exists($file)) {
            $file = fopen($file, "r");
            while (!feof($file)) {
                $line = fgets($file);
                $contents .= $line;
            }
            fclose($file);
            return htmlspecialchars($contents);
        } else {
            return false;
        }
    }

    public function showFileContents($file)
    {
        if (file_exists($file)) {
            $file = fopen($file, "r");
            while (!feof($file)) {
                $line = fgets($file);
                $contents .= $line;
            }
            fclose($file);
            return $contents;
        } else {
            return false;
        }
    }

    public function editFile($file, $content)
    {
        if (file_exists($file)) {
            $openfile = fopen($file, "w+");
            fwrite($openfile, $content);
            fclose($openfile);
            return true;
        } else {
            return false;
        }
    }

    public function unZip($file, $path)
    {
        require_once 'php-zip/autoload.php';
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === true) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    public function createZip($name, $files)
    {
        require_once 'php-zip/autoload.php';
        if (!is_array($files)) {
            return false;
        } else {
            $zipFile = new \PhpZip\ZipFile();
            foreach ($files as $file) {
                if (is_dir($file)) {
                    if ($this->isEmpty) {
                        $zipFile->addEmptyDir($file);
                    } else {
                        $zipFile->addDirRecursive($file);
                    }
                    $zipFile->addDirRecursive($file);
                } else {
                    $zipFile->addFile($file);
                }
            }
            $zipFile->saveAsFile($name);
            $zipFile->close();
            return true;
        }
    }

    public function openZip($name, $password = '')
    {
        require_once 'php-zip/autoload.php';
        if (!file_exists($name)) {
            return false;
        } else {
            $zipFile = new \PhpZip\ZipFile();
            if (!empty($password)) {
                $zipFile->setReadPassword;
            }

            $zipFile->openFile($name);
            $listFiles = $zipFile->getListFiles();
            $zipFile->close();
            return $listFiles;
        }
    }

    public function extractZip($name, $path)
    {
        require_once 'php-zip/autoload.php';
        if (!file_exists($name)) {
            return false;
        } else {
            $zipFile = new \PhpZip\ZipFile();
            $zipFile->openFile($name);
            if ($zipFile->extractTo($this->addSlash($path))) {
                return true;
            } else {
                return false;
            }
            $zipFile->close();

        }
    }

    public function addSlash($path)
    {
        $slash_type = (strpos($path, '\\') === 0) ? 'win' : 'unix';
        $last_char = substr($path, strlen($path) - 1, 1);
        if ($last_char != '/' and $last_char != '\\') {
            $path .= ($slash_type == 'win') ? '\\' : '/';
        }
        return $path;
    }

    public function isZip($ext)
    {
        $archives = array('zip');
        if (in_array($ext, $archives)) {
            return true;
        } else {
            return false;
        }
    }

    public function isImage($ext)
    {
        $images = array('bmp', 'gif', 'ico', 'jpeg', 'jpg', 'png', 'svg', 'tif', 'tiff', 'webp');
        if (in_array($ext, $images)) {
            return true;
        } else {
            return false;
        }
    }

    public function isText($ext)
    {
        $texts = array('asp', 'aspx', 'cer', 'cfm', 'cgi', 'pl', 'css', 'htm', 'html', 'js', 'jsp', 'php', 'py', 'rss', 'xhtml', 'c', 'class', 'cpp', 'cs', 'h', 'java', 'pl', 'sh', 'swift', 'vb', 'txt', 'bat', 'cgi', 'pl', 'py', 'xml', 'sql', 'log');
        if (in_array($ext, $texts)) {
            return true;
        } else {
            return false;
        }
    }

    public function isVideo($ext)
    {
        $videos = array('3g2', '3gp', 'avi', 'flv', 'h264', 'm4v', 'mkv', 'mov', 'mp4', 'mpg', 'mpeg', 'rm', 'swf', 'vob');
        if (in_array($ext, $videos)) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmpty($dir)
    {
        if ($files = glob($dir . "/*")) {
            return false;
        } else {
            return true;
        }
    }

}
