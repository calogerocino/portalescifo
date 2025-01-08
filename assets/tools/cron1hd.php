<?php
ini_set('max_execution_time', 0);

$logghi = '';

include_once((dirname(dirname(__FILE__)) . '/inc/database.php'));
define('DEBUG', false);
define('PS_SHOP_PATH', 'http://scifostore.com');
define('PS_WS_AUTH_KEY', '5ZHVUQ9EMAP5KX9B28GVPDD41PDX7ZD2');
include_once((dirname(dirname(__FILE__)) . '/inc/PSWebServiceLibrary.php'));


$data = '';
$totord = 0;
$totordes = 0;

try {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    // ========== CERCA ORDINI ========== //

    //ORDINI
    $xml = $webService->get(['resource' => 'orders', 'display' => 'full']);

    $totord += count($xml->orders->order);
    $i = 0;
    while ($i <= $totord + 1) {
        $i += 1;
        if ($xml->orders->order[$i]->current_state == 2) {
            $idord =  $xml->orders->order[$i]->id;

            $check_exist_S = "SELECT ID FROM `donl_ordini` WHERE IDPS=" . $idord;
            $check_exist_R = $conn->query($check_exist_S);
            if ($check_exist_R->num_rows >= 1) {
                $logghi .= 'Ordine ID: <b>' . $idord . '</b> già esistente<br/>';
                continue;
            } else {
                $totordes += 1;
                //REF. - ID PREST. - N BOLLA - PAGAMENTO - DATA ORDINE - PREZZO PAGATO
                $ordine['ref'] = strtoupper($xml->orders->order[$i]->reference);
                $ordine['idpr'] =  strtoupper($xml->orders->order[$i]->id);
                $ordine['bolla'] = 'BC' . $xml->orders->order[$i]->delivery_number;
                $ordine['pagamento'] =  $xml->orders->order[$i]->payment;
                $ordine['dataordine'] =  substr($xml->orders->order[$i]->date_add, 0, strlen($xml->orders->order[$i]->date_add) - 9);
                $ordine['prezzopagato'] = number_format((float) $xml->orders->order[$i]->total_paid_real, 2);

                $idind = $xml->orders->order[$i]->id_address_delivery;
                $idcorr = $xml->orders->order[$i]->id_carrier;

                //INDIRIZZI
                $xml2 = $webService->get(['resource' => 'addresses', 'display' => 'full', 'filter[id]' => "[$idind]"]);
                // NOMECOGN - VIA - CAP - CITTA - CELLULARE - TELEFONO
                $cliente['cliente'] =  strtoupper($xml2->addresses->address->firstname)  . ' ' .  strtoupper($xml2->addresses->address->lastname);
                $cliente['indirizzo'] = strtoupper($xml2->addresses->address->address1);
                $cliente['cap'] = strtoupper($xml2->addresses->address->postcode);
                $cliente['citta'] = strtoupper($xml2->addresses->address->city);
                $cliente['cellulare'] = $xml2->addresses->address->phone_mobile;
                $cliente['telefono'] = $xml2->addresses->address->phone;

                $idcust = $xml2->addresses->address->id_customer;

                //CLIENTI
                $xml3 = $webService->get(['resource' => 'customers', 'display' => 'full', 'filter[id]' => "[$idcust]"]);
                // EMAIL
                $cliente['email'] = $xml3->customers->customer->email;

                //CORRIERI
                $xml5 = $webService->get(['resource' => 'carriers', 'display' => 'full', 'filter[id]' => "[$idcorr]"]);
                //CORRIERE
                $ordine['corriere'] = $xml5->carriers->carrier->name;

                //DATI SPEDIZIONE
                $xml6 = $webService->get(['resource' => 'order_carriers', 'display' => '[weight,shipping_cost_tax_incl]', 'filter[id_order]' => "[$idord]"]);
                //PESO SPED. - PREZZO SPED.
                $ordine['peso'] =  $xml6->order_carriers->order_carrier->weight;
                $ordine['costosped'] = $xml6->order_carriers->order_carrier->shipping_cost_tax_incl;


                // ========== CARICA CLIENTE ========== //
                $sql = "SELECT ID FROM donl_clienti WHERE Cliente='" . addslashes($cliente['cliente']) . "' AND Via='" . addslashes($cliente['indirizzo']) . "' LIMIT 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $cliente['id'] = $row["ID"];
                    }
                } else {
                    $sql = "INSERT INTO donl_clienti (Cliente, Via, CAP, Citta, Telefono, Cellulare, EMail) VALUES ("
                        . "'" . addslashes($cliente['cliente']) . "'," . "'" . addslashes($cliente['indirizzo']) . "',"
                        . "'" . addslashes($cliente['cap']) . "'," . "'" . addslashes($cliente['citta']) . "',"
                        . "'" . $cliente['telefono'] . "'," . "'" . $cliente['cellulare'] . "'," . "'" . $cliente['email'] . "')";
                    if (!$conn->query($sql)) {
                        $logghi .= "Errore della query: " . $conn->error;
                    } else {
                        $logghi .= 'Cliente creato<br />';
                        $sql = "SELECT ID FROM donl_clienti WHERE Cliente='" . addslashes($cliente['cliente']) . "' AND Via='" . addslashes($cliente['indirizzo']) . "' LIMIT 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $cliente['id'] = $row["ID"];
                            }
                        }
                    }
                }

                // ========== CHECK MARKETPLACE ========== //
                if (strstr($ordine['pagamento'], 'ePrice')) {
                    $ordine['piattaforma'] = 'ePrice';
                    $ordine['pagamento'] = 'ePrice';
                    $res = explode(' ', $ordine['pagamento']);
                    $ordine['idmarket'] = $res[1];
                } else {
                    if (strstr($ordine['pagamento'], 'eBay')) {
                        $ordine['pagamento'] = 'eBay';
                        $ordine['piattaforma'] = 'eBay';
                        $ordine['idmarket'] = '';
                    } else {
                        if (strstr($ordine['pagamento'], "ManoMano")) {
                            $ordine['pagamento'] = "ManoMano";
                            $ordine['piattaforma'] = 'ManoMano';
                            $ordine['idmarket'] = '';
                        } else {
                            $ordine['piattaforma'] = 'Sito';
                            $ordine['idmarket'] = '';
                        }
                    }
                }
                // ========== CARICA ORDINE ========== //
                $sql = "INSERT INTO `donl_ordini`(`NOrdine`, `NFattura`, `IDMarketplace`, `Tracking`, `Piattaforma`, `DataOrdine`, `Corriere`, `Tipo`, `Stato`, `Noteo`, `DataEvasione`, `Pagamento`, `Importo`, `IDCl`, `IDPS`, `DataCreazione`) VALUES ("
                    . "'" . $ordine['ref'] . "',"
                    . "'" . $ordine['bolla'] . "',"
                    . "'" . $ordine['idmarket'] . "',"
                    . "'',"
                    . "'" . $ordine['piattaforma'] . "',"
                    . "'" . $ordine['dataordine'] . "',"
                    . "'" . $ordine['corriere'] . "',"
                    . "'SPEDIZIONE',"
                    . "'Importato',"
                    . "'',"
                    . "'Non Evaso',"
                    . "'" . $ordine['pagamento'] . "',"
                    . "'" . $ordine['prezzopagato'] . "',"
                    . "'" .  $cliente['id'] . "',"
                    . "'" .  $ordine['idpr'] . "',"
                    . "NOW())";

                if (!$conn->query($sql)) {
                    $logghi .= 'Errore ' . $conn->error;
                } else {
                    $logghi .= 'Ordine creato<br />';
                    //CERCA ID ORDINE
                    $sql = "SELECT ID FROM donl_ordini WHERE NOrdine='" .  $ordine['ref'] . "' AND NFattura='" . $ordine['bolla'] . "' LIMIT 1";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $ordine['idapp'] = $row["ID"];
                    $conn->query("INSERT INTO `donl_stato_ordine`(`IDO`, `Data_stato`, `IDS`) VALUES (" . $row['ID'] . ",NOW(), 12)");
                    $logghi .= 'Stato ordine<br />';
                }

                // ========== CARICA CORRIERE ========== //
                $sql = "INSERT INTO `donl_corriere`(`ID`, `PesoReale`, `PrezzoInserito`) VALUES ("
                    . "'" . $ordine['idapp'] . "', '" . $ordine['peso'] . "', '" . $ordine['costosped'] . "')";

                if (!$conn->query($sql)) {
                    $conn->error;
                } else {
                    $logghi .= 'Corriere caricato<br />';
                }


                // ========== CARICA PRODOTTI ========== //
                $idprodotto = $ordine['idpr'];
                $xml = $webService->get(['resource' => 'orders', 'display' => 'full', 'filter[id]' => "[$idprodotto]"]);

                for ($x = 0; $x <= count($xml->orders->order->associations->order_rows->order_row) - 1; $x++) { //product_id, product_quantity, unit_price_tax_incl
                    $prodotto['codice'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_reference;
                    $prodotto['quantita'] = $xml->orders->order->associations->order_rows->order_row[$x]->product_quantity;
                    $sql = "SELECT ID FROM neg_magazzino WHERE sku='" . $prodotto['codice'] . "'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sql = 'INSERT INTO `neg_relpo`( `IDP`, `IDO`, `quantita`) VALUES ("' .  $row['ID'] . '", "' . $ordine['idapp'] . '", "' . $prodotto['quantita'] . '")';
                            if (!$conn->query($sql)) {
                                $logghi .= 'Errore: ' . $conn->error . ' <br />';
                            } else {
                                $sql1 = 'SELECT sku, disponibilita, tipo FROM neg_magazzino WHERE ID="' .  $row['ID'] . '"';
                                $result1 = $conn->query($sql1);
                                if ($result1->num_rows > 0) {
                                    while ($row1 = $result1->fetch_assoc()) {
                                        if ($row1['disponibilita'] >= $prodotto['quantita']) {
                                            $logghi .= 'Prodotto caricato<br />';
                                        } else {
                                            if ($row1['tipo'] == 1) {
                                                $sql1 = 'INSERT INTO `neg_ordine_prodotti` (`IDPR`, `Quantita`, `Codice`, `IDO`, `Tipo`) VALUES ("' .   $row['ID'] . '",  "' . $prodotto['quantita'] . '", "' . $row1['sku'] . '", "' . $ordine['idapp'] . '", 1)';
                                                if (!$conn->query($sql1)) {
                                                    $logghi .= 'Errore: ' . $conn->error . ' <br />';
                                                } else {
                                                    $logghi .= 'Prodotto caricato + ordine prodotto<br />';
                                                }
                                            } else {
                                                $logghi .= 'Prodotto caricato<br />';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                // ========== STATO ORDINE ========== //
                $id_order =  $ordine['idpr'];

                $opt = array(
                    'resource'   => 'orders',
                    'display'    => 'full',
                    'filter[id]' => $id_order
                );
                $xml = $webService->get($opt);
                if ($xml->orders->order->current_state != 3) { //SE LO STATO NON E' PREPARAZIONE IN CORSO
                    try {
                        $opt = [
                            'resource' => 'order_histories?schema=blank'
                        ];
                        $xml = $webService->get($opt);
                        $resources = $xml->children()->children();
                        $resources->id_order = $id_order;
                        $resources->id_employee = 0;
                        $resources->id_order_state = 3;
                        $opt = [
                            'resource' => 'order_histories',
                            'postXml' => $xml->asXML()
                        ];
                        $webService->add($opt);
                        $logghi .= "L ordine ID [$id_order] è stato aggiornato.<br>";
                    } catch (Exception $e) {
                        echo "Errore : {$e->getMessage()}";
                    }
                }
            }
        }
    }
} catch (PrestaShopWebserviceException $ex) {
    echo 'Errori: ' . $ex->getMessage();
    exit;
}

echo ($totordes == 0 ? '<b>Nessun ordine da importare</b><br/><br/>' : 'Ordini importati ' . $totordes . ' su ordini totali ' .  $totord . '<br/><br/>');
echo (DEBUG == true ? $logghi : '');
exit;
