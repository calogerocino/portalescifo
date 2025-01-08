<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista clienti</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm cl_nuovocliente" type="button"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                            <g transform="translate(224 256)">
                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                </g>
                            </g>
                        </svg><span class="d-none d-sm-inline-block ms-1">Nuovo</span></button>
                    <button class="btn btn-falcon-default btn-sm cl_esporta" type="button"><svg class="svg-inline--fa fa-external-link-alt fa-w-16" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.625em;">
                            <g transform="translate(256 256)">
                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z" transform="translate(-256 -256)"></path>
                                </g>
                            </g>
                        </svg>
                        <span class="d-none d-sm-inline-block ms-1">Esporta</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="ClientiTable" data-list='{"valueNames":["cliente", "dare", "avere", "scadere", "scaduto"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="search">Ricerca:</label>
                        <div class="col-9"><input class="form-control form-control-sm auto" id="search" type="search" placeholder=" " onchange="aggiorna_cli('aggiorna')" autocomplete="off"></div>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th></th>
                            <th class="sort" data-sort="cliente">Cliente</th>
                            <th class="sort text-center" data-sort="dare">Dare</th>
                            <th class="sort text-center" data-sort="avere">Avere</th>
                            <th class="sort text-center" data-sort="scadere">Scadere</th>
                            <th class="sort text-center" data-sort="scaduto">Scaduto</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="tablistaclienti_cli"></tbody>
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
    $(document).ready(function(e) {
        aggiorna_cli()
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