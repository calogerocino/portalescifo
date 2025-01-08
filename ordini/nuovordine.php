<div class="row pagcrea">
    <div class="col-lg-4 m-auto">
        <div class="card mb-3">
            <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>
            <div class="card-header">
                <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                        <h5 class="mb-0" data-anchor="data-anchor" id="card-with-image"><i class="fa-regular fa-cart-plus"></i> Importa un ordine</h5>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="mt-1">Importa un ordine da prestashop inserendo l'ID.</p>
                        <div class="col-sm-12 mb-3"><input class="form-control" id="nordprest" type="number" autocomplete="off" /></div>
                        <button class="btn btn-outline-primary me-1" type="button" onclick="creanuovo_ord(0)">
                            <span class="fa-regular fa-arrow-right me-1" data-fa-transform="shrink-3"></span>Cerca e importa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 m-auto">
        <div class="card mb-3">
            <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>
            <div class="card-header">
                <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                        <h5 class="mb-0" data-anchor="data-anchor" id="card-with-image"><i class="fa-regular fa-cart-plus"></i> Crea nuovo ordine</h5>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="mt-2">Prosegui creando un ordine manualmente</p>
                        <button class="btn btn-outline-primary me-1 mb-1" type="button" onclick="creanuovo_ord(1)">
                            <span class="fa-regular fa-arrow-right me-1" data-fa-transform="shrink-3"></span>Continua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="pagnuovo">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="javascript:void(0)" onclick="tornaindietro_no()" class="text-primary"><i class="fa-regular fa-arrow-left"></i> Torna alla scelta</a>
    </div>
    <div class="row col-md-12 notizie">
    </div>




    <div class="card mb-3">
        <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Crea nuovo ordine</h5>
                </div>
            </div>
        </div>
        <div class="card-body position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="dettagli-tab" data-bs-toggle="tab" href="#tab-dettagli" role="tab" aria-controls="tab-dettagli" aria-selected="true">Dettagli</a></li>
                        <li class="nav-item"><a class="nav-link" id="spedizione-tab" data-bs-toggle="tab" href="#tab-spedizione" role="tab" aria-controls="tab-spedizione" aria-selected="false">Spedizione</a></li>
                        <li class="nav-item ms-auto text-end ps-0"> <button class="btn btn-falcon-default btn-sm nav-link mb-2 invianuovoordine" type="button">
                                <span class="d-none d-sm-inline-block ms-1"><i class="fa-regular fa-truck"></i> Invia ordine</span></button></li>
                    </ul>
                    <div class="tab-content border-x border-bottom p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-dettagli" role="tabpanel" aria-labelledby="dettagli-tab">
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
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3"><input class="form-control auto" id="cliente_no" type="text" placeholder=" " onchange="insclienteno()" /><label for="cliente_no">Cliente</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3"><input class="form-control" id="indirizzo_no" type="text" placeholder=" " /><label for="indirizzo_no">Indirizzo</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-floating mb-3"><input class="form-control" id="cap_no" type="text" placeholder=" " onchange="cercacapcitta($(this).val())" /><label for="cap_no">CAP</label></div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="form-floating mb-3"><input class="form-control" id="citta_no" type="text" placeholder=" " /><label for="citta_no">Città</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="cellulare_no" type="text" placeholder=" " /><label for="cellulare_no">Cellulare</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="cellulare2_no" type="text" placeholder=" " /><label for="cellulare2_no">Secondo cellulare</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="telefono_no" type="text" placeholder=" "><label for="telefono_no">Telefono</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="email_no" type="email" placeholder=" "><label for="email_no">E-Mail</label></div>
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
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="form-floating mb-3"><input class="form-control" id="refordine_no" type="text" placeholder=" " /><label for="refordine_no">Riferimento</label></div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-floating mb-3"><input class="form-control" id="idpresta_no" type="text" placeholder=" " readonly /><label for="idpresta_no">ID Prestashop</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="nfattura_no" type="text" placeholder=" " /><label for="nfattura_no">N° Bolla/Fattura</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="idmarket_no" type="text" placeholder=" " /><label for="idmarket_no">ID Marketplace</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="track_no" type="text" placeholder=" " /><label for="track_no">Tracciabilità</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><select class="form-select" id="corriere_no" aria-label="Scelta del corriere">
                                                            <option value="-">-</option>
                                                            <option value="DHL">DHL</option>
                                                            <option value="Poste Italiane">Poste Italiane</option>
                                                            <option value="SDA">SDA</option>
                                                            <option value="SAVISE">SAVISE</option>
                                                            <option value="TNT">TNT</option>
                                                            <option value="UPS">UPS</option>
                                                            <option value="GLS">GLS</option>
                                                            <option value="SDA">SDA</option>
                                                            <option value="NESSUNO">NESSUNO</option>
                                                        </select><label for="corriere_no">Corriere</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><select class="form-select" id="piattaforma_no" aria-label="Scelta della piattaforma">
                                                            <option value="-">-</option>
                                                            <option value="Sito">Sito</option>
                                                            <option value="ManoMano">ManoMano</option>
                                                            <option value="eBay">eBay</option>
                                                            <option value="ePrice">ePrice</option>
                                                            <option value="Leroy MerlinF">Leroy Merlin</option>
                                                            <option value="Amazon">Amazon</option>
                                                            <option value="Altro">Altro</option>
                                                        </select><label for="piattaforma_no">Piattaforma</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><select class="form-select" id="tsped_no" aria-label="Scelta del tipo di spedizione">
                                                            <option value="-">-</option>
                                                            <option value="SPEDIZIONE">SPEDIZIONE</option>
                                                            <option value="RITIRO">RITIRO</option>
                                                            <option value="DROPSHIPPING">DROPSHIPPING</option>
                                                        </select><label for="tsped_no">Tipo di spedizione</label></div>
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
                                                    <div class="form-floating mb-3"><select class="form-select" id="statoordine_on" aria-label="Scelta dello stato">
                                                            <option value="12" selected="">Importato</option>
                                                            <option value="2">Evaso</option>
                                                            <option value="3">Da Gestire</option>
                                                            <option value="4">In Stock</option>
                                                            <option value="5">Attesa di pagamento</option>
                                                            <option value="6">Preventivo</option>
                                                            <option value="1">Rientrato</option>
                                                            <option value="7">Sospeso</option>
                                                            <option value="8">Allerta</option>
                                                            <option value="9">Da Rimborsare</option>
                                                            <option value="10">Rimborsato</option>
                                                            <option value="11">Annullato</option>
                                                        </select><label for="statoordine_on">Stato</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="dataordine_no" type="date" placeholder=" " /><label for="dataordine_no">Data Ordine</label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><select class="form-select" id="pagamento_no" aria-label="Scelta del pagamento">
                                                            <option value="-">-</option>
                                                            <option value="Bonifico Bancario">Bonifico Bancario</option>
                                                            <option value="Ricarica PostePay">Ricarica PostePay</option>
                                                            <option value="PayPlug">PayPlug</option>
                                                            <option value="Carta di Credito">Carta di Credito</option>
                                                            <option value="ScalaPay">Scalapay</option>
                                                            <option value="Pagantis">Pagantis</option>
                                                            <option value="Soisy">Soisy</option>
                                                            <option value="Findomestic">Findomestic</option>
                                                            <option value="PayPal">PayPal</option>
                                                            <option value="eBay">eBay</option>
                                                            <option value="ManoMano">ManoMano</option>
                                                            <option value="ePrice">ePrice</option>
                                                            <option value="Leroy Merlin">Leroy Merlin</option>
                                                            <option value="bnlpositivity">bnlpositivity</option>
                                                            <option value="Satispay">Satispay</option>
                                                            <option value="Contrassegno">Contrassegno</option>
                                                            <option value="Gratis">Senza Pagamento</option>
                                                        </select><label for="pagamento_no">Modalità pagamento</label></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3"><input class="form-control" id="importo_no" type="text" placeholder=" " /><label for="importo_no">Importo</label></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-8 pe-xl-2">
                                    <div class="card mb-auto">
                                        <div class="bg-holder d-none d-lg-block bg-card"></div>
                                        <div class="card-header">
                                            <div class="row flex-between-center">
                                                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-box"></i> Dettagli prodotti</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body position-relative">
                                            <div class="row" id="prodottoman"></div>
                                            <div class="row">
                                                <div class="table-responsive scrollbar">
                                                    <table class="table table-bordered fs--1 mb-0">
                                                        <thead class="bg-200 text-900">
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Codice</th>
                                                                <th>Nome</th>
                                                                <th>Quantità</th>
                                                                <th>Tipo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list" id="tabellaprodotti"></tbody>
                                                    </table>
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
                                                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-note"></i> Note</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body position-relative">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating"><textarea class="form-control" id="note_no" placeholder=" " style="height: 100px"></textarea><label for="note_no">Note</label></div>
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
                                                            <div class="form-floating mb-3"><input class="form-control" id="altezzacorr_no" type="text" placeholder=" " value="0.00" /><label for="altezzacorr_no">Altezza (cm)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-floating mb-3"><input class="form-control" id="larghezzacorr_no" type="text" placeholder=" " value="0.00" /><label for="larghezzacorr_no">Larghezza (cm)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-floating mb-3"><input class="form-control" id="profonditacorr_no" type="text" placeholder=" " value="0.00" /><label for="profonditacorr_no">Profondità (cm)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-floating mb-3"><input class="form-control" id="pesovolumecorr_no" type="text" placeholder=" " value="0.00" /><label for="pesovolumecorr_no">Peso Volume (KG)</label></div>
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
                                                            <div class="form-floating mb-3"><input class="form-control" id="pesocorr_no" type="text" placeholder=" " value="0.00" /><label for="pesocorr_no">Peso (Kg)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-floating mb-3"><input class="form-control" id="prezzopagcorr_no" type="text" placeholder=" " value="0.00" /><label for="prezzopagcorr_no">Costo spedizione</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-check"><input class="form-check-input" id="colloinf_no" type="checkbox" value="" /><label class="form-check-label" for="colloinf_no">Collo informe (i)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-check"><input class="form-check-input" id="ritirook_no" type="checkbox" value="" /><label class="form-check-label" for="ritirook_no">Ritiro (T)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-check"><input class="form-check-input" id="paesidis_no" type="checkbox" value="" /><label class="form-check-label" for="paesidis_no">Paesi disagiati (K)</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-check"><input class="form-check-input" id="isolemin_no" type="checkbox" value="" /><label class="form-check-label" for="isolemin_no">Isole Minori (J)</label></div>
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
</div>
<?php
include("../assets/inc/database.php");
$sql = "SELECT Cliente FROM donl_clienti";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $data =  $data . "\"" . $row['Cliente'] . "\", ";
}
$data = substr($data, 0, strlen($data) - 1);
?>
<script>
    $(document).ready(function() {
        $('.pagcrea').prop('hidden', false);
        $('.pagnuovo').prop('hidden', true);
    });

    $(".auto").autocomplete({
        source: [<?php echo $data; ?>]
    });
</script>