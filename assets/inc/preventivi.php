<?php
error_reporting(0);
session_start();
include("database.php");
$azione = $_POST['azione'];


if ($azione == 'creaprev') {
    $sql = "INSERT INTO `doff_preventivi`( `idcl`, `Data`, `Stato`, `Totale`) VALUES ('" . $_POST['idcl'] . "' , NOW(), 0, '" . $_POST['tt'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        $sql = "SELECT `ID` FROM doff_preventivi ORDER BY ID DESC LIMIT 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo $row['ID'];
    }
    activitylog($conn, 'in:preventivi:creaprev', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'newprevprod') {
    $sql = "INSERT INTO `doff_prodotti_preventivi`(`Quantita`, `Prezzo`, `Sconto`, `IVA`, `Titolo`, `idpr`, `idpre`) VALUES ('" . $_POST['qt'] . "','" . $_POST['pz'] . "','" . $_POST['sc'] . "','" . $_POST['iva'] . "','" . $_POST['tit'] . "','" . $_POST['idpr'] . "','" . $_POST['idprev'] . "')";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }

    activitylog($conn, 'up:preventivi:newprevprod', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'listaprev') {
    $data = '';
    $sql = "SELECT p.*, c.Cliente FROM doff_preventivi p INNER JOIN doff_clienti c ON (p.idcl=c.ID) ORDER BY p.ID DESC";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {

            $stato = '';
            $totale = 0;
            $totalef = 0;
            $quant = 0;
            $sconto = 0;
            $prez = 0;

            $result1 = $conn->query("SELECT * FROM doff_prodotti_preventivi WHERE idpre=" . $row['ID']);
            if ($result1->num_rows >= 1) {
                while ($row1 = $result1->fetch_assoc()) {
                    $quant = (int)$row1['Quantita'];
                    $sconto = (int)$row1['Sconto'];
                    $prez =  (float)$row1['Prezzo'];

                    if ($sconto >= 1) {
                        $totale = (($quant * $prez) - ((100 - $sconto) / 100));
                    } else {
                        $totale = ($prez * $quant);
                    }
                    $totalef += (float)$totale;
                }
            } else {
                $totale = 0;
            }
            if ($row['Stato'] == '0') {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-primary" title="Non confermato">Non confermato <i class="fa-regular fa-sparkles"></i></span>';
                $StatoPreventivo = '<li><a class="dropdown-item"href="javascript:void(0)" title="Conferma"><i class="fa-regular fa-check"></i> Conferma</a></li>';
                $StatoPreventivo .= '<li><a class="dropdown-item"href="javascript:void(0)" title="Chiudi"><i class="fa-regular fa-ban"></i> Chiudi</a></li>';
            } else if ($row['Stato'] == '1') {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-success" title="Confermato">Confermato <i class="fa-regular fa-check"></i></span>';
                $StatoPreventivo = '<li><a class="dropdown-item"href="javascript:void(0)" title="Chiudi"><i class="fa-regular fa-ban"></i> Chiudi</a></li>';
            } else if ($row['Stato'] == '2') {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-secondary" title="Chiuso">Chiuso <i class="fa-regular fa-ban"></i></span>';
            } else {
                $stato = '<span class="badge badge rounded-pill d-block badge-soft-warning" title="Errore stato">Errore stato <i class="fa-regular fa-hand"></i></span>';
            }

            $dt = explode('-', $row['Data']);

            $data .= '
             <tr>
            <td class="cliente py-2 align-middle"><div class="text-dark-75 font-weight-bolder font-size-lg mb-0"><a href="javascript:void(0)" class="py-2 align-middle ApriCliente_dc" id="' . $row['idcl'] . '">' . $row['Cliente'] . '</a></div></td>
            <td class="data py-2 align-middle" style="cursor: pointer;" onclick="apri_prev(' . $row['ID'] . ')">' . $dt[2] . '/' . $dt[1] . '/' . $dt[0] . '</td>
            <td class="stato py-2 align-middle text-center fs-0 white-space-nowrap" style="cursor: pointer;" onclick="apri_prev(' . $row['ID'] . ')">' . $stato . '</td>
            <td class="totale py-2 align-middle text-end fs-0 fw-medium" style="cursor: pointer;" onclick="apri_prev(' . $row['ID'] . ')">€ ' . str_replace('.', ',', $totalef) . '</td>
            <td class="align-middle py-2 white-space-nowrap text-end"><div class="dropdown font-sans-serif position-static"><button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" id="order-dropdown-1" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="true"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg></button><div class="dropdown-menu dropdown-menu-end border py-0" aria-labelledby="order-dropdown-1" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-31px, 188px);" data-popper-placement="bottom-end"><div class="bg-white py-2">' . $StatoPreventivo . '<div class="dropdown-divider"></div><li><a class="dropdown-item" href="javascript:void(0)" id="' . $row['ID'] . '" title="Elimina"><i class="fa-regular fa-trash"></i> Elimina</a></li></div></div></div></td>
            </tr>';
        }
    } else {
        $data .= '<tr><td class="text-center py-2 align-middle" colspan="4">Nessun dato disponibile</td></tr>';
    }
    echo $data;
    exit;
} else if ($azione == 'carprev') {
    $data = '';
    $i = 1;
    $result = $conn->query("SELECT p.*, c.Cliente FROM doff_preventivi p INNER JOIN doff_clienti c ON (p.idcl=c.ID) WHERE p.ID=" . $_POST['idpre']);
    $row = $result->fetch_assoc();
    $data .= $row['Cliente'] . '|-|' .  $row['ID'] . '|-|' . $row['Data'] . '|-|' . $row['Totale'] . '|-|' . $row['Stato'] . '|-|';

    $result1 = $conn->query("SELECT * FROM doff_prodotti_preventivi WHERE idpre=" . $row['ID']);
    if ($result1->num_rows >= 1) {
        while ($row1 = $result1->fetch_assoc()) {

            $totale = 0;

            $quant = (int)$row1['Quantita'];
            $sconto = (int)$row1['Sconto'];
            $prez =  (float)$row1['Prezzo'];

            if ($sconto >= 1) {
                $totale = (($quant * $prez) - ((100 - $sconto) / 100));
            } else {
                $totale = ($prez * $quant);
            }

            $result2 = $conn->query("SELECT ID,  nome FROM neg_magazzino WHERE ID=" . $row1['idpr']);
            if ($result2->num_rows >= 1) {
                $row2 = $result2->fetch_assoc();
                $prodotto[0] =  $row2['ID'];
                $prodotto[1] =  $row2['nome'];
            } else {
                $prodotto[0] =  '0';
                $prodotto[1] =  $row1['Titolo'];
            }

            $data .= '
            <div class="row gx-card mx-0 align-items-center border-bottom border-200" id="idr' . $i . '">
            <div class="col-5 py-3">
                <div class="d-flex align-items-center"><a href="javascript:void(0)"><img class="img-fluid rounded-1 me-3 d-none d-md-block" src="https://portalescifo.it/upload/image/p/' . $row1['idpr'] . '.jpg" alt="" width="128px" height="auto"></a>
                    <div class="flex-1">
                        <h5 class="fs-0"><a class="text-900 ApriProdotto_bn" href="javascript:void(0)" id="cart_ds_' . $i . '" idprn=' . $prodotto[0]  . '">' . $prodotto[1]  . '</a></h5>
                        <div class="fs--2 fs-md--1"><a class="text-danger" href="javascript:void(0)" onclick="EliminaRiga_bn(' . $i . ')">Rimuovi</a></div>
                    </div>
                </div>
            </div>
            <div class="col-7 py-3">
                <div class="row align-items-center">
                <div class="col-2 ps-0 mb-2 mb-md-0 text-600" contenteditable="" id="cartcont_pr_' . $i . '" title="' . $prez . '">' . $prez . '</div>
                <div class="col-3 d-flex justify-content-end justify-content-md-center">
                    <div>
                        <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" data-field="cartcont_qt_' . $i . '" id="bt_minus_' . $i . '">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="1" aria-label="Amount (to the nearest dollar)" style="width: 50px" value="' . $quant . '" id="cartcont_qt_' . $i . '" /><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" data-field="cartcont_qt_' . $i . '"  id="bt_plus_' . $i . '">+</button></div>
                    </div>
                </div>

                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' . $i . '" " title="' . $row1['IVA'] . '">' . $row1['IVA'] . '</div>
                    <div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' . $i . '" title="' . $sconto . '">' . $sconto . '</div>
                    <div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="vbn_tot' . $i . '" title="' . $totale . '">' . $totale . '</div>
                    <div id="vbn_idpr' . $i . '" hidden>' . $prodotto[0] . '</div>
                </div>
            </div>
        </div>';

            $i += 1;
        }
    }

    echo $data . '|-|' . $i;
    exit;
}
