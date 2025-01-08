<?php
//error_reporting(0);

include("database.php");
$azione = $_POST['azione'];
$export = '';

if ($azione == 'caricanuovo') {
    $sql = "INSERT INTO `amm_dipendenti`(`Dipendente`, `Indirizzo`, `Citta`, `Cellulare`, `Mail`, `pec`, `CodFisc`, `Banca`, `Iban`, `Bic`, `Stato`) VALUES ('" . $_POST['dipe'] . "','" . $_POST['indirizzo'] . "','" . $_POST['citta'] . "','" . $_POST['cellulare'] . "','" . $_POST['mail'] . "','" . $_POST['pec'] . "','" . $_POST['codfisc'] . "','" . $_POST['banca'] . "','" . $_POST['iban'] . "','" . $_POST['bic'] . "','" . $_POST['stato'] . "')";
    if (!$conn->query($sql)) {
        echo $sql;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:schedadipendente:caricanuovo', $_SESSION['session_idu']);
    echo $export;
    exit;
} else if ($azione == 'datidipendente') {
    $sql = "SELECT * FROM `amm_dipendenti` WHERE ID=" . $_POST['iddip'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $export = $row['ID'] . "|-|" .  $row['Dipendente'] . "|-|" . $row['Indirizzo'] . "|-|" . $row['Citta'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['pec'] . "|-|" . $row['CodFisc'] . "|-|" . $row['Banca'] . "|-|" . $row['Iban'] . "|-|" . $row['Bic'] . "|-|" .  $row['Stato'];
    }
    echo $export;
    exit;
} else if ($azione == 'aggiornadipendente') {
    $sql = "UPDATE `amm_dipendenti` SET `" . $_POST['campo'] . "`='" . $_POST['valore'] . "' WHERE `ID`=" . $_POST['iddip'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:schedadipendente:aggiornadipendente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'bustedipendente') {
    $sql = "SELECT * FROM `amm_bustapaga` WHERE IDdp=" . $_POST['iddip'] . " ORDER BY Data DESC";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $p = round(round($row['Saldo'] * 100) / $row['Importo']);
            $d = explode('-', $row['Data']);
            setlocale(LC_TIME, 'it_IT');
            $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));
            $export .= '<tr>
                        <td>' . strtoupper($dd)  . '</td>
                        <td>' . $row['Importo'] . '</td>
                        <td><input id="B-' . $row['ID'] . '" type="number" min="0" max="' . ($row['Importo'] - $row['Saldo']) . '" class="form-control form-control-sm" onchange="CambiaImportoBusta(' . $row['ID'] . ', \'' . ($row['Importo'] - $row['Saldo']) . '\')" value ="0.00" ' .  ($p >= 100 ? "disabled" : "") . '></td>
                        <td>' . $row['Saldo'] . '</td>
                        <td class="align-middle"><div class="progress bg-200 me-2" style="height: 5px;"><div class="progress-bar rounded-pill" role="progressbar" style="width: ' . $p . '%" aria-valuenow="' . $p . '" aria-valuemin="0" aria-valuemax="100"></div></div></td>
                        <td class="py-2 align-middle fs-0 white-space-nowrap text-end"><button class="btn btn-falcon-default btn-sm AllegatoBusta_sd" allegatobusta="[' . $row['ID'] . '] ' . strtoupper($dd) . '" type="button"><span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-file-arrow-up"></i></span></button></td>
                        </tr>';
        }
    } else {
        $export = '<tr>
            <td class="text-center py-2 align-middle" colspan="4">Nessuna busta paga inserita</td>
        </tr>';
    }
    echo '<table class="table table-hover table-borderless">
                <thead class="bg-200 text-900" ><tr><th>Data Emissione</th><th>Importo</th><th>Acconto</th><th>Saldo</th><th>Saldo %</th><th>Allegato</th></tr></thead>
                <tbody class="table-hover">
                <tr class="bg-200 text-900">
                    <td><input id="DataEmissioneBusta_sd" class="form-control form-control-sm" type="date" placeholder="Inserisci data emissione busta paga"></td>
                    <td><input id="ImportoBusta_sd" class="form-control form-control-sm" type="text" placeholder="Inserisci importo busta paga"></td>
                    <td colspan="4" class="text-end"><button class="btn btn-falcon-default btn-sm AggiungiNuovaBusta_sd" type="button"><span class="d-none d-sm-inline-block ms-1">Aggiungi nuova busta</span></button></td>
                </tr>
                    ' . $export . '
                </tbody>
            </table>';

    exit;
} else if ($azione == 'econtodipendente') {
    $iddip = $_POST['iddip'];
    $econto = array();
    $olddare = (float)0;
    $oldavere = (float)0;
    $oldscad = (float)0;

      $sql = "SELECT adp.Data, adp.IDB, adp.Acconto, bp.Data AS DataB FROM `amm_acconti_dp` adp INNER JOIN `amm_bustapaga` bp ON (adp.IDB=bp.ID) WHERE adp.IDdp=" . $iddip;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $d = explode('-', $row['Data']);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));
        
        $d1 = explode('-', $row['DataB']);
        $dd1 = strftime('%B %Y', mktime(0, 0, 0, ($d1[1] + 1), 0, $d1[0]));
        
        array_push($econto, array($row['Data'],  'Acconto su busta ' . ucfirst($dd1), $row['Acconto'], ''));
    }

    $sql = "SELECT Data, ID, Importo FROM `amm_bustapaga` WHERE IDdp=" . $iddip;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $d = explode('-', $row['Data']);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));

        array_push($econto, array($row['Data'],  '<b>Busta</b> paga ' . ucfirst($dd), '', $row['Importo']));
    }
    asort($econto);
    foreach ($econto as $dato1 => $dato2) {
        $d = explode('-', $dato2[0]);
        setlocale(LC_TIME, 'it_IT');
        $dd = strftime('%B %Y', mktime(0, 0, 0, ($d[1] + 1), 0, $d[0]));

        $olddare = (float)$dato2[2] + (float)$olddare;
        $oldavere = (float)$dato2[3] + (float)$oldavere;

        $export .=  '<tr>
        <td><i>' . strtoupper($dd) . '</i></td>
        <td>' . $dato2[1] . '</td>
        <td>' . number_format($dato2[2], 2, ',', '.') . '</td>
        <td>' . number_format($dato2[3], 2, ',', '.') . '</td>
        <td>€ ' . number_format((((float)$oldavere - (float)$olddare)), 2, ',', '.') . '</td>
        </tr>';
    }

    echo '<table class="table table-hover table-borderless">
    <thead class="bg-200 text-900"><tr><th>Data</th><th>Descrizione</th><th>Dare</th><th>Avere</th><th>Saldo</th></tr></thead>
    <tbody class="table-hover">' . $export . '</tbody>
    <tfoot class="bg-200 text-900">
    <th colspan="2">TOTALI</th>
    <th>€ ' . number_format((float)$olddare, 2, ',', '.') . '</th>
    <th>€ ' . number_format((float)$oldavere, 2, ',', '.') . '</th>
    <th>€ ' . number_format(((float)$oldavere - (float)$olddare), 2, ',', '.') . '</th>
    </tr></tfoot>
    </table>';
    exit;
} else if ($azione == 'inseriscibusta') {
    $sql = "INSERT INTO `amm_bustapaga`(`IDdp`, `Data`, `Importo`) VALUES ('" . $_POST['iddip'] . "','" . $_POST['data'] . "','" . $_POST['importo'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:schedadipendente:inseriscibusta', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornaimportobusta') {
    $sql = "SELECT Saldo FROM `amm_bustapaga` WHERE `ID`=" . $_POST['id'];
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $sql = "UPDATE `amm_bustapaga` SET `Saldo`='" . ((float)$_POST['valore'] + (float)$row['Saldo']) . "' WHERE `ID`=" . $_POST['id'];
            if (!$conn->query($sql)) {
                echo $conn->error;
            } else {
                $sql = "INSERT INTO `amm_acconti_dp`(`IDdp`, `IDB`, `Acconto`, `Data`) VALUES ('" . $_POST['iddip'] . "', '" . $_POST['id'] . "', '" . $_POST['valore'] . "', NOW())";
                if (!$conn->query($sql)) {
                    echo $conn->error;
                } else {
                    echo 'si';
                }
            }
        }
    }
    activitylog($conn, 'up:schedadipendente:aggiornaimportobusta', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornalista') {
    $ricerca = $_POST['ricerca'];
    $sql = "SELECT * FROM amm_dipendenti " . $ricerca . " ORDER BY Dipendente ASC";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Stato'] == 0) {
                $s = "<span class=\"badge badge rounded-pill d-block badge-soft-success\" title=\"Assunto\">Assunto <svg class=\"svg-inline--fa fa-check fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"check\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            } else if ($row['Stato'] == 1) {
                $s = "<span class=\"badge badge rounded-pill d-block badge-soft-danger\" title=\"Licenziato\">Licenziato <svg class=\"svg-inline--fa fa-ban fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"ban\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0) scale(0.875, 0.875) rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            } else if ($row['Stato'] == 2) {
                $s = "<span class=\"badge badge rounded-pill d-block badge-soft-warning\" title=\"Malattia\">Malattia <i class=\"fa-regular fa-hand\"></i></span>";
            } else if ($row['Stato'] == 3) {
                $s = "<span class=\"badge badge rounded-pill d-block badge-soft-secondary\" title=\"Deceduto\">Deceduto <svg class=\"svg-inline--fa fa-ban fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"ban\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0) scale(0.875, 0.875) rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            }

            $export .= '<tr>
                    <td class="py-2 align-middle fs-0 white-space-nowrap"><button class="btn btn-falcon-default me-1 mb-1" type="button" onclick="window.open(currentURL + \'assets/pdf/e-conto-dip.php?create_pdf=1&iddip=' . $row['ID'] . '\', \'PDF Documento\', \'width=800, height=800, status, scrollbars=1, location\');"><span class="fa-regular fa-file-invoice me-1" data-fa-transform="shrink-3"></span></button></td>
                    <td iddip="' . $row['ID'] . '" class="dipendente py-2 align-middle ApriDipendente_ld" style="cursor:pointer;"><span class="fw-bolder">' . $row['Dipendente'] .  '</span></td>
                    <td class="stato py-2 align-middle text-center fs-0 white-space-nowrap">' . $s . '</td>
                    </tr>';
        }
    } else {
        $export .= '<tr>
            <td class="text-center py-2 align-middle" colspan="1">Nessun dato disponibile</td>
        </tr>';
    }

    echo $export;
    exit;
}

function sortByDate($a, $b)
{
    if (strtotime($b['createdDate']) < strtotime($a['createdDate'])) {
        return $a['createdDate'];
    } else {
        return $b['createdDate'];
    }
}
