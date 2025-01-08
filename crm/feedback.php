<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista feedback</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="FeedbackTable" data-list='{"valueNames":["cliente", "nordine", "giorni", "dataordine", "dataevasione", "stato"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="feed_searchcode">Ricerca:</label>
                        <div class="col-9"><input class="form-control form-control-sm" id="feed_searchcode" type="search" placeholder=" " onchange="aggiornafeedback()" /></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 me-auto">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="feed_searchstato">Stato:</label>
                        <div class="col-9"><select class="form-select form-select-sm" id="feed_searchstato" onchange="aggiornafeedback()">
                                <option value="0" selected>Da contattare</option>
                                <option value="1">Contattato</option>
                                <option value="2">Rimosso</option>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort" data-sort="cliente">Cliente</th>
                            <th class="sort" data-sort="nordine">Piattaforma</th>
                            <th class="sort text-center" data-sort="giorni">Consegna</th>
                            <th class="sort" data-sort="dataordine">Data Ordine</th>
                            <th class="sort" data-sort="dataevasione">Data Evasione</th>
                            <th class="sort text-center" data-sort="stato">Stato</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="list" id="tabellafeedback"></tbody>
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
        aggiornafeedback();
    });
</script>