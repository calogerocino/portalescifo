<?php
error_reporting(0);
session_start();

if (isset($_SESSION['valoreric1'])) {
    $x1 = $_SESSION['valoreric1'] + 1;
} else {
    $x1 = 0;
}
if (isset($_SESSION['valoreric2'])) {
    $x2 = $_SESSION['valoreric2'] + 1;
} else {
    $x2 = 0;
}
include("database.php");
$azione = $_POST['azione'];
$codice = '';


if ($azione == 'datiprod') {
    $codicepr = $_POST['codicepr'];

    $sql = "SELECT nome FROM neg_magazzino WHERE sku='" . $codicepr . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row['Nome'];
    }
} else if ($azione == 'addprod') {
    $codicepr = $_POST['codicepr'];
    $nomepr = $_POST['nomepr'];
    $quantpr = $_POST['quantpr'];



    $sql = "SELECT ID, disponibilita, tipo FROM neg_magazzino WHERE sku='" . $codicepr . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id1 = $row['ID'];
        $quantitaric = $row['disponibilita'];
        $tipo = $row['tipo'];
    }

    if ($quantitaric >= $quantpr) {
        $classe = "class=\"font-weight-bolder text-success\"";
    } else {
        $classe = "class=\"font-weight-bolder text-danger\"";
        $sql1 = "INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `Tipo`) VALUES ($id1, $quantpr, '$codicepr', 1)";
        $conn->query($sql1);
    }

    $codice .= "<tr>";
    $codice .= "<td><input name=\"idpr[" . $x1 . "]\" type=\"text\" class=\"form-control\" value=\"" . $id1 . "\" readonly></td>";
    $codice .= "<td><a $classe>" . $codicepr . "</a></td>";
    $codice .= "<td>" . $nomepr . "</td>";
    $codice .= "<td><input name=\"quantpr[" . $x1 . "]\" type=\"text\" class=\"form-control\" value=\"" . $quantpr . "\" readonly></td>";
    $codice .= "<td><input name=\"tipopr[" . $x1 . "]\" type=\"text\" class=\"form-control\" value=\"" . $tipo . "\"readonly></td>";
    $codice .= "<tr>";
    $_SESSION['valoreric1'] = $x1;

    echo $codice;
} else if ($azione == 'selezionacliente') {
    $clientepost = $_POST['cliente'];
    $cliente = '';
    $sql = "SELECT * FROM donl_clienti WHERE Cliente='" . $clientepost . "' LIMIT 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row['Cliente'] . ";" .  $row['Via'] . ";" .  $row['CAP'] . ";" .  $row['Citta'] . ";" .  $row['Cellulare'] . ";" .  $row['Cellulare2'] . ";" .  $row['Telefono'] . ";" .  $row['EMail'];
    }
} else if ($azione == 'capcitta') {
    $result = $conn->query("SELECT Comune, Provincia FROM donl_comuni WHERE CAP='" . $_POST['cap'] . "' LIMIT 1");
    $row = $result->fetch_assoc();
    echo $row['Comune'] . ' (' . $row['Provincia'] . ')';
}
