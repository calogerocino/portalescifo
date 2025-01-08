<?php
include_once((dirname(dirname(__FILE__)) . '/mail/Exception.php') );
include_once((dirname(dirname(__FILE__)) . '/mail/phpmailer.php'));
include_once((dirname(dirname(__FILE__)) . '/mail/SMTP.php'));
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



$logghi = '';

include_once((dirname(dirname(__FILE__)) . '/inc/database.php'));

$mese = date("m");
$anno = date("Y");
$giorno = (date("d") - 10);

//SCADENZE IN RITARDO
$sql = "SELECT ID FROM ctb_pagamento WHERE Pagato=0 AND MONTH(Data)='" . $mese . "' AND DAY(Data)='" . $giorno . "'  AND YEAR(Data)='" . $anno . "'";

$result = $conn->query($sql);
if ($result->num_rows >= 1) {

    while ($row = $result->fetch_assoc()) {
        $sql1 = "UPDATE ctb_pagamento SET Pagato=4 WHERE ID=" . $row['ID'];
        if (!$conn->query($sql1)) {
            echo $conn->error;
        } else {
            $logghi .= ' ---  Pagamento ID: ' . $row['ID'] . ' in ritardo!';
        }
        echo '<br />';
    }
    $logghi .= inviamail($logghi, 'Scadenze in ritardo', 'amministrazione');
    $logghi .= inviamail($logghi, 'Scadenze in ritardo', 'info');
} else {
    $logghi .= ' ---  Nulla da fare!';
    echo '<br />';
}


//TICKET CHE SONO IN RITARDO
// $sql1 = "SELECT NTicket, Operatore FROM donl_ticket WHERE (Stato='Nuovo' OR Stato='Gestione') AND (UltimoContatto LIKE '%" . $anno . "-" . $mese . "-%' AND UltimoContatto LIKE '%-" . $giorno . "')";

// $result2 = $conn->query($sql2);
// if ($result2->num_rows >= 1) {
//     while ($row2 = $result->fetch_assoc()) {
//         $logghi .= ' ---  Ticket ID: ' . $row2['NTicket'] . ' da gestire urgentemente!';
//         if ($row2['Operatore'] == 'calogero') {
//             $logghi .= inviamail($logghi, 'Scadenze in ritardo', 'assistenza');
//         } else if ($row2['Operatore'] == 'pasquale') {
//             $logghi .= inviamail($logghi, 'Scadenze in ritardo', 'amministrazione');
//         } else if ($row2['Operatore'] == 'salvatoreg') {
//             $logghi .= inviamail($logghi, 'Scadenze in ritardo', 'info');
//         }
//     }
// } else {
//     $logghi .= ' ---  Nulla da fare!';
//     echo '<br />';
// }

//INVIO MAIL
function inviamail($testo, $titolo, $destinatario)
{
    if ($destinatario == 'assistenza') {
        $destinatario = 'assistenza@scifostore.com';
    } else if ($destinatario == 'amministrazione') {
        $destinatario = 'amministrazione@scifostore.com';
    } else if ($destinatario == 'info') {
        $destinatario = 'info@scifostore.com';
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

    $mail->Subject = $titolo;

    $mail->Body = '<p> <title>&nbsp;' . $mail->Subject . '&nbsp;</title></p><style type="text/css">#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><style type="text/css">@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><style type="text/css">.shadow{box-shadow:0 20px 30px 0 rgba(0,0,0,.1)}.label{font-weight:700}.warning{font-weight:700;font-size:16px}a{color:#25b9d7;text-decoration:underline;font-weight:600}a.light{font-weight:400}span.strong{font-weight:600}@media only screen and (max-width:480px){.no-bg,body{background-color:#fff!important}.left p{text-align:left;display:inline-block}}</style><style type="text/css">#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><style type="text/css">@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><style type="text/css">@import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700);@import url(https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i);</style><style type="text/css">@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important;max-width:100%}.mj-column-px-25{width:25px!important;max-width:25px}}</style><style type="text/css">.shadow{box-shadow:0 20px 30px 0 rgba(0,0,0,.1)}.label{font-weight:700}.warning{font-weight:700;font-size:16px}a{color:#25b9d7;text-decoration:underline;font-weight:600}a.light{font-weight:400}span.strong{font-weight:600}@media only screen and (max-width:480px){.no-bg,body{background-color:#fff!important}.left p{text-align:left;display:inline-block}}</style><div bgcolor="#eeeeee" style="background-color:#eeeeee;"> <div height="40" style="height:40px;">&nbsp;</div><div bgcolor="#ffffff" style="background: #ffffff;background-color: #ffffff;Margin: 0px auto;border-radius: 4px;max-width: 604px;"> <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;background: #ffffff;background-color: #ffffff;width: 80%;border-radius: 4px;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;direction: ltr;font-size: 0px;padding: 0 0 30px;text-align: center;vertical-align: top;"> <div style="Margin:0px auto;"> <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;width: 100%;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;direction: ltr;font-size: 0px;padding: 45px 25px;text-align: center;vertical-align: top;"> <div align="left" style="font-size:13px;text-align:left;vertical-align:top;width:100%;" width="100%"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;vertical-align: top;" width="100%"> <tbody> <tr> <td align="left" style="border-collapse: collapse;font-size: 0px;padding: 10px 25px;word-break: break-word;"> <table border="0" cellpadding="0" cellspacing="0px" style="border-collapse: collapse;border-spacing: 0px;"> <tbody> <tr> <td style="border-collapse: collapse;width: 150px;" width="100%"><a href="https://scifostore.com/" style="color: #25B9D7;text-decoration: underline;font-weight: 600;" target="_blank">&nbsp;<img src="https://scifostore.com/img/home/scifostore-22-ass.png" style="line-height: 100%; border: 0px; text-decoration: none; height: auto; width: 100%; font-size: 13px;" width="50%" height="auto" border="0"></a></td></tr></tbody> </table> </td></tr></tbody> </table> </div></td></tr></tbody> </table> </div><div style="Margin:0px auto;"> <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;width: 100%;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;direction: ltr;font-size: 0px;padding: 0 25px 0;text-align: center;vertical-align: top;"> <div align="left" style="font-size:13px;text-align:left;vertical-align:top;width:100%;" width="100%"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%"> <tbody> <tr> <td style="border-collapse: collapse;vertical-align: top;padding: 0;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%"> <tbody> <tr> <td align="left" style="border-collapse: collapse;font-size: 0px;padding: 10px 25px;padding-top: 0px;padding-bottom: 0px;word-break: break-word;"> <div align="left" style="font-family:Open sans, arial, sans-serif;font-size:16px;font-weight:600;line-height:25px;text-align:left;color:#363A41;">' . $mail->Subject . '</div></td></tr></tbody> </table> </td></tr></tbody> </table> </div></td></tr></tbody> </table> </div><div style="Margin:0px auto;"> <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;width: 100%;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;direction: ltr;font-size: 0px;padding: 15px 50px 40px;text-align: center;vertical-align: top;"> <div align="left" style="font-size:13px;text-align:left;vertical-align:top;width:100%;" width="100%"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%"> <tbody> <tr> <td bgcolor="#fefefe" style="border-collapse: collapse;background-color: #fefefe;border: 1px solid #DFDFDF;vertical-align: top;padding-top: 10px;padding-bottom: 10px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%"> <tbody> <tr> <td align="left" style="border-collapse: collapse;font-size: 0px;padding: 10px 25px;word-break: break-word;"> <div align="left" style="font-family:Open sans, arial, sans-serif;font-size:14px;line-height:25px;text-align:left;color:#363A41;">' . $testo . '</div></td></tr></tbody> </table> </td></tr></tbody> </table> </div></td></tr></tbody> </table> </div></td></tr></tbody> </table> </div><div style="Margin:0px auto;"> <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;width: 100%;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;direction: ltr;font-size: 0px;padding: 20px 0;text-align: center;vertical-align: top;"> <div align="left" style="font-size:13px;text-align:left;vertical-align:top;width:100%;" width="100%"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;vertical-align: top;" width="100%"> <tbody> <tr> <td align="center" style="border-collapse: collapse;font-size: 0px;padding: 10px 25px;word-break: break-word;"> <div align="center" style="font-family:Open sans, arial, sans-serif;font-size:14px;line-height:25px;text-align:center;color:#363A41;"><a href="https://scifostore.com/" style="text-decoration: underline;color: #656565;font-size: 16px;font-weight: 600;">ScifoStore</a></div></td></tr><tr> <td align="center" style="border-collapse: collapse;font-size: 0px;padding: 10px 25px;padding-top: 0;word-break: break-word;"> <div align="center" style="font-family:Open sans, arial, sans-serif;font-size:12px;line-height:25px;text-align:center;color:#656565;"></a></div></td></tr></tbody> </table> </div></td></tr></tbody> </table> </div></div>';


    if (!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return 'Mail inviata con successo';
    }
}

$oggi = date('Y-m-d');
$log = fopen("log/" . $oggi . "_1G8_log.txt", "w") or die("Impossibile creare il file " . $oggi . "_1G8_log.txt.");
$scrivi = fwrite($log, "Eseguito il " . date('Y-m-d H:i:s') . $logghi);
fclose($log);

exit;
