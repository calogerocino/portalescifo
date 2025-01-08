<!DOCTYPE html>
<html lang="it">

<?php
error_reporting(0);
?>

<head>
    <title>Scheda Riparazione | itManagement</title>
    <?php include("page/header.php");
    if (isset($_GET['idrip']))
        $idriparazione = $_GET['idrip'];
    ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("page/navbar.php"); ?>
                <div class="container-fluid">
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
                                <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
                                <p class="mb-0 flex-1">ATTENZIONE! Sistema riparazione in revisione. Potrebbero presentarsi problemi all'utilizzo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header-tabs py-3">
                                <ul class="nav nav-pills mb-3" data-tabs="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#anagraficatab" data-toggle="tab">
                                            <i class="fa-duotone fa-info-circle"></i> Anagrafica
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#inouttab" data-toggle="tab">
                                            <i class="fa-duotone fa-sign-in-alt"></i> Ingresso/Uscita
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#prodottitab" data-toggle="tab">
                                            <i class="fa-duotone fa-box"></i> Prodotti
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item" id="statogaranzia" hidden>
                                        <a class="nav-link" href="#statogaranziatab" data-toggle="tab">
                                            <i class="fa-duotone fa-book-reader"></i> Stato Garanzia
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#richiedigaranziatab" data-toggle="tab">
                                            <i class="fa-duotone fa-toolbox"></i> Richiedi Garanzia
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="anagraficatab">

                                    <div class="row">
                                        <div class="col-lg-4 ml-auto mr-auto">
                                            <!-- Default Card Example -->
                                            <div class="card shadow mt-4 mb-4">
                                                <div class="card-header pb-0">
                                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Dettagli cliente</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-description">
                                                        <input id="idcliente" type="text" hidden>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-floating mb-3">
                                                                <input id="clientecl" type="text" class="form-control">
                                                                <label for="clientecl">Cliente</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="bmd-label">Indirizzo</label>
                                                                <input id="indcl" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">Cellulare</label>
                                                                <input id="cellcl" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">E-Mail</label>
                                                                <input id="mailcl" type="email" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 ml-auto mr-auto">
                                            <!-- Default Card Example -->
                                            <div class="card shadow mt-4 mb-4">
                                                <div class="card-header pb-0">
                                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-address-book"></i> Dati Azienda</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-description">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">Azienda</label>
                                                                <input id="ragsocaz" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">Indirizzo</label>
                                                                <input id="indaz" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">P.IVA</label>
                                                                <input id="pivaz" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label">SDI</label>
                                                                <input id="sdizienda" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="inouttab">

                                </div>
                                <div class="tab-pane" id="prodottitab">

                                </div>
                                <div class="tab-pane" id="statogaranziatab" id="statogaranzia" hidden>

                                </div>
                                <div class="tab-pane" id="richiedigaranziatab">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include("page/footer.php"); ?>
</body>
<script>
    var idrip = '<?php echo $idriparazione; ?>';

    $(document).ready(function() {
        if (idrip != '') {
            caricariparazione()
        }
    });


    function caricariparazione() {
        $.ajax({
            url: "assets/inc/riparazione.php",
            method: "POST", //First change type to method here
            data: {
                azione: 'loadscheda',
                idrip: idrip
            },
            success: function(data) {

            },
            error: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Vendita effettuata con successo'
                })
            }
        });
    }
</script>

</html>