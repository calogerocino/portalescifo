<?php
//error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

$dati = '';

if ($azione == 'aggiornalista') {
    $clausola =  $_POST['clausola'];
    $nsent = '';
    $stato = '';
  echo  $sql = "SELECT p.id, p.descrizione, p.dataapertura, p.stato, a.nome FROM amm_pratiche p INNER JOIN amm_avvocati a ON (p.idavv=a.id)" . $clausola;
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $sql1 = "SELECT COUNT(id) as totsent FROM amm_sentenze WHERE idpratica=" . $row['id'] . " AND Stato=0";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows >= 1) {
                while ($row1 = $result1->fetch_assoc()) {
                    $nsent = $row1['totsent'];
                }
            }
            $ndata = explode('-',  $row['dataapertura']);

            if ($row['stato'] == 0) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-secondary" title="Nuova">Nuova</span>';
            } else if ($row['stato'] == 2) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-warning" title="Attesa sentenza">Attesa sentenza <i class="fa-regular fa-hand"></i></span>';
            } else if ($row['stato'] == 4) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-secondary" title="Sospesa">Sospesa <svg class="svg-inline--fa fa-ban fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" transform="translate(-256 -256)"></path></g></g></svg></span>';
            } else if ($row['stato'] == 5) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-success" title="Chiusa">Chiusa <svg class="svg-inline--fa fa-check fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" transform="translate(-256 -256)"></path></g></g></svg></span>';
            } else if ($row['stato'] == 6) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-warning" title="Attesa risposta">Attesa risposta <i class="fa-regular fa-hand"></i></span>';
            } else if ($row['stato'] == 8) {
                $stato  = '<span class="badge badge rounded-pill d-block badge-soft-warning" title="Attesa scifo">Attesa scifo <i class="fa-regular fa-hand"></i></span>';
            }

            $dati .= '<tr style="cursor:pointer;" onclick="cambiopagina(\'amministrazione\', \'pratica\',\'?idpratica=' . $row['id'] . '\')">
                <td class="descrizione py-2 align-middle fw-bolder">' . $row['descrizione'] . '</td>
                <td class="apertura py-2 align-middle text-center">' . $ndata[2] . '/' . $ndata[1] . '/' . $ndata[0] . '</td>
                <td class="avvocato py-2 align-middle"><i>' . $row['nome'] . '</i></td>
                <td class="sentenze py-2 align-middle">' . $nsent . '</td>
                <td class="stato align-middle py-2 text-center fs-0 white-space-nowrap">' . $stato . '</td>
            </tr>';
        }
    } else {
        $dati .= '<tr>
            <td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>
        </tr>';
    }

    echo $dati;
    exit;
} else if ($azione == 'popolaavvocati') {
    $avvocati = '';
    $sql = "SELECT id, nome FROM amm_avvocati";
    $avvocati = '  <select class="form-select form-select-sm" id="searchavv" onchange="AggiornaPratiche()">';
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $avvocati .= '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
        }
    }
    $avvocati .= '</select>';
    echo $avvocati;
    exit;
} else if ($azione == 'caricapratica') {
    $idpratica = $_POST['idpratica'];

    $pratica = '';
    $avvocato = '';
    $sentenze = '';
    $allegati = '';

    $ndata = '';

    $sql1 = "SELECT * FROM amm_pratiche WHERE id=" . $idpratica;

    $sql3 = "SELECT * FROM amm_sentenze WHERE idpratica=" . $idpratica;

    $result3 = $conn->query($sql3);
    if ($result3->num_rows >= 1) {
        while ($row3 = $result3->fetch_assoc()) {
            $ndata = explode('-',  $row3['data']);
            if ($row3['stato'] == '0') {
                $stato1 = '<a class="text-muted"><i class="badge bg-warning" onclick="ChiudiSentenza(' . $row3['id']  . ')"><b>Aperta</b></i></a>';
            } else {
                $stato1 = '<div class="text-muted"><i class="badge bg-success"><b>Conclusa</b></i></div>';
            }
            $sentenze .= '<tr>';
            $sentenze .= '<td>' . $row3['id']  . '</td>';
            $sentenze .= '<td>' . $ndata[2] . '/' . $ndata[1] . '/' . $ndata[0] . '</td>';
            $sentenze .= '<td>' . $row3['sede'] . '</td>';
            $sentenze .= '<td>' . $row3['note'] . '</td>';
            $sentenze .= '<td>' .  $stato1 . '</td>';
            $sentenze .= '</tr>';
        }
    } else {
        $sentenze .= "<tr>";
        $sentenze .= "<td>NESSUN RISULTATO</td>";
        $sentenze .= "<td></td>";
        $sentenze .= "<td></td>";
        $sentenze .= "<td></td>";
        $sentenze .= "<td></td>";
        $sentenze .= "</tr>";
    }


    $result1 = $conn->query($sql1);
    if ($result1->num_rows >= 1) {
        while ($row1 = $result1->fetch_assoc()) {
            $sql2 = "SELECT * FROM amm_avvocati WHERE id=" . $row1['idavv'];
            $result2 = $conn->query($sql2);
            if ($result2->num_rows >= 1) {
                while ($row2 = $result2->fetch_assoc()) {
                    $avvocato = $row2['id'] . '|-|' . $row2['nome'] . '|-|' . $row2['studio'] . '|-|' . $row2['cellulare1'] . '|-|' . $row2['cellulare2'] . '|-|' . $row2['email'] . '|-|' . $row2['pec'] . '|-|' . $row2['indirizzo'];
                }
            }
            $pratica = $row1['id'] . '|-|' . $row1['descrizione'] . '|-|' . $row1['note'] . '|-|' . $row1['stato'] . '|-|' . $row1['dataapertura'] . '|-|' . $row1['ultimamodifica'];
        }
    }

    echo $pratica . '|--|' . $avvocato . '|--|' . $sentenze . '|--|' . $allegati;
    exit;
} else if ($azione == 'aggiornaavvcato') {
    $sql = "UPDATE amm_avvocati SET " . $_POST['campo'] . "='" . $_POST['nuovodato'] . "' WHERE id=" . $_POST['idavv'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:pratiche:aggiornaavvcato', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornapratica') {
    $sql = "UPDATE amm_pratiche SET " . $_POST['campo'] . "='" . $_POST['nuovodato'] . "', ultimamodifica=NOW() WHERE id=" . $_POST['idpratica'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:pratiche:aggiornapratica', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creassenteza') {
    $sql = "INSERT INTO `amm_sentenze`(`idpratica`, `data`, `sede`, `note`, `stato`) VALUES ('" . $_POST['idpratica'] . "', '" . $_POST['data'] . "', '" . $_POST['sede'] . "', '" . $_POST['note'] . "', 0)";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'in:pratiche:creassenteza', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'chiudisentenza') {
    $sql = "UPDATE amm_sentenze SET stato=1 WHERE id=" . $_POST['idsent'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:pratiche:chiudisentenza', $_SESSION['session_idu']);
    exit;
} else  if ($azione == 'caricaavvocato') {
    $avvocatoo = '';
    $sql2 = "SELECT * FROM amm_avvocati WHERE nome='" . $_POST['nome'] . "'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows >= 1) {
        while ($row2 = $result2->fetch_assoc()) {
            $avvocatoo = $row2['id'] . '|-|' . $row2['nome'] . '|-|' . $row2['studio'] . '|-|' . $row2['cellulare1'] . '|-|' . $row2['cellulare2'] . '|-|' . $row2['email'] . '|-|' . $row2['pec'] . '|-|' . $row2['indirizzo'];
        }
    }
    echo $avvocatoo;
    exit;
} else if ($azione == 'nuovapratica') {
    $sql = "INSERT INTO `amm_pratiche`(`idavv`, `descrizione`, `note`, `stato`, `dataapertura`, `ultimamodifica`) VALUES ('" . $_POST['idavv'] . "', '" . $_POST['descrizione'] . "', '" . $_POST['note'] . "', 0, NOW(), NOW())";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'in:pratiche:nuovapratica', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cambiastatopratica') {
    $sql = "UPDATE amm_pratiche SET stato=" . $_POST['stato'] . "  WHERE id=" . $_POST['idpratica'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:pratiche:cambiastatopratica', $_SESSION['session_idu']);
    exit;
}
