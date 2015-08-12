<?php

require_once('class/Watchdog.php');
require_once('class/Upload.php');

error_reporting(E_ERROR);

$dog = new Watchdog();
$hash = isset($_REQUEST['img_token']) ? $_REQUEST['img_token'] : '';
$cate = isset($_REQUEST['cate']) ? $_REQUEST['cate'] : '';

$result = 2001;
$info = '';
$data = [];
if (!$hash) {
    $info = 'need img token';
} elseif (!$cate) {
    $info = 'need cate string';
} else {
    if (!isset($_FILES)) {
        $info = 'no file uploaded';
    } else {
        try {
            $upload = new Upload($cate, $hash);
            foreach ($_FILES as $key => $file) {
                if ($file['name'] && $file['error'] == 0 && $file['size'] > 0) {
                    $file['key'] = $key;
                    $data[] = $dog->host.$upload->upload($file);
                }
            }
            $result = 2000;
            $info = 'success';
        } catch (Exception $e) {
            $info = $e->getMessage();
        }
    }
}
$re = ['result' => $result, 'data' => $data, 'info' => $info];

echo json_encode($re);
exit;
