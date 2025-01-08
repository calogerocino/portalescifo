<?php
if (isset($_GET["create_pdf"])) {

    $iddoc = $_GET['iddoc'];

    require("../inc/database.php");

    $cliente[4] = '';
    $documento[1] = '';
    $iva[1] = '';
    $prodotti = '';
    $totrighe = (int)0;
    $toteuro = (float)0;

    $sql = "SELECT Data, IDcl FROM ctb_documenti WHERE N_Doc='" . $iddoc . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        if (strpos($iddoc, 'FA') !== false) {
            $documento[3] = 'FATTURA';
        } else {
            $documento[3] = 'BUONO DI CONSEGNA';
        }
        $documento[0] = $iddoc;
        $documento[1] = $row['Data'];
        $documento[2] = $row['IDcl'];
    }


    $sql = "SELECT Cliente, Indirizzo, Cellulare, PIVA FROM doff_clienti WHERE ID=" . $documento[2];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cliente[0] = $row['Cliente'];
        $cliente[1] = $row['Indirizzo'];
        $cliente[2] = $row['Cellulare'];
        $cliente[3] = $row['PIVA'];
    }


    if (strpos($iddoc, 'FA') !== false) {
        //E' UNA FATTURA
        $sql = "SELECT N_Doc, RifFatt FROM ctb_documenti WHERE RifFatt='" . $iddoc . "' AND RifFatt IS NOT null";
        $result = $conn->query($sql);

        if ($result->num_rows >= 1) {
            //E' UNA FATTURA CON RIFERIMENTI FATTURA

            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $row['N_Doc'] . "' ORDER BY '" . $row['N_Doc'] . "' DESC";
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $prodotti .= '
                    <tr>
                        <td class="left" width="55%" style="font-size:10,5px">[Rif. ' .  $row['N_Doc'] . '] ' . $row1['Nome'] . '</td>
                        <td class="right" width="10%" style="font-size:10,5px">' . $row1['Quantita'] . '</td>
                        <td class="right" width="15%" style="font-size:10,5px">€ ' . number_format((float)$row1['Prezzo'], 2) . '</td>
                        <td class="right" width="20%" style="font-size:10,5px">€ ' . number_format((int)$row1['Quantita'] * (float)$row1['Prezzo'], 2) . '</td>
                   </tr> ';

                    $toteuro = ((int)$row1['Quantita'] * (float)$row1['Prezzo']) + (float)$toteuro;
                    $totrighe = (int)$totrighe + 1;
                }
            }
        } else {
            //E' UNA FATTURA CLASSICA

            $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $iddoc . "'";
            $result1 = $conn->query($sql1);
            while ($row1 = $result1->fetch_assoc()) {
                $prodotti .= '
                <tr>
                    <td class="left" width="55%" style="font-size:10,5px">' . $row1['Nome'] . '</td>
                    <td class="right" width="10%" style="font-size:10,5px">' . $row1['Quantita'] . '</td>
                    <td class="right" width="15%" style="font-size:10,5px">€ ' . number_format((float)$row1['Prezzo'], 2, ',', '.') . '</td>
                    <td class="right" width="20%" style="font-size:10,5px">€ ' . number_format((int)$row1['Quantita'] * (float)$row1['Prezzo'], 2, ',', '.') . '</td>
               </tr> ';

                $toteuro = ((int)$row1['Quantita'] * (float)$row1['Prezzo']) + (float)$toteuro;
                $totrighe = (int)$totrighe + 1;
            }
        }
    } else {
        //E' UN BUONO
        $sql = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $iddoc . "'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $prodotti .= '
            <tr>
                <td class="left" width="55%" style="font-size:10,5px">' . $row['Nome'] . '</td>
                <td class="right" width="10%" style="font-size:10,5px">' . $row['Quantita'] . '</td>
                <td class="right" width="15%" style="font-size:10,5px">€ ' . number_format((float)$row['Prezzo'], 2, ',', '.') . '</td>
                <td class="right" width="20%" style="font-size:10,5px">€ ' . number_format((int)$row['Quantita'] * (float)$row['Prezzo'], 2, ',', '.') . '</td>
           </tr> ';

            $toteuro = ((int)$row['Quantita'] * (float)$row['Prezzo']) + (float)$toteuro;
            $totrighe = (int)$totrighe + 1;
        }
    }

    if ($totrighe <= 14) {
        for ($i = $totrighe; $i <= 15; $i++) {
            $prodotti .= '
        <tr>
            <td class="left" width="55%" style="font-size:10,5px"></td>
            <td class="right" width="10%" style="font-size:10,5px"></td>
            <td class="right" width="15%" style="font-size:10,5px"></td>
            <td class="right" width="20%" style="font-size:10,5px"></td>
       </tr> ';
        }
    }

    $iva[0] = ((100 * (float)$toteuro) / 122);
    $iva[1]  = ((float)$toteuro - (float)$iva[0]);
    
    $data = explode('-', $documento[1]);

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Bolla di consegna");
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 12);
    $obj_pdf->AddPage();
    $content = '';
    $content .= '<link href="delivery-slip.css" rel="stylesheet" type="text/css">
    <CENTER>
    <TABLE CELLPADDING=40> <!--- Tabella invisibile che fa da contenitore --->
    <TR>
    
    <TD> <!--- Inizio della prima cella invisibile --->

        <table width="95%" cellpadding="2">
            <tr>
                <td width="43%" align="center"><b style="font-size:16px;">SCIFO STORE</b><br />GFA SRL<br />C/DA DECANO SNC<div style="font-size:10px">P.IVA IT02095720856<br />Tel. 350 5688846</div><br /></td>
                <td width="2%"></td>
            <td width="55%" style="border:1px solid black;"><i style="font-size:10px">SPETT.LE</i><br /><br /><b style="font-size:12px">' . $cliente[0] . '</b><div style="font-size:12px">' . $cliente[1] . '<br />' . $cliente[2] . '<br />P.IVA IT' . $cliente[3] . '</div></td>
            </tr>
        </table>
      <br/>
        <table width="95%" style="border:1px solid black;">
            <tr id="summary-tab">
                <td width="50%"><i align="left" style="font-size:8px">TIPO DOCUMENTO</i><br /><b align="left" style="font-size:12px">' . $documento[3] . '</b></td>
                <td width="25%"><i align="center" style="font-size:8px">NUMERO</i><br /><b align="center" style="font-size:12px">' . $documento[0] . '</b></td>
                <td width="25%"><i align="center" style="font-size:8px">DATA</i><br /><b align="center" style="font-size:12px">' . $data[2].'/'.$data[1].'/'.$data[0] . '</b></td>
            </tr> 
        </table>
      <br/>
        <table class="product" width="95%" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th class="product header" align="center" style="font-size:10px" width="55%">Descrizione</th>
                    <th class="product header" align="center" style="font-size:10px" width="10%">Q.ta</th>
                    <th class="product header" align="center" style="font-size:10px" width="15%">Prezzo</th>
                    <th class="product header" align="center" style="font-size:10px" width="20%">Importo</th>
                </tr>
            </thead>
            <tbody>' . $prodotti . '

            </tbody>      
        </table>
        <br/>
        <table width="95%" style="border:1px solid black;">
            <tr id="summary-tab">
            <td width="25%"><i align="center" style="font-size:8px">TOTALE IVA INCL.</i><br /><b align="center" style="font-size:12px">€ ' . number_format($toteuro, 2, ',', '.') . '</b></td>
            <td width="25%"><i align="center" style="font-size:8px">TOT. IMPONIBILE</i><br /><b align="center" style="font-size:12px">€ ' . number_format($iva[0], 2, ',', '.') . '</b></td>
            <td width="25%"><i align="center" style="font-size:8px">TOTALE IVA</i><br /><b align="center" style="font-size:12px">€ ' . number_format($iva[1], 2, ',', '.') . '</b></td>
            <td width="25%"><i align="center" style="font-size:8px">TOTALE (EURO)</i><br /><b align="center" style="font-size:12px"><b>€ ' . number_format($toteuro, 2, ',', '.') . '</b></b></td>
        </tr> 
        </table>

        </TD> <!--- Fine della prima cella invisibile --->

        <TD> <!--- Inizio della seconda cella invisibile --->

        <table width="95%" cellpadding="2">
        <tr>
        <td width="43%" align="center"><b style="font-size:16px;">SCIFO STORE</b><br />GFA SRL<br />C/DA DECANO SNC<div style="font-size:10px">P.IVA IT02095720856<br />Tel. 350 5688846</div><br /></td>
        <td width="2%"></td>
    <td width="55%" style="border:1px solid black;"><i style="font-size:10px">SPETT.LE</i><br /><br /><b style="font-size:12px">' . $cliente[0] . '</b><div style="font-size:12px">' . $cliente[1] . '<br />' . $cliente[2] . '<br />P.IVA IT' . $cliente[3] . '</div></td>
    </tr>
        </table>
      <br/>
        <table width="95%" style="border:1px solid black;">
            <tr id="summary-tab">
            <td width="50%"><i align="left" style="font-size:8px">TIPO DOCUMENTO</i><br /><b align="left" style="font-size:12px">' . $documento[3] . '</b></td>
            <td width="25%"><i align="center" style="font-size:8px">NUMERO</i><br /><b align="center" style="font-size:12px">' . $documento[0] . '</b></td>
            <td width="25%"><i align="center" style="font-size:8px">DATA</i><br /><b align="center" style="font-size:12px">' . $data[2].'/'.$data[1].'/'.$data[0] . '</b></td>
            </tr> 
        </table>
      <br/>
        <table class="product" width="95%" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th class="product header" align="center" style="font-size:10px" width="60%">Descrizione</th>
                    <th class="product header" align="center" style="font-size:10px" width="10%">Q.ta</th>
                    <th class="product header" align="center" style="font-size:10px" width="15%">Prezzo</th>
                    <th class="product header" align="center" style="font-size:10px" width="15%">Importo</th>
                </tr>
            </thead>
            <tbody>' . $prodotti . '

            </tbody>      
        </table>
        <br/>
        <table width="95%" style="border:1px solid black;">
            <tr id="summary-tab">
                <td width="25%"><i align="center" style="font-size:8px">TOTALE IVA INCL.</i><br /><b align="center" style="font-size:12px">€ ' . number_format($toteuro, 2, ',', '.') . '</b></td>
                <td width="25%"><i align="center" style="font-size:8px">TOT. IMPONIBILE</i><br /><b align="center" style="font-size:12px">€ ' . number_format($iva[0], 2, ',', '.') . '</b></td>
                <td width="25%"><i align="center" style="font-size:8px">TOTALE IVA</i><br /><b align="center" style="font-size:12px">€ ' . number_format($iva[1], 2, ',', '.') . '</b></td>
                <td width="25%"><i align="center" style="font-size:8px">TOTALE (EURO)</i><br /><b align="center" style="font-size:12px"><b>€ ' . number_format($toteuro, 2, ',', '.') . '</b></b></td>
            </tr> 
        </table>
        </TD> <!--- Fine della seconda cella invisibile --->

        </TR>
        </TABLE>
        </CENTER>
      ';
    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('file.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>

</body>

</html>