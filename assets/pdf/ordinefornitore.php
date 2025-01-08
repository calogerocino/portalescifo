<?php
//  SINGLE ROW DIVIDER ____________________________________________________________________________________________________

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $fornitore = $_GET['fornitore'];
    $replace = array('COMPATIBILE', 'ORIGINALE');
    $sql1 = "SELECT NOrdine FROM neg_ordine_prodotti WHERE Fornitore='" . $fornitore . "' ORDER BY DataOrdine DESC LIMIT 1";
    $result = $conn->query($sql1);
    $row = $result->fetch_assoc();
    $nordine = $row['NOrdine'];
    //RECUPERA PRODOTTI
    $sql = "SELECT o.Codice, p.nome, SUM(o.Quantita) as TOTQuant, o.Note FROM neg_ordine_prodotti o INNER JOIN neg_magazzino p ON (o.IDPR=p.ID) WHERE Fornitore='" . $fornitore . "' AND NOrdine=" . $nordine . " GROUP BY o.Codice";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $prodotti .= '<tr>
            <td width="20%" class="center">' . $row['Codice'] . '</td>
            <td width="60%">   ' . substr(str_replace($replace, '', strtoupper($row['nome'])), 0, 45) . '</td>
            <td width="7%" class="center">' . $row['TOTQuant'] . '</td>
            <td width="13%">' . $row['Note'] . '</td>
        </tr> ';
        }
    }

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle('Ordine ricambi ' . $fornitore . ' ' . date("d-m-Y"));
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
    $content = '';
    $content .= '
        <link href="delivery-slip.css" rel="stylesheet" type="text/css">
        <table width="100%" id="body" border="0" cellpadding="0" cellspacing="0" style="margin:0;">
            <tr>
                <td colspan="12">
                    <table id="addresses-tab" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="left"><br /><br /><img src="scifostore-22-ass.png"></td>
                            <td width="20%"></td>
                            <td width="30%" align="right"><br /><br /><span class="bold"> GFA srl <br />
                            Contrada Decano<br />
                            93100 Caltanissetta <br />
                            IT 02095720856 <br /> </span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan="12" height="15">&nbsp;</td></tr>
            <tr>
                <td colspan="12">
                    <table id="summary-tab" width="100%">
                        <tr>
                            <th class="header small" valign="middle">Tipo</th>
                            <th class="header small" valign="middle">Fornitore</th>
                            <th class="header small" valign="middle">Data ordine</th>
                        </tr>
                        <tr>
                            <td class="center small white">Ordine fornitore</td>
                            <td class="center small white">' . $fornitore  . '</td>
                            <td class="center small white">' . date("d-m-Y")  . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan="12" height="20">&nbsp;</td></tr>
            <tr >
                <td colspan="12">
                    <table class="product" width="100%" cellpadding="4" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product header small" width="20%">Riferimento</th>
                                <th class="product header small" width="60%">Prodotto</th>
                                <th class="product header small" width="7%">Quant.</th>
                                <th class="product header small" width="13%">Note</th>
                            </tr>
                        </thead>
                        <tbody>' . $prodotti . '</tbody>
                    </table>
                </td>
            </tr>
        </table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('Ordine ricambi ' . $fornitore . ' ' . date("d-m-Y") . '.pdf', 'I');
}
