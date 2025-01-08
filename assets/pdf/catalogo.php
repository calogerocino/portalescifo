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

        $catprod .= ' <div class="col-xs-4 col-md-4" style="page-break-inside: avoid;" id="' . $elem . '" ondblclick="return removeDummy(' . $elem . ');">
        <div class="product tumbnail thumbnail-3 vcenter">
        <img height="auto" width="200" src="https://portalescifo.it/upload/image/p/' . $row['ID'] . '.jpg" alt="">
          <div class="caption text-center">
            <h6>' . $row['nome'] . '</h6>
            <span class="codice"><b>' . $row['sku'] . '</b></span>
            <span class="price sale">€' . $row['prezzo'] . '</span>
          </div>
        </div>
      </div>';
        $elem += 1;
    }

echo '<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="catalogo.css" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

  <div class="container bootstrap snipets">
<h1 class="text-center text-muted">Catalogo prodotti</h1>
<div class="row flow-offset-1">
' . $catprod . '
</div>
</div>';

    // require_once('tcpdf.php');
    // $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $obj_pdf->SetCreator(PDF_CREATOR);
    // $obj_pdf->SetTitle("Catalogo prodotti");
    // $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    // $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    // $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    // $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    // $obj_pdf->setPrintHeader(false);
    // $obj_pdf->setPrintFooter(false);
    // $obj_pdf->SetAutoPageBreak(TRUE, 10);
    // $fontname = TCPDF_FONTS::addTTFfont('GST.ttf', 'TrueTypeUnicode', '', 96);
    // $obj_pdf->SetFont($fontname, '', 14, '', false);
    // // $obj_pdf->SetDefaultMonospacedFont('helvetica');
    // //$obj_pdf->SetFont('helvetica', '', 12);
    // $obj_pdf->AddPage();
    // $content = '';

    // $obj_pdf->writeHTML($content);
    // $obj_pdf->Output('catalogo.pdf', 'I');
} ?>

<script>
  window.print();
    function removeDummy(id) {
        var elem = document.getElementById(id);
        elem.parentNode.removeChild(elem);
        return false;
    }
</script>
