<?php
error_reporting(0);
include("../assets/inc/ordine.php");
?>

<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Visualizza o modifica ordine</h5>
            </div>
        </div>
    </div>
    <div class="card-body position-relative">

        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="dettagli-tab" data-bs-toggle="tab" href="#tab-dettagli" role="tab" aria-controls="tab-dettagli" aria-selected="true">Dettagli</a></li>
                    <li class="nav-item"><a class="nav-link" id="spedizione-tab" data-bs-toggle="tab" href="#tab-spedizione" role="tab" aria-controls="tab-spedizione" aria-selected="false">Spedizione</a></li>
                    <li class="nav-item ms-auto text-end ps-0 me-2"> <button class="btn btn-falcon-default btn-sm nav-link mb-2 abcliente" type="button">
                            <span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-user-pen"></i> Modifica dati cliente</span></button>
                        <div class="row mt-2 mb-2 ml-auto">
                            <div class="gap-2 d-md-flex justify-content-md-end">
                                <button id="bttsalvacliente" class="btn btn-outline-success btn-just-icon sacliente" hidden>
                                    <i class="fa-regular fa-save"></i>
                                </button>
                                <button id="bttdelcliente" class="btn btn-outline-danger btn-just-icon dabcliente" hidden>
                                    <i class="fa-regular fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item text-end ps-0"> <button class="btn btn-falcon-default btn-sm nav-link mb-2 abordine" type="button">
                            <span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-pen-to-square"></i></i> Modifica dati ordine</span></button>
                        <div class="row mt-2 mb-2 ml-auto">
                            <div class="gap-2 d-md-flex justify-content-md-end">
                                <button id="bttsalvadett" class="btn btn-outline-success btn-just-icon saordine" hidden>
                                    <i class="fa-regular fa-save"></i>
                                </button>
                                <button id="bttdeldett" class="btn btn-outline-danger btn-just-icon dabordine" hidden>
                                    <i class="fa-regular fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="tab-content border-x border-bottom p-3" id="myTabContent">
                    <div class="tab-pane fade active show" id="tab-dettagli" role="tabpanel" aria-labelledby="dettagli-tab">
                        <div class="row">
                            <div class="col-lg-12 pe-xl-2 pb-2">
                                <div class="form-floating"><textarea class="form-control" id="note" placeholder=" " style="height: 87px; margin-top: 0px; margin-bottom: 0px;" onchange="AggiornaNoteOrdine()"><?php echo $ordine['noteo']; ?></textarea><label for="note_no">Note</label></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 pe-xl-2">
                                <div class="card mb-auto">
                                    <div class="bg-holder d-none d-lg-block bg-card"></div>
                                    <div class="card-header">
                                        <div class="row flex-between-center">
                                            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-user"></i> Dettagli cliente</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <input name="idcliente" id="idcliente" type="text" value="<?php echo $cliente['id']; ?>" hidden>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3"><input class="form-control" id="cliente" type="text" placeholder="Nominativo cliente" value="<?php echo $cliente['cliente'] ?>"><label for="cliente">Cliente</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3"><input class="form-control" id="indirizzo" type="text" placeholder="Indirizzo cliente" value="<?php echo $cliente['via'] ?>"><label for="indirizzo">Indirizzo</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3"><input class="form-control" id="cap" type="text" placeholder="CAP cliente" value="<?php echo $cliente['cap'] ?>"><label for="cap">CAP</label></div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="form-floating mb-3"><input class="form-control" id="citta" type="text" placeholder="Città cliebnte" value="<?php echo $cliente['citta'] ?>"><label for="citta">Città</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="cellulare" type="tel" placeholder="1° Cellulare" value="<?php echo $cliente['cellulare']; ?>"><label for="cellulare">Cellulare</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="cellulare2" type="text" placeholder="2° Cellulare" value="<?php echo $cliente['cellulare2'] ?>"><label for="cellulare2">Secondo cellulare</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="telefono" type="tel" placeholder="Recapito telefonico" value="<?php echo $cliente['telefono']; ?>"><label for="telefono">Telefono</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="email" type="email" placeholder="E-Mail cliente" value="<?php echo $cliente['email']; ?>"><label for="email">E-Mail</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 pe-xl-2">
                                <div class="card mb-auto">
                                    <div class="bg-holder d-none d-lg-block bg-card"></div>
                                    <div class="card-header">
                                        <div class="row flex-between-center">
                                            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-circle-info"></i> Dettagli ordine</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <input name="idordine" id="idordine" type="text" value="<?php echo $ordine['id']; ?>" hidden>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="form-floating mb-3"><input class="form-control" id="refordine" type="text" placeholder="Riferimento ordine" value="<?php echo $ordine['riferimento']; ?>"><label for="refordine">Riferimento</label></div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3"><input class="form-control" id="idpresta" type="text" placeholder="ID Prestashop" value="<?php echo $ordine['idpresta']; ?>"><label for="idpresta">ID Prestashop</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="nfattura" type="text" placeholder="N° Bolla o fattura" value="<?php echo $ordine['nfattura']; ?>"><label for="nfattura">N° Bolla/Fattura</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="idmarket" type="text" placeholder="ID Marketplace" value="<?php echo $ordine['idmarketplace']; ?>"><label for="idmarket">ID Marketplace</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="track" type="text" placeholder="Codice tracking" value="<?php echo $ordine['tracking']; ?>"><label for="track">Tracciabilità</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><select class="form-select" id="corriere" aria-label="Scelta del corriere">
                                                        <option <?php if ($ordine['corriere'] == "BRT") {
                                                                    echo "selected";
                                                                } ?>>BRT</option>
                                                        <option <?php if ($ordine['corriere'] == "DHL") {
                                                                    echo "selected";
                                                                } ?>>DHL</option>
                                                        <option <?php if ($ordine['corriere'] == "Poste Italiane") {
                                                                    echo "selected";
                                                                } ?>>Poste Italiane</option>
                                                        <option <?php if ($ordine['corriere'] == "SAVISE") {
                                                                    echo "selected";
                                                                } ?>>SAVISE</option>
                                                        <option <?php if ($ordine['corriere'] == "TNT") {
                                                                    echo "selected";
                                                                } ?>>TNT</option>
                                                        <option <?php if ($ordine['corriere'] == "UPS") {
                                                                    echo "selected";
                                                                } ?>>UPS</option>
                                                        <option <?php if ($ordine['corriere'] == "GLS") {
                                                                    echo "selected";
                                                                } ?>>GLS</option>
                                                        <option <?php if ($ordine['corriere'] == "SDA") {
                                                                    echo "selected";
                                                                } ?>>SDA</option>
                                                        <option <?php if ($ordine['corriere'] == "NESSUNO") {
                                                                    echo "selected";
                                                                } ?>>NESSUNO</option>
                                                    </select><label for="corriere">Corriere</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><select class="form-select" id="piattaforma" aria-label="Scelta della piattaforma">
                                                        <option <?php if ($ordine['piattaforma'] == "Sito") {
                                                                    echo "selected";
                                                                } ?>>Sito</option>
                                                        <option <?php if ($ordine['piattaforma'] == "ManoMano") {
                                                                    echo "selected";
                                                                } ?>>ManoMano</option>
                                                        <option <?php if ($ordine['piattaforma'] == "ePrice") {
                                                                    echo "selected";
                                                                } ?>>ePrice</option>
                                                        <option <?php if ($ordine['piattaforma'] == "eBay") {
                                                                    echo "selected";
                                                                } ?>>eBay</option>
                                                        <option <?php if ($ordine['piattaforma'] == "Leroy Merlin") {
                                                                    echo "selected";
                                                                } ?>>Leroy Merlin</option>
                                                        <option <?php if ($ordine['piattaforma'] == "Amazon") {
                                                                    echo "selected";
                                                                } ?>>Amazon</option>
                                                        <option <?php if ($ordine['piattaforma'] == "Altro") {
                                                                    echo "selected";
                                                                } ?>>Altro</option>
                                                    </select><label for="piattaforma">Piattaforma</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><select class="form-select" id="tsped" aria-label="Scelta del tipo di spedizione">
                                                        <option <?php if ($ordine['tipo'] == "SPEDIZIONE") {
                                                                    echo "selected";
                                                                } ?>>SPEDIZIONE</option>
                                                        <option <?php if ($ordine['tipo'] == "RITIRO") {
                                                                    echo "selected";
                                                                } ?>>RITIRO</option>
                                                        <option <?php if ($ordine['tipo'] == "DROPSHIPPING") {
                                                                    echo "selected";
                                                                } ?>>DROPSHIPPING</option>
                                                    </select><label for="tsped">Tipo di spedizione</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 pe-xl-2">
                                <div class="card mb-auto">
                                    <div class="bg-holder d-none d-lg-block bg-card"></div>
                                    <div class="card-header">
                                        <div class="row flex-between-center">
                                            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-credit-card"></i> Stato e pagamento ordine</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><select class="form-select" id="statoordine" aria-label="Scelta dello stato">
                                                        <option value="<?php echo $ordine['stato'] ?>"><?php echo $ordine['stato'] ?></option>
                                                        <option value="12">Importato</option>
                                                        <option value="2">Evaso</option>
                                                        <option value="3">Da Gestire</option>
                                                        <option value="4">In Stock</option>
                                                        <option value="5">Attesa di pagamento</option>
                                                        <option value="6">Preventivo</option>
                                                        <option value="1">Rientrato</option>
                                                        <option value="7">Sospeso</option>
                                                        <option value="9">Da Rimborsare</option>
                                                        <option value="10">Rimborsato</option>
                                                        <option value="11">Annullato</option>
                                                    </select><label for="statoordine">Stato</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="dataordine" type="date" placeholder="Data ordine" value="<?php echo $ordine['dataordine']; ?>"><label for="dataordine">Data Ordine</label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><select class="form-select" id="pagamento" aria-label="Scelta del pagamento" disabled>
                                                        <option <?php if ($ordine['pagamento'] == "Bonifico Bancario") {
                                                                    echo "selected";
                                                                } ?>>Bonifico Bancario</option>
                                                        <option <?php if ($ordine['pagamento'] == "Ricarica PostePay") {
                                                                    echo "selected";
                                                                } ?>>Ricarica PostePay</option>
                                                        <option <?php if ($ordine['pagamento'] == "PayPlug") {
                                                                    echo "selected";
                                                                } ?>>PayPlug</option>
                                                        <option <?php if ($ordine['pagamento'] == "Carta di Credito") {
                                                                    echo "selected";
                                                                } ?>>Carta di Credito</option>
                                                        <option <?php if ($ordine['pagamento'] == "ScalaPay") {
                                                                    echo "selected";
                                                                } ?>>ScalaPay</option>
                                                        <option <?php if ($ordine['pagamento'] == "Pagantis") {
                                                                    echo "selected";
                                                                } ?>>Pagantis</option>
                                                        <option <?php if ($ordine['pagamento'] == "Soisy") {
                                                                    echo "selected";
                                                                } ?>>Soisy</option>
                                                        <option <?php if ($ordine['pagamento'] == "Findomestic") {
                                                                    echo "selected";
                                                                } ?>>Findomestic</option>
                                                        <option <?php if ($ordine['pagamento'] == "PayPal") {
                                                                    echo "selected";
                                                                } ?>>PayPal</option>
                                                        <option <?php if ($ordine['pagamento'] == "eBay") {
                                                                    echo "selected";
                                                                } ?>>eBay</option>
                                                        <option <?php if ($ordine['pagamento'] == "ManoMano") {
                                                                    echo "selected";
                                                                } ?>>ManoMano</option>
                                                        <option <?php if ($ordine['pagamento'] == "ePrice") {
                                                                    echo "selected";
                                                                } ?>>ePrice</option>
                                                        <option <?php if ($ordine['pagamento'] == "Leroy Merlin") {
                                                                    echo "selected";
                                                                } ?>>Leroy Merlin</option>
                                                        <option <?php if ($ordine['pagamento'] == "bnlpositivity") {
                                                                    echo "selected";
                                                                } ?>>bnlpositivity</option>
                                                        <option <?php if ($ordine['pagamento'] == "Satispay") {
                                                                    echo "selected";
                                                                } ?>>Satispay</option>
                                                        <option <?php if ($ordine['pagamento'] == "Contrassegno") {
                                                                    echo "selected";
                                                                } ?>>Contrassegno</option>
                                                        <option <?php if ($ordine['pagamento'] == "Gratis") {
                                                                    echo "selected";
                                                                } ?>>Gratis</option>
                                                    </select><label for="pagamento">Modalità pagamento</label></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3"><input class="form-control" id="importo" type="text" placeholder="Importo ordine" value="€ <?php echo number_format($ordine['importo'], 2, ',', '.'); ?>" disabled><label for="importo">Importo</label></div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3">
                                                    <select id="cron-statoordine" class="form-select" style="height: 9rem;" multiple="" disabled>
                                                        <?php echo $ordine['cronstato'] ?>
                                                    </select>
                                                    <label for="cron-statoordine">Cronologia stato ordine</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 mt-4 pe-xl-2">
                                <div class="card mb-auto">
                                    <div class="card-header">
                                        <div class="row flex-between-center">
                                            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-box"></i> Dettagli prodotti</h5>
                                            </div>
                                            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                                                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" type="button" onclick="FCreaProdotto_vo()"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                                                            <g transform="translate(224 256)">
                                                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                                                </g>
                                                            </g>
                                                        </svg><span class="d-none d-sm-inline-block ms-1">Aggiungi prodotto</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <div class="row" id="NuovoProdotto" hidden>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-floating mb-3"><input class="form-control" id="CodiceProdotto_vo" type="text" placeholder=" "><label for="CodiceProdotto_vo">Codice prodotto</label></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-floating mb-3"> <input id="QuantitaProdotto_vo" type="text" class="form-control" placeholder=" "> <label for="QuantitaProdotto_vo">Quantita</label> </div>
                                                </div>
                                                <div class="col-md-2 mb-auto mt-auto"><button class="btn btn-outline-primary me-1 mb-1 mt-1 VerificaProdotto_vo" type="button">Verifica</button></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="mb-3"><input class="form-control form-control-sm" id="DescrizioneProdotto_vo" type="text" placeholder="Descrizione prodotto" readonly=""></div>
                                                </div>
                                                <div class="col-md-2"><button class="btn btn-outline-primary me-1 mb-1 mt-1 AggiungiProdotto_vo" type="button">Carica</button> </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive scrollbar">
                                                <table class="table table-bordered fs--1 mb-0">
                                                    <thead class="bg-200 text-900">
                                                        <tr>
                                                            <th>Immagine</th>
                                                            <th>Codice</th>
                                                            <th>Nome</th>
                                                            <th>Quantità</th>
                                                            <th>Azioni</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="list" id="TabellaProdotti_vo">
                                                        <?php echo $prodotti; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-spedizione" role="tabpanel" aria-labelledby="spedizione-tab">
                        <div class="row">
                            <div class="col-lg-4 m-auto">
                                <div class="card mb-3">
                                    <div class="bg-holder d-none d-lg-block"></div>
                                    <div class="card-header">
                                        <div class="row flex-between-end">
                                            <div class="col-auto align-self-center">
                                                <h5 class="mb-0"><i class="fa-regular fa-arrows-maximize"></i> Dimensioni</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="altezzacorr" type="text" placeholder="Altezza collo" value="<?php echo $spedcorr['altezza']; ?>" disabled><label for="altezzacorr">Altezza (cm)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="larghezzacorr" type="text" placeholder="Larghezza collo" value="<?php echo $spedcorr['larghezza']; ?>" disabled><label for="larghezzacorr">Larghezza (cm)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="profonditacorr" type="text" placeholder="Profondità collo" value="<?php echo $spedcorr['profondita']; ?>" disabled><label for="profonditacorr">Profondità (cm)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="pesovolumecorr" type="text" placeholder="Peso volume collo" value="<?php echo $spedcorr['pesovolume']; ?>" disabled><label for="pesovolumecorr">Peso Volume (KG)</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 m-auto">
                                <div class="card mb-3">
                                    <div class="bg-holder d-none d-lg-block"></div>
                                    <div class="card-header">
                                        <div class="row flex-between-end">
                                            <div class="col-auto align-self-center">
                                                <h5 class="mb-0"><i class="fa-regular fa-weight-hanging"></i> Pesi e costi</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body position-relative">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="pesocorr" type="text" placeholder="Peso totale" value="<?php echo $spedcorr['pesoreale']; ?>" disabled><label for="pesocorr">Peso (Kg)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-floating mb-3"><input class="form-control" id="prezzopagcorr" type="text" placeholder="Prezzo pagato" value="<?php echo $spedcorr['prezzoinserito']; ?>" disabled><label for="prezzopagcorr">Costo spedizione</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-check"><input class="form-check-input" id="colloinf" type="checkbox" value="" <?php if (strpos($spedcorr['codici'], "i") !== FALSE) {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> disabled><label class="form-check-label" for="colloinf">Collo informe (i)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-check"><input class="form-check-input" id="ritirook" type="checkbox" value="" <?php if (strpos($spedcorr['codici'], "T") !== FALSE) {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> disabled><label class="form-check-label" for="ritirook">Ritiro (T)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-check"><input class="form-check-input" id="paesidis" type="checkbox" value="" <?php if (strpos($spedcorr['codici'], "K") !== FALSE) {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> disabled><label class="form-check-label" for="paesidis">Paesi disagiati (K)</label></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-check"><input class="form-check-input" id="isolemin" type="checkbox" value="" <?php if (strpos($spedcorr['codici'], "J") !== FALSE) {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> disabled><label class="form-check-label" for="isolemin">Isole Minori (J)</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        dabilitacliente_ord()
        dabilitadettagli()
        dabilitastato()
        dabilitapagamento()
        cercasinistro()

        chiamamail();
    });
</script>