<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Lista vendite</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="VenditeMagazzinoTable" data-list='{"valueNames":["t1", "t2", "t3", "t4"],"page":10,"pagination":true}'>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row mb-3"><label class="col-3 col-form-label-sm" for="rvbn_searchdata">Ricerca:</label>
                        <div class="col-9"><input class="form-control form-control-sm" id="rvbn_searchdata" type="date" placeholder="Cerca per data" onchange="re_vbn_upd()" /></div>
                    </div>
                </div>
            </div>
            <div class="row" id="rvbn_oldtable" hidden>
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <a href="javascript:void(0)" class="text-primary rvbn_back"><i class="fa-regular fa-arrow-left"></i> Torna indietro</a>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered fs--1 mb-0" id="rvbn_dataTable">
                    <thead class="bg-200 text-900">
                        <tr id="rvbn_HeadTabellaVendite"></tr>
                    </thead>
                    <tbody class="list" id="rvbn_BodyTabellaVendite"></tbody>
                    <tfoot class="bg-200 text-900">
                        <tr id="rvbn_FootTabellaVendite"></tr>
                    </tfoot>
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
    $(document).ready(function() {
        document.getElementById('rvbn_searchdata').valueAsDate = new Date();
        re_vbn_upd();
    });

    function re_vbn_upd() {
        bloccaui();
        today = new Date()
        var data = $('#rvbn_searchdata').val();

        if (data != '') {
            data = $('#rvbn_searchdata').val();
        } else {
            if (today.getMonth() <= 8) {
                var mese = '0' + (today.getMonth() + 1)
            } else {
                var mese = (today.getMonth() + 1)
            }
            if (today.getMonth() <= 9) {
                var giorno = '0' + today.getDate()
            } else {
                var giorno = today.getDate()
            }

            data = today.getFullYear() + '-' + mese + '-' + giorno
        }

        $.post(currentURL + "assets/inc/banconeg.php", {
            azione: 'venditebanco',
            data: data
        }, function(data) {
            var res = data.split('|-|')
            $('#rvbn_HeadTabellaVendite').html(res[0]);
            $('#rvbn_BodyTabellaVendite').html(res[1]);
            $('#rvbn_FootTabellaVendite').html(res[2]);
            docReady(listInit);
            sbloccaui();
        })
    }

    $(document).on('click', '.rvbn_aprivend', function() {
        vecchiatabella = $('#rvbn_dataTable').html();
        bloccaui();
        var idv = $(this).attr('idv');
        $.post(currentURL + "assets/inc/banconeg.php", {
            azione: 'prodottivendite',
            idv: idv
        }, function(data) {
            var res = data.split('|-|')
            $('#rvbn_HeadTabellaVendite').html(res[0]);
            $('#rvbn_BodyTabellaVendite').html(res[1]);
            $('#rvbn_FootTabellaVendite').html(res[2]);
            docReady(listInit);
            $('#rvbn_oldtable').prop('hidden', false);
            sbloccaui();
        })
    });

    $(document).on('click', '.rvbn_back', function() {
        $('#rvbn_dataTable').html(vecchiatabella);
        vecchiatabella = '';
        $('#rvbn_oldtable').prop('hidden', true);
    });
</script>