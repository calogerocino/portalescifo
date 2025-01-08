<?php
//error_reporting(0);
include("database.php");
$azione = $_POST['azione'];

define('DEBUG', true);
define('PS_SHOP_PATH', 'http://scifostore.com'); 
define('PS_WS_AUTH_KEY', '5ZHVUQ9EMAP5KX9B28GVPDD41PDX7ZD2');
require_once('PSWebServiceLibrary.php');

if ($azione == 'inviaordine') {
    $id_order = $_POST['idordine'];
    $id_stato = $_POST['idstato'];
    if ($id_stato != 3) { //SE LO STATO NON E' PREPARAZIONE IN CORSO
        try {
            $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
            $opt = [
                'resource' => 'order_histories?schema=blank'
            ];
            $xml = $webService->get($opt);
            $resources = $xml->children()->children();
            $resources->id_order = $id_order;
            $resources->id_employee = 0;
            $resources->id_order_state = $id_stato;
            $opt = [
                'resource' => 'order_histories',
                'postXml' => $xml->asXML()
            ];
            $webService->add($opt);
            echo "L ordine ID [$id_order] è stato aggiornato.<br>";
        } catch (Exception $e) {
            echo "Error : {$e->getMessage()}";
            exit;
        }
    } else {
        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
        $opt = array(
            'resource'   => 'orders',
            'display'    => 'full',
            'filter[id]' => $id_order
        );
        $xml = $webService->get($opt);
        if ($xml->orders->order->current_state != 3) { //SE LO STATO NON E' PREPARAZIONE IN CORSO
            try {
                $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
                $opt = [
                    'resource' => 'order_histories?schema=blank'
                ];
                $xml = $webService->get($opt);
                $resources = $xml->children()->children();
                $resources->id_order = $id_order;
                $resources->id_employee = 0;
                $resources->id_order_state = $id_stato;
                $opt = [
                    'resource' => 'order_histories',
                    'postXml' => $xml->asXML()
                ];
                $webService->add($opt);
                echo "L ordine ID [$id_order] è stato aggiornato.<br>";
            } catch (Exception $e) {
                echo "Error : {$e->getMessage()}";
                exit;
            }
        }
    }
} else if ($azione == 'edittracking') {
    $id_order = $_POST['idordine'];
    $tracking_number = $_POST['tracking'];
    $corriere = $_POST['corriere'];
    $id_carrier = '';
    if ($corriere == 'DHL') {
        $id_carrier = '541';
    } else if ($corriere == 'Poste Italiane') {
        $id_carrier = '547';
    }
    if (empty($id_order) || strlen($tracking_number) <= 0) return;
    try {
        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
        $opt_ord_carr = [
            'resource'   => 'order_carriers',
            'display' => '[id,id_order,id_carrier]',
            'filter[id_order]' => "[$id_order]"
        ];
        $xml_ord_carr = $webService->get($opt_ord_carr);
        $resource_carrier = $xml_ord_carr->children()->children();
        if (!empty($resource_carrier)) {
            $resource_id = (int)$resource_carrier->order_carrier->id;
            $xml_2 = $webService->get([
                'url' => PS_SHOP_PATH . "/api/order_carriers/$resource_id"
            ]);
            $order_carriers = $xml_2->children()->children();
            $order_carriers->tracking_number = (string)$tracking_number;
            $order_carriers->id_carrier = $id_carrier;
            $opt = [
                'resource' => 'order_carriers',
                'putXml' => $xml_2->asXML(),
                'id' => (int)$resource_id
            ];
            $res = $webService->edit($opt);
            echo "si";
            exit;
        } else {
            echo "no";
            exit;
        }
    } catch (Exception $e) {
        echo "Error : {$e->getMessage()}";
        exit;
    }
} else if ($azione == 'upordine') {
    $idordine = $_POST['idordine'];
    $data = '';

    try {
        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);

        //ORDINI
        $xml = $webService->get(['resource'   => 'orders', 'display' => 'full', 'filter[id]' => "[$idordine]"]);
        //REF. - ID PREST. - N BOLLA - PAGAMENTO - DATA ORDINE - PREZZO PAGATO
        $data .= strtoupper($xml->orders->order->reference) . '|-|' . strtoupper($xml->orders->order->id) . '|-|BC' . $xml->orders->order->delivery_number .  '|-|' . $xml->orders->order->payment . '|-|' . substr($xml->orders->order->date_add, 0, strlen($xml->orders->order->date_add) - 9) . '|-|' . number_format((float) $xml->orders->order->total_paid_real, 2) .  '|/|';
        $idind = $xml->orders->order->id_address_delivery;
        $idsto = $xml->orders->order->current_state;
        $idcorr = $xml->orders->order->id_carrier;

        //INDIRIZZI
        $xml2 = $webService->get(['resource' => 'addresses', 'display' => 'full', 'filter[id]' => "[$idind]"]);
        // NOMECOGN - VIA - CAP - CITTA - CELLULARE - TELEFONO
        $data .= strtoupper($xml2->addresses->address->firstname) . ' ' . strtoupper($xml2->addresses->address->lastname)  . '|-|' . strtoupper($xml2->addresses->address->address1) . '|-|' . strtoupper($xml2->addresses->address->postcode) . '|-|' . strtoupper($xml2->addresses->address->city) . '|-|' . $xml2->addresses->address->phone_mobile . '|-|' . $xml2->addresses->address->phone . '|-|';
        $idcust = $xml2->addresses->address->id_customer;

        //CLIENTI
        $xml3 = $webService->get(['resource' => 'customers', 'display' => 'full', 'filter[id]' => "[$idcust]"]);
        // EMAIL
        $data .= $xml3->customers->customer->email . '|/|';

        //STATO ORDINI
        $xml4 = $webService->get(['resource' => 'order_states', 'display' => 'full', 'filter[id]' => "[$idsto]"]);
        //STATO ORDINE
        $data .=  $xml4->order_states->order_state->name->language . '|/|';

        //CORRIERI
        $xml5 = $webService->get(['resource' => 'carriers', 'display' => 'full', 'filter[id]' => "[$idcorr]"]);
        //CORRIERE
        $data .=  $xml5->carriers->carrier->name . '|/|';

        //DATI SPEDIZIONE
        $xml6 = $webService->get(['resource' => 'order_carriers', 'display' => '[weight,shipping_cost_tax_incl]', 'filter[id_order]' => "[$idordine]"]);
        //PESO SPED. - PREZZO SPED.
        $data .=  $xml6->order_carriers->order_carrier->weight . '|-|' . $xml6->order_carriers->order_carrier->shipping_cost_tax_incl . '|/|';

        //NOTE DI ERRORE VARIE
        $data .= 'OK' . '|/|';
        echo $data;
        exit;
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
} else if ($azione == 'prodordprest') {
    $idordine = $_POST['idordine'];
    $prodotti = '';
    $classe = '';

    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);

    //PRODOTTI
    $xml = $webService->get(['resource' => 'orders', 'display' => 'full', 'filter[id]' => "[$idordine]"]);

    for ($x = 0; $x <= count($xml->orders->order->associations->order_rows->order_row) - 1; $x++) { //product_id, product_quantity, unit_price_tax_incl

        $prodotto['codice'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_reference;
        $prodotto['quantita'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_quantity;
        $prodotto['nome'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_name;

        $sql = "SELECT ID, sku, nome, disponibilita, tipo FROM neg_magazzino WHERE sku='" . $prodotto['codice'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['disponibilita'] >= $prodotto['quantita']) {
                    $classe = 'class="font-weight-bolder text-success"';
                } else {
                    $classe = 'class="font-weight-bolder text-danger"';
                }

                $prodotti .= '<tr prr="' . $x . '">
                        <td><input id="ipr' . $x . '" type="text" class="form-control" value="' . $row['ID'] . '" readonly></td>
                        <td><a ' . $classe . '>' . $row['sku'] . '</a></td>
                        <td>' . $row['nome'] . '</td>
                        <td><input id="qpr' . $x . '" type="text" class="form-control" value="' . $prodotto['quantita'] . '" readonly></td>
                        <td><input id="tpr' . $x . '" type="text" class="form-control" value="' . $row['tipo'] . '" readonly></td>
                        <tr>';
            }
        }
    }

    echo $prodotti . '|-|' . $x;
    exit;
} else if ($azione == 'modificastock') {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    $xml = $webService->get(['resource' => 'products', 'display' => 'full', 'filter[id]' => "[" . $_POST['idpr'] . "]"]);
    $id = $xml->products->product->associations->stock_availables->stock_available->id;
    try {
        $xml = $webService->get(array('resource' => 'stock_availables', 'id' => $id));
        $xml->stock_available->quantity = $_POST['quant'];
        $opt['putXml'] = $xml->asXML();
        $opt['id'] = $id;
        $opt['resource'] = 'stock_availables';
        $xml = $webService->edit($opt);
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
    echo "ok";
    exit;
} else if ($azione == 'attdisatt') {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);

    $resource_id = $_POST['id'];
    $st = $_POST['st'];

    $xml_2 = $webService->get([
        'url' => PS_SHOP_PATH . "/api/products/$resource_id"
    ]);
    $products = $xml_2->children()->children();
    $products->active = (string)$st;

    unset($products->position_in_category);
    unset($products->manufacturer_name);
    unset($products->id_default_combination);
    unset($products->quantity);

    $opt = [
        'resource' => 'products',
        'putXml' => $xml_2->asXML(),
        'id' => (int)$resource_id
    ];
    try {
        $res = $webService->edit($opt);
        echo 'ok';
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
    exit;
} else if ($azione == 'changereference') {
    try {
        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
        $resource_id = $_POST['id'];
        $ref = $_POST['ref'];

        $xml_2 = $webService->get([
            'url' => PS_SHOP_PATH . "/api/products/$resource_id"
        ]);
        $products = $xml_2->children()->children();
        $products->reference = (string)$ref;

        unset($products->position_in_category);
        unset($products->manufacturer_name);
        unset($products->id_default_combination);
        unset($products->quantity);

        $opt = [
            'resource' => 'products',
            'putXml' => $xml_2->asXML(),
            'id' => (int)$resource_id
        ];
        try {
            $res = $webService->edit($opt);
        } catch (PrestaShopWebserviceException $ex) {
            echo 'Errori: <br />' . $ex->getMessage();
            exit;
        }
    } catch (Exception $e) {
        echo "Error : {$e->getMessage()}";
        exit;
    }
    echo "ok";
    exit;
} else if ($azione == 'changeean') {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    $resource_id = $_POST['id'];
    $ean = $_POST['ref'];

    $xml_2 = $webService->get([
        'url' => PS_SHOP_PATH . "/api/products/$resource_id"
    ]);
    $products = $xml_2->children()->children();
    $products->ean13 = (string)$ean;

    unset($products->position_in_category);
    unset($products->manufacturer_name);
    unset($products->id_default_combination);
    unset($products->quantity);

    $opt = [
        'resource' => 'products',
        'putXml' => $xml_2->asXML(),
        'id' => (int)$resource_id
    ];
    try {
        $res = $webService->edit($opt);
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
    echo "ok";
    exit;
} else if ($azione == 'changeprice') {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    $resource_id = $_POST['id'];
    $price = $_POST['ref'];

    $xml_2 = $webService->get([
        'url' => PS_SHOP_PATH . "/api/products/$resource_id"
    ]);
    $products = $xml_2->children()->children();
    $products->price = floatval($price);

    unset($products->position_in_category);
    unset($products->manufacturer_name);
    unset($products->id_default_combination);
    unset($products->quantity);

    $opt = [
        'resource' => 'products',
        'putXml' => $xml_2->asXML(),
        'id' => (int)$resource_id
    ];
    try {
        $res = $webService->edit($opt);
    } catch (PrestaShopWebserviceException $ex) {
        echo 'Errori: <br />' . $ex->getMessage();
        exit;
    }
    echo "ok";
    exit;
} else if ($azione == 'nuovoprodotto') {
    try {

        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
        $opt = array('resource' => 'products');
        $xml = $webService->get(array('url' => PS_SHOP_PATH . '/api/products?schema=blank'));
        $resource_product = $xml->children()->children();
    
        unset($resource_product->id);
        unset($resource_product->position_in_category);
        unset($resource_product->id_manufacturer);
        unset($resource_product->id_default_combination);
        unset($resource_product->associations);
    
        $resource_product->id_shop_default = 1;
        $resource_product->minimal_quantity = 1;
        $resource_product->available_for_order =  $_POST['m']; //quantita magazzino
        $resource_product->show_price = 1;
        $resource_product->id_category_default = 2905;   // PRODUCTOS COMO CATEGORÍA RAIZ
        $resource_product->price = $_POST['pvp'];  
        $resource_product->active = 1;
        $resource_product->visibility = 'both';
        $resource_product->name->language[0] = $_POST['np']; 
        $resource_product->description->language[0] = $_POST['dp'];
        $resource_product->state = 1;
        
        $resource_product->reference =  $_POST['cp'];
        $resource_product->ean13 =  $_POST['ep'];
        $resource_product->id_tax_rules_group =  53;
        $resource_product->additional_delivery_times =  1;
        $resource_product->available_now->language[0] = `PRONTA CONSEGNA`;
        $resource_product->redirect_type = 404;
        $resource_product->pack_stock_type = 3;
    
        // CHAPTER 1 // ADDING NEW PRODUCTS... IN FEW WAYS -> NO SUCCESS!!
    
        $resource_product->addChild('associations')->addChild('categories')->addChild('category')->addChild('id', 6);
        $resource_product->addChild('associations')->addChild('categories')->addChild('category')->addChild('id', '7');
        $resource_product->addChild('associations')->addChild('categories')->addChild('category')->addChild('id', "8");
        //$resource_product->associations->categories->category->addChild('id', 5);
    
        $opt = array('resource' => 'products');
        $opt['postXml'] = $xml->asXML();
        $xml = $webService->add($opt);
        $id = $xml->product->id;
        echo "<p>PRODUCTO " . $id . " AÑADIDO</p>";
    
        //set_product_quantity(100,$id,);
    
        // CHAPTER 2 // UPDATING PRODUCT -> NO SUCCESS!!
    
        $new_product_categories = array(29, 30, 31); // List of categories to be linked to product
        $xml = $webService->get(array('resource' => 'products', 'id' => $id));
        $product = $xml->children()->children();
    
        // Unset fields that may not be updated
        unset($product->manufacturer_name);
        unset($product->quantity);
    
        // Remove current categories
        unset($product->associations->categories);
    
        // Create new categories
        $categories = $product->associations->addChild('categories');
    
        foreach ($new_product_categories as $id_category) {
            $category = $categories->addChild('category');
            $category->addChild('id', $id_category);
        }
    
        $xml_response = $webService->edit(array('resource' => 'products', 'id' => $id, 'putXml' => $xml->asXML()));
    
        echo "<p>PRODOTTO " . $xml_response->product->id. "  ACTUALIZADO CON CATEGORIAS</p>";
    
    
    } catch (PrestaShopWebserviceException $e) {
        // Here we are dealing with errors
        $trace = $e->getTrace();
        if ($trace[0]['args'][0] == 404) echo 'ID non valido';
        else if ($trace[0]['args'][0] == 401) echo 'Chiave non valida';
        else echo '<b>ERRORE:</b> ' . $e->getMessage();
    }
}
