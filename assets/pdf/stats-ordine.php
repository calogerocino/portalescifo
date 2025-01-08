<?php
error_reporting(0);
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");

    $dati = '';
    $risultato = array();

    $sql = $_SESSION['SQLstatistiche'];
    $sql1 = $_SESSION['SQL1statistiche'];

    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);

    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            array_push($risultato, array($row['TotaleVendite'], $row['NomeProdotto'], $row['CodiceProdotto']));
        }
    }
    if ($result1->num_rows >= 1) {
        while ($row1 = $result1->fetch_assoc()) {
            array_push($risultato, array($row1['TotaleVendite'], $row1['NomeProdotto'], $row1['CodiceProdotto']));
        }
    }

    arsort($risultato);

    foreach ($risultato AS $dato1 => $dato2) {
        $dati .=  '<tr>
        <td width="30%"><b>' . $dato2[2] .  '</b></td>
        <td width="50%">' . $dato2[1] . '</td>
        <td width="20%"><b>' . $dato2[0] . '</b></td>
        </tr>';
    }

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Statistiche prodotti");
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
    <table width="100%" cellpadding="4" cellspacing="0">
    <tr>
    <th width="70%"><h4>GFA srl - P.IVA IT02095720856</h4><br /><h1>STATISTICHE PRODOTTI</h1></th>
    </tr>
    </table>
    <br />
   <table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr id="summary-tab" width="100%">
            <th class="product header small" width="30%">CODICE</th>
            <th class="product header small" width="50%">DESCRIZIONE</th>
            <th class="product header small" width="20%">QUANTITA</th>
        </tr>
    </thead>
    <tbody id="sorttable">'
        . $dati .
        '</tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('stats-ordine.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
</body>


</html>