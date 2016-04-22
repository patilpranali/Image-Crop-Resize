<?php
require_once ('MysqliDb.php');

class ImageUploader {
    private $hostname = '127.0.0.8';
    private $username = 'root';
    private $password = '';
    private $dbname = 'test';
    private $table_name_images =  'images';

    function Init() {
    	$config = parse_ini_file('myapp.ini');
    	$this->hostname = $config['hostname'];
    	$this->username = $config['username'];
    	$this->password = $config['password'];
    	$this->dbname = $config['dbname'];
      $this->table_name_images = $config['table_name_images'];
		  $mysqli = new mysqli ($this->hostname, $this->username, $this->password, $this->dbname);
		  $db = new MysqliDb ($mysqli);
    }

    function InsertImage($filename) {
      	if( isset($filename) ) {
    			$db = MysqliDb::getInstance();
    			$data = Array ("filename" => $filename);
    			$id = $db->insert($this->table_name_images, $data);
    			return $id;
  		}
    }
    function CropImage($uploaded_file, $cropped_file, $to_crop_array, $extension) {
      $extension = strtolower($extension);
      echo 'extension:' . $extension . ',';
      if($extension == 'jpg' || $extension == 'jpeg') {
        $originalFile = imagecreatefromjpeg($uploaded_file);
        //$croppedImage = imagecrop($originalFile, $to_crop_array);
        $croppedImage = $this->CustomCrop($originalFile, $to_crop_array);
        imagejpeg($croppedImage, $cropped_file, 100);
      }
      else if ($extension == 'png') {
        $originalFile = imagecreatefrompng($uploaded_file);
        $croppedImage = imagecrop($originalFile, $to_crop_array);
        imagepng($croppedImage, $cropped_file, 100);
      }
    }
	function CustomCrop($src, array $rect)
	{
		$dest = imagecreatetruecolor($rect['width'], $rect['height']);
		imagecopy(
			$dest,
			$src,
			0,
			0,
			$rect['x'],
			$rect['y'],
			$rect['width'],
			$rect['height']
		);

		return $dest;
	}
}

$path_parts = pathinfo($_FILES["image"]["name"]);
$extension = pathinfo($_POST["file-name"])['extension'];
$file_name = pathinfo($_POST["file-name"])['filename'];
$uploaded_file = 'uploads/'. $file_name . '_orig.' . $extension ;
$cropped_file = 'uploads/'. $file_name . '_cropped.' . $extension ;
$to_crop_array = array('x' =>$_POST["imageData-x"] ,
    'y' =>$_POST["imageData-y"],
    'width' => $_POST["imageData-width"],
    'height'=> $_POST["imageData-height"]);

move_uploaded_file($_FILES["image"]["tmp_name"], $uploaded_file);

$obj = new ImageUploader();
$obj->Init();
$obj->CropImage($uploaded_file, $cropped_file, $to_crop_array, $extension);
echo 'filename' . $cropped_file . 'OUTPUT' . $obj->InsertImage($cropped_file);
?>
