<div class="row g-0">
    <div class="col-lg-2 pe-lg-2">
        <div class="sticky-sidebar">
            <div class="card sticky-top">
                <div class="card-header border-bottom">
                    <h6 class="mb-0 fs-0">Filtra o cerca</h6>
                </div>
                <div class="card-body">
                    <div class="terms-sidebar nav flex-column fs--1" id="terms-sidebar">
                        <div class="col-12">
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchPrev">Ricerca cliente</label><br /><input id="searchPrev" type="search" class="form-control auto" value="" placeholder="Inserisci qui..." onchange="aggiorna_PrevOff()">
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchdatePrev">Data</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchdatePrev" size="1" onchange="aggiorna_PrevOff()">
                                        <option value="tt">Tutto</option>
                                        <option value="ac">Anno <?php echo date("Y"); ?></option>
                                        <option value="mp">Mese <?php
                                                                setlocale(LC_TIME, 'it_IT');
                                                                echo strftime('%B', mktime(0, 0, 0, (date("m") - 1)));
                                                                ?></option>
                                        <option value="mc" selected>Mese <?php
                                                                            echo strftime('%B', mktime(0, 0, 0, date("m")));
                                                                            ?></option>
                                        <option value="ms">Mese <?php
                                                                echo strftime('%B', mktime(0, 0, 0, (date("m") + 1)));
                                                                ?> </option>
                                        <option value="da">Seleziona periodo </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchstatoPrev">Stato</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchstatoPrev" size="1" onchange="aggiorna_PrevOff()">
                                        <option value="0" selected>Non confermato</option>
                                        <option value="1">Confermato</option>
                                        <option value="2">Chiuso</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-10 ps-lg-2">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row flex-between-center">
                    <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista preventivi</h5>
                    </div>
                    <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                        <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" onclick="apri_prev()" type="button"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                                    <g transform="translate(224 256)">
                                        <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                            <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                        </g>
                                    </g>
                                </svg><span class="d-none d-sm-inline-block ms-1">Nuovo</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="PreventiviTable" data-list='{"page":10,"pagination":true}'>
                    <div class="table-responsive scrollbar">
                        <table class="table table-bordered fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Data</th>
                                    <th class="text-center">Stato</th>
                                    <th class="text-end">Totale</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="list" id="tabellapreventivi"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                        <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        CaricaLista_prev();
    });

    var myArr = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'clienti'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr.push(value)
        });
        $(".auto").autocomplete({
            source: myArr
        });
    });
</script>