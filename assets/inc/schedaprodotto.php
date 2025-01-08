<?php
//error_reporting(0);

include("database.php");
$azione = $_POST['azione'];


if ($azione == 'caricaprodotto') {
    $dati;

    $sql = "SELECT `ID`, `Codice`, `EAN`, `Nome`, `UM`, `Quantita`, `Prezzo`, `QNegozio`, `Fornitori`, `sco1`, `sco2`, `prezforn`, `ricarico`, `Note`, `TAG`, `Tipo` , `Disponibilita` FROM `doff_prodotti` WHERE `ID`=" . $_POST['idpr'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Codice'] . "|-|" . $row['EAN'] . "|-|" . $row['Nome'] . "|-|" . $row['Quantita'] . "|-|" . $row['Prezzo'] . "|-|" . $row['QNegozio'] . "|-|" . $row['Fornitori'] . "|-|" . $row['sco1'] . "|-|" . $row['sco2'] . "|-|" . $row['prezforn'] . "|-|" .  $row['ricarico'] . "|-|" .  $row['Note'] . "|-|" .  $row['TAG'] . "|-|" .  $row['Tipo'] . "|-|" .  $row['UM'] . "|-|" .  $row['Disponibilita'] . "|-|";
    }

    $sql = "SELECT cv.IDCV, cv.IDC, cv.valore FROM doff_car_val cv LEFT JOIN doff_car_valpr cvp ON cv.IDCV=cvp.IDCV  WHERE cvp.IDPR=" . $_POST['idpr'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati .= $row['IDC'] . ";" . $row['IDCV'] . ";" .  $row['valore'] . "/-/";
    }

    echo $dati;
    exit;
} else if ($azione == 'aggiornaprodotto') {
    $sql = "UPDATE neg_magazzino SET " . $_POST['campo'] . "='" . addslashes($_POST['valore']) . "' WHERE id=" . $_POST['idpr'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotto:aggiornaprodotto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornaforn') {
    $sql = "UPDATE neg_magazzino SET fornitori='" . $_POST['fornitori'] . "' WHERE id=" . $_POST['idpr'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotto:aggiornaforn', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creaprodotto') {
    $sql = "INSERT INTO neg_magazzino (sku, ean, nome, um, disponibilita, prezzo, fornitori, PrezzoAcquisto, ricarico, descrizione, tag, tipo) VALUES ('" . $_POST['ref'] . "', '" . $_POST['ean'] . "', '" . $_POST['nomeprod'] . "', '" . $_POST['um'] . "', '" . $_POST['quantmag'] . "','" . $_POST['prezzove'] . "', '" . $_POST['fornitori'] . "', '" . $_POST['prezforn'] . "', '" . $_POST['ricarico'] . "', '" . $_POST['descrprod'] . "', '" . $_POST['tag'] . "', 'Ricambio')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotto:creaprodotto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'codicericambio') {
    $result = $conn->query('SELECT valore FROM doff_dati WHERE dato =\'' . $_POST['testo'] . '\' AND Tipo=' . $_POST['tipo'] . ' LIMIT 1');
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
} else if ($azione == 'add1') {
    $valore = 0;
    $result = $conn->query("SELECT valore FROM doff_dati WHERE ID=232");
    $row = $result->fetch_assoc();
    $var =  substr($_POST['ref'], -4);
    if ($var == $row['valore']) {
        $valore = ($row['valore'] + 1);
        $conn->query("UPDATE doff_dati SET valore='" . $valore . "' WHERE ID=232");
        activitylog($conn, 'up:schedaprodotto:add1', $_SESSION['session_idu']);
    }
    exit;
} // else if ($azione == 'carsrc') {
//     $count = $_POST['count'];
//     $idsl = $_POST['idsl'];
//     $dati = '<label>Caratteristiche<select class="form-control selectpicker" data-style="btn btn-link" id="carpr' . $count . '">';
//     $sql = "SELECT IDC, descrizione FROM car_desc";
//     $result = $conn2->query($sql);
//     while ($row = $result->fetch_assoc()) {
//         if ($idsl == $row['IDC']) {
//             $dati .= '<option value="' . $row['IDC'] . '" selected>' . $row['descrizione'] . '</option>';
//         } else {
//             $dati .= '<option value="' . $row['IDC'] . '" >' . $row['descrizione'] . '</option>';
//         }
//     }
//     $dati .= '</select></label>';
//     echo $dati;
//     exit;
// }
