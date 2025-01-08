<?php
session_start();
error_reporting(0);

include("../assets/inc/database.php");



?>

<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Log attività</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="CatalogoTable" data-list='{"valueNames":["azione", "data"],"page":10,"pagination":true}'>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="text-center">ID Movimento</th>
                            <th class="sort" data-sort="azione">Azione</th>
                            <th class="text-center sort" data-sort="data">Data</th>
                        </tr>
                    </thead>
                    <tbody class="list scrollbar" id="ListaMovimenti_at">
                    </tbody>
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
        bloccaui();
        $.post(currentURL + 'assets/inc/profilo.php', {
            listaattivita: ''
        }, function(data) {
            console.log(data)
            $('#ListaMovimenti_at').html(data);
            docReady(listInit);
            sbloccaui();
        });
    });
</script>