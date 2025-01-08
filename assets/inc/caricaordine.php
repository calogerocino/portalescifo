<?php
error_reporting(0);
$azione = $_POST['azione'];
include("database.php");


if ($azione == 'cercacliente') {
    //CARICA O CERCA INFORMAZIONI CLIENTE
    $sql = "SELECT ID FROM donl_clienti WHERE Cliente='" . addslashes($_POST['cliente']) . "' AND Via='" . addslashes($_POST['indirizzo']) . "' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idcliente = $row["ID"];
        }
    } else {
        $sql = "INSERT INTO donl_clienti (Cliente, Via, CAP, Citta, Telefono, Cellulare, EMail) VALUES ("
            . "'" . addslashes($_POST['cliente']) . "',"
            . "'" . addslashes($_POST['indirizzo']) . "',"
            . "'" . addslashes($_POST['cap']) . "',"
            . "'" . addslashes($_POST['citta']) . "',"
            . "'" . $_POST['telefono'] . "',"
            . "'" . $_POST['cellulare'] . "',"
            . "'" . $_POST['email'] . "')";
        if (!$conn->query($sql)) {
            echo "Errore della query: " . $conn->error . ". <img height=30 width=30 src=/inc/img/NO.png>";
        } else {
            $sql = "SELECT ID FROM donl_clienti WHERE Cliente='" . addslashes($_POST['cliente']) . "' AND Via='" . addslashes($_POST['indirizzo']) . "' LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idcliente = $row["ID"];
                }
            }
        }
    }
    echo $idcliente;
    exit;
} else if ($azione == 'caricaordine') {
    $sql = "INSERT INTO `donl_ordini`(`NOrdine`, `NFattura`, `IDMarketplace`, `Tracking`, `Piattaforma`, `DataOrdine`, `Corriere`, `Tipo`, `Stato`, `Noteo`, `DataEvasione`, `Pagamento`, `Importo`, `IDCl`, `IDPS`, `DataCreazione`) VALUES ("
        . "'" . $_POST['refordine'] . "',"
        . "'" . $_POST['nfattura'] . "',"
        . "'" . $_POST['idmarket'] . "',"
        . "'" . $_POST['track'] . "',"
        . "'" . $_POST['piattaforma'] . "',"
        . "'" . $_POST['dataordine'] . "',"
        . "'" . $_POST['corriere'] . "',"
        . "'" . $_POST['tsped'] . "',"
        . "'" . $_POST['statoordine2'] . "',"
        . "'" . $_POST['note'] . "',"
        . "'Non Evaso',"
        . "'" . $_POST['pagamento'] . "',"
        . "'" . $_POST['importo'] . "',"
        . "'" .  $_POST['idcliente'] . "',"
        . "'" .  $_POST['idpresta'] . "',"
        . "NOW())";

    if (!$conn->query($sql)) {
        echo 'NO|-|' . $conn->error;
        exit;
    } else {
        //CERCA ID ORDINE
        $sql = "SELECT ID FROM donl_ordini WHERE NOrdine='" . $_POST['refordine'] . "' AND NFattura='" . $_POST['nfattura'] . "' AND Tracking='" . $_POST['track'] . "' LIMIT 1";
        $result = $conn->query($sql);;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $row['ID'] . ",NOW()," . $_POST['statoordine'] . ")");
                echo 'SI|-|' . $row["ID"];
                exit;
            }
        }
    }
} else if ($azione == 'infocorriere') {
    $sql = "INSERT INTO `donl_corriere`(`ID`, `PesoReale`, `PesoVolume`, `Altezza`, `Larghezza`, `Profondita`, `PrezzoInserito`, `Codici`) VALUES ("
        . "'" . $_POST['idordine'] . "',"
        . "'" . $_POST['pesocorr'] . "',"
        . "'" . $_POST['pesovolumecorr'] . "',"
        . "'" . $_POST['altezzacorr'] . "',"
        . "'" . $_POST['larghezzacorr'] . "',"
        . "'" . $_POST['profonditacorr'] . "',"
        . "'" . $_POST['prezzopagcorr'] . "',"
        . "'" .  $_POST['crpi'] . "')";

    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'SI';
    }
    exit;
} else if ($azione == 'caricaprodotti') {
    $idpr = $_POST['idpr'];
    $qpr = $_POST['qpr'];
    $tpr = $_POST['tpr'];
    $ido = $_POST['idordine'];


    $sql = 'INSERT INTO `neg_relpo`( `IDP`, `IDO`, `quantita`, `tipo`) VALUES ("' . $idpr . '", "' . $ido . '", "' . $qpr . '", 1)';
    if (!$conn->query($sql)) {
        $conn->error;
    } else {
        $caricato = "SI";


        $sql1 = 'SELECT sku, disponibilita FROM neg_magazzino WHERE ID="' . $idpr . '"';
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                if ($row1['disponibilita'] >= $qpr) {
                    //no
                } else {
                    $sql1 = 'INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`) VALUES ("' .  $idpr . '",  "' . $qpr . '", "' . $row1['sku'] . '", "' . $ido . '", "' . $tpr . '")';
                    if (!$conn->query($sql1)) {
                        echo $conn->error;
                    } else {
                        $caricato = "SI";
                    }
                }
            }
        }
    }
    echo $caricato;
    exit;
} else if ($azione == 'datiprod') {
    $sql = "SELECT nome FROM neg_magazzino WHERE sku='" . $_POST['codicepr'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row['nome'];
    exit;
} else if ($azione == 'addprod') {
    $codicepr = $_POST['codicepr'];
    $nomepr = $_POST['nomepr'];
    $quantpr = $_POST['quantpr'];
    $totprrs = $_POST['totprrs'];

    $sql = "SELECT ID, disponibilita FROM neg_magazzino WHERE sku='" . $codicepr . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id1 = $row['ID'];
        $quantitaric = $row['disponibilita'];
    }

    if ($quantitaric >= $quantpr) {
        $classe = "class=\"font-weight-bolder text-success\"";
    } else {
        $classe = "class=\"font-weight-bolder text-danger\"";
    }

    echo $codice = '<tr>
        <td><input id="ipr' . $totprrs . '" type="text" class="form-control" value="' . $id1 . '" readonly></td>
        <td><a ' . $classe . '>' . $codicepr . '</a></td>
        <td>' . $nomepr . '</td>
        <td><input id="qpr' . $totprrs . '" type="text" class="form-control" value="' . $quantpr . '" readonly></td>
        <td><input id="tpr' . $totprrs . '" type="text" class="form-control" value="R" readonly></td>
        <tr>';
    exit;
} else if ($azione == 'selezionacliente') {
    $clientepost = $_POST['cliente'];
    $cliente = '';
    $sql = "SELECT * FROM donl_clienti WHERE Cliente='" . $clientepost . "' LIMIT 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row['Cliente'] . ";" .  $row['Via'] . ";" .  $row['CAP'] . ";" .  $row['Citta'] . ";" .  $row['Cellulare'] . ";" .  $row['Cellulare2'] . ";" .  $row['Telefono'] . ";" .  $row['EMail'];
    }
    exit;
}
