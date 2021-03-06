<?php

require_once('class/Watchdog.php');
require_once('class/Upload.php');

$dog = new Watchdog();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$hash = isset($_GET['hash']) ? $_GET['hash'] : '';
$cate = isset($_GET['cate']) ? $_GET['cate'] : '';

$result = false;
$info = '';
$data = [];
if ($key != $dog->key) {
    $info = 'access deny';
} elseif (!$hash) {
    $info = 'need hash string';
} elseif (!$cate) {
    $info = 'need cate string';
} elseif (!$id) {
    $info = 'nedd id';
} else {
    $upload = new Upload($cate, $hash);
    try {
        $data = $upload->save($id);
        $result = true;
        $info = 'success';
    } catch (Exception $e) {
        $info = $e->getMessage();
    }
}
$re = ['result' => $result, 'data' => $data, 'info' => $info];

echo json_encode($re);
exit;
