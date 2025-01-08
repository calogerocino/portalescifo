<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista segnalazioni</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="SegnalazioniTable" data-list='{"valueNames":["ordine", "tipologia", "stato", "operatore"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="searchcode">Ricerca:</label>
                        <div class="col-9"><input class="form-control form-control-sm" id="searchcode" type="search" placeholder=" " onchange="aggiornaticket2()"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-2">
                    <div class="form-check form-switch"><input class="form-check-input" id="switc_rimb" type="checkbox" onchange="switc_rimb()" /><label class="form-check-label" for="switc_rimb">Solo rimborsi</label></div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort" data-sort="ordine">Ordine</th>
                            <th class="sort" data-sort="tipologia">Tipologia</th>
                            <th class="sort" data-sort="stato">Stato</th>
                            <th class="sort" data-sort="operatore">Operatore</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="list" id="tabellaticket"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {
        aggiornaticket2()
    });
</script>