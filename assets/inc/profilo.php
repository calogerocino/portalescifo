<?php

//error_reporting(0);
include("database.php");
//$azione = $_POST['azione'];


if (isset($_POST['update'])) {
    $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
    $sql = "UPDATE `app_utenti` SET `user`='" . $_POST['user'] . "',`password`='" . $newpass . "' WHERE `id`=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:profilo:update', $_SESSION['session_idu']);
    exit;
} else if (isset($_POST['updatesp'])) {
    $sql = "UPDATE `app_utenti` SET `user`='" . $_POST['user'] . "' WHERE `id`=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:profilo:updatesp', $_SESSION['session_idu']);
    exit;
} else if (isset($_POST['aggiornau'])) {
    $sql = "UPDATE `app_utenti` SET `" . $_POST['campo'] . "`='" . $_POST['valore'] . "' WHERE `id`=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:profilo:aggiornau', $_SESSION['session_idu']);
    exit;
} else if (isset($_POST['listaattivita'])) {
    $sql = "SELECT * FROM app_activity_log WHERE idu=" . $_SESSION['session_idu'] . " ORDER BY ID DESC";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $dati .= "<tr>
            <td>" . $row['ID'] . "</td>
            <td>" . $row['Azione'] . "</td>
            <td>" . $row['Data'] . "</td>
            </tr>";
        }
    } else {
        $dati .= "<tr><td>NESSUN RISULTATO</td><td></td><td></td></tr>";
    }
    echo $dati;
    exit;
}
