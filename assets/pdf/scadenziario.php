<?php
session_start();

function fetch_data()
{
}
if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $querycont = $_SESSION['querycont'];
    unset($_SESSION['querycont']);
    $risultato = '';
    $totale = 0;

    $sql = $querycont;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data = explode('-', $row['Data']);

        $risultato .= "<tr class=\"product \">";
        $risultato .= "<td class=\"product left\">" . $row['Ragione_Sociale'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['N_Assegno'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['Banca'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['Pagamento'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['Importo'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['N_Fattura'] . "</td>";
        $risultato .= "<td class=\"product left\">" . $row['Note'] . "</td>";
        $risultato .= "</tr>";

        $totale = $totale + $row['Importo'];
    }


    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Scadenziario");
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
            <th class="product header small">Ragione Sociale</th>
            <th class="product header small">Data</th>
            <th class="product header small">N° Assegno</th>
            <th class="product header small">Banca</th>
            <th class="product header small">Pagamento</th>
            <th class="product header small">Importo</th>
            <th class="product header small">N° Fattura</th>
            <th class="product header small">Note</th>
        </tr>
    </thead>
    <tbody>
       
       ' . $risultato . '
       <tr class=\"product \">
       <td class=\"product left\">Importo totale</td>
     <td class=\"product left\">' .  $totale . '</td>
     </tr>
    </tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('scadenziario.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <?php
    echo fetch_data();
    ?>
</body>

</html>