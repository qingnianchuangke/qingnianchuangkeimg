<?php

require_once('class/Watchdog.php');
require_once('class/Upload.php');

$dog = new Watchdog();
$key = isset($_GET['key']) ? $_GET['key'] : '';
$hash = isset($_GET['hash']) ? $_GET['hash'] : '';
$cate = isset($_GET['cate']) ? $_GET['cate'] : '';

$result = false;
$info = '';
if($key != $dog->key){
	$info = 'access deny';
}elseif (!$hash) {
	$info = 'need hash string';
}elseif (!$cate) {
	$info = 'need cate string';
}else{
	if(!isset($_FILES)){
		$info = 'no file uploaded';
	}else{
		try {
			$upload = new Upload($cate, $hash);
			foreach ($_FILES as $key => $file) {
				if($file['name'] && $file['error'] == 0 && $file['size'] > 0){
					$upload->upload($file);
				}
			}
			$result = true;
			$info = 'success';
		} catch (Exception $e) {
			$info = $e->getMessage();
		}
	}
}
$re = ['result' => $result, 'data' => [], 'info' => $info];

echo json_encode($re);
exit;

?>