<?php
date_default_timezone_set('Europe/Rome');

$logghi = "=====START=====<br/>";

include_once((dirname(dirname(__FILE__)) . '/inc/database.php'));
define('DEBUG', true);
define('PS_SHOP_PATH', 'http://scifostore.com');
define('PS_WS_AUTH_KEY', '5ZHVUQ9EMAP5KX9B28GVPDD41PDX7ZD2');
include_once((dirname(dirname(__FILE__)) . '/inc/PSWebServiceLibrary.php'));

$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
$xml = $webService->get(['resource' => 'products']);
$totprodtotc = 0;
$totprodc = 0;
$totimg = 0;

foreach ($xml->products->product as $i) {
    $ii = $i['id'];

    $result = $conn->query("SELECT ID1 FROM neg_magazzino WHERE ID1=" . $ii);

    if ($result->num_rows == 0) {
        $totprodtotc = ($totprodtotc + 1);
        try {
            $xml1 = $webService->get(['resource' => 'products', 'display' => 'full', 'filter[id]' => "[$ii]"]);
        } catch (Exception $e) {
            echo $ii;
            echo "Error : {$e->getMessage()}";
            continue;
        }
        $prodotto['stockavailable'] = $xml1->products->product->associations->stock_availables->stock_available->id;
        $xml2 =  $webService->get(['resource' => 'stock_availables', 'display' => '[quantity]', 'filter[id_product]' => "[" . $prodotto['stockavailable'] . "]"]);

        $prodotto['id'] = $xml1->products->product->id;
        $prodotto['name'] = addslashes($xml1->products->product->name->language);
        $prodotto['codice'] = $xml1->products->product->reference;
        $prodotto['ean'] = $xml1->products->product->ean13;
        $prodotto['prezzosiva'] = $xml1->products->product->price;
        $prodotto['prezzoa'] = $xml1->products->product->wholesale_price;
        $prodotto['qprestashop'] = $xml2->stock_availables->stock_available->quantity;
        $prodotto['peso'] = $xml1->products->product->weight;
        $prodotto['marca'] = addslashes($xml1->products->product->manufacturer_name);
        $prodotto['immagine'] = $xml1->products->product->id_default_image->attributes('xlink', true)->href; //IMG
        $prodotto['tag'] = addslashes($xml1->products->product->meta_keywords->language);
        $prodotto['desc'] = addslashes($xml1->products->product->description_short->language);
        $prodotto['att'] = $xml1->products->product->active;

        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode(PS_WS_AUTH_KEY . ":" . PS_WS_AUTH_KEY)
            )
        ));

        if (!empty($prodotto['name'])) {
            if (strpos($codicepr, "RC1") !== FALSE || strpos($codicepr, "RO1") !== FALSE || strpos($codicepr, "SR5") !== FALSE || strpos($codicepr, "ECR") !== FALSE || strpos($codicepr, "EXR") !== FALSE) {
                $prodotto['tipo'] = '1';
            } else if (strpos($prodotto['codice'], "RC1") !== TRUE || strpos($prodotto['codice'], "RO1") !== TRUE || strpos($prodotto['codice'], "SR") !== TRUE || strpos($prodotto['codice'], "ECR") !== TRUE || strpos($prodotto['codice'], "EXR") !== TRUE) {
                $prodotto['tipo'] = '2';
            }

            $sql = "INSERT INTO `neg_magazzino`(`ID1`, `sku`, `nome`, `disponibilita`, `ean`, `marca`, `peso`, `prezzo`, `PrestashopPrezzo`, `PrezzoAcquisto`, `tag`, `descrizione`, `PrestashopDisponibilita`, `stato`, `tipo`) VALUES ('" . $prodotto['id'] . "', '" . $prodotto['codice'] . "', '" . $prodotto['name'] . "', 0, '" . $prodotto['ean'] . "', '" . $prodotto['marca'] . "','" . $prodotto['peso'] . "','" . prezzo_ivato($prodotto['prezzosiva']) . "', '" . $prodotto['prezzosiva'] . "', '" . $prodotto['prezzoa'] . "', '" . $prodotto['tag'] . "', '" . $prodotto['desc'] . "', '" .  $prodotto['qprestashop'] . "', '" .  $prodotto['att'] . "', '" . $prodotto['tipo'] . "')";
            if (!$conn->query($sql)) {
                $logghi .= "Errore: " . $conn->error;
            } else {
                $result1 = $conn->query("SELECT ID FROM neg_magazzino WHERE `sku`='" . $prodotto['codice'] . "' AND `nome`='" . $prodotto['name'] . "' AND `ean`='" . $prodotto['ean'] . "' LIMIT 1");
                $row1 = $result1->fetch_assoc();
                $totprodc = ($totprodc + 1);
                $image = file_get_contents($prodotto['immagine'], false, $context);

                if (file_put_contents('https://portalescifo.it/upload/image/p/' . $row1['ID'] . '.jpg', $image)) {
                    $totimg = ($totimg + 1);
                }
            }
        }
    } else {
        continue;
    }
}
$logghi .= 'Prodotti caricati: ' . $totprodc . '/' . $totprodtotc;
$logghi .= '<br/>Immagini caricate: ' . $totimg . '/' . $totprodtotc;

$result = $conn->query("SELECT ID1 FROM neg_magazzino ORDER BY ID1 ASC");
$totprodtot = $result->num_rows;
$totprod = 0;
while ($row = $result->fetch_assoc()) {

    $xml = $webService->get(['resource' => 'products', 'display' => 'full', 'filter[id]' => "[" . $row['ID1'] . "]"]);
    $prodotto['stockavailable'] = $xml->products->product->associations->stock_availables->stock_available->id;
    $xml2 =  $webService->get(['resource' => 'stock_availables', 'display' => 'full', 'filter[id]' => "[" .   $prodotto['stockavailable'] . "]"]);

    $prodotto['prezzo'] =  $xml->products->product->price;
    $prodotto['qprestashop'] = $xml2->stock_availables->stock_available->quantity;
    $prodotto['att'] = $xml->products->product->active;

    if (!$conn->query("UPDATE neg_magazzino SET PrestashopDisponibilita='" . $prodotto['qprestashop'] . "', PrestashopPrezzo='" . $prodotto['prezzo'] . "', stato='" . $prodotto['att'] . "' WHERE ID1=" . $row['ID1'])) {
        echo "Errore: " . $conn->error . "<br />";
    } else {
        $totprod = $totprod + 1;
    }
}
$logghi .= '<br/>Prodotti aggiornati: ' . $totprod . '/' . $totprodtot;


function prezzo_ivato($prezzo)
{
    $iva = 22;
    $totale = $prezzo + (($prezzo / 100) * $iva);
    return round($totale, 2);
}
$logghi .= "<br/>=====FINE=====<br/>";

echo $logghi;

$oggi = date('Y-m-d');
// $log = fopen("log/" . $oggi . "_cronprodotti_log.txt", "w") or die("Impossibile creare il file " . $oggi . "_cronprodotti_log.txt.");
// $scrivi = fwrite($log, "Eseguito il " . date('Y-m-d H:i:s') . $logghi);
// fclose($log);

$fp = fopen("log/" . $oggi . "_cronprodotti.dat", "w");
fwrite($fp, "Eseguito il " . date('Y-m-d H:i:s') . $logghi);
fclose($fp);

$fp = fopen("log/" . $oggi . "_cronprodotti.txt", "w");
fclose($fp);

exit;
