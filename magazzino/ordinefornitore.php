<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Prodotti da ordinare</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" onclick="aggiornaor('nonass')" type="button"> <i class="fa-solid fa-shopping-cart"></i>
                        <span class="d-none d-sm-inline-block ms-1">Non assegnati</span></button>
                    <button class="btn btn-falcon-default btn-sm" onclick="aggiornaor('cons')"><i class="fa-solid fa-lightbulb"></i>
                        <span class="d-none d-sm-inline-block ms-1">Consigliati</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="OrdineFornitoreTable" data-list='{"valueNames":["richiesta", "codice", "nome", "quantita", "fornitore", "prezzo"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3 me-auto">
                    <div class="radiobuttonscelta">
                        <input type="radio" id="option-1" value="0" name="toggle_option" checked="">
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
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="search">Ricerca:</label>
                        <div class="col-9"><select class="form-control selectpicker" data-style="btn btn-link" id="searchforn" onchange="aggiornaor()">
                                <option value="-" selected>-</option>
                            </select></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div style="display: inline-block;" class="ml-auto mr-2">
                        <input class="form-control" type="text" id="dataricerca" name="date" list="questedate" onchange="aggiornaor('drc')" placeholder="Ricerca ordine">
                        <datalist id="questedate">
                        </datalist>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0" id="tabellaricambi">

                </table>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-auto">
                    <p class="mb-0 fs--1">
                        <span class="d-none d-sm-inline-block" data-list-info="data-list-info"></span>
                        <span class="d-none d-sm-inline-block"> &mdash; </span>
                        <a class="fw-semi-bold" href="javascript:void(0)" data-list-view="*">Vedi tutto<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semi-bold d-none" href="javascript:void(0)" data-list-view="less">Vedi meno<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                    </p>
                </div>
                <div class="col d-flex justify-content-center">
                    <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                    <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        aggiornaor('nonass');
        fornitoriadd();
    });
</script>