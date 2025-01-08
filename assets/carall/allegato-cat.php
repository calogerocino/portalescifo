<?php
if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo 'fileno';
    exit;
}

$uploaddir = str_replace('v3/', '', $_SERVER['DOCUMENT_ROOT']) . '/upload/image/p/';
$userfile_tmp = $_FILES['userfile']['tmp_name'];
$userfile_name = $_POST['idfile'] . '.jpg';
// $_FILES['userfile']['name'];

if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
    echo $userfile_name;
} else {
    echo 'carno;' . $_FILES['userfile']['error'];
}
