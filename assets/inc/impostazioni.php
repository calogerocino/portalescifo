<?php
error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

if ($azione == 'setimp') { //SU DATA SCAD. PRENDERE SOLO MESE
    if ($_POST['tipo'] != 3) {
        $sql = "UPDATE app_impostazioni SET valore='" . $_POST['valore'] . "' WHERE tipo=" . $_POST['tipo'];
        if (!$conn->query($sql)) {
            echo $conn->error;
        } else {
            echo 'ok';
        }
    } else {
        $key = explode(';', $_POST['valore']);
        $k = -(int)$key[0];
        $kk = 0;
        if (isset($key[1])) {
            $key2 =  substr($key[1],  $k);
            if (strlen($key2) == $key[0]) {
                $kk = (-14 +  $k);
                $DataKey =  substr($key[1], $kk, 14);
                $UnivocoKey =  explode(substr($DataKey, 0, 4), $key[1]);
                if ($UnivocoKey[0] == testunivoco($DataKey, (int)($k + 1))) {
                    $sql = "UPDATE app_impostazioni SET valore='" . $_POST['valore'] . "' WHERE tipo=" . $_POST['tipo'];
                    if (!$conn->query($sql)) {
                        echo $conn->error;
                    } else {
                        echo 'Codice valido!';
                    }
                } else {
                    echo 'Codice non valido [$uni]';
                }
            } else {
                echo 'Codice non valido [$lun]';
            }
        } else {
            echo 'Codice non valido [$key]';
        }
    }
    activitylog($conn, 'up:impostazioni:setimp', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'chemessaggio') {
    $result = $conn->query("SELECT valore FROM app_impostazioni WHERE tipo=" . $_POST['tipo']);
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
} else if ($azione == 'scodeprog') {
    $result = $conn->query("SELECT valore FROM donl_dati WHERE tipo=2");
    $row = $result->fetch_assoc();
    echo $row['valore'];
    $conn->query("UPDATE donl_dati SET valore='" . ((int)$row['valore'] + 1) . "' WHERE tipo=2");
    exit;
} else if ($azione == 'listautenti') {
    $data;
    $result = $conn->query("SELECT * FROM app_utenti");
    while ($row = $result->fetch_assoc()) {
        $result1 = $conn->query("SELECT home FROM app_permessi WHERE IDU=" . $row['id']);
        $row1 = $result1->fetch_assoc();
        $data .= '<tr style="cursor:pointer;" onclick="imp_mostrapermessi(' . $row['id'] . ')">
                <td class="py-2 align-middle text-center white-space-nowrap"><img src="assets/img/team/' . $row['user'] . '.svg" class="img-fluid rounded-circle mb-2" width="80px" height="80px" alt="' . $row['nome'] . '"></td>
                <td class="py-2 align-middle">' . $row['nome'] . '</td>
                <td class="py-2 align-middle">' . $row['user'] . '</td>
                <td class="py-2 align-middle">' . $row['Ruolo'] . '</td>
                <td class="py-2 align-middle"><input class="form-control form-control-sm" onchange="CambiaPermessiHome(' . $row['id'] . ')" id="homepermut_' . $row['id'] . '" value="' . $row1['home'] . '" /></td>
                </tr>';
    }
    echo $data;
    exit;
} else if ($azione == 'mostrapermessi') {
    $data;
    $result = $conn->query("SELECT * FROM app_permessi WHERE IDU=" . $_POST['idu'] . " LIMIT 1");
    $row = $result->fetch_assoc();
    echo $row['s_Online'] . '|-|' . $row['s_PVend'] . '|-|' . $row['s_Amm'] . '|-|' . $row['s_Altro'];
    exit;
} else if ($azione == 'salvaperm') {
    $sql = "UPDATE app_permessi SET " . $_POST['campo'] . "='" . $_POST['valore'] . "' WHERE IDU=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:impostazioni:salvaperm', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cr') {

    $pk = '-----BEGIN RSA PRIVATE KEY----- ... -----END RSA PRIVATE KEY-----';
    $kh = openssl_pkey_get_private($pk);
    $details = openssl_pkey_get_details($kh);

    echo strtoupper(bin2hex($_POST['dati']));
} else if ($azione == 'dec') {
    $pk = '-----BEGIN RSA PRIVATE KEY----- ... -----END RSA PRIVATE KEY-----';
    $kh = openssl_pkey_get_private($pk);
    $details = openssl_pkey_get_details($kh); // convert data from hexadecimal notation 
    echo $data = pack('H*', $_POST['dati']);
    if (openssl_private_decrypt($data, $r, $kh)) {
        echo $r;
    }
} else if ($azione == 'licensekey') {
    $result = $conn->query("SELECT valore FROM app_impostazioni WHERE tipo=3");
    $row = $result->fetch_assoc();
    $key = explode(';', $row['valore']);
    $k = -(int)$key[0];
    $kk = 0;
    $key2 =  substr($key[1],  $k);
    if (strlen($key2) == $key[0]) {
        $kk = (-14 +  $k);
        $DataKey =  substr($key[1], $kk, $k);
        $UnivocoKey =  explode(substr($DataKey, 0, 4), $key[1]);
        $DataKey =  substr($DataKey, 0, 4) . '-' . substr($DataKey, 4, 2) . '-' . substr($DataKey, 6, 2) . ' ' . substr($DataKey, 8, 2) . ':' . substr($DataKey, 10, 2) . ':' . substr($DataKey, 12, 2);
        echo  $key2 . '|-|' . $DataKey . '|-|' . $UnivocoKey[0];
    } else {
        echo 'NV';
    }
    exit;
} else if ($azione == 'homepage') {
    $result = $conn->query("SELECT home FROM app_permessi WHERE IDU=" . $_POST['idu']);
    $row = $result->fetch_assoc();
    echo $row['home'];
    exit;
} else if ($azione == 'DatiDeiDati') {
    $dati = '';

    //STATI ORDINE
    $result = $conn->query("SELECT * FROM donl_dati WHERE tipo=1");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="donl_dati" value="' . $row['ID'] . '">' . $row['valore'] . '</option>';
    }
    $dati .= ';';
    //PROGRESSIVO PRODOTTI NEGOZIO
    $result = $conn->query("SELECT * FROM donl_dati WHERE tipo=2");
    while ($row = $result->fetch_assoc()) {
        $dati .=  $row['valore'];
    }
    $dati .= ';';
    //FORNITORI PRODOTTI
    $result = $conn->query("SELECT * FROM doff_dati WHERE tipo=1");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="doff_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //TIPO PRODOTTI
    $result = $conn->query("SELECT * FROM doff_dati WHERE tipo=2");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="doff_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //TIPI RICAMBI
    $result = $conn->query("SELECT * FROM doff_dati WHERE tipo=3");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="doff_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //BANCHE
    $result = $conn->query("SELECT * FROM ctb_dati WHERE tipo=3");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="ctb_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //TIPO PAGAMENTO (SCADENZA)
    $result = $conn->query("SELECT * FROM ctb_dati WHERE tipo=4");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="ctb_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //TIPO PAGAMENTO (FATTURA)
    $result = $conn->query("SELECT * FROM ctb_dati WHERE tipo=5");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="ctb_dati" value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $dati .= ';';
    //TIPO PAGAMENTO (FATTURA)
    $result = $conn->query("SELECT * FROM neg_iva");
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option tab="neg_iva" value="' . $row['ID'] . '">[<b>' . $row['CodiceIva'] . '</b>] ' . $row['Descrizione'] . '</option>';
    }

    echo $dati;
    exit;
} else if ($azione == 'changehome') {
    $sql = "UPDATE app_permessi SET home='" . $_POST['home'] . "' WHERE IDU=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:impostazioni:changehome', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creadato') {
    $sql = "INSERT INTO `" . $_POST['tabella'] . "`(`dato`, `valore`, `tipo`) VALUES ('" . $_POST['dato'] . "','" . $_POST['valore'] . "','" . $_POST['tipo'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:impostazioni:creadato', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercalink') {
    $result = $conn->query("SELECT valore FROM app_impostazioni WHERE tipo=4");
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
} else if ($azione == 'cercapresta') {
    $result = $conn->query("SELECT valore FROM app_impostazioni WHERE tipo=5");
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
}else if ($azione == 'cercamano') {
    $result = $conn->query("SELECT valore FROM app_impostazioni WHERE tipo=8");
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
} else if ($azione == 'modificalink') {
    $sql = "UPDATE `app_impostazioni` SET `valore`='" . $_POST['valore'] . "' WHERE `tipo`='" . $_POST['tipo'] . "'";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:impostazioni:creadato', $_SESSION['session_idu']);
    exit;
}

function testunivoco($TestKey, $ForKey)
{
    $NewTestKey = $TestKey;
    for ($i = 0; $i <= ($ForKey * -1); $i++) {
        $NewTestKey = sha1($NewTestKey);
    }
    return $NewTestKey;
}
