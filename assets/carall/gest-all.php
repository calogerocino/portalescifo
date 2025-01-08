<?php
error_reporting(0);
$azione = $_POST['azione'];

if ($azione == 'elall') {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/v2/assets/img/' . $_POST['cartella'] . '/' .  $_POST['idfile'] . '.jpg';
    if (unlink($file)) {
        echo 'ok';
    } else {
        echo 'no';
    }
}
