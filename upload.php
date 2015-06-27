<?php

require_once('class/Watchdog.php');
require_once('class/Upload.php');

error_reporting(E_ERROR);

$dog = new Watchdog();
$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
$hash = isset($_REQUEST['imgToken']) ? $_REQUEST['imgToken'] : '';
$cate = isset($_REQUEST['cate']) ? $_REQUEST['cate'] : '';

$result = false;
$info = '';
$data = [];
if ($key != $dog->key) {
    $info = 'access deny';
} elseif (!$hash) {
    $info = 'need hash string';
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
                    $data[] = $upload->upload($file);
                }
            }
            $result = true;
            $info = 'success';
        } catch (Exception $e) {
            $info = $e->getMessage();
        }
    }
}
$re = ['result' => $result, 'data' => $data, 'info' => $info];

echo json_encode($re);
exit;
