<?php

$idordine = $_POST['idordine'];
$azione = $_POST['azione'];
// PRENDI ID SUPPORT

include("database.php");

if ($azione == 'datitk') {
    $sql = "SELECT `ID`, `NTicket`, `Stato`, `Tipologia`, `Creatoda`, `Operatore`, `UltimoContatto`, `Problema`,`Notainterna`, `DataCreazione`, `ido` FROM `donl_ticket` WHERE ido=" . $idordine;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Stato'] . "|-|" . $row['Tipologia'] . "|-|" . $row['Operatore'] . "|-|" . $row['Problema']  . "|-|" . $row['Notainterna'] . "|-|" . $row['DataCreazione'] . "|-|" . $row['UltimoContatto'] . "|-|" . $row['NTicket'];
        }
    }
} else if ($azione == 'aggiornanota') {
    $sql = "UPDATE `donl_ticket` SET `Notainterna`='" . $_POST['notaint'] . "' WHERE `ido`=" . $idordine;
    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "ok";
    }
    activitylog($conn, 'up:ticket:aggiornanota', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'impostacome') {

    session_start();
    if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
        $session_uname = htmlspecialchars($_SESSION['session_nome'], ENT_QUOTES, 'UTF-8');
    }


    if ($_POST['stato'] == 'Nuovo') {
        $sql = "INSERT INTO donl_ticket (`NTicket`, `Stato`, `Tipologia`, `Creatoda`, `Notainterna`, `Aperto`, `DataCreazione`, `ido`) VALUES ('TK-" . $idordine . "', '" . $_POST['stato'] . "', '" . $_POST['tipologia'] . "', '" . $session_uname . "', '" . $_POST['nota'] . "', 'SI', NOW(), '" . $idordine . "')";
        if (!$conn->query($sql)) {
            echo "Errore " . $conn->error;
        } else {
            echo "Ticket creato con successo";
        }
        activitylog($conn, 'in:ticket:impostacome', $_SESSION['session_idu']);
    } else if ($_POST['stato'] == 'Attesa') {
        $sql = "UPDATE `donl_ticket` SET `Stato`='" . $_POST['stato'] . "', `Aperto`='" . $_POST['aperto'] . "', `Operatore`='', `UltimoContatto`= NOW() WHERE `ido`=" . $idordine;
        if (!$conn->query($sql)) {
            echo "Errore " . $conn->error;
        } else {
            echo "Ticket aggiornato con successo";
        }
        activitylog($conn, 'up:ticket:impostacome', $_SESSION['session_idu']);
    } else {
        $sql = "UPDATE `donl_ticket` SET `Stato`='" . $_POST['stato'] . "', `Aperto`='" . $_POST['aperto'] . "', `Operatore`='" . $session_uname . "', `UltimoContatto`= NOW() WHERE `ido`=" . $idordine;
        if (!$conn->query($sql)) {
            echo "Errore " . $conn->error;
        } else {
            echo "Ticket aggiornato con successo";
        }
        activitylog($conn, 'up:ticket:impostacome', $_SESSION['session_idu']);
    }
    exit;
} else if ($azione == 'aggiornadata') {

    session_start();
    if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
        $session_uname = htmlspecialchars($_SESSION['session_nome'], ENT_QUOTES, 'UTF-8');
    }

    $sql = "UPDATE `donl_ticket` SET `Operatore`='" . $session_uname . "', `UltimoContatto`= NOW() WHERE `ido`=" . $idordine;
    if (!$conn->query($sql)) {
        echo "Errore " . $conn->error;
    } else {
        echo "ok";
    }
    activitylog($conn, 'up:ticket:aggiornadata', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornalista') {
    $dati = '';
    $stato = '';
    $clausola1 = $_POST['clausola1'];
    $cl_spec = $_POST['clspec'];

    $sql = "SELECT c.Cliente, t.Tipologia, t.Stato, t.Operatore, t.ido, o.Pagamento, o.Importo FROM donl_clienti c INNER JOIN (donl_ticket t INNER JOIN donl_ordini o ON o.ID = t.ido) ON c.ID = o.IDCl WHERE t.Aperto ='SI' " . $clausola1 . " ORDER BY t.DataCreazione DESC";

    $result = $conn->query($sql);

    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Stato'] == 'Nuovo' || $row['Stato'] == 'Attesa') {
                $stato = "<span class=\"badge badge rounded-pill d-block badge-soft-primary\" title=\"" . $row['Stato'] . "\">" . $row['Stato'] . " <svg class=\"svg-inline--fa fa-redo fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"redo\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M500.33 0h-47.41a12 12 0 0 0-12 12.57l4 82.76A247.42 247.42 0 0 0 256 8C119.34 8 7.9 119.53 8 256.19 8.1 393.07 119.1 504 256 504a247.1 247.1 0 0 0 166.18-63.91 12 12 0 0 0 .48-17.43l-34-34a12 12 0 0 0-16.38-.55A176 176 0 1 1 402.1 157.8l-101.53-4.87a12 12 0 0 0-12.57 12v47.41a12 12 0 0 0 12 12h200.33a12 12 0 0 0 12-12V12a12 12 0 0 0-12-12z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            } else if ($row['Stato'] == 'Gestione') {
                $stato = "<span class=\"badge badge rounded-pill d-block badge-soft-warning\" title=\"" . $row['Stato'] . "\">" . $row['Stato'] . " <i class=\"fa-regular fa-hand\"></i></span>";
            } else {
                $stato = "<span class=\"badge badge rounded-pill d-block badge-soft-success\" title=\"" . $row['Stato'] . "\">" . $row['Stato'] . " <svg class=\"svg-inline--fa fa-check fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"check\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            }

            if ($cl_spec != '') {
                $inforimb = '
                <td>
                    <div class="mb-0" style="text-align: center;"><i>' . $row['Pagamento'] . '</i></div>
                    <div class="mb-0" style="text-align: center;"><b>€ ' . $row['Importo'] . '</b></div>
                </td>';
            } else {
                $inforimb = '<td></td>';
            }

            $dati .=  '<tr style="cursor: pointer;" onclick="CaricaFrame(\'ordini/fastord.php?idordine=' . $row['ido'] . '\', \'Visualizza ordine\', \'Visualizza una pagina rapida delle informazioni in merito a un ordine\', \'85%\')">
            <td class="ordine py-2 align-middle"><a href="javascript:void(0)"> <strong>#' . $row['ido'] . '</strong></a> di<br> <strong>' . $row['Cliente'] . '</strong></td>
            <td class="tipologia py-2 align-middle">' . $row['Tipologia'] . '</td>
            <td class="stato py-2 align-middle text-center fs-0 white-space-nowrap">' . $stato . '</td>
            <td class="operatore py-2 align-middle">' . operatorecolo($row['Operatore']) . '</td>' . $inforimb . '
            </tr>';
        }
    } else {
        $dati .= '<td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td>';
    }

    echo $dati;
    exit;
} else if ($azione == 'cercacliente') {
    $data = '';
    $sql = "SELECT ID, Cliente FROM donl_clienti WHERE Cliente LIKE '%" . $_POST['ricerca'] . "%'";
    $result = $conn->query($sql);;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data .=  '<option label="' . $row['ID'] . '" value="' . $row['Cliente'] . '">';
        }
    }
    echo $data;
    exit;
} else if ($azione == 'cercanordcl') {
    $data = '';
    $sql = "SELECT ID, NOrdine FROM donl_ordini WHERE IDCl = '" . $_POST['ricerca'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data .=  '<option label="' . $row['ID'] . '" value="' . $row['NOrdine'] . '">';
        }
    }
    echo $data;
    exit;
} else if ($azione == 'cercaticket') {
    $sql = "SELECT ido FROM donl_ticket WHERE ido = " . $idordine . " ORDER BY ID DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo 'si|-|' . $idordine;
    } else {
        echo 'no|-|' . $idordine;
    }
    exit;
} else if ($azione == 'cercamail') {
    $sql = "SELECT Email FROM donl_clienti WHERE ID = '" . $_POST['idcl'] . "' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['Email'];
    }
    exit;
}


function operatorecolo($op)
{
    if ($op == '') {
        return '<b><i>Non Assegnato</i></b>';
    } else {
        return $op;
    }
}
