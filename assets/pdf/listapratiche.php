<?php
session_start();

if (isset($_GET["create_pdf"])) {
    require("../inc/database.php");
    $idstato = $_GET['ids'];


    $nsent = '';
    $stato = '';
    $sql = "SELECT p.id, p.descrizione, p.dataapertura, p.stato, a.nome FROM amm_pratiche p INNER JOIN amm_avvocati a ON (p.idavv=a.id) WHERE p.Stato=" . $idstato;
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $sql1 = "SELECT COUNT(id) as totsent FROM amm_sentenze WHERE idpratica=" . $row['id'] . " AND Stato=0";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows >= 1) {
                while ($row1 = $result1->fetch_assoc()) {
                    $nsent = $row1['totsent'];
                }
            }
            $ndata = explode('-',  $row['dataapertura']);

            if ($row['stato'] == 0) {
                $stato  = '<div class="text-muted"><i class="badge bg-danger"><b>NUOVA</b></i></div>';
            } else if ($row['stato'] == 2) {
                $stato  = '<div class="text-muted"><i class="badge bg-yellow"><b>ATTESA SENTENZA</b></i></div>';
            } else if ($row['stato'] == 4) {
                $stato  = '<div class="text-muted"><i class="badge bg-warning"><b>SOSPESA</b></i></div>';
            } else if ($row['stato'] == 5) {
                $stato  = '<div class="text-muted"><i class="badge bg-success"><b>CHIUSA</b></i></div>';
            } else if ($row['stato'] == 6) {
                $stato  = '<div class="text-muted"><i class="badge bg-yellow"><b>ATTESA RISPOSTA</b></i></div>';
            } else if ($row['stato'] == 8) {
                $stato  = '<div class="text-muted"><i class="badge bg-yellow"><b>ATTESA SCIFO</b></i></div>';
            }

            $dati .= '<tr>';
            $dati .= '<td class="text-dark-75 font-weight-bolder font-size-lg mb-0">' . $row['descrizione'] . '</td>';
            $dati .= '<td class="text-primary mb-0">' . $ndata[2] . '/' . $ndata[1] . '/' . $ndata[0] . '</td>';
            $dati .= '<td><i>' . $row['nome'] . '</i></td>';
            $dati .= '<td>' . $stato . '</td>';
            $dati .= '<td>' . $nsent . '</td>';
            $dati .= '</tr>';
        }
    } else {
        $dati .= "<tr>";
        $dati .= "<td>NESSUN RISULTATO</td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "</tr>";
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
    <th width="70%"><h4>GFA srl - P.IVA IT02095720856</h4><br /><h1>LISTA PRATICHE</h1></th>
    </tr>
    </table>
    <br />
    <table class="product" id="dataTable" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr>
            <th>Descrizione</th>
            <th>Data Apertura</th>
            <th>Avvocato</th>
            <th>Stato</th>
            <th>Sentenze in corso</th>
        </tr>
    </thead>
    <tbody>' . $dati . '</tbody>
</table>';

    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('listapratiche.pdf', 'I');
}
?>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
</body>


</html>