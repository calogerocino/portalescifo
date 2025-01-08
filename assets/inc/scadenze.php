<?php
error_reporting(0);
session_start();
include("database.php");
$azione = $_POST['azione'];
$risultato = '';
$pulsanti;
$totale = (float)0;
$pagato = $_POST['pagato'];
$clausola = $_POST['clausola'];
$clausola2  = $_POST['clausola2'];
$clausola3  = $_POST['clausola3'];
$clausola4  = $_POST['clausola4'];

if ($azione == 'aggiorna') {
    $sql = "SELECT p.ID, f.Ragione_Sociale, f.ID AS IDForn, p.Data, p.N_Assegno, d.Dato as Banca, n.Dato as Pagamento, p.Importo, p.N_Fattura, p.Note, p.Pagato FROM ctb_dati d INNER JOIN ctb_pagamento p ON p.Banca=d.ID INNER JOIN ctb_fornitore f ON f.ID=p.Intestatario INNER JOIN ctb_dati n ON p.Tipo_Pag=n.ID WHERE (p.Pagato=" . $pagato . ") AND (p.Tipo_Pag=" . $clausola2 . ") " . $clausola . " " . $clausola3 . " " . $clausola4 . " ORDER BY p.Data ASC";
    $_SESSION['querycont'] = $sql;
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $data = explode("-",  $row['Data']);

            if ($row['Pagato'] == 4) {
                $pulsanti = "
            <li><a class=\"dropdown-item pagato_sca\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Paga\"><i class=\"fa-regular fa-money-bill-alt\"></i> Paga</a></li>
            <li><a class=\"dropdown-item sospeso\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Sospendi\"><i class=\"fa-regular fa-hand\"></i> Sospendi</a></li>
            <li><a class=\"dropdown-item nonpagato\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Non pagato\"><i class=\"fa-regular fa-times\"></i> Non pagato</a></li>
            <li><a class=\"dropdown-item annullapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Annulla\"><i class=\"fa-regular fa-ban\"></i> Annulla</a></li>
            <div class=\"dropdown-divider\"></div>
            <li><a class=\"dropdown-item eliminapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Elimina\"><i class=\"fa-regular fa-trash-alt\"></i> Elimina</a></li>";

                $colore = 'text-danger';
            } else if ($row['Pagato'] == 1) {
                $pulsanti = "<li><a class=\"dropdown-item pagato_sca\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Paga\"><i class=\"fa-regular fa-money-bill-alt\"></i> Paga</a></li>
                <li><a class=\"dropdown-item annullapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Annulla\"><i class=\"fa-regular fa-ban\"></i> Annulla</a></li>";
                $colore = 'text-success';
            } else if ($row['Pagato'] == 0) {
                $pulsanti = "
            <li><a class=\"dropdown-item pagato_sca\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Paga\"><i class=\"fa-regular fa-money-bill-alt\"></i> Paga</a></li>
            <li><a class=\"dropdown-item sospeso\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Sospendi\"><i class=\"fa-regular fa-hand\"></i> Sospendi</a></li>
            <li><a class=\"dropdown-item annullapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Annulla\"><i class=\"fa-regular fa-ban\"></i> Annulla</a></li>
            <div class=\"dropdown-divider\"></div>
            <li><a class=\"dropdown-item eliminapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Elimina\"><i class=\"fa-regular fa-trash-alt\"></i> Elimina</a></li>";
                $colore = 'text-success';
            } else if ($row['Pagato'] == 2) {
                $pulsanti = "
            <li><a class=\"dropdown-item pagato_sca\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Paga\"><i class=\"fa-regular fa-money-bill-alt\"></i> Paga</a></li>
            <li><a class=\"dropdown-item nonpagato\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Non pagato\"><i class=\"fa-regular fa-times\"></i> Non pagato</a></li>
            <li><a class=\"dropdown-item annullapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Annulla\"><i class=\"fa-regular fa-ban\"></i> Annulla</a></li>
            <div class=\"dropdown-divider\"></div>
            <li><a class=\"dropdown-item eliminapag\" id=\"" . $row['ID'] . "\" href=\"javascript:void(0)\" title=\"Elimina\"><i class=\"fa-regular fa-trash-alt\"></i> Elimina</a></li>";
                $colore = 'text-success';
            }

            $risultato .= "<tr>
        <td class=\"intestatario py-2 align-middle\"><a idfor=\"" . $row['IDForn'] . "\" class=\"text-info aprifornitore_sca\" href=\"javascript:void(0);\" title=\"Apri Fornitore\">" . $row['Ragione_Sociale'] . "</a></td>
        <td class=\"scadenza py-2 align-middle $colore fw-bolder apripag_sca\" id=\"" . $row['ID'] . "\" style=\"cursor:pointer;\">" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>
        <td class=\"ndoc py-2 align-middle apripag_sca\" id=\"" . $row['ID'] . "\" style=\"cursor:pointer;\">" . $row['N_Assegno'] . "</td>
        <td class=\"banca py-2 align-middle apripag_sca\" id=\"" . $row['ID'] . "\" style=\"cursor:pointer;\">A mezzo <i><strong>" . $row['Pagamento'] . "</strong></i><br><strong>" . $row['Banca'] . "</strong></td>
        <td class=\"importo py-2 align-middle text-end fs-0 fw-medium apripag_sca\" id=\"" . $row['ID'] . "\" style=\"cursor:pointer;\">€ " . $row['Importo'] . "</td>
        <td class=\"py-2 align-middle white-space-nowrap text-end\">
        
        <div class=\"dropdown font-sans-serif position-static\"><button class=\"btn btn-link text-600 btn-sm dropdown-toggle btn-reveal\" type=\"button\" id=\"order-dropdown-2\" data-bs-toggle=\"dropdown\" data-boundary=\"viewport\" aria-haspopup=\"true\" aria-expanded=\"false\">
        <svg class=\"svg-inline--fa fa-ellipsis-h fa-w-16 fs--1\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"ellipsis-h\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\"><path fill=\"currentColor\" d=\"M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z\"></path></svg>
        </button>
        <div class=\"dropdown-menu dropdown-menu-end border py-0\" aria-labelledby=\"order-dropdown-2\" style=\"\"><div class=\"bg-white py-2\">  
        " . $pulsanti . "</div></div></div>
        
        </td>
        <td hidden>" . $row['N_Fattura'] . "</td>
        <td hidden>" . $row['Note'] . "</td>
        </tr>";

            $totale = (float)$totale + (float)$row['Importo'];
        }
    } else {
        $risultato .= '<tr>
        <td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>
    </tr>';
    }
    echo $risultato . '|-|' . '<tr><th></th><th></th><th></th><th class="text-end">Totale</th><th class="text-end">€ ' .  number_format((float)$totale, 2) . '</th><th></th></tr>';
    exit;
}
