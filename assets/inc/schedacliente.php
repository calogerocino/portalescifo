<?php
//error_reporting(0);

include("database.php");
$azione = $_POST['azione'];


if ($azione == 'daticlienteid') {
    $dati;

    $sql = "SELECT * FROM doff_clienti WHERE ID=" . $_POST['idcl'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Nick'] . "|-|" . $row['Indirizzo'] . "|-|" . $row['Citta'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['PIVA'] . "|-|" . $row['CodFisc'] . "|-|" . $row['SDI'] . "|-|" . $row['PEC'] . "|-|" . $row['Fido'] . "|-|" . $row['Banca'] . "|-|" . $row['IBAN'];
    }

    $sql = "SELECT SUM(Totale) as Tottot FROM ctb_documenti WHERE IDcl=" . $_POST['idcl'] . " AND Pagato!=4";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $totsaldo =  $row['Tottot'];
    }
    $sql = "SELECT SUM(Acconto) as totacc FROM ctb_acconti_cl WHERE IDcl=" . $_POST['idcl'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $totacc = $row['totacc'];
    }
    $dati .= "|-|" . number_format(($totsaldo - $totacc), 2, ',', '.');

    echo $dati;
    exit;
} else if ($azione == 'venditecliente') {
    $sql2 = "SELECT N_Doc, Data, Totale, AgEnt FROM ctb_documenti WHERE IDcl=" . $_POST['idcl'] . " AND Pagato != 4 ORDER BY Data DESC";

    $result2 = $conn->query($sql2);
    if ($result2->num_rows >= 1) {
        while ($row2 = $result2->fetch_assoc()) {
            $data = explode("-",  $row2['Data']);

            if (strpos($row2['N_Doc'], 'BC') !== false) {
                $ndoc = 'Buono di Consegna';
            } else if (strpos($row2['N_Doc'], 'FA') !== false) {
                $ndoc = 'Fattura';
            }


            $risultato .= "<tr>";
            $risultato .= "<td class=\"documento py-2 align-middle ApriDocumento_dc\" style=\"cursor:pointer;\" id=\"" . $row2['N_Doc'] . "\"><div class=\"ml-4\"><div class=\"text-dark-75 fw-bold font-size-lg mb-0\">" . $row2['N_Doc'] . "</div><a href=\"javascript:void(0)\" id=\"" . $row2['N_Doc'] . "\" class=\"small text-muted text-hover-primary\">" . $row2['N_Doc'] . "</a></div></td>";
            $risultato .= "<td class=\"py-2 align-middle text-center data ApriDocumento_dc\" style=\"cursor:pointer;\" id=\"" . $row2['N_Doc'] . "\">" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>";
            $risultato .= "<td class=\"py-2 align-middle text-end fs-0 fw-medium importo ApriDocumento_dc\" style=\"cursor:pointer;\"  id=\"" . $row2['N_Doc'] . "\">€ " . $row2['Totale'] . "</td>";
            $risultato .= "</tr>";
        }
    } else {
        $risultato .= "<tr><td>NESSUN RISULTATO</td><td></td><td></td><td></td></tr>";
    }
    echo $risultato;
    exit;
} else if ($azione == 'visacconti') {
    $clausola = $_POST['clausola'];
    $totale = 0;
    $risultato = '';
    $totaleoff = 0;

    $sql = "SELECT r.ID, c.Cliente, c.Cellulare, CONCAT(r.Marchio,' ', r.Modello) AS Macchina, r.Prodotto, r.Intervento, r.DataIngresso, r.Stato, r.Totale FROM doff_riparazioni r LEFT JOIN doff_clienti c ON (r.idcl=c.id) " . $clausola . " ORDER BY r.ID ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $totale =  number_format($row['Totale'], 2);

            $sql1 = "SELECT Acconto FROM doff_acconti WHERE nscheda=" . $row['ID'];
            $result1 = $conn->query($sql1);
            if ($result1->num_rows >= 1) {
                while ($row1 = $result1->fetch_assoc()) {
                    $totale =  (float)$totale - (float)$row1['Acconto'];
                }
            }

            $risultato .= '<tr>';
            $risultato .= '<td width="5%"><a idr="' . $row['ID'] . '" class="text-info font-weight-bold apririp" href="javascript:void(0);" title="Apri Riparazione">' . $row['ID'] . '</a></td>';
            $risultato .= '<td width="25%">' . $row['Cliente'] . '</td>';
            $risultato .= '<td width="7%">' . $row['Cellulare'] . '</td>';
            $risultato .= '<td width="7%">' . $row['Prodotto'] . '</td>';
            $risultato .= '<td width="16%">' . $row['Macchina'] . '</td>';
            $risultato .= '<td width="7%"><b>' . $row['Intervento'] . '</b></td>';
            $risultato .= '<td width="11%">' . $row['DataIngresso'] . '</td>';
            $risultato .= '<td width="10%"><b><i>' . $row['Stato'] . '</i></b></td>';
            $risultato .= '<td width="7%"><i>' . number_format($totale, 2) . '</i></td>';
            $risultato .= '</tr>';
            if ($row['Stato'] == 'Pagato') {
                $totaleoff = $totaleoff + $totale;
            }
            $totale = $totale + 1;
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td width=\"100%\">NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";

        $totale = 0;
    }
    echo '<table class="table table-hover table-borderless">';
    echo '<thead><tr><th width="5%">Scheda</th><th width="25%">Cliente</th><th width="7%">Cellulare</th><th width="7%">Prodotto</th><th width="16%">Macchina</th><th width="7%">Intervento</th><th width="11%">Data Ingresso</th><th width="10%">Stato</th><th width="7%">Totale</th></tr></thead>';
    echo '<tbody class="table-hover">' . $risultato . '</tbody>';
    echo '<tfoot><tr><th>' . $totale . ' voci</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>€ ' . number_format($totaleoff, 2) . '</th></tr></tfoot>';
    echo '</table>';
    exit;
} else if ($azione == 'aggiornacliente') {
    $sql = "UPDATE `doff_clienti` SET `" . $_POST['campo'] . "`='" . $_POST['valore'] . "' WHERE `ID`=" . $_POST['idcl'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:schedacliente:aggiornacliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'caricanuovo') {
    $sql = "INSERT INTO `doff_clienti`(`Cliente`, `Nick`, `Indirizzo`, `Citta`, `Cellulare`, `Mail`, `PIVA`, `CodFisc`, `SDI`, `PEC`, `Fido`, `Banca`, `IBAN`) VALUES ('" . $_POST['cliente'] . "','" . $_POST['nick'] . "','" . $_POST['indirizzo'] . "','" . $_POST['citta'] . "','" . $_POST['cellulare'] . "','" . $_POST['mail'] . "','" . $_POST['piva'] . "','" . $_POST['codfisc'] . "','" . $_POST['sdi'] . "','" . $_POST['pec'] . "','" . $_POST['fido'] . "','" . $_POST['banca'] . "','" . $_POST['iban'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:schedacliente:caricanuovo', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'storicoprodotti') {
    $idcl = $_POST['idcl'];
    $risultato = '';
    $sql = "SELECT p.Codice, p.Nome, p.Prezzo, p.Quantita FROM ctb_relpo p LEFT JOIN ctb_documenti d ON (d.N_Doc=p.IDdoc) WHERE d.IDcl=" . $idcl . " GROUP BY p.Nome ORDER BY p.Prezzo ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $risultato .= '<tr>';
            $risultato .= '<td width="13%">' . $row['Codice'] . '</a></td>';
            $risultato .= '<td width="61%">' . $row['Nome'] . '</td>';
            $risultato .= '<td width="13%">' . $row['Prezzo'] . '</td>';
            $risultato .= '<td width="13%">' . $row['Quantita'] . '</td>';
            $risultato .= '</tr>';
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td width=\"100%\">NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
    }

    echo '<table class="table table-hover table-borderless">';
    echo '<thead><tr><th width="13%">Codice</th><th width="61%">Descrizione</th><th width="13%">Prezzo</th><th width="13%">Quantita</th></tr></thead>';
    echo '<tbody class="table-hover">' . $risultato . '</tbody>';
    echo '</table>';
    exit;
} else if ($azione == 'saldoclienteid') {
    $tot1 = 0;
    $tot2 = 0;
    $sql = "SELECT Fido FROM doff_clienti WHERE ID=" . $_POST['idcl'] . " LIMIT 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $result1 = $conn->query("SELECT SUM(Totale) as Tottot FROM ctb_documenti WHERE IDcl=" . $_POST['idcl'] . " AND Pagato!=4");
        $row1 = $result1->fetch_assoc();
        $tot1 = $row1['Tottot'];
        $result2 = $conn->query("SELECT SUM(Acconto) as totacc FROM ctb_acconti_cl WHERE IDcl=" . $_POST['idcl']);
        $row2 = $result2->fetch_assoc();
        $tot2 = $row2['totacc'];
        echo number_format($row['Fido'], 2, ',', '.') . '|-|' . number_format(($tot1 - $tot2), 2, ',', '.');
    }


    exit;
}
