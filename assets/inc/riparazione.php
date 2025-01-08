<?php
error_reporting(0);
session_start();
include("database.php");
$azione = $_POST['azione'];
$risultato = '';
$totale = (int)0;

if ($azione == 'aggiorna') {
    $clausola = $_POST['clausola'];
    $totale = 0;
    $classe = '';
    $totaletotale = 0;

    $sql = "SELECT r.ID, c.Cliente, c.Cellulare, CONCAT(r.Prodotto, ' ', r.Marchio, ' ', r.Modello) AS Macchina, r.Intervento, r.DataIngresso, r.Stato, r.Totale FROM doff_riparazioni r LEFT JOIN doff_clienti c ON (r.idcl=c.id) " . $clausola . " ORDER BY r.ID ASC";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $totale =  number_format($row['Totale'], 2);

            $sql1 = "SELECT Acconto FROM doff_acconti WHERE nscheda=" . $row['ID'];
            $result1 = $conn->query($sql1);
            if ($result1->num_rows >= 1) {
                while ($row1 = $result1->fetch_assoc()) {
                    $totale =  (float)$totale - (float)$row1['Acconto'];
                }
            }
            if ($row['Stato'] == 'Lavorazione') {
                $classe = "class=\"badge bg-info\"";
            } else if ($row['Stato'] == 'Completato') {
                $classe = "class=\"badge bg-warning\"";
            } else if ($row['Stato'] == 'Pagato' || $row['Stato'] == 'Usato') {
                $classe = "class=\"badge bg-success\"";
            } else if ($row['Stato'] == 'Sospeso' || $row['Stato'] == 'Annullato') {
                $classe = "class=\"badge bg-danger\"";
            }

            $risultato .= '<tr>';
            $risultato .= '<td width="3%"><a idr="' . $row['ID'] . '" class="text-info font-weight-bold apririp" href="javascript:void(0);" title="Apri Riparazione">' . $row['ID'] . '</a></td>';
            $risultato .= '<td width="33%"><div class="ml-4"><div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' . $row['Cliente'] . '</div><a class="small text-muted font-weight-bold text-hover-primary">Cell. ' . $row['Cellulare'] . '</a></div></td>';
            // $risultato .= '<td width="7%">' . $row['Cellulare'] . '</td>';
            // $risultato .= '<td width="5%">' . $row['Prodotto'] . '</td>';
            $risultato .= '<td width="20%">' . $row['Macchina'] . '</td>';
            $risultato .= '<td width="12%"><b>' . $row['Intervento'] . '</b></td>';
            $risultato .= '<td width="11%">' . $row['DataIngresso'] . '</td>';
            $risultato .= '<td width="7%"><span ' . $classe . '><b><i>' . $row['Stato'] . '</i></b></span></td>';
            $risultato .= '<td width="7%"><i>' . number_format($totale, 2) . '</i></td>';
            $risultato .= '<td width="10%"><gest id="' . $row['ID'] . '" cell="' . $row['Cellulare'] . '" stato="' . $row['Stato'] . '" int="' . $row['Intervento'] . '"><a class="cambia" idr="' . $row['ID'] . '" href="javascript:void(0)" title="Cambia Stato"><i class="fa-duotone fa-dice-d6"></i></a>   <a class="chiamacl" cellulare="' . $row['Cellulare'] . '" href="javascript:void(0)" title="Chiama Cliente"><i class="fa-duotone fa-phone-alt"></i></a>   <a class="stampa_rip" idr="' . $row['ID'] . '" href="javascript:void(0)" title="Stampa"><i class="fa-duotone fa-print"></i></a>   <a class="copiarip" idr="' . $row['ID'] . '" href="javascript:void(0)" title="Copia"><i class="far fa-copy"></i></a></gest></td>';
            $risultato .= '</tr>';

            $totale = $totale + 1;
            $totaletotale =  ($totaletotale + $row['Totale']);
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td width=\"100%\">NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        // $risultato .= "<td></td>";
        // $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";

        $totale = 0;
    }
    echo '<table class="table table-hover table-borderless table-striped">';
    echo '<thead><tr><th width="3%">Scheda</th><th width="33%">Cliente</th><th width="20%">Macchina</th><th width="12%">Intervento</th><th width="11%">Data Ingresso</th><th width="7%">Stato</th><th width="7%">Totale</th><th width="10%">Azioni</th></tr></thead>';
    echo '<tbody class="table-hover">' . $risultato . '</tbody>';
    echo '<tfoot><tr><th width="3%">' . $totale . '</th><th width="33%"></th></th><th width="20%"></th><th width="12%"></th><th width="11%"></th><th width="7%"></th><th width="7%">' . '€ ' . number_format($totaletotale, 2)  . '</th><th width="10%"></th></tr></tfoot>';
    echo '</table>';
    exit;
} else if ($azione == 'loadscheda') {

    exit;
} else if ($azione == 'daticliente') {
    $dati;

    $sql = "SELECT * FROM doff_clienti WHERE ID='" . $_POST['cliente'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['CodFisc'] . "|-|" . $row['PEC'] . "|-|" . $row['PIVA'] . "|-|" . $row['SDI'] . "|-|" . $row['Citta'] . "|-|" . $row['Nick'];
    }

    echo $dati;
    exit;
} else if ($azione == 'aggiornacliente') {
    $sql = "UPDATE `doff_clienti` SET `Cliente`='" . $_POST['clientecl'] . "', `Nick`='" . $_POST['nickcl'] . "', `Citta`='" . $_POST['cittacl'] . "', `Indirizzo`='" . $_POST['indcl'] . "', `Cellulare`='" . $_POST['cellcl'] . "', `Mail`='" . $_POST['mailcl'] . "', `CodFisc`='" . $_POST['codfisc'] . "', `PEC`='" . $_POST['pec'] . "', `PIVA`='" . $_POST['pivaz'] . "', `SDI`='" . $_POST['sdizienda'] . "' WHERE `ID`=" . $_POST['idcliente'];
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:aggiornacliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'creanuovocliente') {
    $sql = "INSERT INTO `doff_clienti`(`Cliente`, `Nick`, `Citta`, `Indirizzo`, `Cellulare`, `Mail`, `CodFisc`, `PEC`, `PIVA`, `SDI`, `Fido`) VALUES ('" . $_POST['clientecl'] . "','" . $_POST['nickcl'] . "','" . $_POST['cittacl'] . "','" . $_POST['indcl'] . "','" . $_POST['cellcl'] . "','" . $_POST['mailcl'] . "','" . $_POST['codfisc'] . "','" . $_POST['pec'] . "','" . $_POST['pivaz'] . "','" . $_POST['sdizienda'] . "',0)";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        $sql = "SELECT ID FROM doff_clienti WHERE Cliente='" . $_POST['clientecl'] . "' AND Nick='" . $_POST['nickcl'] . "' AND Citta='" . $_POST['cittacl'] . "' AND Cellulare='" . $_POST['cellcl'] . "'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo $row['ID'];
        }
    }
    activitylog($conn, 'in:riparazione:creanuovocliente', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercaprodotto') {
    $dati = '';

    $sql = "SELECT ID, sku, nome, um, prezzo FROM neg_magazzino WHERE sku='" . $_POST['codice'] . "' OR (fornitori LIKE '%" . $_POST['codice'] . ";%' OR ean='" . $_POST['codice'] . "') ORDER BY sku ASC LIMIT 1"; // OR EAN='" . $_POST['codice'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['sku'] . "|-|" .  $row['nome'] . "|-|" .  $row['um'] . "|-|" . $row['prezzo'] . "|-|" . $row['ID'];
    }

    echo $dati;
    exit;
} else if ($azione == 'ultimoid') {
    $echoid = 0;
    $sql = "SELECT ID FROM doff_riparazioni ORDER BY ID DESC ";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id = filter_var($row['ID'], FILTER_SANITIZE_NUMBER_INT);
        if ($id > $echoid) {
            $echoid = $id;
        } else if ($id < $echoid) {
            $echoid = $echoid;
        }
    }
    echo $echoid;
    exit;
} else if ($azione == 'creascheda') {
    $sql = "INSERT INTO doff_riparazioni (AccessoriNote, DataIngresso, Intervento, MotivoIntervento, IDcl, Seriale, Marchio, Prodotto, Modello, Totale, Stato) VALUES ('" . $_POST['accessorinote'] . "', NOW(), '" . $_POST['intervento'] . "', '" . $_POST['motivointervento'] . "', '" . $_POST['idcl'] . "', '" . $_POST['seriale'] . "', '" . $_POST['marchio'] . "', '" . $_POST['prodotto'] . "', '" . $_POST['modello'] . "', '" . $_POST['totale'] . "', 'Lavorazione')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:riparazione:creascheda', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovorelpo') {
    if ($_POST['idp'] != '') {
        $sql = "INSERT INTO doff_vendite (UM, Quantita, Prezzo, Sconto, idpr, idrip) VALUES ('" . $_POST['um'] . "', '" . $_POST['quantita'] . "', '" . $_POST['prezzo'] . "', '" . $_POST['sconto'] . "', '" . $_POST['idp'] . "', '" . $_POST['ido'] . "')";
        if (!$conn->query($sql)) {
            echo $conn->error;
        } else {
            $sql = "SELECT nome, disponibilita FROM neg_magazzino WHERE ID=" . $_POST['idp'];
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $quantita = (int)$row['quantita'] - (int)$_POST['quantita'];
                $nome = $row['Nome'];
            }

            if ($quantita < 0) {
                echo 'La quantita da scaricare non è sufficente per il prodotto: ' . $nome;

                $sql = "UPDATE neg_magazzino SET disponibilita=0 WHERE ID=" . $_POST['idp'];
                if (!$conn->query($sql)) {
                    echo $conn->error;
                } else {
                    echo 'si';
                }
            } else {
                $sql = "UPDATE neg_magazzino SET disponibilita=" . $quantita . " WHERE ID=" . $_POST['idp'];
                if (!$conn->query($sql)) {
                    echo $conn->error;
                } else {
                    echo 'si';
                }
            }
        }
    }
    activitylog($conn, 'in:riparazione:nuovorelpo', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'caricascheda') {
    $sql = "SELECT Marchio, Prodotto, Modello, Seriale, AccessoriNote, DataIngresso, Intervento, MotivoIntervento, DataUscita, Tecnico, NoteIntervento, Totale, IDcl FROM doff_riparazioni WHERE id=" . $_POST['idrip'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['Marchio'] . "|-|" .  $row['Prodotto'] . "|-|" .  $row['Modello'] . "|-|" . $row['Seriale'] . "|-|" . $row['AccessoriNote'] . "|-|" . $row['DataIngresso'] . "|-|" . $row['Intervento'] . "|-|" . $row['MotivoIntervento'] . "|-|" . $row['DataUscita'] . "|-|" . $row['Tecnico'] . "|-|" . $row['NoteIntervento'] . "|-|" . $row['IDcl'];
    }

    $sql = "SELECT * FROM doff_garanzia WHERE idrip=" . $_POST['idrip'];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati .= "|-|" . $row['Anomalia'] . "|-|" .  $row['Prodotti'] . "|-|" .  $row['Note'] . "|-|" . $row['Stato'];
    }

    echo $dati;

    exit;
} else if ($azione == 'daticliente2') {
    $dati;

    $sql = "SELECT * FROM doff_clienti WHERE ID='" . $_POST['idcl'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['CodFisc'] . "|-|" . $row['PEC'] . "|-|" . $row['PIVA'] . "|-|" . $row['SDI'] . "|-|" . $row['Citta'] . "|-|" . $row['Nick'];
    }

    echo $dati;
    exit;
} else if ($azione == 'moddettrip') {
    $sql = "UPDATE doff_riparazioni SET Seriale='" . $_POST['seriale'] . "', Marchio='" . $_POST['marchio'] . "', Prodotto='" . $_POST['prodotto'] . "', Modello='" . $_POST['modello'] . "' WHERE ID=" . $_POST['idrip'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:riparazione:moddettrip', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'moddettingr') {
    $sql = "UPDATE doff_riparazioni SET MotivoIntervento='" . $_POST['motint'] . "', `Intervento`='" . $_POST['intervento'] . "' WHERE ID=" . $_POST['idrip'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:riparazione:moddettingr', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'sadatauscita') {
    $sql = "UPDATE doff_riparazioni SET DataUscita=NOW() WHERE ID=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:sadatauscita', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'sanoteint') {
    $sql = "UPDATE doff_riparazioni SET NoteIntervento='" . $_POST['noteint'] .  "' WHERE ID=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:sanoteint', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercaprodotti') {

    $i = 0;
    $dati = '';
    $totquant = (int)0;
    $totprezz = (float)0;

    $sql = "SELECT p.ID, p.sku, p.nome, v.um, v.disponibilita, v.prezzo, v.Sconto FROM doff_vendite v INNER JOIN neg_magazzino p ON v.idpr = p.ID WHERE v.idrip='" . $_POST['nscheda'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $i = (int)$i + 1;
        $dati .= '<tr id="' . $i . '">
       <td><input id="cod' . $i . '" type="text" class="form-control" value="' . $row['sku'] . '" readonly></td>
       <td><input id="desc' . $i . '" type="text" class="form-control" value="' . $row['nome'] . '" readonly></td>
       <td><input id="um' . $i . '" type="text" class="form-control" value="' . $row['um'] . '" readonly></td>
       <td><input id="quant' . $i . '" type="text" class="form-control" value="' . $row['disponibilita'] . '" readonly></td>
       <td><input id="prez' . $i . '" type="text" class="form-control" value="' . $row['prezzo'] . '" readonly></td>
       <td><input id="sco' . $i . '" type="text" class="form-control" value="' . $row['Sconto'] . '" readonly></td>
       <td><input id="tot' . $i . '" type="text" class="form-control" value="' . ((int)$row['disponibilita'] * (float)$row['prezzo']) . '" readonly></td>
       <td><a id="' . $i . '" href="javascript:void(0)" title="Elimina Voce" onclick="eliminaprod_rip(' . $i . ', 2)"><i class="fa-duotone fa-minus-circle"></i></a></td>
       <td><input id="idpr' . $i . '" type="text" class="form-control" value="' . $row['ID'] . '" hidden></td>
       </tr>';

        $totquant = (int) $totquant +  (int)$row['disponibilita'];
        $totprezz = (float)$totprezz + ((int)$row['disponibilita'] * (float)$row['prezzo']);
    }


    echo $dati . '|-|' . $totquant . '|-|' . $totprezz . '|-|' . $i;
    exit;
} else if ($azione == 'eliminaprod') {
    $sql = "DELETE FROM `doff_vendite` WHERE idpr=" . $_POST['idpr'] . " AND idrip='" . $_POST['nscheda']   . "'";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        $sql = "SELECT disponibilita FROM neg_magazzino WHERE ID=" . $_POST['idpr'];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $quantita = (int)$row['disponibilita'] + (int)$_POST['quantita'];
        }

        $sql = "UPDATE neg_magazzino SET disponibilita=" . $quantita . " WHERE ID=" . $_POST['idpr'];
        if (!$conn->query($sql)) {
            echo $conn->error;
        } else {
            echo 'si';
        }
    }
    exit;
} else if ($azione == 'inviagar') {
    $sql = "INSERT INTO doff_garanzia (idrip, Anomalia, Prodotti, Stato) VALUES (" . $_POST['nscheda']   . ",'"  . $_POST['anommacc']   .  "','" . $_POST['prodrich']   . "','IN ATTESA')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:riparazione:inviagar', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'upgaranzia') {
    $sql = "UPDATE doff_garanzia SET Stato='" . $_POST['stato'] . "', Note='" . $_POST['note'] . "' WHERE idrip=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:riparazione:upgaranzia', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'salvatotale') {
    $sql = "UPDATE doff_riparazioni SET Totale='" . $_POST['totale'] .  "' WHERE ID=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:salvatotale', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'upstato') {
    $sql = "UPDATE doff_riparazioni SET Stato='" . $_POST['stato'] .  "' WHERE ID=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:upstato', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'addacconto') {
    $sql = "INSERT INTO `doff_acconti`(nscheda, Acconto, Data, Descrizione) VALUES ('" . $_POST['nscheda'] . "','" . $_POST['acc'] . "','" . $_POST['dataacc'] . "','" . $_POST['comm'] . "')";
    if (!$conn->query($sql)) {
        echo 'no';
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:riparazione:addacconto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'cercaacconti') {
    $risultato = '';
    $totaleacc = 0;
    $voci = 0;
    $totalescheda = 0;

    $sql1 = "SELECT Totale FROM doff_riparazioni WHERE ID='" . $_POST['nscheda'] . "'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows >= 1) {
        while ($row1 = $result1->fetch_assoc()) {
            $totalescheda = $row1['Totale'];
        }
    }

    $sql = "SELECT * FROM doff_acconti WHERE nscheda='" . $_POST['nscheda'] . "'";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $risultato .= "<tr>";
            $risultato .= "<td>" . $row['Descrizione'] . "</td>";
            $risultato .= "<td>" . $row['Data'] . "</td>";
            $risultato .= "<td>" . $row['Acconto'] . "</td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            $totale = $totale + $row['Acconto'];
        }
    } else {
        $risultato = "";
        $voci = 0;
        $totale = 0;
    }

    if ($voci >= 1) {
        $totale = (float)$totalescheda - (float)$totale;
        echo '<table class="table">';
        echo '<thead><tr><th>Descrizione</th><th>Data</th><th>Acconto</th></tr></thead>';
        echo '<tbody class="table-hover">' . $risultato . '</tbody>';
        echo '<tfoot><tr><th>' . $voci . ' voci</th><th>PAGAMENTO TOTALE</th><th id="totaleacconti">€ ' . $totale . '</th></tr></tfoot>';
        echo '</table>';
    }
    exit;
} else if ($azione == 'cercaaccontibanco') {
    $totale = 0;

    $sql = "SELECT * FROM doff_acconti WHERE nscheda='" . $_POST['nscheda'] . "'";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $totale = $totale + $row['Acconto'];
        }
    } else {
        $totale = 0;
    }

    echo $totale;
    exit;
} else if ($azione == 'daticopiarip') {
    $dati;
    $idcl = '';

    $sql = "SELECT * FROM doff_riparazioni WHERE ID='" . $_POST['idrip'] . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati = $row['Marchio'] . "|-|" .  $row['Prodotto'] . "|-|" .  $row['Modello'] . "|-|" . $row['Seriale'];
        $idcl = $row['IDcl'];
    }

    $sql = "SELECT * FROM doff_clienti WHERE ID=" . $idcl;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dati .= "|-|" . $row['ID'] . "|-|" .  $row['Cliente'] . "|-|" .  $row['Indirizzo'] . "|-|" . $row['Cellulare'] . "|-|" . $row['Mail'] . "|-|" . $row['CodFisc'] . "|-|" . $row['PEC'] . "|-|" . $row['PIVA'] . "|-|" . $row['SDI'] . "|-|" . $row['Citta'] . "|-|" . $row['Nick'];
    }

    echo $dati;
    exit;
} else if ($azione == 'venditebanco') {
    $risultato = " <thead><tr><th>Pagamento</th><th>Totale</th><th>Operatore</th><th>Saldato</th><th>Data</th></tr></thead><tbody>";
    $voci = 0;
    $totale = 0;

    $sql = "SELECT ID, Pagamento, Totale, Operatore, Saldato, Data FROM doff_vbanco WHERE Data LIKE '" . $_POST['data'] . "%'";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Saldato'] == 'SI') {
                $classe = "class=\"badge bg-success\"";
            } else {
                $classe = "class=\"badge bg-warning\"";
            }

            $risultato .= "<tr style=\"cursor: pointer;\" class=\"rp_aprivend\" idv=\""  . $row['ID'] .  "\">";
            $risultato .= "<td>" . $row['Pagamento'] . "</td>";
            $risultato .= "<td>€ " . $row['Totale'] . "</td>";
            $risultato .= "<td>" . $row['Operatore'] . "</td>";
            $risultato .= "<td><span " . $classe . ">" . $row['Saldato'] . "</span></td>";
            $risultato .= "<td><b>" . $row['Data'] . "</b></td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;
            if ($row['Saldato'] == 'SI') {
                $totale = ($totale + floatval($row['Totale']));
            }
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
        $voci = 0;
        $totale = 0;
    }
    echo  $risultato . '</tbody><tfoot><tr><th>' . $voci . ' voci</th><th>€ ' . number_format($totale, 2) . '</th><th></th><th></th><th></th></tr><tfoot>';
    //<tfoot><tr><th>' . $voci . ' voci</th><th></th><th>€ ' . $totale . '</th></tr></tfoot>';
    exit;
} else if ($azione == 'prodottivendite') {
    $risultato = " <thead><tr><th>Codice</th><th>Nome</th><th>UM</th><th>Quantita</th><th>Prezzo</th></tr></thead><tbody>";
    $voci = 0;
    $totale = 0;

    $sql = "SELECT p.ID, p.sku, p.nome, vendite.UM, vendite.Quantita, vendite.Prezzo FROM neg_magazzino p INNER JOIN doff_vendite ON vendite.idpr = p.ID WHERE vendite.idba=" . $_POST['idv'];

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $risultato .= "<tr style=\"cursor: pointer;\" onclick=\"co_apriprod("  . $row['ID'] .  ">)\" ";
            $risultato .= "<td>" . $row['sku'] . "</td>";
            $risultato .= "<td>" . $row['Nome'] . "</td>";
            $risultato .= "<td>" . $row['UM'] . "</td>";
            $risultato .= "<td>" . $row['Quantita'] . "</td>";
            $risultato .= "<td><b>" . $row['Prezzo'] . "</b></td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;

            $totale = $totale + $row['Prezzo'];
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td>NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
        $voci = 0;
        $totale = 0;
    }
    echo  $risultato . '</tbody><tfoot><tr><th>' . $voci . ' voci</th><th></th><th></th><th></th><th>€ ' . number_format($totale, 2) . '</th></tr><tfoot>';
    //<tfoot><tr><th>' . $voci . ' voci</th><th></th><th>€ ' . $totale . '</th></tr></tfoot>';
    exit;
} else if ($azione == 'listagaranzie') {
    $clausola = $_POST['clausola'];

    $sql = "SELECT g.idrip, g.Anomalia, g.Stato, c.Cliente, c.Cellulare FROM doff_garanzia g INNER JOIN doff_riparazioni r ON (g.idrip=r.ID) INNER JOIN doff_clienti c ON (c.ID=r.IDcl)" . $clausola;

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $risultato .= '<tr>';
            $risultato .= '<td width="3%"><a idr="' . $row['idrip'] . '" class="text-info font-weight-bold apririp" href="javascript:void(0);" title="Apri Riparazione">' . $row['idrip'] . '</a></td>';
            $risultato .= '<td><div class="ml-4"><div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' . $row['Cliente'] . '</div><a class="small text-muted font-weight-bold text-hover-primary">Cell. ' . $row['Cellulare'] . '</a></div></td>';
            $risultato .= '<td>' . anomaliatesto($row['Anomalia'])  . '</td>';
            $risultato .= '<td><a class="font-weight-bold badge bg-' . colorestato($row['Stato']) . '" href="javascript:void(0)">' . $row['Stato'] . '</a></td>';
            $risultato .= '</tr>';
        }
    } else {
        $risultato .= "<tr>";
        $risultato .= "<td width=\"100%\">NESSUN RISULTATO</td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "<td></td>";
        $risultato .= "</tr>";
    }

    echo $risultato;
    exit;
} else if ($azione == 'upnotegar') {
    $sql = "UPDATE `doff_garanzia` SET `Note`='" . $_POST['notegar'] . "' WHERE `idrip`=" . $_POST['nscheda'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:riparazione:upnotegar', $_SESSION['session_idu']);
    exit;
}



function colorestato($stato)
{
    switch ($stato) {
        case 'IN ATTESA':
            return 'warning';
            break;
        case 'ACCETTATO':
            return 'successs';
            break;
        case 'RIFIUTATO':
            return 'danger';
            break;
    }
}

function anomaliatesto($anomalia)
{
    if (strlen($anomalia) < 80) {
        return $anomalia;
    } else {
        return substr($anomalia, 0, 80) . ' ...';
    }
}
