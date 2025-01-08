<?php
ini_set('max_execution_time', 0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../mail/Exception.php";
require "../mail/PHPMailer.php";
require "../mail/SMTP.php";


$logghi = '';

include("../inc/database.php");


$mese = date("m");
$anno = date("Y");
$giorno = (date("d") - 4);

//SCADENZE IN RITARDO
$sql = "SELECT * FROM amm_pratiche WHERE Stato = 1 OR Stato = 2 OR Stato = 4 OR Stato = 6 OR Stato = 7 ORDER BY stato ASC";

$result = $conn->query($sql);

if ($result->num_rows >= 1) {

    //HEADER TABELLA
    $logghi .= '<style>.badge{display:inline-block;padding:.35em .65em;font-size:.75em;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem}.bg-info{background-color:#0dcaf0!important;color:#212529!important}.bg-yellow{background-color:#ff0!important;color:#212529!important}.bg-warning{background-color:#ffc107!important;color:#212529!important}</style> <table class="table table-hover table-borderless paginated" id="dataTable" style="box-sizing: border-box; border-collapse: collapse; width: 1587.13px; margin-bottom: 1rem; color: rgb(133, 135, 150); font-family: Nunito, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;" width="100%" cellspacing="0"> <thead style="box-sizing: border-box;"><tr style="box-sizing: border-box;"> <th style="box-sizing: border-box; text-align: inherit; padding: 0.75rem; vertical-align: bottom; border: 0px;">ID</th> <th style="box-sizing: border-box; text-align: inherit; padding: 0.75rem; vertical-align: bottom; border: 0px;">Descrizione</th> <th style="box-sizing: border-box; text-align: inherit; padding: 0.75rem; vertical-align: bottom; border: 0px;">Data Apertura</th> <th style="box-sizing: border-box; text-align: inherit; padding: 0.75rem; vertical-align: bottom; border: 0px;">Avvocato</th> <th style="box-sizing: border-box; text-align: inherit; padding: 0.75rem; vertical-align: bottom; border: 0px;">Stato</th><tbody id="tabellapraticheavv" style="box-sizing: border-box;">';

    while ($row = $result->fetch_assoc()) {
        if ($row['stato'] == 1) {
            $stato = '<i class="badge bg-info"><b>IN GESTIONE</b></i>';
        } else if ($row['stato'] == 2) {
            $stato = '<i class="badge bg-yellow"><b>ATTESA SENTENZA</b></i>';
        }else if ($row['stato'] == 4) {
            $stato = '<i class="badge bg-warning"><b>SOSPESA</b></i>';
        } else if ($row['stato'] == 6) {
            $stato = '<i class="badge bg-yellow"><b>ATTESA RISPOSTA</b></i>';
        } else if ($row['stato'] == 7) {
            $stato = '<i class="badge bg-yellow"><b>ATTESA AVVOCATO</b></i>';
        }
        $logghi .= '<tr style="box-sizing: border-box; background-color: rgba(0, 0, 0, 0.05);"> <td style="box-sizing: border-box; padding: 0.75rem; vertical-align: top; border: 0px;"><a class="text-info font-weight-bold" title="' . $row['note'] . '" style="box-sizing: border-box; color: rgb(54, 185, 204) !important; text-decoration: none; background-color: transparent; font-weight: 700 !important;">' . $row['id'] . '</a></td><td class="text-dark-75 font-weight-bolder font-size-lg mb-0" style="box-sizing: border-box; margin-bottom: 0px !important; font-weight: bolder !important; color: rgb(63, 66, 84) !important; padding: 0.75rem; vertical-align: top; border: 0px;">' . $row['descrizione'] . '</td><td class="text-primary mb-0" style="box-sizing: border-box; margin-bottom: 0px !important; color: rgb(78, 115, 223) !important; padding: 0.75rem; vertical-align: top; border: 0px;">' . $row['dataapertura'] . '</td><td style="box-sizing: border-box; padding: 0.75rem; vertical-align: top; border: 0px;"><i style="box-sizing: border-box;">FAVATA FELICE</i></td><td style="box-sizing: border-box; padding: 0.75rem; vertical-align: top; border: 0px;"> <div class="text-muted" style="box-sizing: border-box; color: rgb(181, 181, 195) !important; width: 180px;">' . $stato . '</div></td></tr>';
    }

    //FOOTER TABELLA
    $logghi .= '</tbody></table>';

    // ======== SOSPESO ======== //
    // $logghi .= inviamail($logghi, 'Pratiche aperte', 'avvocato');
} else {

    $logghi .= ' ---  Nulla da fare!';
    echo '<br />';
}



//INVIO MAIL
function inviamail($testo, $titolo, $destinatario)
{
    if ($destinatario == 'assistenza') {
        $destinatario = 'assistenza@scifostore.com';
    } else if ($destinatario == 'amministrazione') {
        $destinatario = 'amministrazione@scifostore.com';
    } else if ($destinatario == 'info') {
        $destinatario = 'info@scifostore.com';
    } else if ($destinatario == 'avvocato') {
        $destinatario = 'favatafelice@gmail.com';
    }

    $mail = new PHPMailer(true);

    $mail->IsSMTP();
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->SMTPAuth   = true;
    $mail->Host       = 'ssl://out.postassl.it';
    $mail->Port       = 465;
    $mail->SMTPSecure = "tls";
    $mail->Username   = 'noreply@scifostore.com';
    $mail->Password   = '2wK$6nCU9Jo9';
    $mail->IsHTML(true);
    $mail->addCustomHeader('X-content-header', 'Content-Type:text/html;charset=utf-8');

    $mail->setFrom('noreply@scifostore.com', 'ScifoStore');
    $mail->addAddress($destinatario);
    $mail->addReplyTo('support@scifostore.com', '[Support] ScifoStore');

    $mail->Subject = $titolo;

    $mail->Body = $testo;


    if (!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return 'Mail inviata con successo';
    }
}
echo $logghi;

// ======== SOSPESO ======== //
// $oggi = date('Y-m-d');
// $log = fopen("log/" . $oggi . "_1GL9_log.txt", "w") or die("Impossibile creare il file " . $oggi . "_1GL9_log.txt.");
// $scrivi = fwrite($log, "Eseguito il " . date('Y-m-d H:i:s') . $logghi);
// fclose($log);

exit;
