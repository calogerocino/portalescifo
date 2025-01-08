<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista utenti</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">

        <div class="table-responsive scrollbar" id="tabellautenti_ut">
            <table class="table table-bordered fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Nome</th>
                        <th>E-Mail</th>
                        <th class="text-center">Activity Logs</th>
                        <th>IPTelefono</th>
                        <th>Ruolo</th>
                        <th class="text-center">Stato</th>
                    </tr>
                </thead>
                <tbody class="list" id="UtentiTableBodyN"></tbody>
            </table>
        </div>
        <div id="activitylog_ut" hidden>
            <div class="row">
                <div class="col-12 m-2">
                    <a href="javascript:void(0);" class="float-left text-primary indietroscheda_ut"><i class="fa-regular fa-arrow-left"></i> Torna alla lista utenti</a>
                </div>
                <div class="col-12">
                    <div id="UtentiTable" data-list='{"valueNames":["idmov", "act", "data"],"page":10,"pagination":true}'>
                        <table class="table table-bordered fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort" data-sort="idmov">ID Movimento</th>
                                    <th class="sort" data-sort="act">Azione</th>
                                    <th class="sort" data-sort="data">Data</th>
                                    <th>Utente</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="UtentiTableBody"></tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
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
        AggiornaUtenti()
    });

    function AggiornaUtenti() {
        bloccaui();
        $.post(currentURL + 'assets/inc/index-control.php', {
            azione: 'listautenti'
        }, function(data) {
            $("#UtentiTableBodyN").html(data);
            sbloccaui();
        });
    }

    function visutenti_ut(idu) {
        bloccaui();
        $('#tabellautenti_ut').prop('hidden', true);
        $('#activitylog_ut').prop('hidden', false);

        $.post(currentURL + 'assets/inc/index-control.php', {
            azione: 'activitylog',
            idu: idu
        }, function(data) {
            $("#UtentiTableBody").html(data);
            docReady(listInit);
            sbloccaui();
        });
    }

    function AbDabUtente(idu, stato) {
        ((stato == 1) ? stato = 0 : stato = 1)
        $.post(currentURL + 'assets/inc/index-control.php', {
            azione: 'abdabutente',
            idu: idu,
            stato: stato
        }, function(response) {
            if (response == 'ok') {
                Toast.fire({
                    icon: 'success',
                    title: 'Stato aggiornato con successo!'
                })
                AggiornaUtenti();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore:' + response
                })
            }
            sbloccaui();
        });
    }

    $(document).on('click', '.indietroscheda_ut', function() {
        bloccaui();
        $('#tabellautenti_ut').prop('hidden', false);
        $('#activitylog_ut').prop('hidden', true);
        sbloccaui();
    });
</script>