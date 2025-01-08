<?php
error_reporting(0);
session_start();

include("database.php");
$azione = $_POST['azione'];
$dati = '';

if ($azione == 'activitylog') {
    $sql = "SELECT al.*, u.nome FROM app_activity_log al INNER JOIN app_utenti u ON (u.id=al.IDU) WHERE al.IDU=" . $_POST['idu'] . " ORDER BY al.ID DESC";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $res = explode(':', $row['Azione']);
            $dati .= "<tr>
            <td class=\"idmov py-2 align-middle\">#" . $row['ID'] . "</td>
            <td class=\"act py-2 align-middle\"><strong>Azione: </strong>" . (($res[0] == 'up') ? 'modifica' : 'inserimento') . " - <strong>Settore: </strong>" . $res[1] . "  - <strong>Funzione: </strong>" . $res[2] . "</td>
            <td class=\"data py-2 align-middle\">" . $row['Data'] . "</td>
            <td class=\"text-muted py-2 align-middle user-select-none\">" . $row['nome'] . "</td>
            </tr>";
        }
    } else {
        $dati .= "<tr>
            <td>NESSUN RISULTATO</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>";
    }

    echo $dati;
    exit;
} else if ($azione == 'listautenti') {
    $sql = "SELECT * FROM app_utenti";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            if ($row['Attivo'] == 1) {
                $stato = '<span title="Esegui un doppio click per disattivare l\'utente!" class="badge badge rounded-pill d-block badge-soft-success" title="Attivato" ondblclick="AbDabUtente(' . $row['id'] . ', ' . $row['Attivo'] . ')" style="cursor:pointer;">Attivato <svg class="svg-inline--fa fa-check fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" transform="translate(-256 -256)"></path></g></g></svg></span>';
            } else {
                $stato = '<span title="Esegui un doppio click per attivare l\'utente!" class="badge badge rounded-pill d-block badge-soft-secondary" title="Disattivato" ondblclick="AbDabUtente(' . $row['id'] . ', ' . $row['Attivo'] . ')" style="cursor:pointer;">Disattivato <svg class="svg-inline--fa fa-ban fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" transform="translate(-256 -256)"></path></g></g></svg></span>';
            }

            $data2 .= '<tr>
                <td class="py-2 align-middle"><strong>' . $row['nome'] . '</strong><br>User: <strong>' . $row['user'] . '</strong></td>
                <td class="py-2 align-middle">' . (($row['email']) ? $row['email'] : '<i>Nessuna mail inserita!</i>')  . '</td>
                <td class="py-2 align-middle text-center white-space-nowrap"><button class="btn btn-falcon-primary rounded-pill me-1 mb-1" type="button" onclick="visutenti_ut(' . $row['id'] . ')">Visualizza</button></td>
                <td class="py-2 align-middle">' . (($row['IPTelefono']) ? $row['IPTelefono'] : '<i>IP non trovato!</i>')  . '</td>
                <td class="py-2 align-middle">' . $row['Ruolo'] . '</td>
                <td class="py-2 align-middle text-center fs-0 white-space-nowrap">' . $stato . '</td>
                </tr>';
        }
    }

    echo $data2;
    exit;
} else if ($azione == 'utentilog') {
    $online = '';
    $int = 2;
    $t = time();
    $loop = $t - ($int * 60);
    $ip = htmlspecialchars($_SESSION['session_idu']);
    // $ip = $_SERVER['REMOTE_ADDR'];
    $fl = $_SERVER['PHP_SELF'];

    $q0 = "INSERT INTO `app_utenti_online` (`ip`, `tempo`, `file`) VALUES ('$ip', '$t', '$fl')";
    if (!$conn->query($q0)) {
        return -1;
    }

    $q1 = "DELETE FROM `app_utenti_online` WHERE `tempo`<'$loop'";
    if (!$conn->query($q1)) {
        return -1;
    }

    $q2 = 'SELECT DISTINCT ip FROM `app_utenti_online`';
    $res1 = $conn->query($q2);
    while ($row = $res1->fetch_assoc()) {
        $q3 = 'SELECT user FROM app_utenti WHERE id=' . $row['ip'];
        $res2 = $conn->query($q3);
        while ($rr = $res2->fetch_assoc()) {
            $online .= $rr['user'] . '//' . $ip . '|-|';
        }
    }

    echo $online;
    exit;
} else if ($azione == 'abdabutente') {
    $sql = "UPDATE app_utenti SET Attivo=" . $_POST['stato'] . " WHERE id=" . $_POST['idu'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:index-control:abdabutente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'weeklySalesTot') {
    $day = date('w');
    $week_start = date('m-d-Y', strtotime('-' . ($day + 1) . ' days'));
    $week_end = date('m-d-Y', strtotime('+' . ((6 - $day) + 1) . ' days'));

    $sett1 = explode('-', $week_start);
    $sett2 = explode('-', $week_end);

    $result = $conn->query("SELECT SUM(Importo) as Totale FROM donl_ordini WHERE DataOrdine BETWEEN '" . $sett1[2] . '-' . $sett1[0] . '-' . $sett1[1] . "' AND '" . $sett2[2] . '-' . $sett2[0] . '-' . $sett2[1] . "'");
    $row = $result->fetch_assoc();
    echo $row['Totale'];
    exit;
} else if ($azione == 'weeklySalesChart') {
    setlocale(LC_TIME, 'it_IT');
    $day = date('w');
    strftime('%a', mktime(0, 0, 0, date('m'), $day));

    for ($i = 0; $i <= 6; $i++) {
        $GiorniSettimana = Ucwords(strftime('%a', mktime(0, 0, 0, date('m'), ($day - $i)))) . '-' . $GiorniSettimana;
    }

    // $day = date('w');
    // $week_start = date('m-d-Y', strtotime('-' . ($day + 1) . ' days'));
    // $week_end = date('m-d-Y', strtotime('+' . ((6 - $day) + 1) . ' days'));

    // $sett1 = explode('-', $week_start);
    // $sett2 = explode('-', $week_end);

    // $result = $conn->query("SELECT SUM(Importo) as Totale FROM donl_ordini WHERE DataOrdine BETWEEN '" . $sett1[2] . '-' . $sett1[0] . '-' . $sett1[1] . "' AND '" . $sett2[2] . '-' . $sett2[0] . '-' . $sett2[1] . "'");
    // $row = $result->fetch_assoc();
    echo $GiorniSettimana . '|-|0';
    exit;
}
