<!DOCTYPE html>
<html lang="it">

<?php
//error_reporting(0);
include("../assets/inc/database.php");
$opzioni = "";
$opzioni2 = "";
$opzioni3 = "";
$opzionintestatario = "";
$opzionbanca = "";
$opzionpagamento = "";

//FORNITORE
$sql = "SELECT ID, Ragione_Sociale FROM ctb_fornitore ORDER BY Ragione_Sociale ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($_GET['idfor'] == $row["ID"]) {
            $select = 'selected';
        } else {
            $select = '';
        }
        $opzioni .= "<option value=\"" . $row["ID"] . "\" $select>" . $row["Ragione_Sociale"] . "</option>";
    }
}

$opzionintestatario .= "<select class=\"form-control selectpicker\" data-style=\"btn btn-link\" name=\"intestatario\" id=\"intestatario\">";
$opzionintestatario .= $opzioni;
$opzionintestatario .= "</select>";

//BANCA
$sql = "SELECT ID, dato FROM ctb_dati WHERE tipo=3 ORDER BY dato ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $opzioni2 .= "<option value=\"" . $row["ID"] . "\">" . $row["dato"] . "</option>";
    }
}

$opzionbanca .= "<select class=\"form-control selectpicker\" data-style=\"btn btn-link\" name=\"banca\" id=\"banca\">";
$opzionbanca .= $opzioni2;
$opzionbanca .= "</select>";

//PAGAMENTO
$sql = "SELECT ID, dato FROM ctb_dati WHERE tipo=4 ORDER BY dato ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $opzioni3 .= "<option value=\"" . $row["ID"] . "\">" . $row["dato"] . "</option>";
    }
}

$opzionpagamento .= "<select class=\"form-control selectpicker\" data-style=\"btn btn-link\" name=\"pagamento\" id=\"pagamento\">";
$opzionpagamento .= $opzioni3;
$opzionpagamento .= "</select>";

$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

?>

<head>
    <title>Carica Pagamento - PixelSmart</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
    <script src="../app/js/config.js?filever=<?= filesize('app/js/config.js') ?>"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="../vendors/overlayscrollbars/OverlayScrollbars.min.css" rel="stylesheet">
    <link href="../app/css/theme-rtl.min.css" rel="stylesheet" id="style-rtl">
    <link href="../app/css/theme.min.css?filever=<?= filesize('app/css/theme.min.css') ?>" rel="stylesheet" id="style-default">
    <link href="../app/css/user-rtl.min.css" rel="stylesheet" id="user-style-rtl">
    <link href="../app/css/user.min.css?filever=<?= filesize('app/css/user.min.css') ?>" rel="stylesheet" id="user-style-default">
    <link href="../app/css/swal2.css" rel="stylesheet" type="text/css">
    <link href="../vendors/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="../vendors/jquery/jquery.min.js"></script>
    <script src="../vendors/jquery/jquery.easing.min.js"></script>
</head>

<body>
    <main class="main" id="top">
        <div class="container" data-layout="container">
            <div class="content">
                <div id="contenutopagina1">
                    <!-- INIZIO CONTENUTO -->
                    <input id="idpag" type="text" class="form-control" value="<?php echo $id; ?>" hidden>
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="row flex-between-center">
                                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Scheda pagamento</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <?php echo $opzionbanca; ?>
                                                <label for="banca">Banca</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="date" type="date" autocomplete="off" />
                                                <label for="date">Data Scadenza</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <input name="importo" id="importo" type="text" class="form-control" placeholder=" " onkeyup="$(this).val($(this).val().replace(',','.'));" required>
                                                <label for="importo">Importo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <?php echo $opzionintestatario; ?>
                                                <label for="intestatario">Intestatario</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <input name="nassegno" id="nassegno" type="text" class="form-control" value="" autocomplete="off" placeholder=" ">
                                                <label for="nassegno">Riferimento pagamento</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <?php echo $opzionpagamento; ?>
                                                <label for="pagamento">Tipo pagamento</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <input name="nfattura" id="nfattura" type="text" class="form-control" value="" placeholder=" ">
                                                <label for="nfattura">N° Fattura</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="form-floating mb-3">
                                                <textarea name="notepag" id="notepag" type="text" class="form-control" value="" rows="8" placeholder=" "></textarea>
                                                <label for="notepag">Note</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="formupload">
                                        <button id="CaricaAllegato_cp" href="javascript:void(0);" class="btn btn-light btn-icon-split CaricaAllegato_cp">
                                            <span class="icon text-gray-600">
                                                <i class="far fa-image"></i>
                                            </span>
                                            <span class="text">Carica allegato</span>
                                        </button>
                                    </div>
                                    <div class="form-group" id="formvisualizza">
                                        <button id="VisualizzaAllegato_cp" href="javascript:void(0);" class="btn btn-light btn-icon-split VisualizzaAllegato_cp">
                                            <span class="icon text-gray-600">
                                                <i class="far fa-image"></i>
                                            </span>
                                            <input id="pag_nomefile" type="text" hidden>
                                            <span class="text">Visualizza allegato</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button id="controllo" href="javascript:void(0);" class="btn btn-light btn-icon-split ControllaPagamento">
                                        <span class="icon text-gray-600">
                                            <i class="fa-duotone fa-check-double"></i>
                                        </span>
                                        <span class="text">Controllo</span>
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button id="carica" href="javascript:void(0);" cosa="carica" class="btn btn-success btn-icon-split carica">
                                        <span class="icon text-white-50">
                                            <i class="fa-duotone fa-arrow-right"></i>
                                        </span>
                                        <span class="text">Carica</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div class="table-responsive">
                                                <table class="table table-bordered fs--1 mb-0">
                                                    <thead class="bg-200 text-900">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Importo</th>
                                                            <th>Data</th>
                                                            <th>Descrizione</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="list" id="tabellapagamenti">
                                                        <td class="text-center py-2 align-middle" colspan="7">Nessun dato disponibile</td>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- FINE CONTENUTO -->
                </div>
                <footer class="footer">
                    <div class="row g-0 justify-content-between fs--1 mt-4 mb-3">
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">PixelSmart <span class="d-none d-sm-inline-block">|
                                </span><br class="d-sm-none" /> 2021 &copy;</p>
                        </div>
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">v3.0.0 <strong>¯\_(ツ)_/¯</strong></p>
                        </div>
                    </div>
                </footer>
            </div>


        </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="../vendors/popper/popper.min.js"></script>
    <script src="../vendors/bootstrap/bootstrap.min.js"></script>
    <script src="../vendors/anchorjs/anchor.min.js"></script>
    <script src="../vendors/is/is.min.js"></script>
    <script src="../vendors/fontawesome/all.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="../app/js/blockui.min.js"></script>
    <script src="../app/js/autocomplete.min.js"></script>
    <script src="../app/js/sweetalert2.all.min.js"></script>
</body>










<script src="../assets/js/CaricaPagamento.js"></script>
<script>
    var currentURL = $(location).attr('protocol') + '//' + $(location).attr('hostname') + '/';
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function bloccaui() {
        $.blockUI({
            message: '<img src="../assets/img/generic/loading.svg">',
            css: {
                backgroundColor: 'rgb(0 0 0 / 0%)',
            }
        });
        $('#top').css('-webkit-filter', 'blur(3px)');
        x = window.setTimeout("sbloccaui(1)", 10000);
    }


    function sbloccaui(i = 0) {
        window.clearInterval(x)
        $.unblockUI({});
        $('#top').css('-webkit-filter', '');
        if (i == 1) {
            Toast.fire({
                icon: 'warning',
                title: 'E\' stato rilevato un errore, la pagina è stata sbloccata. Potrebbe essere necessario un refresh della pagina!'
            })
        }
    }


    $(document).ready(function() {
        var id = '<?php echo $id; ?>';
        if (id != '') {
            $('#carica').attr('cosa', 'aggiorna');
        }
        var cosa = $('#carica').attr('cosa')
        if (cosa == 'aggiorna') {
            CaricaPagamento()
        }
    });
</script>

</html>