<?php
//error_reporting(0);
session_start();
date_default_timezone_set('Europe/Rome');
setlocale(LC_ALL, 'it', 'it_IT', 'ita', 'it_IT@euro', 'it_IT.UTF-8', 'Italian');

include("../inc/database.php");
if (isset($_SESSION['session_id'])) {
    $session_idu = htmlspecialchars($_SESSION['session_idu'], ENT_QUOTES, 'UTF-8');
}

//$start = microtime(true); // BENCHMARK 1

$totmail = 0;
$hostname = '{in.postassl.it:993/imap/ssl}INBOX';
$username = 'support@scifostore.com';
$password = 'kaxof$ZQF8J9';
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect: ' . imap_last_error());

//echo 'Tempo connessione: ' . (microtime(true) - $start) . ' secondi<br>'; // RISULTATO BENCH 1

// ---------------------------------------------------------------------

//$start = microtime(true); // BENCHMARK 2
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    $output = '';
    // rsort($emails);
    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 2, FT_UID); //MESSAGGIO
        $header_string =  imap_fetchheader($inbox, $email_number);
        preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)\r\n/m', $header_string, $matches);
        $headers = array_combine($matches[1], $matches[2]);

        $elements = imap_mime_header_decode($overview[0]->subject);

        //    $idsupport = preg_replace('!\(([^\)]+)\)!', "", $elements[0]->text); // PRENDI ID SUPPORT

        $matches = explode('[', $elements[0]->text);
        $matche  = explode(']',  $matches[1]);
        $idsupport  = explode('#',  $matche[0]);

        $sql = "SELECT IDOP FROM donl_opmail WHERE ID=" . $idsupport[1] . " LIMIT 1";
        $result = $conn->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            if ($session_idu == $row['IDOP']) {
                $output .= $email_number . '|--|' . $elements[0]->text . '|--|' . $overview[0]->from  . '|--|' . mb_convert_encoding(strftime('%A, %d %B %Y', strtotime($overview[0]->date)), 'UTF-8', mb_detect_encoding($out['giorno_app'], "auto"));
                $totmail = $totmail + 1;
            }
        }
        $status = imap_setflag_full($inbox, $email_number, "\\Seen");  // IMPOSTA COME NON LETTO
    }
    echo $totmail . '|-|' . $output;
}

//echo 'Recupero mail: ' . (microtime(true) - $start) . ' secondi<br>'; // BENCH2
imap_close($inbox);
exit;