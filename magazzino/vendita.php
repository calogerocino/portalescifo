<div class="row g-3">
    <div class="col-xl-3 order-xl-1">
        <div class="card">
            <div class="card-header bg-light btn-reveal-trigger d-flex flex-between-center">
                <h5 class="mb-0">Riepilogo ordine</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless fs--1 mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <th class="ps-0">Pezzi</th>
                            <th class="pe-0 text-end" id="vbn_tq">0</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Totale</th>
                            <th class="pe-0 text-end" id="vbn_pt">€ 0,00</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Sconto</th>
                            <th class="pe-0 text-end" id="vbn_sc">- € 0,00</th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Pagamento</th>
                            <th class="pe-0 text-end pb-0"><select class="form-select form-select-sm" id="vbn_tpag" aria-label="Metodo di pagamento">
                                    <option value="vbn_cont">Contanti</option>
                                    <option value="vbn_mon">Merce online</option>
                                    <!-- <option value="vbn_bfc">Buono di consegna</option> -->
                                    <!-- <option value="vbn_fat">Fattura</option> -->
                                </select>
                            </th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Cliente</th>
                            <th class="pe-0 text-end pb-0"><input id="vbn_idcl" class="form-control form-control-sm auto2" type="search" placeholder="Cerca cliente ..." onchange="CercaFidoCliente()">
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between bg-light">
                <div class="fw-semi-bold">Saldo</div>
                <div class="fw-bold" id="vbn_tap">€ 0,00</div>
            </div>
            <div class="card-footer bg-liwght d-flex justify-content-end">
                <a class="btn btn-sm btn-primary vbn_nvend" href="javascript:void(0)">Vendi</a>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-auto">
                        <h5 class="mb-3 mb-md-0">Carrello prodotti</h5>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row p-3">
                    <div class="col-sm-10 col-md-4">
                        <div class="row mb-3"><label class="col-3 col-form-label-sm" for="vbn_cercaprodotto">Ricerca:</label>
                            <div class="col-9"><input class="form-control form-control-sm" data-slide="barcode" id="vbn_cercaprodotto" type="search" placeholder="Cerca per codici o descrizione" onchange="CercaProdotto_bn()"></div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#livestream_scanner">
                            <i class="fa-regular fa-barcode-scan"></i>
                        </button>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="radiobuttonscelta">
                            <input type="radio" id="option-1" value="0" name="toggle_option" checked>
                            <input type="radio" id="option-2" value="2" name="toggle_option">
                            <input type="radio" id="option-3" value="1" name="toggle_option">
                            <label for="option-1" class="option option-1">
                                <div class="dot"></div>
                                <span>Tutto</span>
                            </label>
                            <label for="option-2" class="option option-2">
                                <div class="dot"></div>
                                <span>Prodotti</span>
                            </label>
                            <label for="option-3" class="option option-3">
                                <div class="dot"></div>
                                <span>Ricambi</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="lps" id="lps"></div>
                <div class="row gx-card mx-0 bg-200 text-900 fs--1 fw-semi-bold">
                    <div class="col-5 py-2">Descrizione</div>
                    <div class="col-7">
                        <div class="row">
                            <div class="col-2 py-2 d-md-block text-center">Prezzo</div>
                            <div class="col-3 py-2 d-md-block text-center">Quantita</div>
                            <div class="col-2 py-2 d-md-block text-center">IVA</div>
                            <div class="col-2 py-2 d-md-block text-center">Sc.(%)</div>
                            <div class="col-3 text-end py-2">Totale</div>
                        </div>
                    </div>
                </div>
                <div class="scrollbar" style="height:500px" id="vbn_contcart" att="0">
                    <div style="text-align:center;padding-top: 100px;padding-bottom: 100px;color: #555;">
                        <i class="fa-regular fa-shopping-cart" style="font-size: 150px;"></i>
                        <div style="margin-top: 30px;font-size: 30px;">
                            <span>Il carrello è vuoto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="livestream_scanner" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                    <h4 class="mb-1" id="modalExampleDemoLabel">Scannerrica codice a barre</h4>
                </div>
                <div class="p-4 pb-0">
                    <div id="interactive" class="viewport"></div>
                    <div class="error"></div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <label class="btn btn-default pull-left">
                    <i class="fa fa-camera"></i> Usa camera
                    <input type="file" accept="image/*;capture=camera" capture="camera" class="hidden" />
                </label>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Chiudi</button>
            </div> -->
        </div>
    </div>
</div>
<script src="app/js/barcode.js"></script>
<script>
    $(document).ready(function() {
        var myArr = new Array();
        $.post(currentURL + 'assets/inc/autocomplete.php', {
            azione: 'clienti'
        }, function(data) {
            var res = data.split(',')
            $.each(res, function(index, value) {
                myArr.push(value)
            });
            $(".auto2").autocomplete({
                source: myArr,
                change: function(event, ui) {
                    CercaFidoCliente();
                }
            });
        })
    });
</script>