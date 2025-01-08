<?php
//error_reporting(0);

include("database.php");
$azione = $_POST['azione'];


if ($azione == 'caricaprodotto') {
    $dati;
    $comn = '';

    $sql = "SELECT * FROM `neg_magazzino` WHERE `ID`=" . $_POST['idpr'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['nome'] . "|-|" . $row['sku'] . "|-|" . $row['ean'] . "|-|" . $row['prezzo'] . "|-|" . $row['fornitori'] . "|-|" . $row['ricarico'] . "|-|" . $row['PrezzoAcquisto'] . "|-|" . $row['um'] . "|-|" .  $row['disponibilita'] . "|-|" .  $row['PrestashopDisponibilita'] . "|-|" .  $row['peso'] . "|-|" .  $row['tag'] . "|-|" .  $row['descrizione'] . "|-|" .  $row['ID1'] . "|-|" .  $row['PrestashopPrezzo'] . "|-|" .  $row['iva'] . "|-|" .  $row['DataDisponibilita'] . "|-|" .  $row['tipo'] . "|-|" .  $row['Mostra']; //0-19


        //    $sql1 = "SELECT * FROM `neg_combinazioni` WHERE `ID1`=" . $row['ID1'];
        //     $result1 = $conn->query($sql1);
        //     if ($result1->num_rows >= 1) {
        //         $comn = '|-|<table class="table table-hover table-borderless" width="100%" cellspacing="0"><thead><tr><td>Combinazione</td><td>Riferimento</td><td>Prezzo</td><td>Disponibilità</td></tr></thead><tbody>';
        //         while ($row1 = $result1->fetch_assoc()) {
        //             $comn .= '<tr>
        //         <td>' . $row1['combinazione'] . '</td>
        //         <td>' . $row1['sku'] . '</td>
        //         <td class="text-info font-weight-bold"><div class="ml-4"><div class="text-primary font-size-lg mb-0">Banco: € <div class="text-center" id="ModComb_' . $row['ID1'] . '_prezzo" contenteditable="">' . $row1['prezzo'] . '</div></div>Marketplace: € <div class="text-center" id="ModComb_' . $row['ID1'] . '_PrestashopPrezzo" contenteditable="">' . $row1['PrestashopPrezzo'] . '</div></div></td>
        //         <td class="text-info font-weight-bold"><div class="ml-4"><div class="text-primary font-size-lg mb-0">Magazzino:  <div class="text-center" id="ModComb_' . $row['ID1'] . '_disponibilita" contenteditable="">' . $row1['disponibilita'] . '</div></div>Prestashop: <div class="text-center" id="ModComb_' . $row['ID1'] . '_PrestashopDisponibilita" contenteditable="">' . $row1['PrestashopDisponibilita'] . '</div></div></td>



        //         </tr>';
        //         }
        //         $comn .= '</tbody></table>';
        //     }
    }

    // class="dif" SU TR INIZILAE
    //class="prodotto-comb" SU TBODY prodotti
    // <table class="blocco-2">ean, vendita, prezzo, valori, riferimenti, id, id1, apripresta</table>

    echo $dati;
    exit;
} else if ($azione == 'aggiornaprodotto') {
    $sql = "UPDATE neg_magazzino SET " . $_POST['campo'] . "='" . addslashes($_POST['valore']) . "' WHERE ID=" . $_POST['idpr'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotton:aggiornaprodotto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornaforn') {
    $sql = "UPDATE neg_magazzino SET fornitori='" . $_POST['fornitori'] . "' WHERE ID=" . $_POST['idpr'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotton:aggiornaforn', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creaprodotto') {
    $sql = "INSERT INTO neg_magazzino (ID1, nome, sku, ean, prezzo, PrestashopPrezzo, fornitori, ricarico, PrezzoAcquisto, disponibilita, PrestashopDisponibilita, peso, descrizione, tag, marca, stato, tipo) VALUES
     (0,'" . $_POST['np'] . "','" . $_POST['cp'] . "','" . $_POST['ep'] . "','" . $_POST['pvp'] . "', '0.00','" . $_POST['f'] . "','" . $_POST['r'] . "','" . $_POST['pap'] . "','" . $_POST['m'] . "',0,0,'" . $_POST['dp'] . "','" . $_POST['tp'] . "','Vivai Scifo Store', 1,'" . $_POST['tipo'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotton:creaprodotto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'rangeprod') {
    $sql = "SELECT * FROM donl_costi_spedizione";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        if ((floatval($_POST['peso']) <= floatval($row['range2'])) && (floatval($_POST['peso']) >= floatval($row['range1']))) {
            echo $row['prezzo'];
            exit;
        }
    }
} else if ($azione == 'codicericambio') {
    $result = $conn->query('SELECT valore FROM doff_dati WHERE dato =\'' . $_POST['testo'] . '\' AND Tipo=' . $_POST['tipo'] . ' LIMIT 1');
    $row = $result->fetch_assoc();
    echo $row['valore'];
    exit;
} else if ($azione == 'add1') {
    $valore = 0;
    $result = $conn->query("SELECT valore FROM doff_dati WHERE ID=" . $_POST['id']);
    $row = $result->fetch_assoc();
    $var =  $_POST['ref'];
    if ($var == $row['valore']) {
        $valore = ($row['valore'] + 1);
        $conn->query("UPDATE doff_dati SET valore='" . $valore . "' WHERE ID=" . $_POST['id']);
        activitylog($conn, 'up:schedaprodotto:add1', $_SESSION['session_idu']);
    }
    exit;
} else if ($azione == 'caricaiva') {
    $dati = '';
    $sql = "SELECT * FROM neg_iva ORDER BY ID DESC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati .= '<option title="' . $row['Descrizione'] . '" value="' . $row['Aliquota'] . '">' . $row['CodiceIva'] . '</option>';
    }
    echo $dati;
    exit;
} else if ($azione == 'ModificaCombinazione') {
    $sql = "UPDATE neg_combinazioni SET " . $_POST['c'] . "='" . $_POST['v'] . "' WHERE ID=" . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:schedaprodotton:ModificaCombinazione', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'generaupc') {
    $result = $conn->query('SELECT valore FROM donl_dati WHERE tipo=3');
    $row = $result->fetch_assoc();
    $r = $row['valore'];

    $result = $conn->query('SELECT valore FROM donl_dati WHERE tipo=4');
    $row = $result->fetch_assoc();
    $l = $row['valore'];

    $p[0] = $r . $l;

    $somma_d = $p[0][1] + $p[0][3] + $p[0][5] + $p[0][7] + $p[0][9] + $p[0][11];
    $somma_p = $p[0][0] + $p[0][2] + $p[0][4] + $p[0][6] + $p[0][8] + $p[0][10];
    $somma_pm = $somma_p * 3;
    $somma_t = $somma_d + $somma_pm;
    $check = (ceil($somma_t / 10)) * 10;
    $p[1] = $check - $somma_t;

    $conn->query("UPDATE donl_dati SET valore='" . ($l + 1) . "' WHERE tipo=4");
    echo $p[0] . $p[1];
    exit;
}
