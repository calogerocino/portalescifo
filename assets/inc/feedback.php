<?php
//error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

$dati = '';

if ($azione == 'aggiornalista') {
    $nstato = '';
    $azioni = '';
    $clausola =  $_POST['clausolafeed'];
    $oggi = date("Y-m-d");
    $sql = "SELECT o.ID as IDordine, c.Cliente, o.NOrdine, o.Stfeed, c.ID as IDcliente, o.Piattaforma, o.DataEvasione, o.DataOrdine FROM donl_ordini o INNER JOIN donl_clienti c ON (c.ID=o.IDCl)" . $clausola . ' ORDER BY o.DataOrdine ASC';
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $evasione = DateTime::createFromFormat('d/m/Y', $row['DataEvasione'])->format('Y-m-d');
            $res = explode('-', $row['DataOrdine']);

            $checkdate['diff'] = floor((strtotime($evasione) - strtotime($row['DataOrdine'])) / 86400);
            if ($evasione < $oggi) {
                if ($checkdate['diff'] > 3 && $checkdate['diff'] <= 14) {
                    if ($row['Stfeed'] == 0) {
                        $nstato = '<span class="badge badge rounded-pill d-block badge-soft-primary" title="Da contattare">Da contattare <svg class="svg-inline--fa fa-redo fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="redo" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M500.33 0h-47.41a12 12 0 0 0-12 12.57l4 82.76A247.42 247.42 0 0 0 256 8C119.34 8 7.9 119.53 8 256.19 8.1 393.07 119.1 504 256 504a247.1 247.1 0 0 0 166.18-63.91 12 12 0 0 0 .48-17.43l-34-34a12 12 0 0 0-16.38-.55A176 176 0 1 1 402.1 157.8l-101.53-4.87a12 12 0 0 0-12.57 12v47.41a12 12 0 0 0 12 12h200.33a12 12 0 0 0 12-12V12a12 12 0 0 0-12-12z" transform="translate(-256 -256)"></path></g></g></svg></span>';
                        $azioni = '<li><a class="dropdown-item" id="' . $row['IDordine'] . '" href="javascript:void(0)" onclick="statofeed(1, ' . $row['IDordine'] . ')" title="Contattato"><i class="fa-regular fa-check"></i> Contattato</a></li><li><a class="dropdown-item" id="' . $row['IDordine'] . '" href="javascript:void(0)" onclick="statofeed(2, ' . $row['IDordine'] . ')" title="Elimina"><i class="fa-regular fa-ban"></i> Rimuovi</a></li>';
                    } else if ($row['Stfeed'] == 1) {
                        $nstato = '<span class="badge badge rounded-pill d-block badge-soft-success" title="Contattato">Contattato <svg class="svg-inline--fa fa-check fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" transform="translate(-256 -256)"></path></g></g></svg></span>';
                        $azioni = '<li><a class="dropdown-item" id="' . $row['IDordine'] . '" href="javascript:void(0)" onclick="statofeed(0, ' . $row['IDordine'] . ')" title="Da contattare"><i class="fa-regular fa-tty"></i> Da Contattare</a></li><li><a class="dropdown-item" id="' . $row['IDordine'] . '" href="javascript:void(0)" onclick="statofeed(2, ' . $row['IDordine'] . ')" title="Elimina"><i class="fa-regular fa-ban"></i> Rimuovi</a></li>';
                    } else if ($row['Stfeed'] == 2) {
                        $nstato = '<span class="badge badge rounded-pill d-block badge-soft-secondary" title="Rimosso">Rimosso <svg class="svg-inline--fa fa-ban fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" transform="translate(-256 -256)"></path></g></g></svg></span>';
                        $azioni = '<li><a class="dropdown-item" id="' . $row['IDordine'] . '" href="javascript:void(0)" onclick="statofeed(0, ' . $row['IDordine'] . ')" title="Da contattare"><i class="fa-regular fa-tty"></i> Da contattare</a></li>';
                    }

                    if ($checkdate['diff'] >= 0 && $checkdate['diff'] <= 7) {
                        $checkdate['classe'] = 'success';
                    } else if ($checkdate['diff'] >= 8 && $checkdate['diff'] <= 10) {
                        $checkdate['classe'] = 'warning';
                    } else if ($checkdate['diff'] >= 11 && $checkdate['diff'] <= 14) {
                        $checkdate['classe'] = 'danger';
                    }

                    $dati .= '<tr>';
                    $dati .= '<td class="cliente py-2 align-middle" style="cursor: pointer;" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')"><a href="javascript:void(0)"> <strong>#' . $row['IDordine'] . '</strong></a> di<br> <strong>' . $row['Cliente'] . '</strong></td>';
                    $dati .= '<td class="nordine py-2 align-middle" style="cursor: pointer;" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')"><strong>' . $row['Piattaforma'] . '</strong><br>' . $row['NOrdine'] . '</td>';
                    $dati .= '<td class="giorni align-middle text-center fs-0 white-space-nowrap"  style="cursor:pointer;text-align: center; vertical-align: middle;" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')"><div class="text-muted"><i class="badge bg-' . $checkdate['classe'] . '"><b>' . $checkdate['diff'] . '</b></i></div></td>';
                    $dati .= '<td class="dataordine py-2 align-middle" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')">' . $res[2] . '/' . $res[1] . '/' . $res[0] . '</td>';
                    $dati .= '<td class="dataevasione py-2 align-middle" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')">' . $row['DataEvasione'] . '</td>';
                    $dati .= '<td class="stato py-2 align-middle text-center fs-0 white-space-nowrap" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['IDordine'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')">' . $nstato . '</td>';
                    $dati .= '<td class="py-2 align-middle white-space-nowrap text-end"><div class="dropdown font-sans-serif position-static"><button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" id="order-dropdown-1" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg></button><div class="dropdown-menu dropdown-menu-end border py-0" aria-labelledby="order-dropdown-1" style=""><div class="bg-white py-2">  ' . $azioni . '</div></div></div></td>';
                    $dati .= '</tr>';
                }
            }
        }
    } else {
        $dati .= '<td class="text-center py-2 align-middle" colspan="7">Nessun dato disponibile</td>';
    }

    echo $dati;
    exit;
} else  if ($azione == 'cambiastato') {
    $sql = "UPDATE donl_ordini SET Stfeed=" . $_POST['Stfeed'] . " WHERE ID=" . $_POST['idordine'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:feedback:cambiastato', $_SESSION['session_idu']);
    exit;
}
