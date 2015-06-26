<?php

require_once('class/Watchdog.php');
require_once('class/Upload.php');

$dog = new Watchdog();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$cate = isset($_GET['cate']) ? $_GET['cate'] : '';

$data = [];
$result = false;
$info = '';
if ($key != $dog->key) {
    $info = 'access deny';
} elseif (!$cate) {
    $info = 'need cate string';
} elseif (!$id) {
    $info = 'nedd id';
} else {
    $upload = new Upload($cate, $id);
    try {
        $data = $upload->getList($id);
        $result = true;
        $info = 'success';
    } catch (Exception $e) {
        $info = $e->getMessage();
    }
}
$re = ['result' => $result, 'data' => $data, 'info' => $info];

echo json_encode($re);
exit;
