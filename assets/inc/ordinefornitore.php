<?php
include("database.php");
//error_reporting(0);
$azione = $_POST['azione'];
if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
    $session_ruolo = htmlspecialchars($_SESSION['session_ruolo']);
}


if ($azione == 'aggiorna') {
    $dati = '';
    $fornitore = '';
    $clausola = $_POST['clausola'];
    $mm = $_POST['mm'];
    $totrighe = 0;
    $totprezzo = 0;
    $totquantita = 0;

    if ($mm != 0) {
        $clausola .= ' AND p.tipo=' . $mm;
    }

    $sql = "SELECT o.ID AS IDR, p.ID, o.Codice, p.nome, o.Quantita, o.Fornitore, o.Prezzo, p.fornitori, o.Tipo, o.note FROM neg_ordine_prodotti o INNER JOIN neg_magazzino p ON (o.IDPR=p.ID) $clausola";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $fornitore = '';
            $quantita = '';
            $inviaordine = '';

            $dati .= '<tr>';

            if ($row['Tipo'] == 1) {
                $dati .= '<td class="align-middle py-2 text-center fs-0 white-space-nowrap"><span class="badge badge rounded-pill d-block badge-soft-info">RICAMBIO</span></td>';
            } else if ($row['Tipo'] == 2) {
                $dati .= '<td class="align-middle py-2 text-center fs-0 white-space-nowrap"><span class="badge badge rounded-pill d-block badge-soft-warning">PRODOTTO</span></td>';
            } else if ($row['Tipo'] == 3) {
                $dati .= '<td class="align-middle py-2 text-center fs-0 white-space-nowrap"><span class="badge badge rounded-pill d-block badge-soft-danger">' . ($row['note'] ? $row['note'] : 'OFFICINA') . '</span></td>';
            } else {
                $dati .= '<td class="align-middle py-2 text-center fs-0 white-space-nowrap"><span class="badge badge rounded-pill d-block badge-soft-success">NESSUNO</span></td>';
            }
            $dati .= '<td class="align-middle fs-0 white-space-nowrap"><a href="javascript:void(0)" onclick="ApriProdotto_ca(' . $row['ID'] . ')">' . $row['Codice'] .  '</a></td>';
            $dati .= '<td class="align-middle">' . $row['nome'] . '</td>';

            $fornitore .= '<select class="form-control selectpicker" data-style="btn btn-link" id="fornitore' . $row['IDR'] .  '" onchange="assegnafornitore(' . $row['IDR'] .  ')">';
            if ($row['Fornitore'] == 'NON ASSEGNATO') {
                $prezzo = '0,00';
                $quantita =  $row['Quantita'];
                $fornitore .= '<option value="NON ASSEGNATO;' . $prezzo . '" selected>' . $row['Fornitore'] . '</option>';
                $inviaordine = '';
            } else {
                $prezzo = $row['Prezzo'];
                $quantita = '<input type="number" id="' . $row['IDR'] . '" value="' . $row['Quantita'] . '" class="form-control form-control-sm" onchange="cambiaquant(' . $row['IDR'] . ')">';
                $fornitore .= '<option selected>' . $row['Fornitore'] . '</option>';
                $inviaordine = '<a href="javascript:void(0)" class="btn btn-falcon-default btn-sm neworder">INVIA ORDINE <i class="fa-solid fa-arrow-right"></i></a>';
            }


            if ($row['fornitori'] != '') {
                $res2 = explode('/-/', $row['fornitori']);
                foreach ($res2 as $value) {
                    $res3 = explode(';', $value);
                    $fornitore .= '<option value="' . $res3[1] . ';' . $res3[2] . ';' . $res3[0] . '">[' . $res3[0] . '] ' . $res3[1] . ' (€ ' . $res3[2] . ')</option>';
                }
            }

            $fornitore .= '<option value="ANNULLATO;0.00">ANNULLATO</option>';
            $fornitore .= '</select>';

            $dati .= '<td class="align-middle text-center fs-0">' . $quantita . '</td>';
            $dati .= '<td class="align-middle">' . $fornitore . '</td>';
            $dati .= '<td class="align-middle text-center fs-0">€ ' . $prezzo . '</td>';
            $dati .= '</tr>';

            $totprezzo = (float)$totprezzo + (float)$prezzo;
            $totquantita = (int)$totquantita + (int)$row['Quantita'];
            $totrighe = $totrighe + 1;
        }

        if ($totprezzo > 0) {
            $dati .= '<tr><td colspan="3"></td><td class="py-2 align-middle text-end fs-0 fw-medium">' . $totquantita . '</td><td></td><td class="py-2 align-middle text-end fs-0 fw-medium">€ ' . $totprezzo . '</td></tr>';
        }
        if ($totrighe >= 1 && $inviaordine != '') {
            $dati .= '<tr><td colspan="5"></td><td>' . $inviaordine . '</td></tr>';
        }
    } else {
        $dati .= '<tr class="text-center py-2 align-middle"><td colspan="6">Nessun dato disponibile</td></tr>';
    }

    echo ' <thead class="bg-200 text-900">
    <tr>
        <th class="sort text-center" data-sort="richiesta">Richiesta</th>
        <th class="sort" data-sort="codice">Codice</th>
        <th class="sort" data-sort="nome">Nome</th>
        <th class="sort text-center" data-sort="quantita">Quantita</th>
        <th class="sort" data-sort="fornitore">Fornitore</th>
        <th class="sort text-center" data-sort="prezzo">Prezzo</th>
    </tr>
    </thead>
    <tbody class="list">' . $dati . '</tbody>';
    exit;
} else if ($azione == 'aggiornafornitore') {
    $sql = "UPDATE neg_ordine_prodotti SET Fornitore='" . $_POST['fornitore'] . "', Prezzo='" . $_POST['prezzo'] . "', Codice='" . $_POST['codice'] . "' WHERE ID=" . $_POST['idr'];
    if (!$conn->query($sql)) {
        echo  $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'up:ordine-ricambi:aggiornafornitore', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornaquantita') {
    $sql = "UPDATE neg_ordine_prodotti SET Quantita='" . $_POST['quantita'] . "' WHERE ID=" . $_POST['idr'];
    if (!$conn->query($sql)) {
        echo  $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'up:ordine-ricambi:aggiornaquantita', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'neworder') {
    $nordine = 0;
    $sql1 = "SELECT NOrdine FROM neg_ordine_prodotti WHERE Fornitore='" . $_POST['forn'] . "' ORDER BY DataOrdine DESC LIMIT 1";
    $result = $conn->query($sql1);
    $row = $result->fetch_assoc();
    $nordine = (intval($row['NOrdine']) + 1);

    $sql = "UPDATE neg_ordine_prodotti SET NOrdine='" . $nordine . "', DataOrdine=NOW() WHERE Fornitore='" . $_POST['forn'] . "' AND NOrdine='0'";
    if (!$conn->query($sql)) {
        echo  $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'up:ordine-ricambi:neworder', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercainforicambio') {
    $nordine = 0;
    $sql1 = "SELECT * FROM neg_ordine_prodotti WHERE IDO='" . $_POST['idord'] . "' AND IDPR='" . $_POST['idpr'] . "' LIMIT 1";
    $result = $conn->query($sql1);
    $row = $result->fetch_assoc();
    if ($row['Fornitore'] == 'NON ASSEGNATO' || $row['Fornitore'] == 'ANNULLATO' || $row['Fornitore'] == '') {
        echo 'Nessun ordine trovato';
    } else {
        $data = explode('-', $row['DataOrdine']);
        echo 'Ordine eseguito il ' .  $data[2] . '/' . $data[1] . '/' . $data[0] . ' su ' . $row['Fornitore'];
    }
    exit;
} else if ($azione == 'fornitori') {
    $checkforn = [];;
    $sql1 = "SELECT DISTINCT Fornitore FROM neg_ordine_prodotti WHERE Fornitore != 'NON ASSEGNATO' AND Fornitore != 'ANNULLATO' AND NOrdine=0 ORDER BY Fornitore ASC";
    $result = $conn->query($sql1);
    $row = $result->fetch_assoc();
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $dati .= '<option value="' . $row['Fornitore'] . '">' . $row['Fornitore'] . '</option>';
            array_push($checkforn, $row['Fornitore']);
        }
    }

    $dati .= '<option disabled>-----</option>';
    $dati .= '<option disabled>ALTRO</option>';
    $dati .= '<option disabled>-----</option>';

    $sql2 = "SELECT DISTINCT Fornitore FROM neg_ordine_prodotti WHERE Fornitore != 'NON ASSEGNATO' AND Fornitore != 'ANNULLATO' AND NOrdine!=0 ORDER BY Fornitore ASC";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows >= 1) {
        while ($row1 = $result2->fetch_assoc()) {
            if (!in_array($row1['Fornitore'], $checkforn)) {
                $dati .= '<option value="' . $row1['Fornitore'] . '">' . $row1['Fornitore'] . '</option>';
            }
        }
    }

    echo $dati;
    exit;
} else if ($azione == 'daordinemanuale') {
    echo $sql1 = "INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`, `NomeManuale`, `Fornitore`, `Note`) VALUES (0,  '" .  $_POST['quantita'] . "', '" . strtoupper($_POST['codice']) . "', 0, 3, '" . strtoupper($_POST['nome']) . "', 'NON ASSEGNATO', '" . $_POST['note'] . "')";
    if (!$conn->query($sql1)) {
        echo $conn->error;
    } else {
        echo "SI";
    }
    activitylog($conn, 'up:ordine-ricambi:daordinemanuale', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'consigli') {
    $mm = ($_POST['mm'] != '0' ?  ' AND tipo=' . $_POST['mm'] : '');
    $sql = "SELECT COUNT(IDO) as Totord, IDP, SUM(quantita) AS Totquant FROM neg_relpo GROUP BY IDP ORDER BY Totquant DESC";
    $result = $conn->query($sql);
    $data = $result->num_rows;
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Totquant'] >= 3 && $row['Totord'] >= 3) {
                echo    $sql1 = "SELECT ID, nome, sku, prezzo, disponibilita FROM neg_magazzino WHERE disponibilita=0 AND ID=" . $row['IDP'] . $mm;
                $result1 = $conn->query($sql1);
                if ($result1->num_rows >= 1) {
                    while ($row1 = $result1->fetch_assoc()) {
                        echo 'ciao';
                        $dati .= '<tr>
                        <td class="py-2 align-middle text-center fs-0 white-space-nowrap"><img width="64" height="auto" src="https://portalescifo.it/upload/image/p/' . $row1['ID'] . '.jpg"/></td>
                        <td class="py-2 align-middle" style="cursor:pointer;" onclick="ApriProdotto_ca(' . $row1['ID'] . ')">' . $row1['sku'] . '</td>
                        <td class="py-2 align-middle" style="cursor:pointer;" onclick="ApriProdotto_ca(' . $row1['ID'] . ')">' . $row1['nome'] . '</td>
                        <td class="py-2 align-middle text-center fs-0 white-space-nowrap">' . $row['Totquant'] . '</td>
                        <td class="py-2 align-middle text-center fs-0 white-space-nowrap">' . $row1['disponibilita'] . ' <a href="javascript:void(0)" title="Aggiungi ricambio" onclick="addcartric(' . $row1['ID'] . ')"><i class="fa-solid fa-cart-plus"></i></a></td>
                        </tr>';
                    }
                }
            }
        }
    }

    echo ' <thead class="bg-200 text-900">
    <tr>
        <th class="sort text-center" data-sort="codice">Immagine</th>
        <th class="sort" data-sort="nome">Codice</th>
        <th class="sort text-center" data-sort="quantita">Nome</th>
        <th class="sort text-center" data-sort="fornitore">Quantità venduta</th>
        <th class="sort text-center" data-sort="prezzo">Disponibilità</th>
    </tr>
    </thead>
    <tbody class="list">' . $dati . '</tbody>';
    exit;
} else if ($azione == 'ord-data') {
    $sql = "SELECT DISTINCT NOrdine, DataOrdine FROM neg_ordine_prodotti WHERE Fornitore='" . $_POST['forn'] . "' GROUP BY DataOrdine ORDER BY DataOrdine DESC";
    $result = $conn->query($sql);
    $data = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        echo '<option label="' . $row['NOrdine'] . '">' . $row['DataOrdine'] . '</option>';
    }
    exit;
}
