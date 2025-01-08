<?php
function fetch_data()
{
}
if (isset($_GET["create_pdf"])) {
    $idordine = $_GET['idordine'];

    require("../inc/database.php");
    $prodotti1 = "";
    $prodotti2 = "";
    $cliente[] = "";
    $ordine[] = "";

    $sql1 = "SELECT c.cliente, c.via, c.citta, c.cap, c.telefono, c.cellulare, c.cellulare2, c.EMail FROM donl_ordini o LEFT JOIN donl_clienti c ON (o.idcl=c.id) WHERE o.id=" . $idordine;
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $cliente['cliente'] = $row1['cliente'];
            $cliente['via'] = $row1['via'];
            $cliente['citta'] = $row1['citta'];
            $cliente['cap'] = $row1['cap'];
            $cliente['telefono'] = $row1['telefono'];
            $cliente['cellulare'] = $row1['cellulare'];
            $cliente['cellulare2'] = $row1['cellulare2'];
            $cliente['EMail'] = $row1['EMail'];
        }
    }
    if ($idordine <= 12395) {
        $database = 'donl_prodotti';
    } else  if ($idordine >= 12396) {
        $database = 'donl_prodottin';
    }
    $sql2 = "SELECT p.ID, p.NomeProdotto, p.CodiceProdotto, r.quantita FROM " . $database . " p INNER JOIN (donl_ordini o INNER JOIN donl_relpo r ON o.ID = r.IDO) ON p.ID = r.IDP WHERE r.IDO=" . $idordine;
    $result2 = $conn->query($sql2);
    if (empty($result2) !== TRUE) {
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $prodotti1 = "<tr class=\"product \">
                <td class=\"product center\">
                 " . $row2['CodiceProdotto'] . "
                </td>
                <td class=\"product left\">
                    <table width=\"100%\">
                        <tr>
                            <!-- <td width=\"15%\">
                            IMM
                            </td> -->
                            <td width=\"5%\">&nbsp;</td>
                            <td width=\"80%\">
                            " . $row2['NomeProdotto'] . "
                            </td>
                        </tr>
                    </table>
                </td>
                <td class=\"product center\">
                " . $row2['quantita'] . "
                </td>
                </tr> " . $prodotti1;
            }
        }
    }

    $sql3 = "SELECT p.ID, p.Nome, p.Codice, r.quantita FROM donl_prodotti INNER JOIN donl_relpo r ON p.ID = r.IDP WHERE r.IDO=" . $idordine;
    $result3 = $conn->query($sql3);
    if (empty($result3) !== TRUE) {
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $prodotti2 = "<tr>
                <td class=\"center\">
                 " . $row3['Codice'] . "
                </td>
                <td class=\"left\">
                    <table width=\"100%\">
                        <tr>
                        <!-- <td width=\"15%\">
                            IMM
                            </td> -->
                            <td width=\"5%\">&nbsp;</td>
                            <td width=\"80%\">
                            " . $row3['Nome'] . "
                            </td>
                        </tr>
                    </table>
                </td>
                <td class=\"center\">
                " . $row3['quantita'] . "
                </td>
                </tr> " . $prodotti2;
            }
        }
    }

    if (isset($prodotti1)) {
        $prodotti = $prodotti1;
    }
    if (isset($prodotti2)) {
        $prodotti .= $prodotti2;
    }
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







    require_once('tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Bolla di spedizione");
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
    <!-- Addresses -->
    <tr>
        <td colspan="12">

            <table id="addresses-tab" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%"><span class="bold"> </span><br /><br />
                    <img src="logo.jpg"> <br />
                    Vivai Scifo Store <br /> C/da Decano <br /> 93017 San Cataldo <br /> 0934 588985
                    </td>
                    <td width="66%"><span class="bold">Indirizzo di spedizione</span><br /><br />
                    ' . $cliente['cliente'] . ' <br />
                    ' . $cliente['via'] . ' <br />
                    ' . $cliente['cap'] . ' ' . $cliente['citta'] . ' <br /> 
                    ' . $cliente['cellulare']  . ' <br />
                    ' . $cliente['telefono']  . ' <br /> 
                    ' . $cliente['EMail']  . ' <br />
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="12" height="30">&nbsp;</td>
    </tr>
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
                    <td class="center small white">' . $ordine['dataordine'] . '</td>
                        <td class="center small white">' . $ordine['corriere'] . '</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="12" height="20">&nbsp;</td>
    </tr>

    <!-- Products -->
    <tr>
        <td colspan="12">
            {$product_tab}
        </td>
    </tr>

    <tr>
        <td colspan="12" height="20">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="7" class="left">
            <table id="payment-tab" width="100%" cellpadding="4" cellspacing="0">
                <tr>
                    <td class="payment center small grey bold" width="44%">Metodo di pagamento</td>
                    <td class="payment left white" width="56%">
                        <table width="100%" border="0">
                                <tr>
                                    <td class="right small">' . $ordine['pagamento'] . '</td>
                                    <td class="right small">' . $ordine['importo'] . '</td>
                                </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td colspan="5">&nbsp;</td>
    </tr>
    </table>';

    // <!-- Hook -->
    //     <tr>
    //         <td colspan="12" height="30">&nbsp;</td>
    //     </tr>
    //     <tr>
    //         <td colspan="2">&nbsp;</td>
    //         <td colspan="10">
          //INSERIRE COSE NOTE ECC...
    //         </td>
    //     </tr>




    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('file.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- <title>Webslesson Tutorial | Export HTML Table data to PDF using TCPDF in PHP</title> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> -->
</head>

<body>
    <!-- <br /><br />
    <div class="container" style="width:700px;">
        <h3 align="center">Export HTML Table data to PDF using TCPDF in PHP</h3><br />
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="5%">ID</th>
                    <th width="30%">Name</th>
                    <th width="10%">Gender</th>
                    <th width="45%">Designation</th>
                    <th width="10%">Age</th>
                </tr>
                <?php
                echo fetch_data();
                ?>
            </table>
            <br />
            <form method="post">
                <input type="submit" name="create_pdf" class="btn btn-danger" value="Create PDF" />
            </form>
        </div>
    </div> -->
</body>

</html>