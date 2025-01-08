<?php
include('database.php');
error_reporting(0);
$azione = $_POST['azione'];
if ($azione == 'prodotti') {
    $sql = "SELECT dato FROM doff_dati WHERE tipo=2";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export =  $export . "" . $row['dato'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'marchi') {
    $sql = "SELECT dato FROM doff_dati WHERE tipo=1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export =  $export . "" . $row['dato'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'tiporicambi') {
    $sql = "SELECT dato, valore FROM doff_dati WHERE tipo=3";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export =  $export . "" . $row['dato'] . " (" . $row['valore'] . "),";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'tiporicambi2') {
    $sql = "SELECT dato FROM doff_dati WHERE tipo=3";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export =  $export . "" . $row['dato'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'fornitori') {
    $sql = "SELECT ID, Ragione_Sociale FROM ctb_fornitore";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= " [" . $row['ID'] . "] "  . $row['Ragione_Sociale'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'clienti') {
    $sql = "SELECT ID, (case when (`Nick` != '')  then CONCAT(`Cliente`, ' (',`Nick`,')') when (`Nick`='') then `Cliente` end)AS Clientec FROM doff_clienti";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= " [" . $row['ID'] . "] "  . $row['Clientec'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'cliention') {
    $sql = "SELECT ID, Cliente FROM donl_clienti";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= " [" . $row['ID'] . "] "  . $row['Cliente'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'citta-cron') {
    $sql = "SELECT CONCAT(Comune, ' (', Provincia, ')' AS Citta FROM donl_comuni";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export =  $export . "\"" . $row['Citta'] . "\",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'fornitoriprod') {
    $sql = "SELECT dato FROM doff_dati WHERE tipo=1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= $row['dato'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'avvocati') {
    $sql = "SELECT nome FROM amm_avvocati";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= $row['nome'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
} else if ($azione == 'dipendenti') {
    $sql = "SELECT Dipendente FROM amm_dipendenti";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $export .= $row['Dipendente'] . ",";
        }
    }
    $export = substr($export, 0, strlen($export) - 1);
    echo $export;
    exit;
}
