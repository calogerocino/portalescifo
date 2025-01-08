<?php
error_reporting(0);

$hostname = '{in.postassl.it:993/imap/ssl}INBOX';
$username = 'support@scifostore.com';
$password = 'kaxof$ZQF8J9';
$message = '';
$idmail = $_POST['idmail'];

$inbox = imap_open($hostname, $username, $password) or die('Cannot connect: ' . imap_last_error());

$emails = imap_search($inbox, 'ALL');

if ($emails) {

    rsort($emails);
    foreach ($emails as $email_number) {
        if ($email_number == $idmail) {

            $overview = imap_fetch_overview($inbox, $email_number, 0);

            $message = imap_fetchbody($inbox, $email_number, 2); //MESSAGGIO
            //$message = base64_decode($message);
            $oggetto = $overview[0]->subject;
            $from = $overview[0]->from;

            $structure = imap_fetchstructure($inbox, $email_number);
            $part = $structure->parts[1];

            if ($part->encoding == 3) {
                $message = imap_base64($message);
            } else if ($part->encoding == 1) {
                $message = imap_8bit($message);
            } else if ($part->encoding == 4) {
                $message = imap_qprint($message);
            }

            $status = imap_setflag_full($inbox, $idmail, "\\Seen");
        }
    }
}

echo $message . "|-|" . $oggetto . "|-|" . $from;

imap_close($inbox);
