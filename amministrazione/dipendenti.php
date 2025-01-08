<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista dipendenti</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm NuovoDipendente_ld" type="button"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
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
    <div class="card-body p-3">
        <div id="DipendentiTable" data-list='{"valueNames":["dipendente", "stato"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="Cerca_ld">Ricerca:</label>
                        <div class="col-9"><input class="form-control form-control-sm auto" id="Cerca_ld" type="search" placeholder=" " onchange="AggiornaLista_ld()" autocomplete="off"></div>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th></th>
                            <th class="sort" data-sort="dipendente">Dipendente</th>
                            <th class="sort" data-sort="stato">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="DipendentiTableBody"></tbody>
                    <?= (false ? '<tfoot class="bg-200 text-900" id="DipendentiTableFooter"></tfoot>' : ''); ?>
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
        AggiornaLista_ld()

        var myArr = new Array();
        $.post(currentURL + 'assets/inc/autocomplete.php', {
            azione: 'dipendenti'
        }, function(data) {
            var res = data.split(',')
            $.each(res, function(index, value) {
                myArr.push(value)
            });
            $(".auto").autocomplete({
                source: myArr
            });
        });
    });
</script>