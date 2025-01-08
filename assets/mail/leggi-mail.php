<?php
//error_reporting(0);

$hostname = '{in.postassl.it:993/imap/ssl}INBOX';

$message = '';
$email = $_POST['mail'];

if (isset($_POST['dachi'])) {
    $dachi = $_POST['dachi'];

    if ($dachi == 0) {
        $username = 'support@scifostore.com';
        $password = 'kaxof$ZQF8J9';
    } else if ($dachi == 1) {
        $username = 'amministrazione@scifostore.com';
        $password = 'zhW7gm@BjH@3';
    } else if ($dachi == 5) {
        $username = 'assistenza@scifostore.com';
        $password = 'k46P8c!9CtPD';
    } else if ($dachi == 2) {
        $username = 'info@scifostore.com';
        $password = 'PutE!byrN29@';
    } else if ($dachi == 3) {
        $username = 'ricambi@scifostore.com';
        $password = 'gYGsc$3@vjmg';
    } else if ($dachi == 4) {
        $username = 'spedizioni@scifostore.com';
        $password = 'eY5LWRVUqhH@';
    }
} else {
    $username = 'support@scifostore.com';
    $password = 'kaxof$ZQF8J9';
}


$inbox = imap_open($hostname, $username, $password) or die('Impossibile connettersi: ' . imap_last_error());

$emails = imap_search($inbox, 'FROM "' . $email . '"', SE_UID);

if ($emails) {

    rsort($emails);

    foreach ($emails as $email_number) {
        $message .= imap_fetchbody($inbox, $email_number, 2, FT_UID); //MESSAGGIO
    }
}

echo utf8_encode($message);

imap_close($inbox);
