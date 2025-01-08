<?php
//error_reporting(0);
session_start();
include("database.php");
$azione = $_POST['azione'];

$dati = '';

if ($azione == 'aggiorna') {
    $_SESSION['querymagneg'] = $_POST['sql'];
    $result = $conn->query($_POST['sql']);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $nbm = number_format($row['PrestashopPrezzo'] + ((($row['PrestashopPrezzo'] * 22) / 100)), 2, ',', '.');
            $nbm == "0,00" ? $nbm = 'N/D' : $nbm;

            $dati .= '<tr>
                        <td class="py-2 align-middle" onclick="ApriProdotto_ca(' . $row['ID'] . ')" style="cursor:pointer;" width="10%"><img onerror="replaceErrorImg(this);" height="auto" width="128" src="https://portalescifo.it/upload/image/p/' . $row['ID'] . '.jpg" data-zoom-image="https://portalescifo.it/upload/image/p/' . $row['ID'] . '.jpg" onmouseover="$.removeData($(this), \'elevateZoom\'); $(this).elevateZoom({zoomWindowFadeIn: 500,zoomWindowFadeOut: 500,lensFadeIn: 500,lensFadeOut: 500});" /></td>
                        <td class="nome py-2 align-middle" onclick="ApriProdotto_ca(' . $row['ID'] . ')" style="cursor:pointer;">' . $row['nome'] . '</td>
                        <td class="sku py-2 align-middle text-center"><span id="PoP-' . $row['ID'] . '" cp="' . $row['sku'] . '" style="cursor:pointer;">' . $row['sku'] . '</span></td>
                        <td class="Prezzo py-2 align-middle text-info font-weight-bold" onclick="ApriProdotto_ca(' . $row['ID'] . ')" style="cursor:pointer;"><div class="ml-4 fw-bolder"><div class="text-primary font-size-lg mb-0">Banco: € ' . number_format($row['prezzo'], 2, ',', '.') . '</div>Marketplace: € ' .  $nbm  . '</div></td></td>                      
                        <td class="QNegozio py-2 align-middle text-center" id="cquant_' . $row['ID'] . '_disponibilita" contenteditable>' . $row['disponibilita'] . '</td>';

            if ($row['stato'] == 0) {
                $stato = '<a class="dropdown-item text-danger" onclick="modst(1,' . $row['ID1'] . ')" href="javascript:void(0);"><i class="fa-regular fa-xmark fa-xl"></i> Disattivato</a>';
            } else {
                $stato = '<a class="dropdown-item text-success" onclick="modst(0,' . $row['ID1'] . ')" href="javascript:void(0);"><i class="fa-regular fa-check fa-xl"></i> Attivato</a>';
            }
            $dati .= '<td class="QPrestashop py-2 align-middle text-center" id="cquant_' . $row['ID'] . '_PrestashopDisponibilita_' . $row['ID1'] . '" contenteditable>' . $row['PrestashopDisponibilita'] . '</td>
            <td class="align-middle py-2 white-space-nowrap text-end">
            <div class="dropdown font-sans-serif position-static">
                <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" id="order-dropdown-0" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end border py-0" aria-labelledby="order-dropdown-0">
                    <div class="bg-white py-2">
                        <li><a class="dropdown-item StampaZebraNegozio" id="' . $row['ID'] . '" href="javascript:void(0)" title="Etichetta negozio"><i class="fa-regular fa-tag" style="color:var(--falcon-teal);"></i> Etichetta negozio</a></li>
                        <li><a class="dropdown-item StampaZebraOfficina" id="' . $row['ID'] . '" href="javascript:void(0)" title="Etichetta officina"><i class="fa-regular fa-tag" style="color:var(--falcon-indigo);"></i> Etichetta officina</a></li>
                        <li><a class="dropdown-item" onclick="AggiungiAlCarrello(\'' . $row['ID'] . '\', \'' . $row['sku'] . '\')" href="javascript:void(0)" title="Ordina prodotto"><i class="fa-regular fa-cart-plus"></i> Ordina prodotto</a></li>
                        <li><a class="dropdown-item" onclick="DuplicaProdotto(\'' . $row['ID'] . '\')" href="javascript:void(0)" title="Duplica"><i class="fa-regular fa-clone"></i> Duplica</a></li>
                        <div class="dropdown-divider"></div>
                        ' . $stato . '
                        </div>
                    </div>  
                </div>
            </td>
            </tr>';
        }
    } else {
        $dati .= '<td class="text-center py-2 align-middle" colspan="7">
        <div style="text-align:center;padding-top: 50px;padding-bottom: 50px;color: #555;">
        <i class="fa-regular fa-face-monocle" style="font-size: 150px;"></i>
        <div style="margin-top: 30px;font-size: 30px;">
            <span>Non ho trovato nulla!</span>
        </div>
    </div>
    </td>';
    }

    echo $dati;
    exit;
} else if ($azione == 'aggquant') {
    $sql = "UPDATE neg_magazzino SET " . $_POST['campo'] . "=" . $_POST['quant'] . " WHERE ID=" . $_POST['idpr'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:catalogo-neg:aggquant', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'infostampa2') {
    $sql = 'SELECT nome, sku, ean, prezzo FROM neg_magazzino WHERE ID=' . $_POST['idpr'] . ' LIMIT 1';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo '' . $row['nome'] . ';' . $row['sku'] . ';' . $row['ean'] . ';' . $row['prezzo'];
    exit;
} else if ($azione == 'duplicaprodotto') {
    $sql = 'INSERT INTO neg_magazzino (`nome`, `um`, `peso`, `descrizione`, `tag`, `tipo`, `marca`, `stato`) SELECT `nome`, `um`, `peso`, `descrizione`, `tag`, `tipo`, `marca`, 0 FROM neg_magazzino WHERE ID=' . $_POST['id'];
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'up:catalogo-neg:duplicaprodotto', $_SESSION['session_idu']);
    exit;
} else if ($azione == 'aggiungicarrello') {
    $sql = 'INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`, `Note`) VALUES ("' .   $_POST['id'] . '",  "' . $_POST['quantita'] . '", "' . $_POST['sku'] . '", "0", 3, "' . $_POST['note'] . '")';
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo 'si';
    }
    activitylog($conn, 'in:catalogo-neg:aggiungicarrello', $_SESSION['session_idu']);
    exit;
}
