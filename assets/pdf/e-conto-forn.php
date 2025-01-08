<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $idfornitore = $_GET['idfo'];


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

        array_push($risultato, array($row['DataDoc'],  "<b>DOCUMENTO</b>", $descrizione, '', $row['TotDoc'], ''));
    }

    $sql = "SELECT Data, Descrizione, Acconto FROM ctb_acconti_fo WHERE IDfo=" . $idfornitore;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "PAGAMENTO", $row['Descrizione'], $row['Acconto'], '', ''));
    }

    $sql = "SELECT p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.Pagato=0 AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "SCADENZA", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo']));
    }

    $sql = "SELECT p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.Pagato=4 AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "SCADUTO", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo']));
    }

    $sql = "SELECT p.Data, p.Importo, p.N_Assegno, d.Dato FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE (p.Pagato=2) AND Intestatario=" . $idfornitore;
    $result = $conn->query($sql);
    echo  $conn->error;
    while ($row = $result->fetch_assoc()) {
        array_push($risultato, array($row['Data'], "SOSPESO", $row['Dato'] . ' ' . $row['N_Assegno'], '', '', $row['Importo']));
    }

    $sql = "SELECT Ragione_Sociale, Citta FROM ctb_fornitore WHERE ID=" . $idfornitore;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cliente[0] = $row['Ragione_Sociale'];
        $cliente[1] = $row['Citta'];
    }

    asort($risultato);


    foreach ($risultato as $dato1 => $dato2) {
        $olddare = (float)$dato2[3] + (float)$olddare;
        $oldavere = (float)$dato2[4] + (float)$oldavere;
        if ($dato2[1] != 'SCADUTO') {
            $oldscad = (float)$dato2[5] + (float)$oldscad;
        }
        $datanew = explode('-', $dato2[0]);

        $dati .=  '<tr class="product ">
        <td class="product left" width="11%"><i>' . $datanew[2] . '/' . $datanew[1] . '/' . $datanew[0] . '</i></td>
        <td class="product left" width="15%">' . $dato2[1] . '</td>
        <td class="product left" width="30%">' . $dato2[2] . '</td>
        <td class="product left" width="11%" align="right">' . $dato2[3] . '</td>
        <td class="product left" width="11%" align="right">' . $dato2[4] . '</td>
        <td class="product left" width="11%" align="right">' . $dato2[5] . '</td>
        <td class="product left" width="11%" align="right">€ ' . number_format((((float)$oldavere - (float)$olddare) - (float)$oldscad), 2) . '</td>
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
    <th width="70%"><h4>GFA srl - P.IVA IT0209570856</h4><br /><h1>ESTRATTO CONTO</h1></th>
    <th width="30%">Spett.le<br /><b>' . strtoupper($cliente[0]) . '</b><br />' . strtoupper($cliente[1]) . '</th>
    </tr>
    </table>
    <br />
   <table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr id="summary-tab" width="100%">
            <th class="product header small" width="11%">DATA</th>
            <th class="product header small" width="15%">CAUSALE</th>
            <th class="product header small" width="30%">DESCRIZIONE</th>
            <th class="product header small" width="11%">DARE</th>
            <th class="product header small" width="11%">AVERE</th>
            <th class="product header small" width="11%">A SCADERE</th>
            <th class="product header small" width="11%">SALDO</th>
        </tr>
    </thead>
    <tbody id="sorttable">'
        . $dati .
        '<tr class=\"product \">
            <th class="product header small" width="11%"></th>
            <th class="product header small" width="15%">TOTALI</th>
            <th class="product header small" width="30%"></th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$olddare, 2) . '</th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$oldavere, 2) . '</th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((float)$oldscad, 2) . '</th>
            <th class="product header small" width="11%" align="right">€ ' . number_format((((float)$oldavere - (float)$olddare) - (float)$oldscad), 2) . '</th>
     </tr>
    </tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output(strtoupper($cliente[0]) . '.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
</body>


</html>