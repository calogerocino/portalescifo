<?php
//  SINGLE ROW DIVIDER ____________________________________________________________________________________________________

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $idordine = $_GET['idordine'];
    $cliente[] = '';
    $ordine[] = '';
    $messaggio = '';

    //MESAGGIO DA IMPOSTARE
    $result0 = $conn->query('SELECT valore FROM app_impostazioni WHERE tipo=1');
    while ($row0 = $result0->fetch_assoc()) {
        $messaggio = $row0['valore'];
    }

    //RECUPERA DATI CLIENTE
    $sql1 = "SELECT c.cliente, c.via, c.citta, c.cap, c.telefono, c.cellulare, c.cellulare2, c.EMail FROM donl_ordini o LEFT JOIN donl_clienti c ON (o.idcl=c.id) WHERE o.id=" . $idordine;
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $cliente['cliente'] = $row1['cliente'];
            $cliente['via'] = $row1['via'];
            $cliente['citta'] = $row1['citta'];
            $cliente['cap'] = $row1['cap'];
            if ($row1['telefono'] == '' && $row1['cellulare'] != '') {
                $cliente['recapito'] = $row1['cellulare'];
            } else if ($row1['telefono'] != '' && $row1['cellulare'] == '') {
                $cliente['recapito'] = $row1['telefono'];
            } else if ($row1['telefono'] != '' && $row1['cellulare'] != '') {
                $cliente['recapito'] = $row1['telefono'] . '<br />' . $row1['cellulare'];
            } else {
                $cliente['recapito'] = '';
            }
            $cliente['EMail'] = $row1['EMail'];
        }
    }


    //RECUPERA I PRODOTTI DAL MAGAZZINO
    $sql2 = "SELECT p.ID, p.nome, p.sku, r.quantita FROM neg_magazzino p INNER JOIN (donl_ordini o INNER JOIN neg_relpo r ON o.ID = r.IDO) ON p.ID = r.IDP WHERE r.IDO=" . $idordine;
    $result2 = $conn->query($sql2);
    if (empty($result2) !== TRUE) {
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $prodotti = '<tr>
                <td width="20%" class="center">' . $row2['sku'] . '</td>
                <td width="10%"class="center" ><img src="https://portalescifo.it/upload/image/p/' . $row2['ID'] . '.jpg"/></td> 
                <td width="60%">   ' . $row2['nome'] . '</td>
                <td width="10%" class="center">' . $row2['quantita'] . '</td>
            </tr> ' . $prodotti;
            }
        }
    }



    //RECUPERA I DATI DELL'ORDINE
    $sql4 = "SELECT `ID`, `NOrdine`, `NFattura`, `DataOrdine`, `Corriere`, `Noteo`, `Pagamento`, `Importo` FROM `donl_ordini` WHERE `ID`=" . $idordine;
    $result4 = $conn->query($sql4);
    if ($result4->num_rows > 0) {
        while ($row4 = $result4->fetch_assoc()) {
            $ordine['id'] = $row4['ID'];
            $ordine['riferimento'] = $row4['NOrdine'];
            $ordine['nfattura'] = $row4['NFattura'];
            $ordine['dataordine'] = $row4['DataOrdine'];
            $ordine['corriere'] = $row4['Corriere'];
            $ordine['noteo'] = $row4['Noteo'];
            $ordine['pagamento'] = $row4['Pagamento'];
            $ordine['importo'] = $row4['Importo'];
        }
    }

    $rep = explode('-', $ordine['dataordine']);
    $ordine['dataordine_rep'] = $rep[2] . '/' . $rep[1] . '/' . $rep[0];

    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle('Bolla ordine ' . $ordine['id']);
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
                            <td width="30%"></td>
                            <td width="20%" align="right"><br /><br /><span class="bold"> SPEDIZIONE </span><br />' . $ordine['nfattura'] . ' <br />' . $ordine['id'] . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="12">
                    <table id="addresses-tab" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="35%" align="left"><br /><br /><br />ScifoStore <br />
                                Contrada Decano <br />
                                93017 San Cataldo <br />
                                Italia <br />
                                </td>
                            <td width="45%"><br /><br /><span class="bold">Indirizzo di spedizione</span><br /><br />
                                ' . $cliente['cliente'] . ' <br />
                                ' . $cliente['via'] . ' <br />
                                ' . $cliente['cap'] . ' ' . $cliente['citta'] . ' <br /> 
                                ' . $cliente['recapito'] . ' <br />
                                ' . $cliente['EMail']  . ' <br />
                            </td>
                            <td width="20%" align="right"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan="12" height="30">&nbsp;</td></tr>
            <tr>
                <td colspan="12">
                    <table id="summary-tab" width="100%">
                        <tr>
                            <th class="header small" valign="middle">Riferimento</th>
                            <th class="header small" valign="middle">Data ordine</th>
                            <th class="header small" valign="middle">Corriere</th>
                        </tr>
                        <tr>
                            <td class="center small white">' . $ordine['riferimento'] . '</td>
                            <td class="center small white">' . $ordine['dataordine_rep'] . '</td>
                            <td class="center small white">' . $ordine['corriere'] . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan="12" height="20">&nbsp;</td></tr>
            <tr>
                <td colspan="12">
                    <table class="product" width="100%" cellpadding="4" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product header small" width="20%">Riferimento</th>
                                <th class="product header small" width="10%">Immagine</th>
                                <th class="product header small" width="60%">Prodotto</th>
                                <th class="product header small" width="10%">Quantita</th>
                            </tr>
                        </thead>
                        <tbody>' . $prodotti . '</tbody>
                    </table>
                </td>
            </tr>
            <tr><td colspan="12" height="20">&nbsp;</td></tr>
            <tr>
                <td colspan="7" class="left">
                    <table id="payment-tab" width="100%" cellpadding="4" cellspacing="0">
                        <tr>
                            <td class="payment center small grey bold" width="44%">Metodo di pagamento</td>
                            <td class="payment left white" width="56%">
                                <table width="100%" border="0">
                                    <tr>
                                        <td class="right small"> ' . $ordine['pagamento'] . '</td>
                                        <td class="right small"> ' . $ordine['importo'] . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="12" height="30">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="20" align="left">' . $messaggio . '</td>
            </tr>
        </table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output($ordine['id'] . '.pdf', 'I');
}
