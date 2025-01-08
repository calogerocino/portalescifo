<?php
include("database.php");
session_start();
$azione = $_POST['azione'];

if ($azione == 'aggiorna') {
    $risultato = '';
    $totale = '0,00';
    $classe = '';
    $percsaldo = 0;
    $clausola = $_POST['clausola'];
    $oggi = date("Y-m-d");

    $sql = "SELECT ID, (case
    when (`Nick` != '')  then
    CONCAT(`Cliente`, ' (',`Nick`,')')
    when (`Nick`='') then `Cliente`
    end 
    )AS Clientec, Nick, Citta, Cellulare, Fido FROM doff_clienti " . $clausola . " ORDER BY Clientec ASC";

    $_SESSION['querycont'] = $sql;
    $_SESSION['azionecont'] = $azione;
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $scadere = 0;
            $scaduto = 0;
            $sql1 = "SELECT SUM(Totale) AS TotTotale FROM ctb_documenti WHERE idcl=" . $row['ID'] . " AND Pagato!=4";
            $result1 = $conn->query($sql1);

            $sql2 = "SELECT SUM(Acconto) AS TotSaldo FROM ctb_acconti_cl WHERE IDcl=" . $row['ID'];
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $sql3 = "SELECT SUM(importorata) AS imprata, SUM(saldorata) AS saldrata, datarata FROM ctb_ratescadenze WHERE idcl=" . $row['ID'] . " GROUP BY datarata";
            $result3 = $conn->query($sql3);
            while ($row1 = $result1->fetch_assoc()) {
                if (is_null($row1['TotTotale']) == FALSE) {

                    $totale = ((float)$row1['TotTotale'] - (float)$row2['TotSaldo']);
                    while ($row3 = $result3->fetch_assoc()) {
                        if ($oggi > $row3['datarata']) {
                            $scadere = ($scadere + ((float)$row3['imprata'] - (float)$row3['saldrata']));
                        } else {
                            $scaduto = ($scaduto + ((float)$row3['imprata'] - (float)$row3['saldrata']));
                        }
                    }
                    $risultato .= '<tr>
                            <td><button class="btn btn-falcon-default me-1 mb-1" type="button" onclick="window.open(currentURL + \'assets/pdf/e-conto.php?create_pdf=1&idcl=' . $row['ID'] . '\', \'PDF Documento\', \'width=800, height=800, status, scrollbars=1, location\');"><span class="fa-regular fa-file-invoice me-1" data-fa-transform="shrink-3"></span></button></td>
                           <td class="cliente py-2 align-middle cl_apricliente" idcl="' . $row['ID'] . '" style="cursor:pointer;"><span class="fw-bold text-dark-75">' . $row['Clientec'] .  '</span></td>
                            <td class="dare py-2 align-middle text-center fs-0 fw-bold text-primary">€ ' . number_format($row1['TotTotale'], 2, ',', '') . '</td>
                            <td class="avere py-2 align-middle text-center fs-0 fw-bold text-info">€ ' . number_format($row2['TotSaldo'], 2, ',', '') . '</td>
                            <td class="scaduto py-2 align-middle text-center fs-0 fw-bold ' . checksaldo($row['Fido'], $totale, $scaduto) . '">€ ' . number_format($scaduto, 2, ',', '') . '</td>
                           <td class="scadere py-2 align-middle text-center fs-0 fw-bold ' . checksaldo($row['Fido'], $totale, $scadere) . '">€ ' . number_format($scadere, 2, ',', '') . '</td>
                            </tr>';
                } else {
                    $risultato .= '<tr>
                    <td><button class="btn btn-falcon-default me-1 mb-1" type="button" onclick="window.open(currentURL + \'assets/pdf/e-conto.php?create_pdf=1&idcl=' . $row['ID'] . '\', \'PDF Documento\', \'width=800, height=800, status, scrollbars=1, location\');"><span class="fa-regular fa-file-invoice me-1" data-fa-transform="shrink-3"></span></button></td>
                   <td class="cliente py-2 align-middle cl_apricliente" idcl="' . $row['ID'] . '" style="cursor:pointer;"><span class="fw-bold text-dark-75">' . $row['Clientec'] .  '</span></td>
                   <td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td>
                    </tr>';
                }
            }
        }
    } else {
        $risultato .= '<tr>
        <td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>
    </tr>';
    }
    echo $risultato;
    exit;
}


function checksaldo($fido, $totale, $pos)
{
    $percsaldo = 0;
    if ($pos >= 1) {
        if ($fido <= $totale) {
            return 'text-danger';
        } else {
            (int)$percsaldo = ($totale * 100) / $fido;
            if ($percsaldo >= 85) {
                return 'text-warning';
            } else if ($percsaldo >= 50 && $percsaldo <= 84) {
                return 'text-yellow';
            } else if ($percsaldo <= 49) {
                return 'text-success';
            }
        }
    } else {
        return 'text-success';
    }
}
