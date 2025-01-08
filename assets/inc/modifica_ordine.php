<?php
include("database.php");
$tipomodifica = $_POST['modifica'];


if ($tipomodifica == "cliente") {
    $sql = "UPDATE donl_clienti SET Cliente='" . $_POST['cliente'] . "', Via='" . $_POST['via'] . "', CAP='" . $_POST['cap'] . "', Citta='" . $_POST['citta'] . "', Telefono='" . $_POST['telefono'] . "', Cellulare='" . $_POST['cellulare'] . "', Cellulare2='" . $_POST['cellulare2'] . "', EMail='" . $_POST['email'] . "' WHERE id=" . $_POST['idcliente'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Cliente modificato con successo";
    }

    activitylog($conn, 'up:modifica_ordine:cliente', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "nuovotk") {
    $sql = "INSERT INTO donl_ticket (NTicket, Stato, Tipologia, Creatoda, Problema, Aperto, Riserva, DataCreazione, ido) VALUES ('" . $_POST['nticket'] . "', '" . $_POST['stato'] . "', '" . $_POST['tipologia'] . "', '" . $_POST['creatoda'] . "', '" . $_POST['problema'] . "', 'SI', '" . $_POST['riserva'] . "' , NOW(), '" . $_POST['ido'] . "')";

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Ticket aperto con successo";
    }

    activitylog($conn, 'in:modifica_ordine:nuovotk', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "bloccasped") {
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), 7)");
    $sql = "UPDATE donl_ordini SET Stato='Sospeso' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Stato impostato con successo";
    }
    activitylog($conn, 'up:modifica_ordine:bloccasped', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "rimborsped") {
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), 10)");
    $sql = "UPDATE donl_ordini SET Stato='Rimborsato' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Stato impostato con successo";
    }
    activitylog($conn, 'up:modifica_ordine:rimborsped', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "darimb") {
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), 9)");
    $sql = "UPDATE donl_ordini SET Stato='Da Rimborsare' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Stato impostato con successo";
    }
    activitylog($conn, 'up:modifica_ordine:darimb', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "rientro") {
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), 1)");
    $sql = "UPDATE donl_ordini SET Stato='Rientrato' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Stato impostato con successo";
    }
    activitylog($conn, 'up:modifica_ordine:rientro', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "rimborparzsp") {
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), 13)");
    $sql = "UPDATE donl_ordini SET Stato='Rimborso parziale' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "Stato impostato con successo";
    }
    activitylog($conn, 'up:modifica_ordine:rimborparzsp', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "dettagli") {
    $sql = "UPDATE donl_ordini SET Tracking='" . $_POST['tracking'] . "', Corriere='" . $_POST['corriere'] . "', Tipo='" . $_POST['tsped'] . "', Stato='" . $_POST['staoord2'] . "', IDMarketplace='" . $_POST['idmarket'] . "' WHERE id=" . $_POST['idordine'];

    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $_POST['idordine'] . ",NOW(), " . $_POST['staoord'] . ")");
        echo "Ordine aggiornato con sucesso";
    }
    activitylog($conn, 'up:modifica_ordine:dettagli', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "modificanote") {
    $sql = "UPDATE donl_ordini SET Noteo='" . $_POST['note'] . "' WHERE id=" . $_POST['idordine'];
    if (!$conn->query($sql)) {
        echo "Errore: " . $conn->error;
    } else {
        echo "ok";
    }
    activitylog($conn, 'up:modifica_ordine:modificanote', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "nuovosinistro") {
    $sql = "INSERT INTO `donl_sinistri`(`ido`, `Apertura`, `Importo`, `Stato`) VALUES ('" . $_POST['ido'] . "','" . $_POST['datapertura'] . "','" . $_POST['importo'] . "','Attesa')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "si";
    }

    activitylog($conn, 'in:modifica_ordine:nuovosinistro', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "cambiastato") {
    $sql = "UPDATE donl_sinistri SET Stato='" . $_POST['stato'] . "' WHERE ido=" . $_POST['ido'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "si";
    }

    activitylog($conn, 'up:modifica_ordine:cambiastato', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == "cercasinistro") {
    $sql = "SELECT Apertura, Importo, Stato FROM donl_sinistri WHERE ido=" . $_POST['ido'] . " LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Apertura'] . "|-|" . $row['Importo'] . "|-|" . $row['Stato'];
        }
    } else {
        echo 'no';
    }
    exit;
} else if ($tipomodifica == "listasinistri") {
    $sql = "SELECT s.ido, s.Apertura, c.Cliente, o.Corriere, s.Importo, s.Stato FROM donl_sinistri s INNER JOIN donl_ordini o ON o.ID=s.ido INNER JOIN donl_clienti c ON c.id=o.idcl " . $_POST['clausola'];
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Stato'] == 'Attesa') {
                $classe = "<span class=\"badge badge rounded-pill d-block badge-soft-warning\" title=\"Attesa\">Attesa <i class=\"fa-regular fa-hand\"></i></span>";
            } else if ($row['Stato'] == 'Annullato' || $row['Stato'] == 'Rifiutato') {
                $classe = "<span class=\"badge badge rounded-pill d-block badge-soft-secondary\" title=\"" . $row['Stato'] . "\">" . $row['Stato'] . " <svg class=\"svg-inline--fa fa-ban fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"ban\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            } else if ($row['Stato'] == 'Rimborsato') {
                $classe = "<span class=\"badge badge rounded-pill d-block badge-soft-success\" title=\"Rimborsato\">Rimborsato <svg class=\"svg-inline--fa fa-check fa-w-16 ms-1\" data-fa-transform=\"shrink-2\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"check\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\" data-fa-i2svg=\"\" style=\"transform-origin: 0.5em 0.5em;\"><g transform=\"translate(256 256)\"><g transform=\"translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)\"><path fill=\"currentColor\" d=\"M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z\" transform=\"translate(-256 -256)\"></path></g></g></svg></span>";
            }

            $resData = explode('-', $row['Apertura']);
            echo '<tr>
            <td class="ordine py-2 align-middle" class="align-middle" style="cursor: pointer;" onclick="cambiopagina(\'ordini\', \'ordine\',\'?idordine=' . $row['ido'] . '\')"><a href="javascript:void(0)"> <strong>#' . $row['ido'] . '</strong></a> di<br> <strong>' . $row['Cliente'] . '</strong></td>
            <td class="corriere py-2 align-middle" >' . $row['Corriere'] . '</td>
            <td class="datasinistro py-2 align-middle" >' . $resData[2] . '/' . $resData[1] . '/' . $resData[0] . '</td>
            <td class="importo py-2 align-middle" >€ ' . $row['Importo'] . '</td>
            <td class="stato py-2 align-middle text-center fs-0 white-space-nowrap" >' . $classe . '</span></td>
            </tr>';
        }
    } else {
        echo '<td class="text-center py-2 align-middle" colspan="5">Nessun dato disponibile</td>';
    }
    exit;
} else if ($tipomodifica == 'evadiordine') {
    $idordine = $_POST['idordine'];
    $dataordine = date("d/m/Y");
    $quantita = 0;
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $idordine . ",NOW(), 2)");

    $sql = "UPDATE donl_ordini SET Stato='Evaso', DataEvasione='" . $dataordine . "' WHERE ID=" . $idordine;
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
        // $result = $conn2->query("SELECT IDP, quantita FROM relpo WHERE IDO=" . $idordine);
        // if ($result->num_rows >= 1) {
        //     while ($row = $result->fetch_assoc()) {
        //         $result = $conn2->query("SELECT Nome, Quantita FROM prodotti WHERE ID=" . $row['IDP']);
        //         while ($row1 = $result->fetch_assoc()) {
        //             $quantita = (int)$row1['Quantita'] - (int)$row['quantita'];
        //         }

        //         if ($quantita < 0) {
        //             $sql = "UPDATE prodotti SET Quantita=0 WHERE ID=" . $row['IDP'];
        //             if (!$conn2->query($sql)) {
        //                 echo $conn2->error;
        //             } else {
        //                 echo 'ok';
        //                 activitylog($conn, 'up:modifica_ordine:evadiordine-id:' . $idordine . '+QUANT-id:' . $row['IDP'], $_SESSION['session_idu']);
        //             }
        //         } else {
        //             $sql = "UPDATE prodotti SET Quantita=" . $quantita . " WHERE ID=" . $row['IDP'];
        //             if (!$conn2->query($sql)) {
        //                 echo $conn2->error;
        //             } else {
        //                 echo 'ok';
        //                 activitylog($conn, 'up:modifica_ordine:evadiordine-id:' . $idordine . '+QUANT-id:' . $row['IDP'], $_SESSION['session_idu']);
        //             }
        //         }
        //     }
        // } else {
        //}
    }
    activitylog($conn, 'up:modifica_ordine:evadiordine-id:' . $idordine, $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == 'n_controversia') {
    $idordine = $_POST['idordine'];

    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $idordine . ",NOW(), 14)");

    $sql = "UPDATE donl_ordini SET Stato='Controversia' WHERE ID=" . $idordine;
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:modifica_ordine:n_controversia:' . $idordine, $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == 'a_controversia') {
    $idordine = $_POST['idordine'];

    //PRENDO LA PENULTIMA RIGA
    $result0 = $conn->query("SELECT IDS FROM donl_stato_ordine WHERE IDO=" . $idordine . " ORDER BY ID ASC");
    $i = $result0->num_rows;
    $result0->data_seek($i - 2);
    $row0 = $result0->fetch_assoc();

    //MODIFICO ALL'ULTIMO STATO ORDINE
    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $idordine . ",NOW(), " . $row0['IDS'] . ")");
    //CERCO A QUALE STATO SI RIFERISCE L'ULTIMO STATO ORDINE
    $result1 = $conn->query("SELECT valore FROM donl_dati WHERE ID=" . $row0['IDS']);
    $row1 = $result1->fetch_assoc();

    //AGGIORNO LA TABELLA ORDINE CON IL VECCHIO STATO
    $sql = "UPDATE donl_ordini SET Stato='" . $row1['valore'] . "' WHERE ID=" . $idordine;
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'ok';
    }
    activitylog($conn, 'up:modifica_ordine:a_controversia:' . $idordine, $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == 'evadiordinebarcode') {
    // $tracking = $_POST['tracking'];
    // $corriere = $_POST['corriere'];
    // $note = $_POST['note'];
    // $dataordine = date("d/m/Y");
    // $idordine = "";
    // $statoordine = "";

    // $sql1 = "SELECT ID, Stato FROM ordini WHERE Tracking='" . $tracking . "'";
    // $result1 = $conn->query($sql1);
    // if ($result1->num_rows > 0) {
    //     while ($row1 = $result1->fetch_assoc()) {
    //         $idordine = $row1['ID'];
    //         $statoordine = $row1['Stato'];
    //     }

    //     if ($statoordine == "Sospeso") {
    //         echo  "0;" . $idordine  . ";" . $statoordine;
    //     } else if ($statoordine == "Rimborsato") {
    //         echo  "0;" . $idordine  . ";" . $statoordine;
    //     } else if ($statoordine == "Annullato") {
    //         echo  "0;" . $idordine  . ";" . $statoordine;
    //     } else if ($statoordine == "Da Rimborsare") {
    //         echo  "0;" . $idordine  . ";" . $statoordine;
    //     } else if ($statoordine == "Da Gestire" || $statoordine == "In Stock" || $statoordine == "Allerta" || $statoordine == "Evaso") {

    //         $sql2 = "UPDATE ordini SET Stato='Evaso', DataEvasione='" . $dataordine . "', Priorita='Bassa', Corriere= '" . $corriere . "', Noteo= '" . $note . "' WHERE ID=" . $idordine;

    //         if (!$conn->query($sql2)) {
    //             echo  "0;" . $idordine  . ";Errore";
    //         } else {
    //             $sql3 = "SELECT IDPS FROM ordini WHERE ID=" . $idordine;
    //             $result3 = $conn->query($sql3);
    //             if ($result3->num_rows > 0) {
    //                 while ($row3 = $result3->fetch_assoc()) {
    //                     $idpresta = $row3['IDPS'];
    //                     echo $idpresta . ";" . $idordine  . ";" . $statoordine;
    //                 }
    //             }
    //         }
    //     } else {
    //         echo "00;00;Errore";
    //     }
    // }

    // activitylog($conn, 'up:modifica_ordine:evadiordinebarcode', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == 'totlistacorr') {
    $data = '';
    $dataoggi = date("d/m/Y");
    $result = $conn->query("SELECT COUNT(ID) AS Totale, Corriere FROM donl_ordini WHERE DataEvasione='" . $dataoggi . "' GROUP BY Corriere");
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $data .= ' <span class="text-primary">' . $row['Corriere'] . ' </span><span class="text-danger mr-3">' . $row['Totale'] . '</span>  ';
        }
    }
    echo $data;
    exit;
} else if ($tipomodifica == 'daticliente') {
    $sql = "SELECT o.NOrdine, c.Cliente FROM donl_ordini o INNER JOIN donl_clienti c ON  (o.IDCl=c.ID) WHERE o.ID=" . $_POST['ido'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row['NOrdine'] . '|-|' . $row['Cliente'];
    exit;
} else if ($tipomodifica == "cercaticket") {
    $sql = "SELECT ID FROM donl_ticket WHERE APERTO='SI' AND ido=" . $_POST['ido'] . " LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        echo 'si';
    } else {
        echo 'no';
    }
    exit;
} else if ($tipomodifica == 'aggiungiprodotto') {
    $codicepr = $_POST['codicepr'];
    $quantpr = $_POST['quantpr'];
    $totprrs = $_POST['totprrs'];
    $ido = $_POST['ido'];

    $sql = "SELECT ID, disponibilita FROM neg_magazzino WHERE sku='" . $codicepr . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id1 = $row['ID'];
        $quantitaric = $row['disponibilita'];



        $sql = 'INSERT INTO `neg_relpo`( `IDP`, `IDO`, `quantita`, `tipo`) VALUES ("' . $id1 . '", "' . $ido . '", "' . $quantpr . '", 1)';
        if (!$conn->query($sql)) {
            echo $conn->error;
        } else {
            $sql1 = 'SELECT sku, disponibilita FROM neg_magazzino WHERE ID="' . $id1 . '"';
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    if ($row1['disponibilita'] >= $quantpr) {
                        //no
                    } else {
                        $sql1 = 'INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`) VALUES ("' .  $id1 . '",  "' . $quantpr . '", "' . $row1['sku'] . '", "' . $ido . '", "1")';
                        if (!$conn->query($sql1)) {
                            echo $conn->error;
                        } else {
                            $caricato = "SI";
                        }
                    }
                }
            }
        }
    }
    ($quantitaric >= $quantpr ?   $classe = "class=\"font-weight-bolder text-success\"" : $classe = "class=\"font-weight-bolder text-danger\"");

    echo '<tr>
    <td><input type="text" class="form-control" value="' . $id1 . '" readonly></td>
    <td><a ' . $classe . '>' . $codicepr . '</a></td>
    <td>' . $nomepr . '</td>
    <td><input type="text" class="form-control" value="' . $quantpr . '" readonly></td>
    <td><a class="text-primary" href="javascript:void(0);" onclick="InfoOrdineRicambi_ov(' . $id1 . ', ' . $ido . ')"><i class="fa-solid fa-info-circle "></i></a>  <a href="javascript:void(0);" onclick="RimuoviProdottoOrdine(' . $ido . ', ' . $id1 . ')"><i class="fa-solid fa-trash-xmark"></i></a></td>
    <tr>';
    activitylog($conn, 'in:modifica_ordine:aggiungiprodotto', $_SESSION['session_idu']);
    exit;
} else if ($tipomodifica == 'eliminarelpo') {
    $sql = 'DELETE FROM `neg_relpo` WHERE IDP=\'' . $_POST['idpr'] . '\' AND IDO=\'' . $_POST['ido'] . '\'';
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'de:modifica_ordine:eliminarelpo', $_SESSION['session_idu']);
    exit;
}
