<!-- <!DOCTYPE html>
<html lang="it"> -->

<?php
$al = 'https://' . $_SERVER['HTTP_HOST'] . '/v3';

$session_idu = htmlspecialchars($_SESSION['session_idu']);
if (isset($_GET['idpr'])) {
    $idpr = $_GET['idpr'];

    // STATISTICHE PRODOTTO
    include("../inc/database.php");
    $anno = date("Y");
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $i = "0" . $i;
        }

        $result = $conn->query("SELECT SUM(r.quantita) as Totale FROM donl_ordini o INNER JOIN neg_relpo r ON (r.IDO=o.ID) WHERE r.IDP=$idpr AND o.DataOrdine LIKE '$anno-$i-%'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            (!empty($row['Totale'])) ? $dt .= $row["Totale"] . ", " : $dt .=  '0, ';
        }
        $anno1 = (date("Y") - 1);
        $result = $conn->query("SELECT SUM(r.quantita) as Totale FROM donl_ordini o INNER JOIN neg_relpo r ON (r.IDO=o.ID) WHERE r.IDP=$idpr AND o.DataOrdine LIKE '$anno1-$i-%'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            (!empty($row['Totale'])) ? $dt1 .= $row["Totale"] . ", " : $dt1 .=  '0, ';
        }
    }
    $dt = substr($dt, 0, -2);
    $dt1 = substr($dt1, 0, -2);
    // FINE STATISTICHE PRODOTTO
}
if (isset($_GET['nuovo'])) {
    $idpr = 'nuovo';
}

?>

<div class="col-12">
    <div class="row">
        <div class="col-md-4 col-sm-12" id="ImmagineProdotto" hidden>
        </div>
        <div class="col-md-4 col-sm-12 mt-auto mb-auto" id="formupload">
            <button href="javascript:void(0);" class="btn btn-light btn-icon-split CaricaAllegato_off">
                <span class="icon text-gray-600">
                    <i class="far fa-image"></i>
                </span>
                <span class="text">Carica immagine</span>
            </button>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="col-md-12 col-sm-12">
                <div class="form-floating mb-3"><input class="form-control" id="nomeprod" type="text" placeholder="Nome prodotto" onchange="AggiornaProdotto('nomeprod',true)"><label for="nomeprod">* Nome prodotto</label></div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="search-box" data-list="" style="width:auto!important">
                        <div class="position-relative" data-bs-toggle="search" data-bs-display="static" aria-expanded="false">
                            <div class="form-floating mb-3">
                                <input class="form-control search-input-f" id="riferimento_off" type="text" placeholder="Codice prodotto" aria-label="Cerca" onchange="AggiornaProdotto('riferimento_off',true)" autocomplete="off"">
                                <label for=" riferimento_off">* Codice prodotto</label>
                            </div>
                        </div>
                        <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none" data-bs-dismiss="search">
                            <div class="btn-close-falcon" aria-label="Chiudi"></div>
                        </div>
                        <div class="dropdown-menu border font-base start-0 py-0 overflow-hidden w-100">
                            <div class="scrollbar list py-3" style="max-height: 24rem;">
                                <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">Generazione codice</h6>
                                <a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)">
                                    <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-warning" style="cursor:pointer;" onclick="GeneraCodice(0)">Codice negozio</span>
                                        <div class="flex-1 fs--1 title CodMaOf" id="CoDMa" style="cursor:pointer;"></div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)">
                                    <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-success" style="cursor:pointer;" onclick="GeneraCodice(1)">Codice officina</span>
                                        <div class="flex-1 fs--1 title CodMaOf" id="CoDOf" style="cursor:pointer;"></div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)">
                                    <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-info" style="cursor:pointer;" onclick="GeneraCodice(3)">Codice Minicar</span>
                                        <div class="flex-1 fs--1 title CoDMaMi" id="CoDMi" style="cursor:pointer;"></div>
                                    </div>
                                </a>
                            </div>
                            <div class="text-center mt-n3">
                                <p class="fallback fw-bold fs-1 d-none">Nessun risultato.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="search-box" data-list="" style="width:auto!important">
                        <div class="position-relative" data-bs-toggle="search" data-bs-display="static" aria-expanded="false">
                            <div class="form-floating mb-3">
                                <input class="form-control search-input-f" id="ean" type="text" placeholder="Codice prodotto" aria-label="Cerca" onchange="AggiornaProdotto('ean',true)" autocomplete="off"">
                                <label for=" ean">* UPC prodotto</label>
                            </div>
                        </div>
                        <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none" data-bs-dismiss="search">
                            <div class="btn-close-falcon" aria-label="Chiudi"></div>
                        </div>
                        <div class="dropdown-menu border font-base start-0 py-0 overflow-hidden w-100">
                            <div class="scrollbar list py-3" style="max-height: 24rem;">
                                <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">Genera UPC</h6>
                                <a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)">
                                    <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-warning" style="cursor:pointer;" onclick="GeneraCodice(2)">UPC -></span>
                                        <div class="flex-1 fs--1 title CoDUpcC" id="CoDUpc" style="cursor:pointer;"></div>
                                    </div>
                                </a>
                            </div>
                            <div class="text-center mt-n3">
                                <p class="fallback fw-bold fs-1 d-none">Nessun risultato.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-floating mb-3"><input class="form-control" id="prezzove1" type="text" placeholder="Prezzo di vendita" readonly><label for="prezzove1">Vendita</label></div>
                </div>
            </div>
            <div class="row" id="MarketZone"></div>
        </div>
        <div class="col-md-2 col-sm-12 mt-4" style="float:left;min-height: 400px;color:white;">
            <ul id="artSezList" class="nav nav-pills mb-3" role="tablist" style="list-style: none;margin: 0px;padding: 0px;width: 100%;">
                <li><a class="nav-link active" id="product-details-tab" data-bs-toggle="tab" href="#product-details" role="tab" aria-controls="product-details-tab" aria-selected="true"><i class="fa-regular fa-info-circle"></i> Informazioni</a></li>
                <li><a class="nav-link" id="prezzi-product-details-tab" data-bs-toggle="tab" href="#prezzi-product-details" role="tab" aria-controls="prezzi-product-details-tab" aria-selected="false"><i class="fa-regular fa-euro-sign"></i> Prezzi</a></li>
                <li><a class="nav-link" id="supp-product-details-tab" data-bs-toggle="tab" href="#supp-product-details" role="tab" aria-controls="supp-product-details-tab" aria-selected="false"><i class="fa-regular fa-industry"></i> Fornitori</a></li>
                <li><a class="nav-link" id="more-product-details-tab" data-bs-toggle="tab" href="#more-product-details" role="tab" aria-controls="more-product-details-tab" aria-selected="false"><i class="fa-regular fa-cubes"></i> Magazzino</a></li>
                <li><a class="nav-link" id="prestashop-tab" data-bs-toggle="tab" href="#prestashop-details" role="tab" aria-controls="prestashop-details-tab" aria-selected="false"><i class="fa-solid fa-shop"></i> Prestashop</a></li>
                <li><a class="nav-link" id="combinazioni-details-tab" data-bs-toggle="tab" href="#combinazioni-product-details" role="tab" aria-controls="combinazioni-product-details-tab" aria-selected="false" hidden><i class="fa-regular fa-table-list"></i> Combinazioni</a></li>
                <li><a class="nav-link" id="impostazioni-details-tab" data-bs-toggle="tab" href="#impostazioni-product-details" role="tab" aria-controls="impostazioni-product-details-tab" aria-selected="false"><i class="fa-regular fa-gear"></i> Impostazioni</a></li>
                <li><a class="btn btn-danger me-1 mb-1 propre_sincronizza" valore="NO" href="javascript:void(0)"></i>Sincronizza: NO</a></li>
            </ul>
        </div>
        <div class="col-md-10 col-sm-12">
            <div class="tab-content border p-3 mt-3" id="pill-myTabContent">
                <div class="tab-pane fade show active" id="product-details" role="tabpanel" aria-labelledby="product-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mt-2 mb-2" id="descrprod">* Descrizione breve</h5>
                            <div class="min-vh-50">
                                <textarea id="tmce-DP" class="tinymce d-none" name="content"></textarea>
                            </div>

                            <h5 class="mt-2 mb-2" id="descrprod">* Descrizione lunga</h5>
                            <div class="min-vh-50">
                                <textarea id="tmce-DL" class="tinymce d-none" name="content"></textarea>
                            </div>
                            <h5 class="mt-2 mb-2" for="tag">TAG</h5>
                            <input class="form-select js-choice" id="tag" type="text" placeholder="Tag prodotto" style="width: 100%;" data-options='{"removeItemButton":true,"placeholder":true, "delimiter": ",","editItems": true,"maxItemCount": 100}' onchange="AggiornaProdotto('tag',true)" />
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="prezzi-product-details" role="tabpanel" aria-labelledby="prezzi-tab">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 me-auto">
                            <h5 class="mt-2 mb-2">Prezzi vendita al banco</h5>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzove2">Prezzo vendita al banco €</span>
                                <input class="form-control text-end" type="text" aria-label="Prezzo vendita al banco" aria-describedby="Prezzo vendita al banco" id="prezzove2" autocomplete="off" onchange="CalcolaPrezzo3_sp()" />
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="impvend">Imponibile di vendita €</span>
                                <input class="form-control text-end" type="text" aria-label="Imponibile di vendita" aria-describedby="Imponibile di vendita" id="impvend" autocomplete="off" onkeyup="CalcolaPrezzo_sp()" />
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="ricarico">Ricarico %</span>
                                <input class="form-control text-end" type="text" aria-label="Ricarico" aria-describedby="Ricarico" id="ricarico" autocomplete="off" onkeyup="CalcolaPrezzo_sp()" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 ms-auto">
                            <h5 class="mt-2 mb-2">Prezzi marketplace</h5>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzomark">Prezzo marketplace €</span>
                                <input class="form-control text-end" type="text" aria-label="Prezzo marketplace" aria-describedby="Prezzo marketplace" id="prezzomark" autocomplete="off" onchange="CalcolaPrezzo2_sp()" />
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzomarksi">Imponibile di vendita €</span>
                                <input class="form-control text-end" type="text" aria-label="Imponibile di vendita" aria-describedby="Imponibile di vendita" id="prezzomarksi" autocomplete="off" onchange="CalcolaPrezzo2_sp()" readonly />
                            </div>
                            <div style="height: 20px;"></div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="percmarket">Percentuale commissioni %</span>
                                <input class="form-control text-end" type="text" value="12" aria-label="Percentuale commissioni" aria-describedby="Percentuale commissioni" id="percmarket" autocomplete="off" onchange="CalcolaPrezzo2_sp()" />
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="commissionimarket">Importo commissioni €</span>
                                <input class="form-control text-end" type="text" aria-label="Importo commissioni" aria-describedby="Importo commissioni" id="commissionimarket" autocomplete="off" readonly />
                            </div>
                            <div style="height: 20px;"></div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzospedica">Importo spedizione €</span>
                                <input class="form-control text-end" type="text" aria-label="Importo spedizione" aria-describedby="Importo spedizione" id="prezzospedica" autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12" style="float:left">
                            <h5 class="mt-2 mb-2">Acquisto fornitore</h5>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzove2">Prezzo acquisto €</span>
                                <input class="form-control text-end" type="text" aria-label="Prezzo acquisto" aria-describedby="Prezzo acquisto" id="prezforn" onkeyup="CalcolaPrezzo_sp()" autocomplete="off" />
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="impiva">Impostazioni IVA</span>
                                <select class="form-control text-end selectpicker" data-style="btn btn-link" id="impiva" onchange="CalcolaPrezzo_sp()" aria-label="Impostazioni IVA" aria-describedby="Impostazioni IVA">
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" for="prezzoiva">Imponibile acquisto €</span>
                                <input class="form-control text-end" type="text" aria-label="Imponibile di vendita" aria-describedby="Imponibile di vendita" id="prezzoiva" onkeyup="CalcolaPrezzo_sp()" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="supp-product-details" role="tabpanel" aria-labelledby="supp-tab">
                    <h5 class="mt-2 mb-2">Fornitori</h5>
                    <div class="col-md-12" id="fornitoreabc"></div>
                    <div class="row">
                        <div class="col-6 mt-4">
                            <button class="btn btn-falcon-default btn-sm" onclick="AddRigaFornitori()" type="button"><span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-plus fa-sm"></i> Aggiungi</span></button>
                        </div>
                        <div class="col-6 mt-4 ms-auto text-end">
                            <button class="btn btn-falcon-default btn-sm" type="button" id="aggiorna_forn" onclick="AggiornaFornitori();"><span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-redo-alt"></i> Aggiorna</span></button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="more-product-details" role="tabpanel" aria-labelledby="more-tab">
                    <h5 class="mt-2 mb-2">Magazzino</h5>
                    <div class="row">
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label" for="unitmis">Unità di misura</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="unitmis" aria-label="Unità di misura" aria-describedby="Unità di misura">
                                <option value="PZ">PZ</option>
                                <option value="LT">LT</option>
                                <option value="KG">KG</option>
                                <option value="MT">MT</option>
                                <option value="CM">CM</option>
                                <option value="MG">MG</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label" for="quantmag">Magazzino</label>
                            <div class="input-group input-group flex-nowrap" data-quantity="data-quantity">
                                <button class="btn btn-outline-secondary border-300 px-2" data-type="minus" data-field="quantmag" id="bt_minus_1" onclick="AggiornaProdotto('quantmag',true)">-</button>
                                <input class="form-control text-center px-2 input-spin-none" type="number" min="0" value="1" aria-label="Quantita magazzino" style="width: 50px" id="quantmag" onchange="AggiornaProdotto('quantmag',true)">
                                <button class="btn btn-outline-secondary border-300 px-2" data-type="plus" data-field="quantmag" id="bt_plus_1" onclick="AggiornaProdotto('quantmag',true)">+</button>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label" for="quantprest">Prestashop</label>
                            <div class="input-group input-group flex-nowrap" data-quantity="data-quantity">
                                <button class="btn btn-outline-secondary border-300 px-2" data-type="minus" data-field="quantprest" id="bt_minus_1" onclick="AggiornaProdotto('quantprest',true)" disabled>-</button>
                                <input class="form-control text-center px-2 input-spin-none" type="number" min="-10" value="0" aria-label="Quantita prestashop" style="width: 50px" id="quantprest" onchange="AggiornaProdotto('quantprest',true)" readonly>
                                <button class="btn btn-outline-secondary border-300 px-2" data-type="plus" data-field="quantprest" id="bt_plus_1" onclick="AggiornaProdotto('quantprest',true)" disabled>+</button>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="quantprest">Disponibilità</label>
                            <input id="DataDisponibilita" type="date" class="form-control" onchange="AggiornaProdotto('DataDisponibilita', true)">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="quantprest">Posizione</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="PosMag" aria-label="Posizione magazzino" aria-describedby="Posizione magazzino">
                                <option selected disabled>== IN ARRIVO ==</option>
                            </select>
                        </div>
                    </div>
                    <h5 class="mt-2 mb-2">Spedizione</h5>
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="pesoimb">Peso imballaggio</label>
                            <input id="pesoimb" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="PrezzoSpedizione">Spese di spedizione</label>
                            <input id="PrezzoSpedizione" type="text" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="combinazioni-product-details" role="tabpanel" aria-labelledby="combinazioni-tab">
                    e
                </div>
                <div class="tab-pane fade" id="impostazioni-product-details" role="tabpanel" aria-labelledby="impostazioni-tab">
                    <h5 class="mt-2 mb-2">Impostazioni marketplace</h5>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label" for="prestashopid">ID Prestashop</label>
                        <input id="prestashopid" type="text" class="form-control" onchange="AggiornaProdotto('prestashopid',true)">
                    </div>
                    <h5 class="mt-2 mb-2">Impostazioni magazzino</h5>
                    <div class="row">
                        <div class="col-md-2 col-sm-4">
                            <label class="form-label" for="idprod">ID Catalogo</label>
                            <input id="idprod" type="text" class="form-control" readonly disabled>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="tipomagazzino">Tipo magazzino</label>
                            <button class="btn btn-falcon-default btn-sm" type="button" onclick="AggiornaProdotto('tipomagazzino',true)" id="tipomagazzino"><span class="d-none d-sm-inline-block ms-1"></button>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label" for="mostrata">Mostra in catalogo</label>
                            <button class="btn btn-falcon-default btn-sm" type="button" onclick="AggiornaProdotto('mostrata',true)" id="mostrata"><span class="d-none d-sm-inline-block ms-1"></button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="prestashop-details" role="tabpanel" aria-labelledby="prestashop-tab">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 me-auto">
                            <span>Per sincronizzare correttamente un prodotto su prestashop bisogna compilare obbligatoriamente i seguenti campi:<br><br>
                                • Quantita<br>
                                • Prezzo<br>
                                • Titolo<br>
                                • Descrizione<br>
                            </span>
                            <!-- <div class="mt-2 mb-2"><b> Stato prodotto: </b>
                                <div id="statuspropre">Offline <span style="color: red;">●</span></div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    var idpr = '<?php echo $idpr ?>';
    var myArr1 = new Array();
    var myArr2 = new Array();
    var myArr3 = new Array();
    countf = 0;
    $(document).ready(function(e) {
        tinymce.remove("#tmce-DP");
        tinymce.remove("#tmce-DL");
        docReady(tinymceInit('tmce-DP'));
        docReady(tinymceInit('tmce-DL'));
        docReady(searchInit);
        docReady(quantityInit);
        if (idpr == 'nuovo') {
            $('#aggiorna_forn').prop('hidden', true);
            $('#prodpresta').prop('hidden', true);
            $('#vendite-details-tab').prop('hidden', true);
            $('#impostazioni-details-tab').prop('hidden', true);
            $('#classnuovoprod').prop('hidden', false);
            $('.CaricaAllegato_off').attr('id', 'PoPM-');
            $('.CaricaAllegato_off').attr('tt', 'Impossibile aggiungere un immagine, salva prima il prodotto!');
            $('.CaricaAllegato_off').removeClass('CaricaAllegato_off')
            $('#prezzomark').prop('readonly', true).addClass('noinput');
            CaricaIva();
            docReady(choicesInit);
            $('#MarketZone').html('<div class="col-12 d-grid gap-2"><button class="btn btn-outline-primary me-1 mb-1 CreaNuovoProdotto" type="button">CREA PRODOTTO</button></div>');
        } else {
            $('#aggiorna_forn').prop('hidden', false);
            $('#prodpresta').prop('hidden', false);
            $('#classnuovoprod').prop('hidden', true);
            $('.propre_sincronizza').prop('hidden', true);
            CaricaIva();
            CaricaDatiProdotto();
        }
        CaricaArrayOfficina();
    });


    $(".CodMaOf").click(function() {
        $('#riferimento_off').val($(this).text());
        AggiornaProdotto('riferimento_off', true);
    });
    $(".CoDMaMi").click(function() {
        $('#riferimento_off').val($(this).text());
        AggiornaProdotto('riferimento_off', true);
    });
    $(".CoDUpcC").click(function() {
        $('#ean').val($(this).text());
        AggiornaProdotto('ean', true);
    });
</script>