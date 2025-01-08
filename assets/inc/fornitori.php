<?php
include("database.php");
//errore_reporting(0)
$azione = $_POST['azione'];

if ($azione == 'aggiorna') {
    $clausola = $_POST['clausola'];
    $dati = '';
    $totaledoc = 0;
    $totalesal = 0;
    $totaleimp = 0;

    $sql = "SELECT ID, Ragione_Sociale, Cellulare FROM ctb_fornitore " . $clausola . " ORDER BY Ragione_Sociale ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $result1 =  $conn->query("SELECT SUM(TotDoc) AS TotaleDoc, SUM(Saldo) AS TotaleSal FROM ctb_fatture WHERE Fornitore=" . $row['ID']);
            $row1 = $result1->fetch_assoc();

            $result2 =  $conn->query("SELECT SUM(Importo) AS TotaleImp FROM ctb_pagamento WHERE Intestatario=" . $row['ID'] . " AND Pagato=0 AND Tipo_Pag!=50");
            $row2 = $result2->fetch_assoc();

            $result3 =  $conn->query("SELECT SUM(Importo) AS TotaleImpScad FROM ctb_pagamento WHERE Intestatario=" . $row['ID'] . " AND Pagato=4 AND Tipo_Pag!=50");
            $row3 = $result3->fetch_assoc();

            $result4 =  $conn->query("SELECT SUM(Acconto) AS TotaleSal FROM ctb_acconti_fo WHERE IDfo=" . $row['ID']);
            $row4 = $result4->fetch_assoc();

            if ($row1['TotaleDoc'] >= 1) {
                $dati .= '<tr>
                    <td><button class="btn btn-falcon-default me-1 mb-1" type="button" onclick="window.open(currentURL + \'assets/pdf/e-conto-forn.php?create_pdf=1&idfo=' . $row['ID'] . '\', \'PDF Documento\', \'width=800, height=800, status, scrollbars=1, location\');"><span class="fa-regular fa-file-invoice me-1" data-fa-transform="shrink-3"></span></button></td>
                    <td idfor="' . $row['ID'] . '" class="fornitore py-2 align-middle aprifornitore" style="cursor:pointer;"><span class="fw-bolder">' . $row['Ragione_Sociale'] .  '</span></td>
                    <td class="avere py-2 align-middle text-center fs-0 fw-bold text-primary">€ ' . number_format($row1['TotaleDoc'], 2) . '</td>
                    <td class="dare py-2 align-middle text-center fs-0 fw-bold text-info">€ ' . number_format($row4['TotaleSal'], 2) . '</td>
                    <td class="scadere py-2 align-middle text-center fs-0 fw-bold text-warning">€ ' . number_format($row2['TotaleImp'], 2) . '</td>
                    <td class="scaduto py-2 align-middle text-center fs-0 fw-bold text-danger">€ ' . number_format((((float)$row1['TotaleDoc'] - (float)$row4['TotaleSal']) - (float)$row2['TotaleImp']), 2) . '</td>
                </tr>';

                $totaledoc = $totaledoc + $row1['TotaleDoc'];
                $totalesal = $totalesal + $row4['TotaleSal'];
                $totaleimp = $totaleimp + $row2['TotaleImp'];
            } else {
                $dati .= '<tr>
                <td><button class="btn btn-falcon-default me-1 mb-1" type="button" onclick="window.open(currentURL + \'assets/pdf/e-conto-forn.php?create_pdf=1&idfo=' . $row['ID'] . '\', \'PDF Documento\', \'width=800, height=800, status, scrollbars=1, location\');"><span class="fa-regular fa-file-invoice me-1" data-fa-transform="shrink-3"></span></button></td>
                <td idfor="' . $row['ID'] . '" class="fornitore py-2 align-middle aprifornitore" style="cursor:pointer;"><span class="fw-bolder">' . $row['Ragione_Sociale'] .  '</span></td>
                <td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td>
            </tr>';
            }
        }

        $footer .= "<tr>
            <th></th>
            <th></th>
            <th class=\"text-end\">€ " . number_format($totaledoc, 2, ',', '.') . "</th>
            <th class=\"text-end\">€ " . number_format($totalesal, 2, ',', '.') . "</th>
            <th class=\"text-end\">€ " . number_format($totaleimp, 2, ',', '.') . "</th>
            <th class=\"text-end\">€ " . number_format(($totaledoc - $totalesal - $totaleimp), 2, ',', '.') . "</th>
        </tr>";
    } else {
        $dati .= '<tr>
            <td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>
        </tr>';
    }

    echo $dati . '|-|' . $footer;
    exit;
}
