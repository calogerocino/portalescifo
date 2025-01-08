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
                  <label class="fw-bold " for="search">Ricerca</label><br /><input name="search" id="search" type="search" class="form-control auto" value="" placeholder="Ragione sociale" onchange="AggiornaFatture_fa()">
                </div>
              </div>
              <div class="row pb-2">
                <div class="col-12">
                  <label class="fw-bold" for="searchdate">Periodo</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchdate" size="1" onchange="AggiornaFatture_fa()">
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
                  <label class="fw-bold" for="DataPickerRangeFatture">Seleziona un periodo</label>
                  <input class="form-control datetimepicker" id="DataPickerRangeFatture" type="text" placeholder="dal g/m/a al g/m/a" onchange="AggiornaFatture_fa()" />
                </div>
              </div>
              <div class="row pb-2">
                <div class="col-12">
                  <label class="fw-bold" for="searchtipo">Visualizza</label><br /><select class="btn btn-falcon-default rounded-pill me-1 mb-1" id="searchtipo" size="1" onchange="AggiornaFatture_fa()">
                    <option value="1" selected>Non saldati</option>
                    <option value="0">Saldati</option>
                    <option value="2">Tutto</option>
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
            <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista fatture</h5>
          </div>
          <div class="col-8 col-sm-auto ms-auto text-end ps-0">
            <div id="orders-actions"><button class="btn btn-falcon-default btn-sm CreaNuovaFattura_fa" type="button"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
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
        <div id="FattureTable" data-list='{"valueNames":["documento", "intestatario", "datadoc", "importo"],"page":10,"pagination":true}'>
          <div class="table-responsive scrollbar">
            <table class="table table-bordered fs--1 mb-0">
              <thead class="bg-200 text-900">
                <tr>
                  <th class="sort" data-sort="documento">Documento</th>
                  <th class="sort" data-sort="intestatario">Intestatario</th>
                  <th class="sort" data-sort="datadoc">Data documento</th>
                  <th class="sort text-end" data-sort="importo">Importo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list" id="BodyTabellaFatture"></tbody>
              <tfoot class="bg-200 text-900" id="FootTabellaFatture"></tfoot>
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


<script>
  $(document).ready(function() {
    AggiornaFatture_fa()
    NuovaModalitaPagamento_fa()
    flatpickr('#DataPickerRangeFatture', {
      mode: 'range',
      dateFormat: "d/m/y",
      disableMobile: true
    });
  });

  var myArr = new Array();
  $.post(currentURL + 'assets/inc/autocomplete.php', {
    azione: 'fornitori'
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