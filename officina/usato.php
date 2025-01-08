<?php
if (isset($_GET['idr'])) {
    $idr = $_GET['idr'];
} else {
    $idr = '';
}
?>

<div class="container-fluid">
    <div class="row g-0">
        <div class="col-12">
            <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
                <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1">ATTENZIONE! Sistema usato in revisione. Potrebbero presentarsi problemi all'utilizzo.</p>
            </div>
        </div>
    </div>
    <div class="row" id="listausato">
        <div class="col-md-12">
            <div class="card card-custom shadow mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <h6 class="card-label text-primary font-weight-bold">Lista usato</h6>
                    </div>
                    <div class="card-toolbar">
                        <a href="javascript:void(0)" class="btn btn-sm btn-light-primary us_nuovous">
                            <i class="fa-duotone fa-plus"></i> Inserisci nuova</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 dataTables_wrapper">
                            <div class="dataTables_filter">
                                <label class="bmd-label">Filtra per:
                                    <select class="form-control form-control-sm selectpicker" onchange="aggiornaus()" data-style="btn btn-link" id="sceltastato">
                                        <option selected>IN VENDITA</option>
                                        <option>NOLEGGIATO</option>
                                        <option>VENDUTO</option>
                                    </select></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive tabellausato">
                            Caricamento ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="aggiungiusato" hidden>
        <div class="row">
            <div class="col-md-12 my-auto">
                <a href="javascript:void(0)" class="float-left text-primary us_indietroscheda"><i class="fa-duotone fa-arrow-left"></i> Torna indietro</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="col-lg-12 ml-auto mr-auto">
                    <!-- Default Card Example -->
                    <div class="card shadow mt-4 mb-4">
                        <div class="card-header pb-0">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Dettagli Macchina</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-description">
                                <input id="idusato" type="text" class="form-control" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label">Marchio</label>
                                                <input id="marchio" type="search" class="form-control auto1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label">Prodotto</label>
                                                <input id="prodotto" type="search" class="form-control auto2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label">Modello</label>
                                                <input id="modello" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label">Seriale</label>
                                                <input id="us_seriale" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-lg-12 ml-auto mr-auto">
                    <!-- Default Card Example -->
                    <div class="card shadow mt-4 mb-4">
                        <div class="card-header pb-0">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fa-duotone fa-money-bill"></i> Dettagli valore</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-description">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Valore macchina</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa-duotone fa-wallet"></i>
                                                        </span>
                                                    </div>
                                                    <input id="permuta" type="text" class="form-control" onchange="cambiavalore()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Costo Riparazione</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa-duotone fa-wrench"></i>
                                                        </span>
                                                    </div>
                                                    <input id="riparazione" type="text" class="form-control" onchange="cambiavalore()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Costo Vendita</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa-duotone fa-money-bill"></i>
                                                        </span>
                                                    </div>
                                                    <input id="vendita" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-lg-12 ml-auto mr-auto">
                    <!-- Default Card Example -->
                    <div class="card shadow mt-4 mb-4">
                        <div class="card-header pb-0">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fa-duotone fa-info"></i> Dettagli usato</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-description">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Note</label>
                                    <textarea id="note" type="text" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="salvaschedaid"> <a href="javascript:void(0)" class="float-right d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm btn-block us_salvaus"><i class="fa-duotone fa-save fa-sm text-white-50"></i> Salva</a></div>
        <div id="modificascheda"> <a href="javascript:void(0)" class="float-right d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm btn-block us_modificaus"><i class="fa-duotone fa-save fa-sm text-white-50"></i> Aggiorna</a></div>

    </div>
</div>

<script>
    var idr = '<?php echo $idr; ?>'
    $(document).ready(function(e) {
        aggiornaus();

        if (idr != '') {
            $('#listausato').prop('hidden', true);
            $('#aggiungiusato').prop('hidden', false);

            $.post(currentURL + "assets/inc/usato.php", {
                azione: 'schedadaid',
                idr: idr
            }, function(data) {
                var res = data.split('|-|');
                $('#prodotto').val(res[0]);
                $('#modello').val(res[1])
                $('#marchio').val(res[2])
                $('#seriale').val(res[3])
                $('#riparazione').val(res[4])
            })

            bloccadettmacch()
        } else {
            aggiornaus();
            $('#listausato').prop('hidden', false);
            $('#aggiungiusato').prop('hidden', true);
            sbloccadettmacch()
        }
    });

    var myArr = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'marchi'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr.push(value)
        });
        $(".auto1").autocomplete({
            source: myArr
        });
    });
    var myArr1 = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'prodotti'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr1.push(value)
        });
        $(".auto2").autocomplete({
            source: myArr1
        });
    });
</script>