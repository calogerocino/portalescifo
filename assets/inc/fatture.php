<?php
error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

$risultato = '';
$totale = 0;

if ($azione == 'modpag') {
    $risultato = '<option value="-">Selezionare</option>';
    $sql = "SELECT ID, dato FROM ctb_dati WHERE tipo=5";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $risultato .= '<option value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    $risultato .= '<option value="nuovo">- Aggiungi nuovo -</option>';
    echo $risultato;
    exit;
} else if ($azione == 'carica') {
    $sql = "INSERT INTO `ctb_fatture`(`Fornitore`, `TipoDoc`, `NDoc`, `DataDoc`, `Imp22`, `Imp10`, `Imp4`, `ImpEse`, `Note`, `Pagamento`, `Allegato`, `TotDoc`, `Saldo`) VALUES ('" . $_POST['fornitore'] . "','" . $_POST['tipodoc'] . "','" . $_POST['ndoc'] . "','" . $_POST['datadoc'] . "','" . $_POST['imp22'] . "','" . $_POST['imp10'] . "','" . $_POST['imp4'] . "','" . $_POST['impese'] . "','" . $_POST['note'] . "','" . $_POST['pagamento'] . "','" . $_POST['allegato'] . "','" . $_POST['totdoc'] . "','0.00')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:fatture:carica', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiorna') {
    $statopag = $_POST['statopag'];
    $datapag = $_POST['datapag'];
    $clausola = $_POST['clausola'];
    $percsaldo = '';
    $percentuale = '';
    $pulsanti = '';
    $ndoc = '';

    $sql = "SELECT fa.ID AS IDDOC, fa.NDoc, fa.DataDoc, fa.TotDoc, fa.TipoDoc, fa.Saldo, f.ID AS IDRC, f.Ragione_Sociale FROM ctb_fatture fa INNER JOIN ctb_fornitore f ON (fa.Fornitore=f.ID) " . $statopag . $clausola . $datapag . " ORDER BY fa.DataDoc ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $data = explode("-",  $row['DataDoc']);

            if ($row['Saldo'] == '0.00') {
                $pulsanti = "<td class=\"align-middle py-2 white-space-nowrap text-end\"><div class=\"dropdown font-sans-serif position-static\"><button class=\"btn btn-link text-600 btn-sm dropdown-toggle btn-reveal\" type=\"button\" id=\"order-dropdown-2\" data-bs-toggle=\"dropdown\" data-boundary=\"viewport\" aria-haspopup=\"true\" aria-expanded=\"false\">
                <svg class=\"svg-inline--fa fa-ellipsis-h fa-w-16 fs--1\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"ellipsis-h\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\"><path fill=\"currentColor\" d=\"M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z\"></path></svg>
                </button>
                <div class=\"dropdown-menu dropdown-menu-end border py-0\" aria-labelledby=\"order-dropdown-2\" style=\"\"><div class=\"bg-white py-2\">  
                <li><a class=\"dropdown-item eliminafatt\" id=\"" . $row['IDDOC'] . "\" href=\"javascript:void(0)\" title=\"Elimina\"><i class=\"fa-regular fa-trash-alt\"></i> Elimina</a></li>
                </div>
                </div>
                </div></td>";
            }

            $risultato .= "
            <tr>
                <td class=\"documento py-2 align-middle ApriFattura_fa\" idd=\"" . $row['IDDOC'] . "\" style=\"cursor: pointer;\"><a href=\"javascript:void(0)\"> <strong>#" . $row['IDDOC'] . "</strong></a> tipo<br> <strong>" . $row['TipoDoc'] . "</strong></td>
                <td class=\"intestatario py-2 align-middle\"><a href=\"javascript:void(0)\" class=\"text-hover-primary aprifornitore_sca\" idfor=\"" . $row['IDRC'] . "\">" . $row['Ragione_Sociale'] . "</a></td>
                <td class=\"datadoc py-2 align-middle ApriFattura_fa\" idd=\"" . $row['IDDOC'] . "\" style=\"cursor: pointer;\">" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>
                <td class=\"importo py-2 align-middle text-end fs-0 fw-medium ApriFattura_fa\" idd=\"" . $row['IDDOC'] . "\" style=\"cursor: pointer;\">€ " . $row['TotDoc'] . "</td>
               " . $pulsanti . "</tr>";
            $totale = $totale + $row['TotDoc'];
        }
    } else {
        $risultato .= '<tr>
        <td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>
    </tr>';

        $totale = '0.00';
    }
    echo $risultato . '|-| ' . number_format((float)$totale, 2, ',', '.');
    exit;
} else if ($azione == 'apridoc') {
    $risultato = '
    ';
    $sql = "SELECT f.*, fo.Ragione_Sociale FROM ctb_fatture f INNER JOIN ctb_fornitore fo ON (f.Fornitore=fo.ID) WHERE f.ID=" . $_POST['idd'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $risultato .= $row['Ragione_Sociale'] . '|-|' . $row['NDoc'] . '|-|' . $row['DataDoc'] . '|-|' . $row['Imp22'] . '|-|' . $row['Imp10'] . '|-|' . $row['Imp4'] . '|-|' . $row['ImpEse'] . '|-|' . $row['Note'] . '|-|' . $row['Pagamento'] . '|-|' . $row['Allegato'] . '|-|' . $row['TotDoc'] . '|-|' . $row['ID'];
    }
    echo $risultato;
    exit;
} else if ($azione == 'listafatture') {
    $sql = "SELECT NDoc, DataDoc, TotDoc, Saldo FROM ctb_fatture WHERE Fornitore=" . $_POST['idforn'];

    $voci = 0;
    $totale;
    $percsaldo = 0;
    $saldo = 0;

    $result1 =  $conn->query("SELECT SUM(Acconto) AS TotaleSal FROM ctb_acconti_fo WHERE IDfo=" . $_POST['idforn']);
    $row1 = $result1->fetch_assoc();
    $saldo = $row1['TotaleSal'];

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $saldo = $saldo - $row['TotDoc'];
            $percsaldo = ($row['Saldo'] * 100) / $row['TotDoc'];

            $percentuale = '<div class="d-flex flex-column w-100 mr-2">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted mr-2 font-size-sm font-weight-bold">' . number_format($percsaldo, 0) . '%</span>
                <span class="text-muted font-size-sm font-weight-bold">Saldo</span>
            </div>
            <div class="progress progress-xs w-100">
                <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($percsaldo, 0) . '%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>';


            $risultato .= "<tr>";
            $risultato .= "<td><div class=\"ml-4\"><div class=\"text-dark-75 font-weight-bolder font-size-lg mb-0\">Fattura</div></div></td>";
            $risultato .= "<td>" . $row['DataDoc'] . "</td>";
            $risultato .= "<td>" . $row['NDoc'] . "</td>";
            $risultato .= "<td>" . $row['TotDoc'] . "</td>";
            $risultato .= "<td>" . $percentuale . "</td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            $totale = $totale + $row['TotDoc'];
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUNA FATTURA</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";

        $voci = 0;
        $totale = 0;
    }
    echo '<table class="table">';
    echo '<thead><tr><th>Documento</th><th>Data Doc.</th><th>Num. Doc.</th><th>Totale</th><th>Saldato %</th></tr></thead>';
    echo '<tbody class="table-hover">' . $risultato . '</tbody>';
    echo '<tfoot><tr><th>' . $voci . ' voci</th><th></th><th></th><th>€ ' . $totale . '</th><th></th></tr></tfoot>';
    echo '</table>';
    echo '|-|'  . $totale;

    exit;
} else if ($azione == 'accontifornitore') {
    $sql = "SELECT ID, Descrizione, Data, Acconto FROM ctb_acconti_fo WHERE IDfo=" . $_POST['idforn'];

    $voci = 0;
    $totale;

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $risultato .= "<tr>";
            $risultato .= "<td>" . $row['Descrizione'] . "</td>";
            $risultato .= "<td>" . $row['Data'] . "</td>";
            $risultato .= "<td id=\"" . $row['ID'] . "\">" . $row['Acconto'] . "</td>";
            $risultato .= "<td width=\"12%\"><a href=\"#\" id=\"" . $row['ID'] . "\" class=\"d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm eliminaacc\"><i class=\"far fa-save fa-sm text-white-50\"></i> Elimina</a></td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            $totale = $totale + $row['Acconto'];
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN ACCONTO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
        $voci = 0;
        $totale = 0;
    }

    echo '<table class="table">';
    echo '<thead><tr><th>Descrizione</th><th>Data</th><th>Acconto</th><th>Azioni</th></tr></thead>';
    echo '<tbody class="table-hover">' . $risultato . '</tbody>';
    echo '<tfoot><tr><th>' . $voci . ' voci</th><th></th><th id="totaleacconti">€ ' . $totale . '</th><th></th></tr></tfoot>';
    echo '</table>';
    echo '|-|'  . $totale;
    exit;
} else if ($azione == 'addacconto') {
    $response = '';
    $impacc =  $_POST['acc'];
    $sql = "INSERT INTO `ctb_acconti_fo`(`IDfo`, `Acconto`, `Data`, `Descrizione`, `idpag`) VALUES (" . $_POST['IDfo'] . ",'" . $impacc . "','" . $_POST['dataacc'] . "','" . $_POST['comm'] . "', " . $_POST['idpag'] . ")";
    $conn->query($sql);

    while ($impacc >= 1) {
        $sql = "SELECT NDoc, TotDoc, Saldo FROM ctb_fatture WHERE Fornitore=" . $_POST['IDfo'] . " AND Saldo < TotDoc ORDER BY DataDoc ASC LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows >= 1) {
            while ($row = $result->fetch_assoc()) {
                if (($row['Saldo'] + $impacc) < $row['TotDoc']) {
                    $impacc = $row['Saldo'] + $impacc;
                    $sql = "UPDATE `ctb_fatture` SET Saldo='" . $impacc . "' WHERE NDoc='" . $row['NDoc'] . "'";
                    if (!$conn->query($sql)) {
                        $response = 'no';
                    } else {
                        $response = 'si';
                    }
                    $impacc = 0;
                } else if (($row['Saldo'] + $impacc) >= $row['TotDoc']) {
                    $sql = "UPDATE `ctb_fatture` SET Saldo='" . $row['TotDoc'] . "' WHERE NDoc='" . $row['NDoc'] . "'";
                    if (!$conn->query($sql)) {
                        $response = 'no';
                    } else {
                        $response = 'si';
                    }
                    $impacc = $impacc - ($row['TotDoc'] - $row['Saldo']);
                }
            }
        }
    }
    echo $response;
    activitylog($conn, 'in:fatture:addacconto', $_SESSION['session_idu']);
    exit;
} else if ($azione == "aggiornaallegato") {
    $sql = "UPDATE ctb_fatture SET Allegato='" . $_POST['allegato'] . "' WHERE ID=" . $_POST['idfatt'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "ok";
    }
    activitylog($conn, 'up:fatture:aggiornaallegato', $_SESSION['session_idu']);
    exit;
} else if ($azione == "modificafatt") {
    $sql = "UPDATE ctb_fatture SET " . $_POST['campo'] . "='" . $_POST['valore'] . "', TotDoc='" . $_POST['totale'] . "' WHERE ID=" . $_POST['idfatt'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "ok";
    }
    activitylog($conn, 'up:fatture:modificafatt', $_SESSION['session_idu']);
    exit;
} else if ($azione == "elimina") {
    $sql = "DELETE FROM `ctb_fatture` WHERE ID=" . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'el:fatture:elimina', $_SESSION['session_idu']);
    exit;
}