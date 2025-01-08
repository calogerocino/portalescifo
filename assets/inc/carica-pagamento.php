<?php
//error_reporting(0);
include("./database.php");
$azione = $_POST['azione'];

$anno = date("Y");
$totalemese = "";

if ($azione == 'controllo') { //SU DATA SCAD. PRENDERE SOLO MESE
    $mese = $_POST['datascad'];
    $sql = "SELECT SUM(Importo) as Totale_Mese FROM ctb_pagamento WHERE Data LIKE '" . $anno  . "-" . $mese . "-%' AND (Pagato=0 OR Pagato=4) AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalemese = $row['Totale_Mese'];
        }
    }

    switch ($mese) {
        case '01':
            if (($_POST['importo'] + $totalemese) >= massimomese('Gennaio', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '02':
            if (($_POST['importo'] + $totalemese) >= massimomese('Febbraio', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '03':
            if (($_POST['importo'] + $totalemese) >= massimomese('Marzo', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '04':
            if (($_POST['importo'] + $totalemese) >= massimomese('Aprile', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '05':
            if (($_POST['importo'] + $totalemese) >= massimomese('Maggio', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '06':
            if (($_POST['importo'] + $totalemese) >= massimomese('Giugno', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '07':
            if (($_POST['importo'] + $totalemese) >= massimomese('Luglio', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '08':
            if (($_POST['importo'] + $totalemese) >= massimomese('Agosto', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '09':
            if (($_POST['importo'] + $totalemese) >= massimomese('Settembre', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '10':
            if (($_POST['importo'] + $totalemese) >= massimomese('Ottobre', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '11':
            if (($_POST['importo'] + $totalemese) >= massimomese('Novembre', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
        case '12':
            if (($_POST['importo'] + $totalemese) >= massimomese('Dicembre', $conn)) {
                echo 'no';
            } else {
                echo 'ok';
            }
            break;
    }
} else if ($azione == "carica") {
    $sql = "INSERT INTO ctb_pagamento (Data, Importo, Banca, Tipo_Pag, N_Assegno, N_Fattura, Intestatario, Note, Allegato, Pagato) VALUES ('" . $_POST['datascadenza'] . "', '" . $_POST['importo'] .  "', " . $_POST['banca'] .  ", " . $_POST['pagamento'] .  ", '" . $_POST['nassegno'] .  "', '" . $_POST['nfattura'] .  "', " . $_POST['intestatario'] .  ", '" . $_POST['note'] .  "', '" . $_POST['allegato'] .  "', 0)";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "si";
    }

    activitylog($conn, 'in:carica-pagamento:carica', $_SESSION['session_idu']);
    exit;
} else if ($azione == "cerca") {
    $r = '';
    $sql = "SELECT * FROM ctb_pagamento WHERE id=" . $_POST['idpag'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $r = $row['Data'] . "|-|" . $row['Importo'] . "|-|" . $row['Banca'] . "|-|" . $row['Tipo_Pag'] . "|-|" . $row['N_Assegno'] . "|-|" . $row['N_Fattura'] . "|-|" . $row['Intestatario'] . "|-|" . $row['Note'] . "|-|" . $row['Allegato'] . "|-|";


        $sql1 = "SELECT * FROM ctb_acconti_fo WHERE idpag=" . $_POST['idpag'];
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $r .= '<tr>
                <td class="py-2 align-middle">' . $row1['ID'] . '</td>
                <td class="py-2 align-middle">' . $row1['Acconto'] . '</td>
                <td class="py-2 align-middle">' . $row1['Data'] . '</td>
                <td class="py-2 align-middle">' . $row1['Descrizione'] . '</td>
                <td class="py-2 align-middle white-space-nowrap text-end"><a class="dropdown-item eliminapaga" id="' . $row1['ID'] . '" href="javascript:void(0)" title="Elimina"><i class="fa-regular fa-trash-alt"></i> Elimina</a></td>
                </tr>';
            }
        }
        echo $r;
    }
} else if ($azione == "aggiorna") {
    $dataoggi = date('Y-m-d');
    $datacheck = $_POST['datascadenza'];
    $stato = '0';
    if ($datacheck < $dataoggi) {
        $stato = '4';
    } else {
        $stato = '0';
    }
    $sql = "UPDATE ctb_pagamento SET Data='" . $_POST['datascadenza'] . "', N_Assegno='" . $_POST['nassegno'] . "', N_Fattura='"  . $_POST['nfattura'] . "', Note='" . $_POST['note'] . "', Allegato='" . $_POST['allegato'] . "', Importo='" . $_POST['importo'] . "', Pagato=" . $stato . " WHERE ID=" . $_POST['idpag'];
    if (!$conn->query($sql)) {
        echo  $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'up:carica-pagamento:aggiorna', $_SESSION['session_idu']);
    exit;
} else if ($azione == "stato") {
    $sql = "UPDATE `ctb_pagamento` SET `Pagato`=" . $_POST['stato'] . " WHERE `ID`=" . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:carica-pagamento:stato', $_SESSION['session_idu']);
    exit;
} else if ($azione == "massimali") {
    echo '<table class="table responsive">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Mese</th>';
    echo '<th>Massimale</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tr>';
    echo '<td>Gennaio</td>';
    echo '<td ondblclick="cambiamass(\'Gennaio\')">' . massimomese('Gennaio', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Febbraio</td>';
    echo '<td ondblclick="cambiamass(\'Febbraio\')">' . massimomese('Febbraio', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Marzo</td>';
    echo '<td ondblclick="cambiamass(\'Marzo\')">' . massimomese('Marzo', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Aprile</td>';
    echo '<td ondblclick="cambiamass(\'Aprile\')">' . massimomese('Aprile', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Maggio</td>';
    echo '<td ondblclick="cambiamass(\'Maggio\')">' . massimomese('Maggio', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Giugno</td>';
    echo '<td ondblclick="cambiamass(\'Giugno\')">' . massimomese('Giugno', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Luglio</td>';
    echo '<td ondblclick="cambiamass(\'Luglio\')">' . massimomese('Luglio', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Agosto</td>';
    echo '<td ondblclick="cambiamass(\'Agosto\')">' . massimomese('Agosto', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Settembre</td>';
    echo '<td ondblclick="cambiamass(\'Settembre\')">' . massimomese('Settembre', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Ottobre</td>';
    echo '<td ondblclick="cambiamass(\'Ottobre\')">' . massimomese('Ottobre', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Novembre</td>';
    echo '<td ondblclick="cambiamass(\'Novembre\')">' . massimomese('Novembre', $conn) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Dicembre</td>';
    echo '<td ondblclick="cambiamass(\'Dicembre\')">' . massimomese('Dicembre', $conn) . '</td>';
    echo '</tr>';
} else if ($azione == "cambiamese") {
    $sql = "UPDATE `ctb_massimali` SET `Massimale`='" . $_POST['massimo'] . "' WHERE `Mese`='" . $_POST['mese'] . "'";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "si";
    }
    activitylog($conn, 'up:carica-pagamento:cambiamese', $_SESSION['session_idu']);
    exit;
} else if ($azione == "nassegni") {
    $banca[3] = '';

    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=19 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[0] = $row['Totale'];
        }
    }
    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=20 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[1] = $row['Totale'];
        }
    }
    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=21 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[2] = $row['Totale'];
        }
    }
    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=22 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[3] = $row['Totale'];
        }
    }
    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=49 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[4] = $row['Totale'];
        }
    }
    $sql = "SELECT COUNT(DISTINCT ID) AS Totale FROM ctb_pagamento WHERE (Pagato=0 OR Pagato=2 OR Pagato=4) AND Banca=53 AND Tipo_Pag=25";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $banca[5] = $row['Totale'];
        }
    }
    echo $banca[0] . "|-|" . $banca[1] . "|-|" . $banca[2] . "|-|" . $banca[3] . "|-|" . $banca[4] . "|-|" . $banca[5];
} else if ($azione == "daticheservono") {
    $sql = "SELECT d.dato, p.Importo, p.Data, p.Intestatario FROM ctb_pagamento p INNER JOIN ctb_dati d ON (p.Tipo_Pag=d.ID) WHERE p.ID=" . $_POST['id'] . " LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['dato'] . "|-|" . $row['Importo'] . "|-|" . $row['Data'] . "|-|" . $row['Intestatario'];
        }
    }
} else if ($azione == "modall") {
    $sql = "UPDATE `ctb_pagamento` SET `Allegato`='" . $_POST['allegato'] . "' WHERE `ID`=" . $_POST['idpag'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:carica-pagamento:modall', $_SESSION['session_idu']);
    exit;
} else if ($azione == "elimina") {
    $sql = "DELETE FROM `ctb_pagamento` WHERE `ID`=" . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'el:carica-pagamento:elimina', $_SESSION['session_idu']);
    exit;
} else if ($azione == "eliminarigapagamento") {
    $sql = "DELETE FROM `ctb_acconti_fo` WHERE `ID`=" . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'el:carica-pagamento:eliminarigapagamento', $_SESSION['session_idu']);
    exit;
}


function massimomese($mesecheck, $conn)
{
    $sql = "SELECT * FROM ctb_massimali WHERE Mese='" . $mesecheck . "' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row['Massimale'];
        }
    }
}
