<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");

    $iddip = $_GET['iddip'];
    $econto = array();
    $olddare = (float)0;
    $oldavere = (float)0;
    $oldscad = (float)0;

    $sql = "SELECT adp.Data, adp.IDB, adp.Acconto, bp.Data AS DataB FROM `amm_acconti_dp` adp INNER JOIN `amm_bustapaga` bp ON (adp.IDB=bp.ID) WHERE adp.IDdp=" . $iddip;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $d = explode('-', $row['Data']);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));
        
        $d1 = explode('-', $row['DataB']);
        $dd1 = strftime('%B %Y', mktime(0, 0, 0, ($d1[1] + 1), 0, $d1[0]));
        
        array_push($econto, array($row['Data'],  'Acconto su busta ' . ucfirst($dd1), $row['Acconto'], ''));
    }

    $sql = "SELECT Data, ID, Importo FROM `amm_bustapaga` WHERE IDdp=" . $iddip;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $d = explode('-', $row['Data']);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));

        array_push($econto, array($row['Data'],  'Busta paga ' . ucfirst($dd), '', $row['Importo']));
    }
    asort($econto);
    foreach ($econto as $dato1 => $dato2) {
        $d = explode('-', $dato2[0]);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));

        $olddare = (float)$dato2[2] + (float)$olddare;
        $oldavere = (float)$dato2[3] + (float)$oldavere;

        $export .=  '<tr>
        <td width="17%"><i>' . strtoupper($dd) . '</i></td>
        <td width="32%">' . $dato2[1] . '</td>
        <td width="17%" align="right">' . number_format($dato2[2], 2, ',', '.') . '</td>
        <td width="17%" align="right">' . number_format($dato2[3], 2, ',', '.') . '</td>
        <td width="17%" align="right">€ ' . number_format((((float)$oldavere - (float)$olddare)), 2, ',', '.') . '</td>
        </tr>';
    }


    $sql = "SELECT Dipendente, CodFisc FROM `amm_dipendenti` WHERE ID=" . $iddip;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dipendente[0] = $row['Dipendente'];
        $dipendente[1] = $row['CodFisc'];
    }

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Estratto Conto");
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
    $obj_pdf->AddPage();
    $content = '  <link href="delivery-slip.css" rel="stylesheet" type="text/css">
    <table width="100%" cellpadding="4" cellspacing="0">
    <tr>
    <th width="70%"><h4>GFA srl - P.IVA IT0209570856</h4><br /><h1>ESTRATTO CONTO</h1></th>
    <th width="30%">Spett.le<br /><b>' . strtoupper($dipendente[0]) . '</b><br />' . strtoupper($dipendente[1]) . '</th>
    </tr>
    </table>
    <br />
   <table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr id="summary-tab" width="100%">
            <th class="product header small" width="17%">DATA</th>
            <th class="product header small" width="32%">DESCRIZIONE</th>
            <th class="product header small" width="17%">DARE</th>
            <th class="product header small" width="17%">AVERE</th>
            <th class="product header small" width="17%">SALDO</th>
        </tr>
    </thead>
    <tbody id="sorttable">'
        . $export .
        '<tr class=\"product \">
            <th colspan="2" class="product header small" align="right" width="49%">TOTALI</th>
            <th class="product header small" align="right" width="17%">€ ' . number_format((float)$olddare, 2, ',', '.') . '</th>
            <th class="product header small" align="right" width="17%">€ ' . number_format((float)$oldavere, 2, ',', '.') . '</th>
            <th class="product header small" align="right" width="17%">€ ' . number_format(((float)$oldavere - (float)$olddare), 2, ',', '.') . '</th>
     </tr>
    </tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output(strtoupper($dipendente[0]) . '.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
</body>


</html>