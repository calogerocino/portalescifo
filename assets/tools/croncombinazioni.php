<?php
date_default_timezone_set('Europe/Rome');

$logghi = "=====START=====<br/>";


include_once((dirname(dirname(__FILE__)) . '/inc/database.php'));
define('DEBUG', true);
define('PS_SHOP_PATH', 'http://scifostore.com/');
define('PS_WS_AUTH_KEY', '5ZHVUQ9EMAP5KX9B28GVPDD41PDX7ZD2');
include_once((dirname(dirname(__FILE__)) . '/inc/PSWebServiceLibrary.php'));


$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
$xml = $webService->get(['resource' => 'combinations']);
$totprodtotc = 0;
$totprodc = 0;

foreach ($xml->combinations->combination as $i) {
    $ii = $i['id'];
    $totprodtotc = ($totprodtotc + 1);

    try {
        $xml = $webService->get(['resource' => 'combinations', 'display' => 'full', 'filter[id]' => "[$ii]"]);

        $combinazione['id'] = $xml->combinations->combination->id;
        $combinazione['id1'] = $xml->combinations->combination->id_product;
        $combinazione['ean'] = $xml->combinations->combination->ean13;
        $combinazione['sku'] = $xml->combinations->combination->reference;
        $combinazione['acquisto'] = $xml->combinations->combination->wholesale_price;
        $combinazione['vendita'] = $xml->combinations->combination->price;

        $combinazione['cercanome'] = $xml->combinations->combination->associations->product_option_values->product_option_value->id;
        $xml1 =  $webService->get(['resource' => 'product_option_values', 'display' => '[name]', 'filter[id]' => "[" . $combinazione['cercanome'] . "]"]);
        $combinazione['nome'] = addslashes($xml1->product_option_values->product_option_value->name->language);

        $xml2 =  $webService->get(['resource' => 'stock_availables', 'display' => '[quantity]', 'filter[id_product_attribute]' => "[" . $combinazione['id'] . "]"]);
        $combinazione['qprestashop'] = $xml2->stock_availables->stock_available->quantity;

        if (!empty($combinazione['nome'])) {
            $sql = "INSERT INTO `neg_combinazioni`(`ID`, `ID1`, `combinazione`, `sku`, `ean`, `PrezzoAcquisto`, `prezzo`, `PrestashopPrezzo`, `disponibilita`, `PrestashopDisponibilita`) VALUES (
        '" . $combinazione['id'] . "',
        '" . $combinazione['id1'] . "',
        '" . $combinazione['nome'] . "',
        '" . $combinazione['sku'] . "',
        '" . $combinazione['ean'] . "',
        '" . $combinazione['acquisto'] . "',
        '" . $combinazione['vendita'] . "',
        '" . $combinazione['vendita'] . "',
        '0',
        '" . $combinazione['qprestashop'] . "')";
            if (!$conn->query($sql)) {
                $logghi .= "Errore: " . $conn->error;
            } else {
                $totprodc = ($totprodc + 1);
            }
        }
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
}

$logghi .= 'Prodotti caricati: ' . $totprodc . '/' . $totprodtotc;

$logghi .= "<br/>=====FINE=====<br/>";
echo $logghi;

exit;
