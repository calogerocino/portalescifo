<?php

/* connect to gmail */
$hostname = '{in.postassl.it:993/imap/ssl}INBOX';
$username = 'support@scifostore.com';
$password = 'kaxof$ZQF8J9';

/* try to connect */
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox, 'ALL');

/* if emails are returned, cycle through each... */
if ($emails) {

    /* begin output var */
    $output = '';

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    foreach ($emails as $email_number) {

        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        if ($overview[0]->seen == 'unread') {

            $message = imap_fetchbody($inbox, $email_number, 2);

            $header_string =  imap_fetchheader($inbox, $email_number);
            preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)\r\n/m', $header_string, $matches);
            $headers = array_combine($matches[1], $matches[2]);
            // $xmailer = $headers['X-idticket'];

            /* output the email header information */
            $output .= '<div class="toggler ' . ($overview[0]->seen ? 'read' : 'unread') . '">';
            $output .= '<span class="subject">' . $overview[0]->subject . '</span> ';
            $output .= '<span class="from">' . $overview[0]->from . '</span>';
            $output .= '<span class="date">on ' . $overview[0]->date . '</span>';
            $output .= '</div>';

            /* output the email body */
            $output .= '<div class="body">' . $message . '</div>';
            // $output .= '<div class="body">' . $xmailer . '</div>';

          //  $status = imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged");

            $int = preg_replace("/[^0-9]/", "", $overview[0]->subject);
        }
    }

    echo $output;
    //echo $int;
}

/* close the connection */
imap_close($inbox);
