<?php
//error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

$risultato = '';

if ($azione == 'aggiorna') {
    $pagato = $_POST['pagato'];
    $clausola = $_POST['clausola'];
    $clausola2  = $_POST['clausola2'];
    $fatturazione  = $_POST['fatturazione'];
    $ncheck = 0;
    $doc  = $_POST['doc'];

    $ndoc = '';

    $sql2 = "SELECT N_Doc, Data, Totale, IDcl FROM ctb_documenti  WHERE (Pagato=" . $pagato . ") " . $clausola  . " " . $clausola2 . " " . $doc . " ORDER BY N_Doc ASC";

    $result2 = $conn->query($sql2);
    if ($result2->num_rows >= 1) {
        while ($row2 = $result2->fetch_assoc()) {
            $data = explode("-",  $row2['Data']);

            if (strpos($row2['N_Doc'], 'BC') !== false) {
                $ndoc = 'Buono di Consegna';
            } else if (strpos($row2['N_Doc'], 'FA') !== false) {
                $ndoc = 'Fattura';
            }


            $sql = "SELECT ID, Cliente FROM doff_clienti WHERE ID=" . $row2['IDcl'] . $clausola2;
            $result = $conn->query($sql);
            if ($result->num_rows >= 1) {
                $row = $result->fetch_assoc();
                $cliente = $row['Cliente'];
                $risultato .= "<tr>";
                if ($fatturazione == 'si') {
                    $ncheck = ($ncheck + 1);
                    $risultato .= "<td class=\"documento py-2 align-middle\"><div class=\"row\"><div class=\"col-2\"><div><div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" value=\"" . $row2['N_Doc'] . "\" idcl=\"" . $row['ID'] . "\" id=\"fattcheck" . $ncheck . "\"></div></div></div><div class=\"col-10\"><div id=\"" . $row2['N_Doc'] . "\" class=\"ApriDocumento_dc\"><div class=\"ml-4\"><div class=\"text-dark-75 fw-bold font-size-lg mb-0\">" . $ndoc . "</div><a href=\"javascript:void(0)\" id=\"" . $row2['N_Doc'] . "\" style=\"cursor:pointer;\" class=\"small text-muted text-hover-primary ApriDocumento_dc\">" . $row2['N_Doc'] . "</a></div></div></td>";
                } else if ($fatturazione == 'no') {
                    $risultato .= "<td class=\"documento py-2 align-middle ApriDocumento_dc\" style=\"cursor:pointer;\" id=\"" . $row2['N_Doc'] . "\"><div class=\"ml-4\"><div class=\"text-dark-75 fw-bold font-size-lg mb-0\">" . $ndoc . "</div><a href=\"javascript:void(0)\" id=\"" . $row2['N_Doc'] . "\" class=\"small text-muted text-hover-primary\">" . $row2['N_Doc'] . "</a></div></td>";
                }
                //$risultato .= "<td><b>" . $ndoc . "</b></td>";
                $risultato .= "<td class=\"py-2 align-middle ragione\"><a class=\"ApriCliente_dc\" href=\"javascript:void(0)\" id=\"" . $row2['IDcl'] . "\">" . $cliente . "</a></td>";
                $risultato .= "<td class=\"py-2 align-middle text-center data ApriDocumento_dc\" style=\"cursor:pointer;\" id=\"" . $row2['N_Doc'] . "\">" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>";
                $risultato .= "<td class=\"py-2 align-middle text-end fs-0 fw-medium importo ApriDocumento_dc\" style=\"cursor:pointer;\"  id=\"" . $row2['N_Doc'] . "\">€ " . $row2['Totale'] . "</td>";
                $risultato .= "</tr>";
            }
        }
    } else {
        $risultato .= '<td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td>';
    }
    echo $risultato . '|-|' . $ncheck;
    exit;
} else if ($azione == 'ultimoid') {
    $echoid = 0;
    $sql = "SELECT N_Doc FROM ctb_documenti WHERE N_Doc LIKE '" . $_POST['tipodoc'] . "%' ORDER BY N_Doc DESC ";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id = filter_var($row['N_Doc'], FILTER_SANITIZE_NUMBER_INT);
        if ($id > $echoid) {
            $echoid = $id;
        } else if ($id < $echoid) {
            $echoid = $echoid;
        }
    }
    // $split = split('/', $id);
    echo $echoid;
    exit;
} else if ($azione == 'modpag') {
    $stampa = '';
    $sql = "SELECT ID, dato FROM ctb_dati WHERE tipo=5";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $stampa = $stampa . '<option value="' . $row['ID'] . '">' . $row['dato'] . '</option>';
    }
    echo $stampa;
    exit;
} else if ($azione == 'daticliente') {
    $dati;
    $idcl;
    $totsaldo;

    $sql = "SELECT * FROM doff_clienti WHERE ID='" . $_POST['cliente'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $idcl = $row['ID'];
        $dati = $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['Banca'] . "|-|" . $row['IBAN'] . "|-|" . $row['PIVA'] . "|-|" . $row['SDI'] . "|-|" . $row['Fido'] . "|-|" . $row['Nick'] . "|-|" . $row['Citta'] . "|-|" . $row['CodFisc'] . "|-|" . $row['PEC'];
    }



    $sql = "SELECT SUM(Totale) as Tottot FROM ctb_documenti WHERE IDcl=" . $idcl . " AND Pagato!=4";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $totsaldo =  $row['Tottot'];
    }
    $sql = "SELECT SUM(Acconto) as totacc FROM ctb_acconti_cl WHERE IDcl=" . $idcl;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $totacc = $row['totacc'];
    }
    $dati .= "|-|" . ($totsaldo - $totacc);

    echo $dati;
    exit;
} else if ($azione == 'nuovocliente') {

    $sql = "INSERT INTO `doff_clienti`(`Cliente`, `Indirizzo`, `Cellulare`, `Mail`, `Azienda`, `IndirizzoAz`, `PIVA`, `SDI`, `Fido`, `ida`) VALUES ('" . $_POST['cliente'] . "','" . $_POST['indcl'] . "','" . $_POST['cellcl'] . "','" . $_POST['mailcl'] . "','" . $_POST['ragsocaz'] . "','" . $_POST['indaz'] . "','" . $_POST['pivaz'] . "','" . $_POST['sdizienda'] . "','" . $_POST['fido'] . "',0)";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovocliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiornacliente') {
    $sql = "UPDATE `doff_clienti` SET `Cliente`='" . $_POST['clientecl'] . "',`Nick`='" . $_POST['nickcl'] . "',`Indirizzo`='" . $_POST['indcl'] . "',`Citta`='" . $_POST['cittcl'] . "',`Cellulare`='" . $_POST['cellcl'] . "',`Mail`='" . $_POST['mailcl'] . "',`Banca`='" . $_POST['banca'] . "',`IBAN`='" . $_POST['iban'] . "',`PIVA`='" . $_POST['pivaz'] . "',`SDI`='" . $_POST['sdizienda'] . "',`Fido`='" . $_POST['fido'] . "',`CodFisc`='" . $_POST['codfisc'] . "',`PEC`='" . $_POST['pec'] . "' WHERE `ID`=" . $_POST['idcliente'];
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:documenti:aggiornacliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovotipopagamento') {
    $sql = "INSERT INTO `ctb_dati`(`dato`, `tipo`) VALUES ('" . $_POST['dato'] . "',5)";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }
    exit;
} else if ($azione == 'addacconto') {
    $impacc = 0;
    $impacc =  (float)$_POST['acc'];
    $res = '';

    $sql = "INSERT INTO `ctb_acconti_cl`(`IDcl`, `Acconto`, `Data`, `Descrizione`, `Pagamento`) VALUES (" . $_POST['idcl'] . ",'" . $impacc . "','" . $_POST['dataacc'] . "','" . $_POST['comm'] . "', '" . $_POST['pagacc'] . "')";
    if (!$conn->query($sql)) {
        $res = $conn->error;
    } else {
        while ($impacc >= 1) {

            $sql1 = "SELECT ID, N_Doc, importorata, saldorata FROM ctb_ratescadenze WHERE idcl=" . $_POST['idcl'] . " AND statorata=0 ORDER BY datarata ASC LIMIT 1";
            $result1 = $conn->query($sql1);

            while ($row1 = $result1->fetch_assoc()) {

                if ((($row1['saldorata'] + $impacc) < $row1['importorata'])) {
                    $impacc = $row1['saldorata'] + $impacc;
                    $sql = "UPDATE `ctb_ratescadenze` SET saldorata='" . $impacc . "', statorata=0 WHERE ID='" . $row1['ID'] . "'";
                    if (!$conn->query($sql)) {
                        $res = $conn->error;
                    } else {
                        $res = 'si';
                    }
                    $impacc = 0;
                } else if (($row1['saldorata'] + $impacc) >= $row1['importorata']) {
                    $sql = "UPDATE `ctb_ratescadenze` SET saldorata='" . $row1['importorata'] . "', statorata=1 WHERE ID='" . $row1['ID'] . "'";
                    if (!$conn->query($sql)) {
                        $res = $conn->error;
                    } else {
                        $res = 'si';
                    }
                    $sql = "UPDATE `ctb_documenti` SET Pagato=1 WHERE Pagato !=4 AND N_Doc='" . $row1['N_Doc'] . "'";
                    if (!$conn->query($sql)) {
                        $res = $conn->error;
                    } else {
                        $res = 'si';
                    }
                    $impacc = $impacc - ($row1['importorata'] - $row1['saldorata']);
                }
            }
        }
    }
    echo $res;
    activitylog($conn, 'up:documenti:addacconto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'impostarata') {
    $nrata = "`Rata" . $_POST['rata'] . "Sald`";
    $sql = "UPDATE `ctb_ratescadenze` SET " . $nrata . "=" . $_POST['sald'] . " WHERE ID=" . $_POST['iddoc'];
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:documenti:impostarata', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'venditecliente') {
    $sql = "SELECT N_Doc, Data, Totale, Saldo FROM ctb_documenti WHERE IDcl=" . $_POST['idcl'] . " AND Pagato!=4";

    $voci = 0;
    $totale;


    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if (strpos($row['N_Doc'], 'BC') !== false) {
                $ndoc = 'Buono di Consegna';
            } else if (strpos($row['N_Doc'], 'FA') !== false) {
                $ndoc = 'Fattura';
            }

            $risultato .= "<tr>";
            $risultato .= "<td>" . $ndoc . "</td>";
            $risultato .= "<td>" . $row['Data'] . "</td>";
            $risultato .= "<td>" . $row['N_Doc'] . "</td>";
            $risultato .= "<td>€ " . $row['Totale'] . "</td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            $totale = $totale + $row['Totale'];
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN DOCUMENTO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";

        $voci = 0;
        $totale = 0;
    }
    echo '<table class="table table-hover table-borderless">';
    echo '<thead><tr><th>Documento</th><th>Data Doc.</th><th>Num. Doc.</th><th>Totale</th></tr></thead>';
    echo '<tbody>' . $risultato . '</tbody>';
    echo '<tfoot><tr><th>' . $voci . ' voci</th><th></th><th></th><th>€ ' . $totale . '</th><th></th></tr></tfoot>';
    echo '</table>';
    echo '|-|'  . $totale;
    exit;
} else if ($azione == 'acconticliente') {
    $sql = "SELECT ID, Descrizione, Data, Acconto FROM ctb_acconti_cl WHERE IDcl=" . $_POST['idcl'];

    $voci = 0;
    $totale;

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $risultato .= "<tr>";
            $risultato .= "<td>" . $row['Descrizione'] . "</td>";
            $risultato .= "<td>" . $row['Data'] . "</td>";
            $risultato .= "<td id=\"" . $row['ID'] . "\">" . $row['Acconto'] . "</td>";
            $risultato .= "<td width=\"12%\"><a href=\"#\" id=\"" . $row['ID'] . "\" class=\"d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm eliminaacc\"><i class=\"far fa-save fa-sm text-white-50\"></i> Elimina</a></td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            $totale = $totale + $row['Acconto'];
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN ACCONTO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
        $voci = 0;
        $totale = 0;
    }

    echo '<table class="table table-hover table-borderless">';
    echo '<thead><tr><th>Descrizione</th><th>Data</th><th>Acconto</th><th>Azioni</th></tr></thead>';
    echo '<tbody>' . $risultato . '</tbody>';
    echo '<tfoot><tr><th>' . $voci . ' voci</th><th></th><th id="totaleacconti">€ ' . $totale . '</th><th></th></tr></tfoot>';
    echo '</table>';
    echo '|-|'  . $totale;
    exit;
} else if ($azione == 'acconticlientet2') {
    $sql = "SELECT ac.ID, ac.Descrizione, ac.Data, ac.Acconto, da.dato FROM ctb_acconti_cl ac INNER JOIN ctb_dati da ON (da.ID=ac.Pagamento) WHERE ac.IDcl=" . $_POST['idcl'] . " ORDER BY DATA ASC";
    $sql1 = "SELECT SUM(Totale) AS totdoc FROM ctb_documenti WHERE Pagato != 4 AND IDcl=" . $_POST['idcl'];
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
    $totdoc = 0;
    $totdoc = $row1['totdoc'];
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $totdoc = ($totdoc - (float)$row['Acconto']);
            $res = explode('-', $row['Data']);
            $risultato .= "<tr>";
            $risultato .= "<td><b>" . $row['Descrizione'] . "</b></td>";
            $risultato .= "<td>" . $res[2] . '/' . $res[1] . '/' . $res[0] . "</td>";
            $risultato .= "<td><b>€ " . number_format($row['Acconto'], 2, ',', '.') . "</b></td>";
            $risultato .= "<td>€ " . number_format($totdoc, 2, ',', '.') . "</td>";
            $risultato .= "<td>" . $row['dato'] . "</td>";
            $risultato .= "</tr>";
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN ACCONTO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
    }

    echo $risultato;
    exit;
} else if ($azione == 'nuovodoc') {
    $sql = "INSERT INTO `ctb_documenti`(`N_Doc`, `AgEnt`, `IDcl`, `Data`, `IDdato`, `Totale`, `Saldo`, `Pagato`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['nfatt'] . "','" . $_POST['idcl'] . "','" . $_POST['datadoc'] . "','" . $_POST['iddato'] . "','" . $_POST['totale'] . "','" . $_POST['saldo'] . "','" . $_POST['pagato'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovodoc', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovodocn') {
    $sql = "INSERT INTO `ctb_documenti`(`N_Doc`, `IDcl`, `Data`,  `Totale`, `Pagato`, `AgEnt`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['idcl'] . "','" . $_POST['datadoc'] . "','" . $_POST['totale'] . "',2,'" . $_POST['nfatt'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovodocn', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovorelpon') {
    $sql = "INSERT INTO `ctb_relpo`(`IDdoc`, `idp`, `Nome`, `Quantita`, `Prezzo`, `IVA`, `Sconto`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['idp'] . "','" . $_POST['desc'] . "','" . $_POST['quant'] . "','" . $_POST['prez'] . "','" . $_POST['iva'] . "','" . $_POST['sconto'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovorelpo', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovaratan') {
    echo $sql = "INSERT INTO `ctb_ratescadenze`(`N_Doc`, `idcl`, `nrata`, `datarata`, `statorata`, `importorata`, `saldorata`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['idcl'] . "','" . $_POST['nrata'] . "','" . $_POST['datarata'] . "','0','" . $_POST['importorata'] . "','0')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovaratan', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovorelpo') {
    $sql = "INSERT INTO `ctb_relpo`(`IDdoc`, `Codice`, `Nome`, `Quantita`, `Prezzo`, `UM`, `IVA`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['codice'] . "','" . $_POST['desc'] . "','" . $_POST['quant'] . "','" . $_POST['prez'] . "','" . $_POST['um'] . "','" . $_POST['iva'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovorelpo', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovoratescadenze') {
    $sql = "INSERT INTO `ctb_ratescadenze`(`N_Doc`, `Rata1Data`, `Rata2Data`, `Rata3Data`, `Rata4Data`, `Rata1Imp`, `Rata2Imp`, `Rata3mp`, `Rata4Imp`, `Rata1Sald`, `Rata2Sald`, `Rata3Sald`, `Rata4Sald`) VALUES ('" . $_POST['ndoc'] . "','" . $_POST['Rata1Data'] . "','" . $_POST['Rata2Data'] . "','" . $_POST['Rata3Data'] . "','" . $_POST['Rata4Data'] . "','" . $_POST['Rata1Imp'] . "','" . $_POST['Rata2Imp'] . "','" . $_POST['Rata3Imp'] . "','" . $_POST['Rata4Imp'] . "',0,0,0,0)";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }

    activitylog($conn, 'in:documenti:nuovoratescadenze', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'infodoc') {
    $sql = "SELECT Data, Pagato, IDcl, AgEnt FROM ctb_documenti WHERE N_Doc='" . $_POST['ndoc'] . "' LIMIT 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['Data'] . "|-|" .   $row['IDcl'] . "|-|" .  $row['AgEnt'];
        //   $cliente =  $row['IDcl'];
    }
    // echo  $row['IDcl'];
    // $sql2 = "SELECT Cliente FROM clienti WHERE ID=" . $cliente . " LIMIT 1";
    // $result2 = $conn->query($sql2);
    // while ($row2 = $result2->fetch_assoc()) {
    //     $dati = $dati . "|-|" . $row2['Cliente'];
    // }

    echo $dati;
    exit;
} else if ($azione == 'cercaprodottin') {

    $dati = '';
    $totquant = (int)0;
    $totprezz = (float)0;
    $prog = 0;

    //CONTROLLO SE FATTURA O BUONO
    if (strpos($_POST['ndoc'], 'FA') !== false) {
        //E' UNA FATTURA
        $sql = "SELECT N_Doc, RifFatt FROM ctb_documenti WHERE RifFatt='" . $_POST['ndoc'] . "' AND RifFatt IS NOT null";
        $result = $conn->query($sql);

        if ($result->num_rows >= 1) {
            //E' UNA FATTURA CON RIFERIMENTI FATTURA

            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $row['N_Doc'] . "' ORDER BY '" . $row['N_Doc'] . "' DESC";
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $prog = ($prog + 1);
                    $dati .= '
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <tr>
                                <td><input type="text" class="form-control" value="' . $row1['Codice'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="[Rif. ' .  $row['N_Doc'] . '] ' . $row1['Nome']  . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['UM'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['Quantita'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['Prezzo'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['IVA'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . ($row1['Quantita'] * $row1['Prezzo']) . '" readonly></td>
                              </tr>';

                    $totquant = (int) $totquant +  (int)$row1['Quantita'];
                    $totprezz = (float)$totprezz + ((int)$row1['Quantita'] * (float)$row1['Prezzo']);
                }
            }
        } else {
            //E' UNA FATTURA CLASSICA

            $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $_POST['ndoc'] . "'";
            $result1 = $conn->query($sql1);
            while ($row1 = $result1->fetch_assoc()) {
                $prog = ($prog + 1);
                $dati .= '<tr>
                            <td><input type="text" class="form-control" value="' . $row1['Codice'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Nome'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['UM'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Quantita'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Prezzo'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['IVA'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . ($row1['Quantita'] * $row1['Prezzo']) . '" readonly></td>
                          </tr>';

                $totquant = (int) $totquant +  (int)$row1['Quantita'];
                $totprezz = (float)$totprezz + ((int)$row1['Quantita'] * (float)$row1['Prezzo']);
            }
        }
    } else {
        //E' UN BUONO
        $sql = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $_POST['ndoc'] . "'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if ($row['idp'] == 0) {
                $img = 'noimg.png';
            } else {
                $img = $row['idp'] . '.jpg';
            }
            $prog = ($prog + 1);
            $dati .= '
            <div class="row gx-card mx-0 align-items-center border-bottom border-200" id="idr' . $prog . '">
            <div class="col-5 py-3">
                <div class="d-flex align-items-center"><a href="javascript:void(0)"><img class="img-fluid rounded-1 me-3 d-none d-md-block" src="https://portalescifo.it/upload/image/p/' . ($row['ID'] == 0 ? $row['ID']  . '.jpg' : 'noimg.png') . '" alt="" width="64px" height="auto"></a>
                    <div class="flex-1">
                        <h5 class="fs-0"><a class="text-900" href="javascript:void(0)" id="cart_ds_' . $prog . '">' . $row['Nome'] . '</a></h5>
                        <div class="fs--2 fs-md--1"><a class="text-danger" href="javascript:void(0)" onclick="EliminaRiga_bn(' . $prog . ')" hidden>Rimuovi</a></div>
                    </div>
                </div>
            </div>
            <div class="col-7 py-3">
                <div class="row align-items-center">
                <div class="col-2 ps-0 mb-2 mb-md-0 text-600" contenteditable="" id="cartcont_pr_' . $prog . '" title="' . $row['Prezzo'] . '">' . $row['Prezzo'] . '</div>
                <div class="col-3 d-flex justify-content-end justify-content-md-center">
                    <div>
                        <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" id="cartcont_minus_' . $prog . '" onclick="cartCont_docRitardato(this)">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="' . $row['Quantita'] . '" aria-label="Quantita" style="width: 50px" id="cartcont_qt_' . $prog . '" onchange="cartCont_doc(this);"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" id="cartcont_plus_' . $prog . '" onclick="cartCont_docRitardato(this)(this);">+</button></div>
                    </div>
                </div>

                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' . $prog . '" " title="' . $row['IVA'] . '">' . $row['IVA'] . '</div>
                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' . $prog . '" title="' . $row['Sconto'] . '">' . $row['Sconto'] . '</div>
                    <div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="doc_tot' . $prog . '" title=""></div>
                    <div id="doc_idpr' . $prog . '" hidden>' . $row['ID'] . '</div>
                </div>
            </div>
        </div>';
        }
    }

    echo $dati . '|-|' . $prog;
    exit;
} else if ($azione == 'cercaprodotti') {

    $dati = '';
    $totquant = (int)0;
    $totprezz = (float)0;


    //CONTROLLO SE FATTURA O BUONO
    if (strpos($_POST['ndoc'], 'FA') !== false) {
        //E' UNA FATTURA
        $sql = "SELECT N_Doc, RifFatt FROM ctb_documenti WHERE RifFatt='" . $_POST['ndoc'] . "' AND RifFatt IS NOT null";
        $result = $conn->query($sql);

        if ($result->num_rows >= 1) {
            //E' UNA FATTURA CON RIFERIMENTI FATTURA

            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $row['N_Doc'] . "' ORDER BY '" . $row['N_Doc'] . "' DESC";
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $dati .= '<tr>
                                <td><input type="text" class="form-control" value="' . $row1['Codice'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="[Rif. ' .  $row['N_Doc'] . '] ' . $row1['Nome']  . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['UM'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['Quantita'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['Prezzo'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . $row1['IVA'] . '" readonly></td>
                                <td><input type="text" class="form-control" value="' . ($row1['Quantita'] * $row1['Prezzo']) . '" readonly></td>
                              </tr>';

                    $totquant = (int) $totquant +  (int)$row1['Quantita'];
                    $totprezz = (float)$totprezz + ((int)$row1['Quantita'] * (float)$row1['Prezzo']);
                }
            }
        } else {
            //E' UNA FATTURA CLASSICA

            $sql1 = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $_POST['ndoc'] . "'";
            $result1 = $conn->query($sql1);
            while ($row1 = $result1->fetch_assoc()) {
                $dati .= '<tr>
                            <td><input type="text" class="form-control" value="' . $row1['Codice'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Nome'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['UM'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Quantita'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['Prezzo'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . $row1['IVA'] . '" readonly></td>
                            <td><input type="text" class="form-control" value="' . ($row1['Quantita'] * $row1['Prezzo']) . '" readonly></td>
                          </tr>';

                $totquant = (int) $totquant +  (int)$row1['Quantita'];
                $totprezz = (float)$totprezz + ((int)$row1['Quantita'] * (float)$row1['Prezzo']);
            }
        }
    } else {
        //E' UN BUONO
        $sql = "SELECT * FROM ctb_relpo WHERE IDdoc='" . $_POST['ndoc'] . "'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $dati .= '<tr>
                        <td><input type="text" class="form-control" value="' . $row['Codice'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . $row['Nome'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . $row['UM'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . $row['Quantita'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . $row['Prezzo'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . $row['IVA'] . '" readonly></td>
                        <td><input type="text" class="form-control" value="' . ($row['Quantita'] * $row['Prezzo']) . '" readonly></td>
                      </tr>';

            $totquant = (int) $totquant +  (int)$row['Quantita'];
            $totprezz = (float)$totprezz + ((int)$row['Quantita'] * (float)$row['Prezzo']);
        }
    }

    echo $dati . '|-|' . $totquant . '|-|' . $totprezz;
    exit;
} else if ($azione == 'eliminaacc') {
    //FARE LO SCRIPT
    exit;
} else if ($azione == 'cercarate') {
    $dati = '';
    $mrata = 0;
    $sql = "SELECT *, MAX(nrata) AS mrata FROM ctb_ratescadenze WHERE N_Doc='" . $_POST['ndoc'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $mrata =  $row['mrata'];
        $dati .= '
        <tr>
            <th class="ps-0">' . $row['nrata'] . '^ Scadenza</th>
            <th class="pe-0 text-end">
                <input id="imp_' . $row['nrata'] . '" type="text" class="form-control form-control-sm mb-2" style="width: 100%" value="' . $row['importorata'] . '" placeholder="Importo scadenza">
                <input id="scad_' . $row['nrata'] . '" type="date" class="form-control form-control-sm " value="' . $row['datarata'] . '" style="width: 100%" placeholder="Data Documento">
            </th>
        <tr>';
    }
    echo $dati . '|-|' . $mrata;
    exit;
} else if ($azione == 'eliminadoc') {
    $sql = "DELETE FROM `ctb_documenti` WHERE `N_Doc`='" . $_POST['iddoc'] . "'";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        $sql = "DELETE FROM `ctb_relpo` WHERE `IDdoc`='" . $_POST['iddoc'] . "'";
        if (!$conn->query($sql)) {
            echo $conn->error;
        } else {
            echo 'si';
        }
    }
    activitylog($conn, 'in:documenti:eliminadoc', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'genfattura') {
    $totale = 0;
    $idcl;
    $nbuono = explode("|-|", $_POST['buonisel']);
    $totbuoni = count($nbuono);

    //FACCIO IL TOTALE IMPORTO BUONI
    for ($i = 1; $i <= $totbuoni; $i++) {
        $result = $conn->query("SELECT Totale FROM ctb_documenti WHERE N_Doc='" . $nbuono[$i] . "'");
        $row = $result->fetch_assoc();
        $totale = ($totale + $row['Totale']);
    }

    //MI PRENDO L'ID DEL CLIENTE
    $result = $conn->query("SELECT IDcl FROM ctb_documenti WHERE N_Doc='" . $nbuono[1] . "'");
    $row = $result->fetch_assoc();
    $idcl = $row['IDcl'];

    //PRENDO LULTIMO NUMERO FATTURA
    $sql = "SELECT N_Doc FROM ctb_documenti WHERE N_Doc LIKE 'FA%' ORDER BY N_Doc DESC ";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id = filter_var($row['N_Doc'], FILTER_SANITIZE_NUMBER_INT);
        if ($id > $echoid) {
            $echoid = $id;
        } else if ($id < $echoid) {
            $echoid = $echoid;
        }
    }

    //CREO LA FATTURA
    $sql = "INSERT INTO `ctb_documenti`(`N_Doc`, `IDcl`, `Data`,`Totale`, `Pagato`) VALUES ('FA/" . ($echoid + 1) . "','" . $idcl . "',NOW(),'" . $totale . "',2)";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        for ($i = 1; $i <= $totbuoni; $i++) {
            $sql = "UPDATE `ctb_documenti` SET `Pagato`=4,`RifFatt`='FA/" . ($echoid + 1) . "' WHERE N_Doc='" . $nbuono[$i] . "'";
            if (!$conn->query($sql)) {
                echo $conn->error;
            } else {
            }
        }
        echo "FA/" . ($echoid + 1);
    }
    activitylog($conn, 'in:documenti:genfattura', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'modificafatt') {
    $sql = "UPDATE `ctb_documenti` SET `AgEnt`='" . $_POST['nfatt'] . "' WHERE `N_Doc`='" . $_POST['ndoc'] . "'";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:documenti:modificafatt', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'scadenzecliente') {
    $exp = '';
    echo  $sql = "SELECT datarata, statorata, SUM(importorata) AS totrate, SUM(saldorata) AS saldrate FROM ctb_ratescadenze WHERE idcl=" . $_POST['idcl'] . " AND statorata=0 GROUP BY datarata ORDER BY datarata ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $tr = (floatval($row['totrate']) - floatval($row['saldrate']));
            $res = explode('-', $row['datarata']);
            $exp .= "<tr><td>" . $res[2] . '/' . $res[1] . '/' . $res[0] . "</td><td>" . $tr . "</td></tr>";
        }
    } else {
        $exp .= "<tr><td>NESSUN ACCONTO</td><td></td></tr>";
    }

    echo $exp;
    exit;
} else if ($azione == 'cercaprodotto') {
    $result = $conn->query($_POST['sql']);
    while ($row = $result->fetch_assoc()) {
        $dati .= '
        <div class="itemterm" style="cursor:pointer;" onclick="CaricaProdotto_doc(' . $row['ID'] . ')">
            <div class="itempr">
                <div class="itemimage">
                    <div class="itemimagecont" style="height: auto; width: 128px;"><img height="auto" width="128" src="https://portalescifo.it/upload/image/p/' . $row['ID'] . '.jpg"></div>
                </div>
                <div class="ipd">
                    <div>' . $row['nome'] . '</div>
                    <div class="ipdt">
                        <div class="itemterms"><span>Riferimento</span><strong><span><a class="text-info font-weight-bold" href="javascript:void(0);" onclick="ApriProdotto_ca(' . $row['ID'] . ')">' . $row['sku'] . '</a></span></strong></div>
                    </div>
                    <div class="ipdt">
                        <div class="itemterms"><span>EAN</span><strong><span>' . $row['ean'] . '</span></strong></div>
                    </div>
                </div>
            </div>
        </div>';
    }
    echo $dati;
    exit;
} else if ($azione == 'prodottolista') {
    $sql = "SELECT ID, nome, prezzo, iva FROM neg_magazzino WHERE ID=" . $_POST['idpr'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati .= '
            <div class="row gx-card mx-0 align-items-center border-bottom border-200" id="idr' . $_POST['prog'] . '">
            <div class="col-5 py-3">
                <div class="d-flex align-items-center"><a href="javascript:void(0)"><img class="img-fluid rounded-1 me-3 d-none d-md-block" src="https://portalescifo.it/upload/image/p/' . $row['ID'] . '.jpg" alt="" width="128px" height="auto"></a>
                    <div class="flex-1">
                        <h5 class="fs-0"><a class="text-900 ApriProdotto_bn" href="javascript:void(0)" id="cart_ds_' . $_POST['prog'] . '" idprn=' . $row['ID'] . '">' . $row['nome'] . '</a></h5>
                        <div class="fs--2 fs-md--1"><a class="text-danger" href="javascript:void(0)" onclick="EliminaRiga_bn(' . $_POST['prog'] . ')">Rimuovi</a></div>
                    </div>
                </div>
            </div>
            <div class="col-7 py-3">
                <div class="row align-items-center">
                <div class="col-2 ps-0 mb-2 mb-md-0 text-600" contenteditable="" id="cartcont_pr_' . $_POST['prog'] . '" title="' . $row['prezzo'] . '">' . $row['prezzo'] . '</div>
                <div class="col-3 d-flex justify-content-end justify-content-md-center">
                    <div>
                        <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" id="cartcont_minus_' . $_POST['prog'] . '" onclick="cartCont_docRitardato(this)">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="1" aria-label="Quantita" style="width: 50px" id="cartcont_qt_' . $_POST['prog'] . '" onchange="cartCont_doc(this);"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" id="cartcont_plus_' . $_POST['prog'] . '" onclick="cartCont_docRitardato(this)(this);">+</button></div>
                    </div>
                </div>

                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' . $_POST['prog'] . '" " title="' . $row['iva'] . '">' . $row['iva'] . '</div>
                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' . $_POST['prog'] . '" title="0">0</div>
                    <div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="doc_tot' . $_POST['prog'] . '" title="' . $row['prezzo'] . '">' . $row['prezzo'] . '</div>
                    <div id="doc_idpr' . $_POST['prog'] . '" hidden>' . $row['ID'] . '</div>
                </div>
            </div>
        </div>';
    }

    echo $dati;
    exit;
} else if ($azione == 'forzaimporto') {
    $sql = "UPDATE `ctb_documenti` SET `Totale`='" . $_POST['nuovoimporto'] . "' WHERE `N_Doc`='" . $_POST['ndoc'] . "'";
    if (!$conn->query($sql)) {
        echo  $conn->error;
    } else {
        $sql = "UPDATE `ctb_ratescadenze` SET `importorata`='" . $_POST['nuovoimporto'] . "' WHERE `N_Doc`='" . $_POST['ndoc'] . "'";
        if (!$conn->query($sql)) {
            echo  $conn->error;
        } else {
            echo 'si';
        }
    }
    activitylog($conn, 'up:documenti:forzaimporto', $_SESSION['session_idu']);
    exit;
}
