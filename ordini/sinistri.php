<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Sinistri</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="SinistriTable" data-list='{"valueNames":["ordine", "corriere", "datasinistro", "importo", "stato"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="sinsitro_searchstato">Stato:</label>
                        <div class="col-9"><select class="form-select form-select-sm" id="sinsitro_searchstato" onchange="StatoSinistro()">
                                <option selected="">Attesa</option>
                                <option>Rimborsato</option>
                                <option>Rifiutato</option>
                                <option>Annullato</option>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort" data-sort="ordine">Ordine</th>
                            <th class="sort" data-sort="corriere">Corriere</th>
                            <th class="sort" data-sort="datasinistro">Apertura Sinistro</th>
                            <th class="sort" data-sort="importo">Importo Sinistro</th>
                            <th class="sort text-center" data-sort="stato">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="tabellasinistri"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination mb-0"></ul>
            </div>
        </div>
    </div>
</div>

<script>
    var stato = 'Attesa';
    $(document).ready(function() {
        aggiorna();
    });

    function aggiorna() {
        var clausola = " WHERE s.Stato='" + stato + "'";
        $.post(currentURL + "assets/inc/modifica_ordine.php", {
            modifica: 'listasinistri',
            clausola: clausola
        }, function(dati) {
            $('#tabellasinistri').html(dati);
            docReady(listInit);
        })
    }

    function StatoSinistro() {
        stato = $("#sinsitro_searchstato option:selected").text();
        aggiorna();
    }
</script>