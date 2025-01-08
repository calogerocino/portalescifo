<?php
session_start();

if (isset($_SESSION['session_id'])) {
    $session_ipt = htmlspecialchars($_SESSION['session_ipt']);
}



$tipo = $_POST['tipo'];
include("database.php");

if ($tipo == 'daid') {
    $idordine = $_POST['idordine'];

    $sql = "SELECT c.Cellulare, c.Cellulare2, c.Telefono FROM donl_clienti c INNER JOIN donl_ordini o ON c.ID = o.IDCl WHERE o.ID=" . $idordine;
    $result = $conn->query($sql);
    if (empty($result) !== TRUE) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (empty($row['Cellulare']) !== TRUE) {
                    echo $session_ipt . ";" . $row['Cellulare'];
                } else  if (empty($row['Cellulare2']) !== TRUE) {
                    echo $session_ipt . ";" . $row['Cellulare2'];
                } else if (empty($row['Telefono']) !== TRUE) {
                    echo $session_ipt . ";" . $row['Telefono'];
                }
            }
        }
    }
} else if ($tipo == 'nuovo') {
    echo $session_ipt;
} else if ($tipo == 'mail') {
    $idordine = $_POST['idordine'];

    $sql = "SELECT c.EMail FROM donl_clienti c INNER JOIN donl_ordini o ON c.ID = o.IDCl WHERE o.ID=" . $idordine;
    $result = $conn->query($sql);
    if (empty($result) !== TRUE) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo $row['EMail'];
            }
        }
    }
}
