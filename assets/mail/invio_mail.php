<?php
include("../inc/database.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "Exception.php";
require "phpmailer.php";
require "SMTP.php";

//function inviomail()
//{
//inizio variabili da modificare:

$firmaa = '';
function firma($chi, $int, $mai)
{
    return '<div>Distinti Saluti,<br /><br /></div>
    <div>
    <div><b>ScifoStore Europe </b>| <span><span color="#ff0000" style="color: #ff0000;"><b>' . $chi . '</b></span></span></div>
    <div><b><span color="#0000cd" style="color: #0000cd;">Tel.</span></b>: +39 0934 588985  <b>Interno ' . $int . '</b>| <b><span color="#0000ff" style="color: #0000ff;">Cel.</span></b>: +39 392 6563467</div>
    <div><b><span color="#0000ff" style="color: #0000ff;">Email</span></b>: ' . $mai . '</div>
    </div>';
}

$track = '[ScifoStore] ';

if ($_POST['mittente'] == 'noreply') {
    $mittente = 'noreply@scifostore.com';
    $password = '2wK$6nCU9Jo9';
    $nome = 'No Reply';
    $firmaa = '<div style="color: #b5b5b5;">##- Si prega di non rispondere a questa mail -##</div>';
} else  if ($_POST['mittente'] == 'support') {
    $mittente = 'support@scifostore.com';
    $password = 'kaxof$ZQF8J9';
    $nome = 'Support';
    $firmaa = firma('Support', '3', $mittente);

    if (strpos($_POST['oggetto'], '[#') !== false) {
        $track = '';
    }

    if (isset($_POST['idord'])) {


        $sql = "SELECT * FROM donl_opmail  WHERE IDOR=" . $_POST['idord'] . " AND IDOP='" . $_POST['iduser'] . "' LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows >= 1) {
            $sql = "UPDATE donl_opmail SET IDOP='" . $_POST['iduser'] . "' WHERE IDOR= " .  $_POST['idord'];
            if (!$conn->query($sql)) {
                //errore
            } else {
                $sql = "SELECT ID FROM donl_opmail  WHERE IDOR=" . $_POST['idord'] . " AND IDOP='" . $_POST['iduser'] . "' LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $track = '[#' . $row['ID'] . '] ';
            }
        } else {
            $sql = "INSERT INTO donl_opmail (IDOP, IDOR) VALUES ('" . $_POST['iduser'] . "', " .  $_POST['idord'] . ")";
            if (!$conn->query($sql)) {
                //errore
            } else {
                $sql = "SELECT ID FROM donl_opmail  WHERE IDOR=" . $_POST['idord'] . " AND IDOP='" . $_POST['iduser'] . "' LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $track = '[#' . $row['ID'] . '] ';
            }
        }
    }
} else if ($_POST['mittente'] == 'ricambi') {
    $mittente = 'ricambi@scifostore.com';
    $password = 'gYGsc$3@vjmg';
    $nome = 'Ricambi';
    $firmaa = firma('Support', '3', $mittente);
} else if ($_POST['mittente'] == 'assistenza') {
    $mittente = 'assistenza@scifostore.com';
    $password = 'k46P8c!9CtPD';
    $nome = 'Assistenza';
    $firmaa = firma('Assistenza Clienti', '1', $mittente);
} else if ($_POST['mittente'] == 'amministrazione') {
    $mittente = 'amministrazione@scifostore.com';
    $password = 'zhW7gm@BjH@3';
    $nome = 'Servizio Clienti';
    $firmaa = firma('Servizio Clienti', '1', $mittente);
} else if ($_POST['mittente'] == 'spedizioni') {
    $mittente = 'spedizioni@scifostore.com';
    $password = 'eY5LWRVUqhH@';
    $nome = 'Spedizioni';
    $firmaa = firma('Info Spedizioni', '2', $mittente);
} else if ($_POST['mittente'] == 'info') {
    $mittente = 'info@scifostore.com';
    $password = 'PutE!byrN29@';
    $nome = 'Info';
    $firmaa = firma('Info VivaiScifo', '0', $mittente);
}
//  else if ($_POST['mittente'] == 'eprice') {
//     $mittente = 'eprice@scifostore.com';
//     $password = '3yqygmuiP-';
//     $nome = 'ePtice';
//     $firmaa = firma('ePrice VivaiScifo', '0', $mittente);
// }



$server_smtp = 'ssl://out.postassl.it';
$username_smtp =   $mittente;
$password_smtp =  $password;
$indirizzo_mittente =  $mittente;
$descrizione_mittente = $nome;
$indirizzo_destinatario =  $_POST['indirizzodest'];
$corpo = $_POST['corpo'];
//fine variabili da modificare



try {

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
    $mail->Host       = $server_smtp;
    $mail->Port       = 465;
    $mail->SMTPSecure = "tls";
    $mail->Username   = $username_smtp;
    $mail->Password   = $password_smtp;
    $mail->CharSet = 'UTF-8';
    $mail->IsHTML(true);
    $mail->addCustomHeader('X-content-header', 'Content-Type:text/html;charset=utf-8');

    $mail->setFrom($indirizzo_mittente, $descrizione_mittente);
    $mail->addAddress($indirizzo_destinatario);
    // if (isset($_POST['cc'])) {
    //     $mail->AddCC($_POST['cc']);
    // }

    $mail->Subject = $track . $_POST['oggetto'];

    $mail->Body = '<html><p> <title>&nbsp;' . $mail->Subject . '&nbsp;</title></p><style type="text/css"> @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700); @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i); .background{background-color: #eeeeee;}.separatore{height: 40px;}.interno{background: #ffffff; background-color: #ffffff; Margin: 0px auto; border-radius: 4px; max-width: 55rem;}.tb1{margin: auto; width: 50%; padding: 10px; text-align: center; border-collapse: collapse; background: #ffffff; background-color: #ffffff; width: 80%; border-radius: 4px;}.tb2{padding: 10px; margin-left: 35px; font-family: Open sans, arial, sans-serif; font-size: 20px; font-weight: 600; line-height: 25px; text-align: left; color: #363A41;}.tb3-1{border-collapse: collapse; direction: ltr; font-size: 0px; padding: 15px 50px 15px; text-align: center; vertical-align: top;}.tb3-2{font-size: 13px; text-align: left; vertical-align: top; width: 100%;}.tb3-3{border-collapse: collapse; background-color: #fefefe; border: 1px solid #DFDFDF; vertical-align: top; padding-top: 10px; padding-bottom: 10px;}.tb3-4{border-collapse: collapse; font-size: 0px; padding: 10px 25px; word-break: break-word;}.tb4{padding: 10px; margin-left: 35px; font-family: Open sans, arial, sans-serif; font-size: 13px; line-height: 25px; text-align: left; color: #363A41;}.testo-corpo{font-family: Open sans, arial, sans-serif; font-size: 14px; line-height: 25px; text-align: left; color: #363A41;}.logo{color: #25B9D7; text-decoration: underline; font-weight: 600;}.logo2{line-height: 100%; border: 0px; text-decoration: none; font-size: 13px;}</style><div class="background"> <div class="separatore">&nbsp;</div><div class="interno"> <div class="tb1"> <a class="logo" href="https://scifostore.com/" target="_blank">&nbsp;<img src="https://scifostore.com/img/home/scifostore-22-ass.png" class="logo2" width="60%"></a> </div><div class="tb2"> ' . $mail->Subject . ' </div><div class="tb3-1"> <div class="tb3-2"> <div class="tb3-3"> <div class="tb3-4"> <div class="testo-corpo"> ' . $corpo . ' </div></div></div></div></div><div class="tb4"> ' . $firmaa . ' </div></div><div class="separatore">&nbsp;</div></div></html>';

    if (isset($_FILES['file'])) {
        $mail->AddAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
    }

    $mail->send();
    echo "Mail inviata con successo!";
    $stream = imap_open('{in.postassl.it:993/imap/ssl}INBOX.Sent', $username_smtp, $password_smtp);
    imap_append($stream, "{in.postassl.it:993/imap/ssl}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
    imap_close($stream);
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}
