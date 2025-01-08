<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista pratiche</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" type="button" onclick="cambiopagina('amministrazione', 'pratica','?nuova=1')"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                            <g transform="translate(224 256)">
                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                </g>
                            </g>
                        </svg><span class="d-none d-sm-inline-block ms-1">Nuova pratica</span></button>
                    <button class="btn btn-falcon-default btn-sm pr_listaprac_print" type="button"><i class="fa-regular fa-print"></i>
                        <span class="d-none d-sm-inline-block ms-1">Esporta</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="PraticheTable" data-list='{"valueNames":["descrizione", "apertura", "avvocato", "sentenze", "stato"],"page":10,"pagination":true}'>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">

                        <tr>
                            <td><input id="searchdesc" onchange="AggiornaPratiche()" type="search" class="form-control form-control-sm" style="width: 100%" placeholder="Ricerca..." autocomplete="off"></td>
                            <td></td>
                            <td>
                                <div id="avvocatisel"></div>
                            </td>
                            <td></td>
                            <td>
                                <select class="form-select form-select-sm" id="searchstato" onchange="AggiornaPratiche()">
                                    <option value="0" selected="">Nuova</option>
                                    <option value="6">Attesa risposta</option>
                                    <option value="8">Attesa scifo</option>
                                    <option value="2">Attesa sentenza</option>
                                    <option value="4">Sospesa</option>
                                    <option value="5">Chiusa</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="sort" data-sort="descrizione">Descrizione</th>
                            <th class="sort text-center" data-sort="apertura">Apertura</th>
                            <th class="sort" data-sort="avvocato">Avvocato</th>
                            <th class="sort" data-sort="sentenze">Sentenze</th>
                            <th class="sort text-center" data-sort="stato">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="list scrollbar" id="PraticheTableBody"></tbody>
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
        PopolaAvvocati();
    });
</script>