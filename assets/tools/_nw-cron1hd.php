<?php
ini_set('max_execution_time', 0);

$logghi = '';

include_once((dirname(dirname(__FILE__)) . '/inc/database.php'));
define('DEBUG', true);
define('PS_SHOP_PATH', 'https://scifostore.com');
define('PS_WS_AUTH_KEY', '5ZHVUQ9EMAP5KX9B28GVPDD41PDX7ZD2');
include_once((dirname(dirname(__FILE__)) . '/inc/PSWebServiceLibrary.php'));

try {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    // ========== CERCA ORDINI ========== //

    //ORDINI
    $xml = $webService->get(['resource' => 'orders', 'display' => 'full', 'filter[id]' => "[9]"]);

    // ========== CARICA PRODOTTI ========== //
    $idprodotto = $xml->orders->order[$i + 1]->id;

    for ($x = 0; $x <= count($xml->orders->order->associations->order_rows->order_row) - 1; $x++) { //product_id, product_quantity, unit_price_tax_incl
        $prodotto['codice'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_reference;
        $prodotto['quantita'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_quantity;
        $sql = "SELECT ID FROM neg_magazzino WHERE sku='" . $prodotto['codice'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sql = 'INSERT INTO `neg_relpo`( `IDP`, `IDO`, `quantita`) VALUES ("' .  $row['ID'] . '", "9", "' . $prodotto['quantita'] . '")';
                if (!$conn->query($sql)) {
                    $logghi .= 'Errore: ' . $conn->error . ' <br />';
                } else {
                    $sql1 = 'SELECT sku, disponibilita FROM neg_magazzino WHERE ID="' .  $row['ID'] . '"';
                    $result1 = $conn->query($sql1);
                    if ($result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            if ($row1['disponibilita'] >= $prodotto['quantita']) {
                                $logghi .= 'Prodotto caricato<br />';
                            } else {
                                $sql1 = 'INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`) VALUES ("' .   $row['ID'] . '",  "' . $prodotto['quantita'] . '", "' . $row1['sku'] . '", "9", 1)';
                                if (!$conn->query($sql1)) {
                                    $logghi .= 'Errore: ' . $conn->error . ' <br />';
                                } else {
                                    $logghi .= 'Prodotto caricato + ordine prodotto<br />';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} catch (PrestaShopWebserviceException $ex) {
    echo 'Errori: ' . $ex->getMessage();
}

echo  $logghi;

exit;
