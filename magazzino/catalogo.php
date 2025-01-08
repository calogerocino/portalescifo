<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Magazzino</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" type="button" onclick="ApriProdotto_ca('nuovo')"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                            <g transform="translate(224 256)">
                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                </g>
                            </g>
                        </svg><span class="d-none d-sm-inline-block ms-1">Nuovo prodotto</span></button>
                    <button class="btn btn-falcon-default btn-sm" type="button" onclick="StampaCatalogo_ca()"><i class="fa-regular fa-print"></i>
                        <span class="d-none d-sm-inline-block ms-1">Stampa catalogo</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="CatalogoTable" data-list='{"valueNames":["descrizione", "sku", "prezzo", "magazzino", "prestashop"],"page":10,"pagination":true}'>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <td colspan="7">
                                <div class="radiobuttonscelta">
                                    <input type="radio" id="option-1" value="0" name="toggle_option" onchange="AggiornaCatalogo()" checked="">
                                    <input type="radio" id="option-2" value="2" name="toggle_option" onchange="AggiornaCatalogo()">
                                    <input type="radio" id="option-3" value="1" name="toggle_option" onchange="AggiornaCatalogo()">
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
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input id="ctn_s_descr" type="search" class="form-control form-control-sm" style="width: 100%" placeholder="Descrizione" autocomplete="off" onchange="AggiornaCatalogo()"></td>
                            <td><input id="ctn_s_ref" type="search" class="form-control form-control-sm" style="width: 100%" placeholder="Riferimento" autocomplete="off" onchange="AggiornaCatalogo()"></td>
                            <td></td>
                            <td></td>
                            <td><input id="ctn_s_quantpr" type="number" class="form-control form-control-sm" style="width: 100%" placeholder="Quantità" autocomplete="off" onchange="AggiornaCatalogo()"></td>
                            <td>
                                <select class="form-select form-select-sm" id="ctn_s_stato" onchange="AggiornaCatalogo()">
                                    <option value="-" selected=""></option>
                                    <option value="1">Attivo</option>
                                    <option value="0">Inattivo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">Immagine</th>
                            <th class="sort" data-sort="descrizione">Nome</th>
                            <th class="text-center sort" data-sort="sku">Riferimento</th>
                            <th class="text-center sort" data-sort="prezzo">Prezzo finale</th>
                            <th class="text-center sort" data-sort="magazzino">Disponibilità</th>
                            <th class="text-center sort" data-sort="prestashop">Prestashop</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="list scrollbar" id="cnm_tp"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(e) {
        avvio = 0;
        AggiornaCatalogo();
    });
</script>