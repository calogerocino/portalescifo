<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $catprod = '';
    $rowcat = 0;
    $page = 0;
    $elem = 0;



    $result = $conn->query($_SESSION['querymagneg']);
    while ($row = $result->fetch_assoc()) {
        $catprod .= '<div class="shop-card" id="' . $elem . '" ondblclick="return removeDummy(' . $elem . ');"> <div class="title"> ' . $row['nome'] . ' </div><div class="desc"> ' . $row['sku'] . '</div><div class="slider"> <figure data-color="#E24938, #A30F22"> <img height="auto" width="128" src="https://' . $_SERVER['HTTP_HOST'] . '/upload/image/p/' . $row['ID'] . '.jpg"/> </figure> </div><div class="cta"> <div class="price">€ ' . $row['prezzo'] . '</div><button class="btn textean13">' . $row['ean'] . '</button> </div></div>';
        $elem += 1;
    }
    echo ' <link rel="stylesheet/less" type="text/css" href="catalogo.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script><div class="Items_productDetails__2u3lv">' . $catprod . ' </div>';

    // require_once('tcpdf.php');
    // $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $obj_pdf->SetCreator(PDF_CREATOR);
    // $obj_pdf->SetTitle("Catalogo negozio");
    // $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    // $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    // $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    // $obj_pdf->SetDefaultMonospacedFont('helvetica');
    // $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    // $obj_pdf->setPrintHeader(false);
    // $obj_pdf->setPrintFooter(false);
    // $obj_pdf->SetAutoPageBreak(TRUE, 10);
    // $obj_pdf->SetFont('helvetica', '', 12);
    // $obj_pdf->AddPage();
    // $content = '<table width="100%" cellpadding="4" cellspacing="0"><tbody><tr>' . $catprod . ' </tbody></table>';

    // $obj_pdf->writeHTML($content);
    // $obj_pdf->Output('catalogo.pdf', 'I');
} ?>

<script>
    function removeDummy(id) {
        var elem = document.getElementById(id);
        elem.parentNode.removeChild(elem);
        return false;
    }
</script>