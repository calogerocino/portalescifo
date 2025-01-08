<div class="row g-0">
    <div class="col-12">
        <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
            <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
            <p class="mb-0 flex-1">ATTENZIONE! Le modifiche non sono disponibili, qualunque cambiamento non verrà salvato.</p>
        </div>
    </div>
</div>
<?php
if (isset($_GET['idprev'])) {
    $idprev = $_GET['idprev'];
}
?>
<div class="row g-3">
    <div class="col-xl-3 order-xl-1">
        <div class="card">
            <div class="card-header bg-light btn-reveal-trigger d-flex flex-between-center">
                <h5 class="mb-0">Riepilogo preventivo</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless fs--1 mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <th class="ps-0">Pezzi</th>
                            <th class="pe-0 text-end" id="prev_quantita">0</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Totale</th>
                            <th class="pe-0 text-end" id="prev_totale">€ 0,00</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Sconto</th>
                            <th class="pe-0 text-end" id="prev_sconto">- € 0,00</th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Stato</th>
                            <th class="pe-0 text-end pb-0 fw-bold" id="prev_Stato">Non Confermato</th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">N° preventivo</th>
                            <th class="pe-0 text-end pb-0 fw-bold" id="Numprev_prev">Non Assegnato</th>
                        </tr>
                            <th class="ps-0 pb-0">Data</th>
                            <th class="pe-0 text-end pb-0 fw-bold" id="Data_prev"></th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Cliente</th>
                            <th class="pe-0 text-end pb-0" id="prev_Cliente"><input id="prev_idcl" class="form-control form-control-sm auto2" type="search" placeholder="Cerca cliente ...">
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between bg-light">
                <div class="fw-semi-bold">Saldo</div>
                <div class="fw-bold" id="prev_saldo">€ 0,00</div>
            </div>
            <div class="card-footer bg-liwght d-flex justify-content-end">
                <a class="btn btn-sm btn-primary Stampa_prev" href="javascript:void(0)" hidden>Stampa</a>
                <a class="btn btn-sm btn-primary prev_InviaPrev" href="javascript:void(0)">Invia</a>
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
                    <div class="col-sm-12 col-md-4">
                        <div class="row mb-3"><label class="col-3 col-form-label-sm" for="vbn_cercaprodotto">Ricerca:</label>
                            <div class="col-9"><input class="form-control form-control-sm" id="vbn_cercaprodotto" type="search" placeholder="Cerca per codici o descrizione" onchange="CercaProdotto_bn()"></div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-1">
                        <button title="Inserisci codice manuale" class="btn btn-outline-info btn-block prev_ProdottoManuale"><i class="fa-duotone fa-pen-line"></i></button>
                    </div>
                    <div class="col-sm-12 col-md-3">
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

<script>
    $(document).ready(function() {
        var idpre_prev = '<?php echo $idprev; ?>';
        (idpre_prev != 'nuovo' ?  Carica_prev(idpre_prev) :'')
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