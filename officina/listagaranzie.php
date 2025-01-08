<?php error_reporting(0); ?>
<div class="container-fluid">
    <div class="row g-0">
        <div class="col-12">
            <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
                <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1">ATTENZIONE! Sistema garanzie in revisione. Potrebbero presentarsi problemi all'utilizzo.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header pb-0">
                    <h6 class="m-0 font-weight-bold text-primary">Lista Garanzie</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless paginated">
                            <thead>
                                <tr>
                                    <th>Ref. Scheda</th>
                                    <th>Cliente</th>
                                    <th>Anomalia</th>
                                    <th>Stato</th>
                                </tr>
                            </thead>
                            <tbody class="table-hover" id="tabellagaranzie"></tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <a class="font-weight-bold badge bg-warning si_newstato" href="javascript:void(0)" title="IN ATTESA">IN ATTESA</a><br />
                                        <a class="font-weight-bold badge bg-success si_newstato" href="javascript:void(0)" title="ACCETTATA">ACCETTATA</a><br />
                                        <a class="font-weight-bold badge bg-danger si_newstato" href="javascript:void(0)" title="RIFIUTATA">RIFIUTATA</a>
                                    <th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var stato = 'IN ATTESA';
    $(document).ready(function() {
        aggiornagar();
    });

    function aggiornagar() {
        var clausola = " WHERE g.Stato='" + stato + "'";
        $.post(currentURL + "assets/inc/riparazione.php", {
            azione: 'listagaranzie',
            clausola: clausola
        }, function(dati) {
            $('#tabellagaranzie').html(dati);
        })
    }

    $(document).on('click', '.si_newstato', function() {
        stato = $(this).html();
        aggiornagar();
    });
</script>