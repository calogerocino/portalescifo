<?php
error_reporting(0);
include("database.php");
$azione = $_POST['azione'];
$dati = '';

if ($azione == 'daticliente') {
    $sql = "SELECT * FROM doff_clienti WHERE ID='" . $_POST['cliente'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['Citta'] . "|-|" . $row['Nick'];
    }

    echo $dati;
    exit;
} else if ($azione == 'aggiornacliente') {
    $sql = "UPDATE `doff_clienti` SET `Cliente`='" . $_POST['clientecl'] . "', `Nick`='" . $_POST['nickcl'] . "', `Citta`='" . $_POST['cittacl'] . "', `Indirizzo`='" . $_POST['indcl'] . "', `Cellulare`='" . $_POST['cellcl'] . "', `Mail`='" . $_POST['mailcl'] . "' WHERE `ID`=" . $_POST['idcliente'];
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:regmacch:aggiornacliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creanuovocliente') {
    $sql = "INSERT INTO `doff_clienti`(`Cliente`, `Nick`, `Citta`, `Indirizzo`, `Cellulare`, `Mail`) VALUES ('" . $_POST['clientecl'] . "','" . $_POST['nickcl'] . "','" . $_POST['cittacl'] . "','" . $_POST['indcl'] . "','" . $_POST['cellcl'] . "','" . $_POST['mailcl'] . "')";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        $sql = "SELECT ID FROM doff_clienti WHERE Cliente='" . $_POST['clientecl'] . "' AND Nick='" . $_POST['nickcl'] . "' AND Citta='" . $_POST['cittacl'] . "' AND Cellulare='" . $_POST['cellcl'] . "'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo $row['ID'];
        }
    }

    activitylog($conn, 'in:regmacch:creanuovocliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'seriale') {
    if (strlen($_POST['seriale']) >= 5) {
        $sql = "SELECT ID FROM doff_regmacch WHERE Seriale LIKE '%" . $_POST['seriale'] . "%'";
        $result = $conn->query($sql);
        if ($result->num_rows >= 1) {
            echo 'no';
        } else {
            echo 'si';
        }
    }
    exit;
} else if ($azione == 'salvaregistrazione') {
    $sql = "INSERT INTO doff_regmacch (Marchio, Prodotto, Modello, Seriale, idcl, DataAcquisto) VALUES ('" . $_POST['marchio'] . "','" . $_POST['prodotto'] . "','" . $_POST['modello'] . "','" . $_POST['seriale'] . "','" . $_POST['idcl'] . "', NOW())";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        echo 'no';
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:regmacch:salvaregistrazione', $_SESSION['session_idu']);
    exit;
}
