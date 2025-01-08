<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");

    $risultato = '';
    $totale = '0,00';
    $classe = '';
    $percsaldo = 0;
    $clausola = $_POST['clausola'];

    $sql = $_SESSION['querycont'];
    unset($_SESSION['querycont']);

    $_SESSION['querycont'] = $sql;
    $_SESSION['azionecont'] = $azione;
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $sql1 = "SELECT SUM(Totale) AS TotTotale FROM ctb_documenti WHERE idcl=" . $row['ID'] . " AND Pagato!=4";
            $result1 = $conn->query($sql1);

            $sql2 = "SELECT SUM(Acconto) AS TotSaldo FROM ctb_acconti_cl WHERE IDcl=" . $row['ID'];
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();


            while ($row1 = $result1->fetch_assoc()) {
                if (is_null($row1['TotTotale']) == FALSE) {
                    if ((float)$row1['TotTotale'] >= 1) {

                        $totale = ((float)$row1['TotTotale'] - (float)$row2['TotSaldo']);

                        $risultato .= '<tr>
                            <td class="product left" width="40%">' . $row['Clientec'] .  '</td>
                            <td class="product center" width="15%">€ ' . number_format($row1['TotTotale'], 2, ',', '.') . '</td>
                            <td class="product center" width="15%">€ ' . number_format($row2['TotSaldo'], 2, ',', '.') . '</td>
                            <td class="product center" width="15%">0</td>
                            <td class="product center" width="15%"><b>€ ' . number_format($totale, 2, ',', '.') . '</b></td>
                            </tr>';
                    }
                }
            }
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td width=\"100%\">NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
    }


    unset($_SESSION['azionecont']);

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Lista Clienti");
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $fontname = TCPDF_FONTS::addTTFfont('GST.ttf', 'TrueTypeUnicode', '', 96);
    $obj_pdf->SetFont($fontname, '', 14, '', false);
    // $obj_pdf->SetDefaultMonospacedFont('helvetica');
    //$obj_pdf->SetFont('helvetica', '', 12);
    $obj_pdf->AddPage();
    $content = '  <link href="delivery-slip.css" rel="stylesheet" type="text/css">
    <table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr id="summary-tab" width="100%">
            <th class="product header small" width="40%">Cliente</th>
            <th class="product header small" width="15%">Dare</th>
            <th class="product header small" width="15%">Avere</th>
            <th class="product header small" width="15%">Scadere</th>
            <th class="product header small" width="15%">Scaduto</th>
        </tr>
    </thead>
    <tbody>
       
       ' . $risultato . '
    </tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('saldoclienti.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>

</body>

</html>