<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $idcliente = $_GET['idcl'];

    $tipo = '';
    $descrizione;
    $dati = '';
    $nd = '';
    $risultato = array();
    $olddare = (float)0;
    $oldavere = (float)0;
    $aa = date('Y');

    $cliente[1] = '';



    $sap = 0;
    //======== SOMMA DOCUMENTI ANNO-1 =========
    $result1 = $conn->query("SELECT SUM(Totale) as tt FROM ctb_documenti WHERE IDcl=" . $idcliente . " AND Pagato != 4 AND Data NOT LIKE '" . $aa . "%'");
    $row1 = $result1->fetch_assoc();
    $sap = floatval($row1['tt']);
    //======== SOMMA PAGAMENTI ANNO-1 =========
    $result2 = $conn->query("SELECT SUM(Acconto) AS tt FROM ctb_acconti_cl WHERE IDcl=" . $idcliente . " AND NOT Data LIKE '" . $aa . "%'");
    $row2 = $result2->fetch_assoc();
    $sap = ($sap - floatval($row2['tt']));
    $sap = number_format($sap, 2, '.', '');
    if ($sap <= 0) {
        array_push($risultato, array(($aa - 1) . '-12-31', 'SALDO', 'Saldo fine anno ' . ($aa - 1), '', $sap));
    } else {
        array_push($risultato, array(($aa - 1) . '-12-31', 'SALDO', 'Saldo fine anno ' . ($aa - 1), $sap, ''));
    }





    $sql = "SELECT Data, N_Doc, Totale, AgEnt FROM ctb_documenti WHERE IDcl=" . $idcliente . " AND Pagato != 4 AND Data LIKE '" . $aa . "%'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {

        if (strpos($row['N_Doc'], 'BC') !== false) {
            $tipo = 'Buono di consegna';
            $nd = $row['N_Doc'];
        } else if (strpos($row['N_Doc'], 'FA') !== false) {
            if ($row['AgEnt'] != '' || $row['AgEnt'] != null) {
                $nd = $row['AgEnt'];
            } else {
                $nd = $row['N_Doc'];
            }
            $tipo = 'Fattura';
        }

        $descrizione = 'Doc. N° ' . $nd . ' del ' . $row['Data'];
        array_push($risultato, array($row['Data'], $tipo, $descrizione, $row['Totale'], ''));
    }

    $sql = "SELECT Data, Descrizione, Acconto FROM ctb_acconti_cl WHERE IDcl=" . $idcliente . " AND Data LIKE '" . $aa . "%'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "PAGAMENTO", $row['Descrizione'], '', $row['Acconto']));
    }


    $sql = "SELECT Cliente, Indirizzo FROM doff_clienti WHERE ID=" . $idcliente;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cliente[0] = $row['Cliente'];
        $cliente[1] = $row['Indirizzo'];
    }


    asort($risultato);


    foreach ($risultato as $dato1 => $dato2) {
        $olddare = (float)$dato2[3] + (float)$olddare;
        $oldavere = (float)$dato2[4] + (float)$oldavere;
        $datanew = explode('-', $dato2[0]);

        $dati .=  '<tr class="product ">
        <td class="product left" width="11%"><i>' . $datanew[2] . '/' . $datanew[1] . '/' . $datanew[0] . '</i></td>
        <td class="product left" width="20%">' . $dato2[1] . '</td>
        <td class="product left" width="36%">' . $dato2[2] . '</td>
        <td class="product left" width="11%" align="right">' . $dato2[3] . '</td>
        <td class="product left" width="11%" align="right">' . $dato2[4] . '</td>
        <td class="product left" width="11%" align="right">€ ' . number_format(((float)$olddare - (float)$oldavere), 2) . '</td>
        </tr>';
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
    // $obj_pdf->SetDefaultMonospacedFont('helvetica');
    //$obj_pdf->SetFont('helvetica', '', 12);
    $obj_pdf->AddPage();
    $content = '  <link href="delivery-slip.css" rel="stylesheet" type="text/css">
    <table width="100%" cellpadding="4" cellspacing="0">
    <tr>
    <th width="70%"><h4>GFA srl - P.IVA IT02095720856</h4><br /><h1>ESTRATTO CONTO</h1></th>
    <th width="30%">Spett.le<br /><b>' . strtoupper($cliente[0]) . '</b><br />' . strtoupper($cliente[1]) . '</th>
    </tr>
    </table>
    <br />
   <table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr id="summary-tab" width="100%">
            <th class="product header small" width="11%">DATA</th>
            <th class="product header small" width="20%">CAUSALE</th>
            <th class="product header small" width="36%">DESCRIZIONE</th>
            <th class="product header small" width="11%">DARE</th>
            <th class="product header small" width="11%">AVERE</th>
            <th class="product header small" width="11%">SALDO</th>
        </tr>
    </thead>
    <tbody id="sorttable">'
        . $dati .
        '<tr class=\"product \">
            <th class="product header small" width="11%"></th>
            <th class="product header small" width="20%">SALDO GLOBALE</th>
            <th class="product header small" width="36%"></th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$olddare, 2) . '</th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$oldavere, 2) . '</th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$olddare - (float)$oldavere, 2) . '</th>
     </tr>
    </tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output($cliente[0] . '.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
</body>


</html>