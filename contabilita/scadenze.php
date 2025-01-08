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
                            <div class="row">
                                <div class="col-12">
                                    <label class="fw-bold" for="search">Ricerca</label><br /><input name="search" id="search" type="search" class="form-control" value="" placeholder=" " onchange="aggiorna_sca()">
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchdate">Periodo</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchdate" size="1" onchange="aggiorna_sca()">
                                        <option value="tt">Tutto</option>
                                        <option value="ac">Anno <?php echo date("Y"); ?></option>
                                        <option value="mpr">Mese <?php
                                                                    setlocale(LC_TIME, 'it_IT');
                                                                    echo strftime('%B', mktime(0, 0, 0, (date("m") - 1)));
                                                                    ?></option>
                                        <option value="mc" selected>Mese <?php
                                                                            echo strftime('%B', mktime(0, 0, 0, date("m")));
                                                                            ?></option>
                                        <option value="mp">Mese <?php
                                                                echo strftime('%B', mktime(0, 0, 0, (date("m") + 1)));
                                                                ?> </option>
                                        <option value="da">Seleziona periodo </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2" id="dalalricerca" hidden>
                                <div class="col-12">
                                    <label class="fw-bold" for="DataPickerRangeScad">Seleziona un periodo</label>
                                    <input class="form-control datetimepicker" id="DataPickerRangeScad" type="text" placeholder="dal g/m/a al g/m/a" onchange="aggiorna_sca()" />
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchtipo">Visualizza</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchtipo" size="1" onchange="aggiorna_sca()">
                                        <option value="0 OR p.Pagato=4" selected>Non saldati</option>
                                        <option value="1">Saldati</option>
                                        <option value="2">Sospesi</option>
                                        <option value="0 OR p.Pagato=1 OR p.Pagato=2 OR p.Pagato=4">Tutto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-12">
                                    <label class="fw-bold" for="searchpay">Tipo pagamento</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchpay" size="1" onchange="aggiorna_sca()">
                                        <option value="25" selected>Assegno</option>
                                        <option value="24">Bonifico</option>
                                        <option value="23">Effetto</option>
                                        <option value="28">RI.BA.</option>
                                        <option value="46">Contanti</option>
                                        <option value="50">Effetto a garanzia</option>
                                        <option value="27">Nessuno</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="totassegni" hidden>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label" id="bancabnl"></label><br />
                                        <label class="bmd-label" id="bancaposta"></label><br />
                                        <label class="bmd-label" id="bancacred"></label><br />
                                        <label class="bmd-label" id="bancauniaz"></label><br />
                                        <label class="bmd-label" id="bancaunipe"></label><br />
                                        <label class="bmd-label" id="bancaintsp"></label>
                                    </div>
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
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista scadenze</h5>
                    </div>
                    <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                        <div id="orders-actions"><button class="btn btn-falcon-default btn-sm nuovopag_sca" type="button"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                                    <g transform="translate(224 256)">
                                        <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                            <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                        </g>
                                    </g>
                                </svg><span class="d-none d-sm-inline-block ms-1">Nuovo</span></button>
                            <button class="btn btn-falcon-default btn-sm esporta_sca" type="button"><i class="fa-regular fa-download fa-sm fa-sm"></i>
                                <span class="d-none d-sm-inline-block ms-1">Esporta</span></button>
                            <button class="btn btn-falcon-default btn-sm massimali_sca" type="button"><i class="fa-regular fa-exclamation-circle fa-sm"></i>
                                <span class="d-none d-sm-inline-block ms-1">Massimali</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="ScadenzeTable" data-list='{"valueNames":["intestatario", "scadenza", "ndoc", "banca", "importo"],"page":10,"pagination":true}'>
                    <div class="table-responsive scrollbar">
                        <table class="table table-bordered fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort" data-sort="intestatario">Intestatario</th>
                                    <th class="sort" data-sort="scadenza">Scadenza</th>
                                    <th class="sort" data-sort="ndoc">N° Documento</th>
                                    <th class="sort" data-sort="banca">Banca</th>
                                    <th class="sort text-end" data-sort="importo">Importo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="list" id="BodyTabellaScadenze"></tbody>
                            <tfoot class="bg-200 text-900" id="FootTabellaScadenze"></tfoot>
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
    </div>
</div>



<div class="modal fade" id="massimalitab" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                    <h4 class="mb-1" id="modalExampleDemoLabel">Imposta massimali</h4>
                </div>
                <div class="p-4 pb-0" id="inserisciquidentro">

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        aggiorna_sca()
        flatpickr('#DataPickerRangeScad', {
            mode: 'range',
            dateFormat: "d/m/y",
            disableMobile: true
        });
    });
</script>