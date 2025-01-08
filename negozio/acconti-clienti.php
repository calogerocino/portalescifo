<!DOCTYPE html>
<html lang="it">

<?php
error_reporting(0);
?>

<head>
    <title>Acconti clienti - PixelSmart</title>
    <link href="../app/fa/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../app/app.min.css" rel="stylesheet">
    <link rel="icon" href="../img/fav.png" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row ml-2 mb-3">
                                        <h3 class="m-0 font-weight-bold text-primary">SCHEDA ACCONTI</h3>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa-duotone fa-euro-sign"></i></span>
                                                    </div>
                                                    <input id="accontocons" type="text" class="form-control" autocomplete="off"></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-comment"></i></span>
                                                    </div>
                                                    <input id="commentoacconto" type="text" class="form-control" autocomplete="off"></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                    <input id="dataacconto" type="date" class="form-control" autocomplete="off"></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                    <select class="form-control selectpicker" data-style="btn btn-link" id="tippag_ac" data-live-search="true" data-size="7">
                                                        <option value="-" selected>Nessuno</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-outline-success salvaacc_ac">Aggiungi pagamento</a>
                                        </div>
                                    </div>
                                    <div class="row mr-3 ml-3">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Descrizione</th>
                                                                <th>Data operazione</th>
                                                                <th>Credito</th>
                                                                <th>Saldo</th>
                                                                <th>Pagamento</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-hover" id="tabellaacconti_ac"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                                <th id="totaleacconti"></th>
                                                                <th></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="../app/jq/jquery.min.js"></script>
            <script src="../app/bs/bootstrap-notify.min.js"></script>
            <script src="../app/bs/sweetalert2.all.min.js"></script>
            <script src="../app/jq/blockui.min.js"></script>

            <script>

                function bloccaui() {
                    $.blockUI({
                        message: '<div class="dataTables_processing card"> <div class="spinner spinner-primary spinner-lg mr-15"><div class="d-flex justify-content-center">Caricamento ...</div></div></div>'
                    });
                }

                function sbloccaui() {
                    $.unblockUI({});
                }
            </script>
            <script>
                var idcl = <?php echo $_GET['idcl']; ?>;

                $(document).ready(function() {
                    aggiorna();
                    aggiungimodpag_doc();
                });

                function aggiorna() {
                    bloccaui();
                    var saldo1 = 0;
                    var saldo2 = 0


                    bloccaui();
                    $.ajax({
                        url: currentURL + "assets/inc/documenti.php",
                        method: "POST",
                        data: {
                            azione: 'acconticlientet2',
                            idcl: idcl
                        },
                        success: function(data) {
                            $("#tabellaacconti_ac").html(data);
                            sbloccaui();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Errore nel gestire la richiesta'
                            })
                            sbloccaui();
                        }
                    });

                }

                $(document).on('click', '.salvaacc_ac', function() {
                    bloccaui();
                    $('.salvaacc_ac').prop('disabled', true);
                    var comm = $('#commentoacconto').val();
                    var acc = $('#accontocons').val();
                    var dataacc = $('#dataacconto').val();
                    var pagacc = $('#tippag_ac').val();

                    if (idcl == '' || idcl == null) {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Prima di aggiungere un acconto devi caricare il cliente'
                        })
                    } else if (acc == '' || acc == null) {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Devi inserire l\'importo dell\'acconto'
                        })
                    } else {

                        acc = acc.replace(',', '.');
                        acc = parseFloat(acc).toFixed(2);

                        $.ajax({
                            url: currentURL + "assets/inc/documenti.php",
                            method: "POST",
                            data: {
                                azione: 'addacconto',
                                IDcl: idcl,
                                comm: comm,
                                acc: acc,
                                dataacc: dataacc,
                                pagacc: pagacc
                            },
                            success: function(response) {
                                if (response == 'no') {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Errore nel caricamento, si prega di controllare i dati inseriti'
                                    })
                                } else {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Acconto aggiunto con successo'
                                    })
                                    $('commentoacconto').val('');
                                    $('accontocons').val('');
                                    $("#ttabellaacconti_ac").html('');
                                    aggiorna();
                                }
                                sbloccaui();
                            },
                            error: function(response) {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Errore: ' + response
                                })
                                $('.salvaacc_ac').prop('disabled', false);
                                sbloccaui();
                            }
                        });
                    }
                });


                $(document).on('click', '.eliminaacc', function() {
                    var data = new FormData();

                    data.append("mittente", 'noreply');
                    data.append("indirizzodest", 'assistenza@scifostore.com');
                    data.append("oggetto", 'Richiesta eliminazione Acconto Fornitore');
                    data.append("corpo", 'E\' stata richiesta l\'eliminazione dell\'acconto sul cliente ID:' + idcl);


                    $.ajax({
                        url: currentURL + "assets/mail/invio_mail.php",
                        method: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: data,
                        success: function(response) {
                            Swal.fire({
                                title: 'OK!',
                                text: 'Richiesta effettuata con successo \n riceverai una conferma ad avvenuta eliminazione...',
                                icon: 'info',
                                confirmButtonText: 'Chiudi'
                            })
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Errore nel gestire la richiesta'
                            })
                        }
                    });
                });

                function aggiungimodpag_doc() {
                    $.ajax({
                        url: currentURL + "assets/inc/documenti.php",
                        method: "POST",
                        data: {
                            azione: 'modpag'
                        },
                        success: function(data) {
                            $("#tippag_ac").html(data);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Errore nel gestire la richiesta'
                            })
                        }
                    });
                }


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
            </script>
            <!-- End of Footer -->
</body>

</html>