<?php
//error_reporting(0);

include("database.php");
$azione = $_POST['azione'];


if ($azione == 'datifornitoreid') {
    $dati;

    $sql = "SELECT * FROM ctb_fornitore WHERE ID=" . $_POST['idfor'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Ragione_Sociale'] . "|-|" . $row['Citta'] . "|-|" . $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['Banca'] . "|-|" . $row['Iban'] . "|-|" . $row['BIC'] . "|-|" . $row['PIVA'] . "|-|" . $row['CodFisc'] . "|-|" .  $row['SDI'] . "|-|" .  $row['PEC'] . "|-|" .  $row['scontofornitore'];
    }

    echo $dati;
    exit;
} else if ($azione == 'venditefornitore') {
    $idfor = $_POST['idfor'];
    $percsaldo = '';
    $percentuale = '';

    $ndoc = '';

    $sql = "SELECT fa.ID AS IDDOC, fa.NDoc, fa.DataDoc, fa.TotDoc, fa.TipoDoc, fa.Saldo, f.ID AS IDRC, f.Ragione_Sociale FROM ctb_fatture fa INNER JOIN ctb_fornitore f ON (fa.Fornitore=f.ID) WHERE f.ID=$idfor ORDER BY fa.DataDoc ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $data = explode("-",  $row['DataDoc']);

            if ($row['TotDoc'] >= 1) {
                $percsaldo = ($row['Saldo'] * 100) / $row['TotDoc'];
            } else {
                $percsaldo = 0;
            }
            $percentuale = '<div class="progress bg-200 me-2" style="height: 5px;"><div class="progress-bar rounded-pill" role="progressbar" style="width: ' . number_format($percsaldo, 0)  . '%" aria-valuenow="' . number_format($percsaldo, 0) . '" aria-valuemin="0" aria-valuemax="100"></div></div>';

            $risultato .= '<tr>
                                <td><div class="ml-4"><div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' . $row['TipoDoc'] . '</div><a href="javascript:void(0)" idd="' . $row['IDDOC'] . '" class="small text-muted font-weight-bold text-hover-primary aprifatt">' . $row['NDoc'] . '</a></div></td>
                                <td><a href="javascript:void(0)" class="text-hover-primary ApriFornitore_fa" idfor="' . $row['IDRC'] . '">' . $row['Ragione_Sociale'] . '</a></td>
                                <td>'. $data[2] . '/' . $data[1] . '/' . $data[0] . '</td>
                                <td>€ ' . $row['TotDoc'] . '</td>
                                <td>' . $percentuale . '</td>
                            </tr>';

            $totale = $totale + $row['TotDoc'];
        }
    } else {
        $risultato .= '<tr><td class="align-middle text-center" colspan="4">NESSUN RISULTATO</td></tr>';
        $totale = 0;
    }
    echo '<table class="table table-hover table-borderless table-striped">
                <thead class="bg-200 text-900"><tr><th>N° Documento</th><th>Fornitore</th><th>Data Documento</th><th>Totale Documento</th><th>Saldato %</th></tr></thead>
                <tbody>' . $risultato . '</tbody>
                <tfoot class="bg-200 text-900"><tr><th colspan="3"></th><th id="totaledoc">€ ' . $totale . '</th><th></th></tr></tfoot>
        </table>';
    exit;
} else if ($azione == 'econtofornitore') {
    $idfornitore = $_POST['idfor'];
    $descrizione;
    $dati = '';
    $risultato = array();
    $olddare = (float)0;
    $oldavere = (float)0;
    $oldscad = (float)0;

    $cliente[1] = '';

    $sql = "SELECT DataDoc, NDoc, TotDoc FROM ctb_fatture WHERE Fornitore=" . $idfornitore;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {

        $descrizione = 'Doc. N° ' . $row['NDoc'] . ' del ' . $row['DataDoc'];

        array_push($risultato, array($row['DataDoc'],  $row['NDoc'], $descrizione, '', $row['TotDoc'], '', '0'));
    }

    $sql = "SELECT Data, Descrizione, Acconto, idpag FROM ctb_acconti_fo WHERE IDfo=" . $idfornitore;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "<div class=\"font-weight-bold text-success\">PAGAMENTO</div>", $row['Descrizione'], $row['Acconto'], '', '', $row['idpag']));
    }

    $sql = "SELECT p.ID, p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.Pagato=0  AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "<div class=\"font-weight-bold text-warning\">SCADENZA</div>", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo'], $row['ID']));
    }

    $sql = "SELECT p.ID, p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.Pagato=4  AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "<div class=\"font-weight-bold text-danger\">SCADUTO</div>", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo'], $row['ID']));
    }

    $sql = "SELECT p.ID, p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.Pagato=2  AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "<div class=\"font-weight-bold text-danger\">SOSPESO</div>", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo'], $row['ID']));
    }

    $sql = "SELECT Ragione_Sociale, Citta FROM ctb_fornitore WHERE ID=" . $idfornitore;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cliente[0] = $row['Ragione_Sociale'];
        $cliente[1] = $row['Citta'];
    }

    asort($risultato);

    $apripag = '';
    foreach ($risultato as $dato1 => $dato2) {
        $apripag = $dato2[6] != 0 ? 'style="cursor:pointer;" class="apripag_sca" id="' . $dato2[6] . '"' : '';
        if ($dato2[2] == 'Titolo a garanzia ') {
            $dati .=  '<tr ' .  $apripag . '>
        <td><i>' . $datanew[2] . '/' . $datanew[1] . '/' . $datanew[0] . '</i></td>
        <td>' . $dato2[1] . '</td>
        <td>' . $dato2[2] . '</td>
        <td>' . number_format($dato2[3], 2, ',', '.') . '</td>
        <td>' . number_format($dato2[4], 2, ',', '.') . '</td>
        <td>' . number_format($dato2[5], 2, ',', '.') . '</td>
        <td>€ ' . number_format((((float)$oldavere - (float)$olddare) - (float)$oldscad), 2) . '</td>
        </tr>';
        } else {
            $pos = strpos($dato2[1], 'SCADUTO');

            $olddare = (float)$dato2[3] + (float)$olddare;
            $oldavere = (float)$dato2[4] + (float)$oldavere;
            if ($pos === false) {
                $oldscad = (float)$dato2[5] + (float)$oldscad;
            }
            $datanew = explode('-', $dato2[0]);

            $dati .=  '<tr ' .  $apripag . '>
        <td><i>' . $datanew[2] . '/' . $datanew[1] . '/' . $datanew[0] . '</i></td>
        <td>' . $dato2[1] . '</td>
        <td>' . $dato2[2] . '</td>
        <td>' . number_format($dato2[3], 2, ',', '.') . '</td>
        <td>' . number_format($dato2[4], 2, ',', '.') . '</td>
        <td>' . number_format($dato2[5], 2, ',', '.') . '</td>
        <td>€ ' . number_format((((float)$oldavere - (float)$olddare) - (float)$oldscad), 2, ',', '.') . '</td>
        </tr>';
        }
    }

    echo '<table class="table table-hover table-borderless">
    <thead class="bg-200 text-900"><tr><th>Data</th><th>Causale</th><th>Descrizione</th><th>Dare</th><th>Avere</th><th>A scadere</th><th>Saldo</th></tr></thead>
    <tbody>' . $dati . '</tbody>
    <tfoot class="bg-200 text-900">
    <th class="text-end" colspan="3">TOTALI</th>
    <th>€ ' . number_format((float)$olddare, 2, ',', '.') . '</th>
    <th>€ ' . number_format((float)$oldavere, 2, ',', '.') . '</th>
    <th>€ ' . number_format((float)$oldscad, 2, ',', '.') . '</th>
    <th>€ ' . number_format(((float)$oldavere - (float)$olddare) - (float)$oldscad, 2, ',', '.') . '</th>
    </tr>
    </tfoot>
    </table>';
    exit;
} else if ($azione == 'aggiornafornitore') {
    $sql = "UPDATE `ctb_fornitore` SET `" . $_POST['campo'] . "`='" . $_POST['valore'] . "' WHERE `ID`=" . $_POST['idfor'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:schedafornitore:aggiornafornitore', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'caricanuovo') {
    $sql = "INSERT INTO `ctb_fornitore` (`Ragione_Sociale`, `Citta`, `Indirizzo`, `Cellulare`, `Mail`, `Banca`, `Iban`, `BIC`, `PIVA`, `CodFisc`, `SDI`, `PEC`) VALUES ('" . $_POST['ragsoc'] . "','" . $_POST['citta'] . "','" . $_POST['indirizzo'] . "','" . $_POST['cellulare'] . "','" . $_POST['mail'] . "','" . $_POST['piva'] . "','" . $_POST['codfisc'] . "','" . $_POST['sdi'] . "','" . $_POST['pec'] . "','" . $_POST['banca'] . "','" . $_POST['iban'] . "','" . $_POST['bic'] . "')";
    if (!$conn->query($sql)) {
        echo $sql;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:schedafornitore:caricanuovo', $_SESSION['session_idu']);
    exit;
}
