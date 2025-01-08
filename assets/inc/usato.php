<?php
//error_reporting(0);
include("./database.php");
$azione = $_POST['azione'];

$dati = "";

if ($azione == 'aggiorna') {
    $clausola = $_POST['clausola'];
    $classe = '';
    $azioni = '';
    $sql = "SELECT ID, Prodotto, CONCAT(Marchio, ' ', Modello) AS Macchina, Valore, Stato, Facebook FROM doff_usato WHERE Stato='$clausola'";


    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Stato'] == 'IN VENDITA') {
                $classe = 'class="text-success text-uppercase"';
                $azioni = '<a class="cambiastato" stato="VENDUTO" idus="' . $row['ID'] . '" href="javascript:void(0)" title="VENDI"><i class="fa-duotone fa-money-bill"></i></a>  <a class="cambiastato" stato="NOLEGGIATO" idus="' . $row['ID'] . '" href="javascript:void(0)" title="Noleggia"><i class="fa-duotone fa-toilet"></i></a>';
            } else if ($row['Stato'] == 'NOLEGGIATO') {
                $classe = 'class="text-warning text-uppercase"';
                $azioni = '<a class="cambiastato" stato="VENDUTO" idus="' . $row['ID'] . '" href="javascript:void(0)" title="VENDI"><i class="fas fa-money-bill"></i></a>  <a class="cambiastato" stato="IN VENDITA" idus="' . $row['ID'] . '" href="javascript:void(0)" title="Da vendere"><i class="fas fa-exchange-alt"></i></a>';
            } else if ($row['Stato'] == 'VENDUTO') {
                $classe = 'class="text-danger text-uppercase"';
            }
            if ($row['Facebook'] == 'SI') {
                $facebook = '<div class="text-danger font-weight-bolder"><b>SI</b></div>';
            } else {
                $facebook = 'NO';
            }

            $dati .= '<tr>';
            $dati .= '<td><a idus="' . $row['ID'] . '" class="text-info font-weight-bold aprius" href="javascript:void(0);" title="Apri macchina">' . $row['ID'] . '</a></td>';
            $dati .= '<td>' . $row['Prodotto'] . '</td>';
            $dati .= '<td>' . $row['Macchina'] . '</td>';
            $dati .= '<td><b>' . $row['Valore'] . '</b></td>';
            $dati .= '<td><div ' . $classe . '><b>' . $row['Stato'] . '</b></div></td>';
            $dati .= '<td>' . $facebook . '</td>';
            $dati .= "<td>$azioni</td>";
            $dati .= '</tr>';
        }
    } else {
        $dati .= "<tr>";
        $dati .= "<td>NESSUN RISULTATO</td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "<td></td>";
        $dati .= "</tr>";
    }
    echo '<table class="table table-hover table-borderless paginated">
    <thead><tr><th>ID</th><th>Prodotto</th><th>Macchina</th><th>Valore</th><th>Stato</th><th>Facebook</th><th>Azioni</th></tr></thead>
   <tbody class="table-hover">' . $dati . '</tbody></table>';
    exit;
} else if ($azione == 'schedadaid') {
    $sql = "SELECT Prodotto, Modello, Marchio, Seriale, Totale FROM doff_riparazioni WHERE ID=" . $_POST['idr'];

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Prodotto'] . "|-|" . $row['Modello'] . "|-|" . $row['Marchio'] . "|-|" . $row['Seriale'] . "|-|" . number_format($row['Totale'], 2);
        }
    }
} else if ($azione == 'nuovous') {
    $sql = "INSERT INTO `doff_usato`(`Marchio`, `Prodotto`, `Modello`, `Seriale`, `Note`, `Valore`, `Permuta`, `Riparazione`, `idr`, `Data_Permuta`, `Stato`) VALUES ('" . $_POST['marchio'] . "','" . $_POST['prodotto'] . "','" . $_POST['modello'] . "','" . $_POST['seriale'] . "','" . $_POST['note'] . "','" . $_POST['valore'] . "','" . $_POST['permuta'] . "','" . $_POST['riparazione'] . "','" . $_POST['idr'] . "',NOW(),'IN VENDITA')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:usato:nuovous', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercausato') {
    $sql = "SELECT * FROM doff_usato WHERE ID=" . $_POST['idus'];
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            echo $row['ID'] . "|-|" . $row['Marchio'] . "|-|" . $row['Prodotto'] . "|-|" . $row['Modello'] . "|-|" . $row['Seriale'] . "|-|" . $row['Note'] . "|-|" . $row['Valore'] . "|-|" . $row['Permuta'] . "|-|" . $row['Riparazione'] . "|-|" . $row['Data_Permuta'] . "|-|" . $row['Stato'];
        }
    }
} else if ($azione == 'modificaus') {
    $sql = "UPDATE `doff_usato` SET `Marchio`='" . $_POST['marchio'] . "',`Prodotto`='" . $_POST['prodotto'] . "',`Modello`='" . $_POST['modello'] . "',`Seriale`='" . $_POST['seriale'] . "',`Note`='" . $_POST['note'] . "',`Valore`='" . $_POST['valore'] . "',`Permuta`='" . $_POST['permuta'] . "',`Riparazione`='" . $_POST['riparazione'] . "' WHERE `ID`=" . $_POST['idus'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:usato:modificaus', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cambiastato') {
    $sql = "UPDATE `doff_usato` SET `Stato`='" . $_POST['stato'] . "' WHERE `ID`=" . $_POST['idusato'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:usato:cambiastato', $_SESSION['session_idu']);
    exit;
}
