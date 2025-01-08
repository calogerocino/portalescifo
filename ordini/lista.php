<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Ordini</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="orders-actions"><button class="btn btn-falcon-default btn-sm" type="button" onclick="cambiopagina('ordini', 'nuovordine','')"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                            <g transform="translate(224 256)">
                                <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                </g>
                            </g>
                        </svg><span class="d-none d-sm-inline-block ms-1">Nuovo</span></button>
                    <button class="btn btn-falcon-default btn-sm" type="button" onclick="cambiopagina('ordini', 'manomano','')"><svg class="svg-inline--fa fa-external-link-alt fa-w-16" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.625em;">
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
        <div class="table-responsive scrollbar">
            <table class="table table-sm fs--1 mb-0 overflow-hidden" id="dataTable">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>ID</th>
                        <th>Ordine</th>
                        <th>Ordine</th>
                        <th>Tracciabilità</th>
                        <th>Piattaforma</th>
                        <th>Data Ordine</th>
                        <th>Corriere</th>
                        <th>Tipo</th>
                        <th>Data Evasione</th>
                        <th>Stato</th>
                        <th>ID Marketplace</th>
                        <th>ID Prestashop</th>
                        <th>Mail</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        aggiornaordini()
    });
</script>