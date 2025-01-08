<?php
error_reporting(0);
include("database.php");
$azione = $_POST['azione'];
$dati = '';

if ($azione == 'cercaprodotto') {
    $result = $conn->query($_POST['sql']);
    while ($row = $result->fetch_assoc()) {
        $dati .= '
        <div class="itemterm" style="cursor:pointer;" onclick="CaricaProdotto_bn(' . $row['ID'] . ')">
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
                        <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" data-field="cartcont_qt_' . $_POST['prog'] . '" id="bt_minus_' . $_POST['prog'] . '">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="1" aria-label="Amount (to the nearest dollar)" style="width: 50px" id="cartcont_qt_' . $_POST['prog'] . '" /><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" data-field="cartcont_qt_' . $_POST['prog'] . '"  id="bt_plus_' . $_POST['prog'] . '">+</button></div>
                    </div>
                </div>

                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' . $_POST['prog'] . '" " title="' . $row['iva'] . '">' . $row['iva'] . '</div>
                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' . $_POST['prog'] . '" title="0">0</div>
                    <div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="vbn_tot' . $_POST['prog'] . '" title="' . $row['prezzo'] . '">' . $row['prezzo'] . '</div>
                    <div id="vbn_idpr' . $_POST['prog'] . '" hidden>' . $row['ID'] . '</div>
                </div>
            </div>
        </div>';
    }

    echo $dati;
    exit;
} else if ($azione == 'nuovorelpo') {
    $sql = "INSERT INTO `neg_relpo`(`IDP`, `IDD`, `quantita`, `prezzo`, `sconto`, `tipo`) VALUES ('" . $_POST['idp'] . "','" . $_POST['ndoc'] . "','" . $_POST['qt'] . "'," . $_POST['pz'] . "','" . $_POST['sc'] . "', 3)";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
    }
    activitylog($conn, 'in:bancooff:nuovorelpo', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'nuovavendita') {
    $sql = "INSERT INTO neg_vbanco (Pagamento, Totale, Saldato) VALUES ('" . $_POST['tp'] . "','" . $_POST['tt'] . "','" . $_POST['sd'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        //CERCA L ID DELLA VENDITA EFFETTUATA
        $sql = "SELECT ID FROM neg_vbanco WHERE Totale='" . $_POST['tt'] . "' ORDER BY ID DESC LIMIT 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo $row['ID'];
    }
    activitylog($conn, 'in:banconeg:nuovavendita', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'newvendprod') {
    $sql = "INSERT INTO `neg_vendite`(`Quantita`, `Prezzo`, `Sconto`, `idpr`, `idba`) VALUES ('" . $_POST['qt'] . "','" . $_POST['pz'] . "','" . $_POST['sc'] . "','" . $_POST['idpr'] . "','" . $_POST['idba'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        $sql = "SELECT nome, disponibilita FROM neg_magazzino WHERE ID=" . $_POST['idpr'];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $quantita = (int)$row['disponibilita'] - (int)$_POST['qt'];
            $nome = $row['nome'];
        }

        if ($quantita < 0) {
            echo 'La quantita da scaricare non è sufficente per il prodotto: ' . $nome;

            $sql = "UPDATE neg_magazzino SET disponibilita=0 WHERE ID=" . $_POST['idpr'];
            if (!$conn->query($sql)) {
                echo $conn->error;
            } else {
                echo 'si';
            }
        } else {
            $sql = "UPDATE neg_magazzino SET disponibilita=" . $quantita . " WHERE ID=" . $_POST['idpr'];
            if (!$conn->query($sql)) {
                echo $conn->error;
            } else {
                echo 'si';
            }
        }
    }

    activitylog($conn, 'up:banconeg:newvendprod', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'venditebanco') {
    $risultato;
    $voci = 0;
    $totale = 0;

    $sql = "SELECT * FROM neg_vbanco WHERE Data LIKE '" . $_POST['data'] . "%'";

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Saldato'] == '1') {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-success" title="Saldato">Saldato <i class="fa-regular fa-check"></i></span>';
            } else {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-warning" title="Non saldato">Non saldato <i class="fa-regular fa-hand"></i></span>';
            }
            if ($row['Pagamento'] == 'vbn_cont') {
                $pagamento = '<strong>Pagamento effettuato</strong><br>in contanti';
            } else if ($row['Pagamento'] == 'vbn_mon') {
                $pagamento = '<strong>Pagamento effettuato</strong><br>online';
            } else {
                $pagamento = '<strong>Pagamento non trovato</strong>';
            }

            $risultato .= "<tr style=\"cursor: pointer;\" class=\"rvbn_aprivend\" idv=\""  . $row['ID'] .  "\">
            <td class=\"t1 py-2 align-middle\">" . $pagamento . "</td>
            <td class=\"t2 py-2 align-middle\">" . $row['Data'] . "</td>
            <td class=\"t3 align-middle py-2 text-center fs-0 white-space-nowrap\">" . $stato . "</td>
            <td class=\"t4 py-2 align-middle text-end fs-0 fw-medium\">€ " . $row['Totale'] . "</td>
            </tr>";
            $voci = $voci + 1;
            if ($row['Saldato'] == '1') {
                $totale = ($totale + floatval($row['Totale']));
            }
        }
    } else {
        $risultato .= '<td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td>';;
        $voci = 0;
        $totale = 0;
    }
    echo '<th class="sort" data-sort="t1">Pagamento</th><th class="sort" data-sort="t2">Data</th><th class="sort text-center" data-sort="t3">Saldato</th><th class="sort text-end" data-sort="t4">Totale</th>|-|' .
        $risultato . '|-|<th>' . $voci . ' voci</th><th></th><th></th><th class="py-2 align-middle text-end fs-0 fw-medium">€ ' . number_format($totale, 2) . '</th>';

    exit;
} else if ($azione == 'prodottivendite') {
    $voci = 0;
    $totale = 0;

    $sql = "SELECT p.ID, p.sku, p.nome, v.Quantita, v.Prezzo FROM neg_magazzino p INNER JOIN neg_vendite v ON v.idpr = p.ID WHERE v.idba=" . $_POST['idv'];

    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $risultato .= "<tr style=\"cursor: pointer;\" onclick=\"ApriProdotto_ca("  . $row['ID'] .  ")\">";
            $risultato .= "<td class=\"t1 py-2 align-middle\">" . $row['sku'] . "</td>";
            $risultato .= "<td class=\"t2 py-2 align-middle\">" . $row['nome'] . "</td>";
            $risultato .= "<td class=\"t3 py-2 align-middle text-center\">" . $row['Quantita'] . "</td>";
            $risultato .= "<td class=\"t4 py-2 align-middle text-end fs-0 fw-medium\">€ " . $row['Prezzo'] . "</td>";
            $risultato .= "</tr>";
            $voci = $voci + 1;

            $totale = $totale + $row['Prezzo'];
        }
    } else {
        $risultato .= "<tr><td>NESSUN RISULTATO</td><td></td><td></td><td></td></tr>";
        $voci = 0;
        $totale = 0;
    }
    echo '<th class="sort" data-sort="t1">Codice</th><th class="sort" data-sort="t2">Nome</th><th class="sort text-center" data-sort="t3">Quantita</th><th class="sort text-end" data-sort="t4">Prezzo</th>|-|' .
        $risultato . '|-|<th>' . $voci . ' voci</th><th></th><th></th><th class="py-2 align-middle text-end fs-0 fw-medium">€ ' . number_format($totale, 2) . '</th>';
    exit;
}
