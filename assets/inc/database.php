<?php
error_reporting(0);
session_start();

$host = "localhost";
// username dell'utente in connessione
$user = "oxy80n60_pxluser";  //
// password dell'utente
$password = "Pxluser&&00"; //

// stringa di connessione al DBMS
$conn = new mysqli($host, $user, $password, 'oxy80n60_pxlsmt'); //
//$name1 = mysqli_connect($host, $user, $password, $database);
$pdo = new PDO('mysql:host=localhost;dbname=oxy80n60_pxlsmt', $user, $password); //
// verifica su eventuali errori di connessione
if ($conn->connect_errno) {
    echo "Connessione fallita: " . $conn->connect_error . ".";
    exit();
}

function activitylog($con, $act, $idu)
{
    $sql = "INSERT INTO `app_activity_log`(`idu`, `Azione`, `Data`) VALUES ('" . $idu . "','" . $act . "',NOW())";
    if (!$con->query($sql)) {
        //echo "Errore: " . $con->error;
    } else {
        // echo 'log creato';
    }
}
