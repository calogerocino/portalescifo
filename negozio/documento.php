<?php
session_start();
if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
    $session_idu = htmlspecialchars($_SESSION['session_idu']);
    if (isset($_GET['iddoc'])) {
        $iddoc = $_GET['iddoc'];
    }
    if (isset($_GET['nuovo'])) {
        $iddoc = 'nuovo';
    }
}
?>

<style>
    #artSezList {
        list-style: none;
        margin: 0px;
        padding: 0px;
        width: 100%;
    }

    input.noinput {
        background-color: #fafafa;
    }

    table.dati input[type=text] {
        width: 100%;
        font-size: 14px;
        padding: 5px;
        padding-bottom: 6px;
    }

    table.dati input[type=text]:focus {
        outline: none;
        background-color: #f3f3f3;
    }

    div.editable {
        width: 300px;
        height: 200px;
        border: 1px solid #ccc;
        padding: 5px;
    }

    td.right {
        text-align: right;
    }
</style>

<div class="row g-3">
    <div class="col-xl-3 order-xl-1">
        <div class="card">
            <div class="card-header bg-light btn-reveal-trigger d-flex flex-between-center">
                <h5 class="mb-0">Riepilogo ordine</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless fs--1 mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <th class="ps-0">Pezzi</th>
                            <th class="pe-0 text-end" id="doc_tq">0</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Totale</th>
                            <th class="pe-0 text-end" id="doc_pt">€ 0,00</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Sconto</th>
                            <th class="pe-0 text-end" id="doc_sc">- € 0,00</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">di cui IVA</th>
                            <th class="pe-0 text-end" id="doc_iva">€ 0,00</th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="fs-2 fw-bold ps-0">Saldo</th>
                            <th class="fs-2 fw-bold pe-0 text-end" id="doc_tap">€ 0,00</th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Pagamento</th>
                            <th class="pe-0 text-end pb-0"><select class="form-select form-select-sm" id="seldoc" aria-label="Metodo di pagamento" onchange="cambiatipodoc_doc()">
                                    <option value="1">Buono di consegna</option>
                                    <option value="2">Fattura</option>
                                    <option value="3">DDT</option>
                                </select>
                            </th>
                        </tr>
                        <tr>
                            <th class="ps-0 pb-0">Cliente</th>
                            <th class="pe-0 text-end pb-0"><input id="cliente_doc" class="form-control form-control-sm auto_cl" type="search" placeholder="Cerca cliente ..." autocomplete="off">
                            </th>
                        </tr>
                        <tr>
                            <th class="ps-0">Documento</th>
                            <th class="pe-0 text-end fw-bold" id="doc_ndoc"></th>
                        </tr>
                        <tr>
                            <th class="ps-0">SDI</th>
                            <th class="pe-0 text-end"><input id="nfattag" type="text" class="form-control" onchange="CambiaNFattura_dc()"></th>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-0">Data</th>
                            <th class="pe-0 text-end"><input id="data_doc" type="date" class="form-control form-control-sm " style="width: 100%" placeholder="Data Documento"></th>
                        </tr>
                        <tr>
                            <table class="table table-borderless fs--1 mb-0">
                                <tbody id="scadrate-list">
                                    <tr>
                                        <th class="ps-0">1^ Scadenza</th>
                                        <th class="pe-0 text-end"><input id="imp_1" type="text" class="form-control form-control-sm mb-2" style="width: 100%" placeholder="Importo scadenza"><input id="scad_1" type="date" class="form-control form-control-sm " style="width: 100%" placeholder="Data Documento"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-liwght d-flex justify-content-end">
                <a class="btn btn-sm btn-primary AggiungiRigaScadenze_doc me-3" href="javascript:void(0)"><i class="fa-solid fa-plus"></i></a>
                <a class="btn btn-sm btn-primary ForzaImporto_doc me-3" href="javascript:void(0)" hidden>Forza importo</a>
                <a class="btn btn-sm btn-primary StampaDocumento_doc" href="javascript:void(0)" hidden>Stampa</a>
                <a class="btn btn-sm btn-primary nuovodoc_dcc" href="javascript:void(0)">Crea documento</a>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-auto">
                        <h5 class="mb-3 mb-md-0">Carrello prodotti</h5>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row p-3">
                    <div class="col-sm-10 col-md-4">
                        <div class="row mb-3"><label class="col-3 col-form-label-sm" for="doc_cercaprodotto">Ricerca:</label>
                            <div class="col-9"><input class="form-control form-control-sm" data-slide="barcode" id="doc_cercaprodotto" type="search" placeholder="Cerca per codici o descrizione" onchange="doc_cercaprodotto()"></div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button class="btn btn-falcon-info btn-sm AggiungiProdottoManuale_doc" type="button" title="Inserisci codice manuale">
                            <i class="fa-regular fa-pen-line"></i>
                        </button>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#livestream_scanner">
                            <i class="fa-regular fa-barcode-scan"></i>
                        </button>
                    </div>
                </div>
                <div class="lps" id="lps"></div>
                <div class="row gx-card mx-0 bg-200 text-900 fs--1 fw-semi-bold">
                    <div class="col-5 py-2">Descrizione</div>
                    <div class="col-7">
                        <div class="row">
                            <div class="col-2 py-2 d-md-block text-center">Prezzo</div>
                            <div class="col-3 py-2 d-md-block text-center">Quantita</div>
                            <div class="col-2 py-2 d-md-block text-center">IVA</div>
                            <div class="col-2 py-2 d-md-block text-center">Sc.(%)</div>
                            <div class="col-3 text-end py-2">Totale</div>
                        </div>
                    </div>
                </div>
                <div class="scrollbar" style="height:500px" id="doc_contcart" att="0">
                    <div style="text-align:center;padding-top: 100px;padding-bottom: 100px;color: #555;">
                        <i class="fa-regular fa-shopping-cart" style="font-size: 150px;"></i>
                        <div style="margin-top: 30px;font-size: 30px;">
                            <span>Il carrello è vuoto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="livestream_scanner" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                    <h4 class="mb-1" id="modalExampleDemoLabel">Scannerrica codice a barre</h4>
                </div>
                <div class="p-4 pb-0">
                    <div id="interactive" class="viewport"></div>
                    <div class="error"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var iddoc = '<?php echo $iddoc; ?>';


    $(document).ready(function(e) {
        doc_progpr = 0;
        doc_prog_rg = 1;
        if (iddoc == 'nuovo') {
            var now = new Date();
            var dd = String(now.getDate()).padStart(2, '0');
            var yyyy = now.getFullYear();
            var mm = String(now.getMonth() + 1).padStart(2, '0'); //January is 0!
            var lastDayOfMonth = new Date(yyyy, mm, 0);

            $('#data_doc').val(yyyy + '-' + mm + '-' + dd);
            $('#scad_1').val(yyyy + '-' + mm + '-' + daysInMonth(mm, yyyy));
            cambiatipodoc_doc()
        } else {
            ApriDocumento_dc(iddoc);
        }
        var myArr = new Array();
        $.post(currentURL + 'assets/inc/autocomplete.php', {
            azione: 'clienti'
        }, function(data) {
            var res = data.split(',')
            $.each(res, function(index, value) {
                myArr.push(value)
            });
            $(".auto_cl").autocomplete({
                source: myArr
            });
        });
    });

    function ApriProdotto(idpr, iddoc) {
        window.parent.chiudi_parent();
        window.parent.apri_parent_prodotto(idpr, iddoc)
    }

    function ApriDocumento_dc(ndoc) {
        bloccaui();
        $.post(currentURL + "assets/inc/documenti.php", {
                azione: 'infodoc',
                ndoc: ndoc
            },
            function(data) {
                var res = data.split('|-|');
                $('#data_doc').val(res[0]);
                $('#doc_ndoc').text(ndoc);
                $("#cliente_doc option[value^='[" + res[1] + "]']").prop('selected', true);

                //INIZIO INSERISCI RATE
                $('#scadrate-list').html('');
                $.post(currentURL + 'assets/inc/documenti.php', {
                    azione: 'cercarate',
                    ndoc: ndoc
                }, function(data) {
                    var res = data.split('|-|');
                    $('#scadrate-list').append(res[0]);
                    doc_prog_rg = res[1];


                    if ($('#imp_1').val() <= 0) {
                        $('.ForzaImporto_doc').prop('hidden', false);
                    }
                })
                //FINE INSERISCI RATE

                //INIZIO IMPOSTA SELEZIONA DOCUMENTO
                if (ndoc.includes('BC')) {
                    $('#seldoc option[value=1]').attr('selected', 'selected');
                } else if (ndoc.includes('FA')) {
                    $('#numeroagfatt').prop('hidden', false)
                    $('#nfattag').val(res[2])
                    $('#seldoc option[value=2]').attr('selected', 'selected');
                } else if (ndoc.includes('DDT')) {
                    $('#seldoc option[value=3]').attr('selected', 'selected');
                }
                //FINE IMPOSTA SELEZIONA DOCUMENTO

                //INIZIO INFO PRODOTTI
                $('#doc_contcart').html('<table class="table" style=""><tbody id="doc_contcart2"></tbody></table>');
                $('#doc_contcart').attr("att", 1)
                $.post(currentURL + 'assets/inc/documenti.php', {
                    azione: 'cercaprodottin',
                    ndoc: ndoc
                }, function(data) {
                    var res = data.split('|-|')
                    $('#doc_contcart2').append(res[0]);
                    doc_progpr = res[1];
                    calcolarighe_carico();
                    sbloccaui();
                    EventKeyUp_doc();
                    docReady(quantityInit);
                })
                //FINE INFO PRODOTTI
            }
        );

        //DISABILITA INPUT
        $('#data_doc').prop('readonly', true);
        $('#seldoc').prop('disabled', true);
        $('.nuovodoc_dcc').prop('hidden', true);
        $('.StampaDocumento_doc').prop('hidden', false);
        $('.AggiungiRigaScadenze_doc').prop('hidden', true);
    }

    function cambiatipodoc_doc() {
        bloccaui();
        var tipodoc = $("#seldoc").val();

        if (tipodoc == 1) {
            tipodoc = "BC/"
            $('#numeroagfatt').prop('hidden', true);
        } else if (tipodoc == 2) {
            tipodoc = "FA/"
            $('#numeroagfatt').prop('hidden', false);
        } else if (tipodoc == 3) {
            tipodoc = "DDT/"
            $('#numeroagfatt').prop('hidden', true);
        }

        $.ajax({
            url: currentURL + "assets/inc/documenti.php",
            method: "POST",
            data: {
                azione: 'ultimoid',
                tipodoc: tipodoc
            },
            success: function(data) {
                $("#doc_ndoc").text(tipodoc + (parseInt(data) + 1));
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


    function doc_cercaprodotto() {
        clausola = ''
        var ctn_s_descr = $('#doc_cercaprodotto').val();
        var sql;

        if (ctn_s_descr.startsWith("N0") || ctn_s_descr.startsWith("RC") || ctn_s_descr.startsWith("RO")) {
            sql = "SELECT * FROM neg_magazzino WHERE sku LIKE '%" + ctn_s_descr + "%' OR ean='" + ctn_s_descr + "'" + clausola + " ORDER BY ID DESC"
        } else {
            var mclausola = '';
            clausola = clausola + "1";
            if (ctn_s_descr != '') {
                if (clausola.length <= 2) {
                    clausola = ''
                } else {
                    clausola = 'AND ' + clausola

                }

                var blabla = '';
                var res_descr = ctn_s_descr.split(' ')
                for (const val of res_descr) {
                    if (!isNaN(parseFloat(val))) { // contiene numeri
                        blabla += ' ' + val + ' '
                    } else if (val.includes('"')) { //contiene il carattere "
                        blabla += '+' + val.replace('"', '\"') + ' '
                    } else if (val.length <= 3) {
                        blabla = blabla
                    } else {
                        blabla += '+' + val + '* '
                    }

                    mclausola += " AND (nome LIKE '%" + val + "%' OR tag LIKE '%" + val + "%') "
                }
                sql = "SELECT *, MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE) as score FROM neg_magazzino WHERE MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE)>0.001 " + mclausola + clausola + " AND Mostra=1 ORDER BY score DESC"
            }
        }
        $.post(currentURL + 'assets/inc/documenti.php', {
            azione: 'cercaprodotto',
            sql: sql
        }, function(data) {
            var sur = ' <h5 class="text-primary font-weight-bold"><svg class="header-close" width="16px" height="16px" viewBox="0 0 32 32" role="button" onclick="$(\'#lps\').html(\'\');"><path d="M15.9 14.4858l8.1929-8.193c.3905-.3904 1.0237-.3904 1.4142 0 .3905.3906.3905 1.0238 0 1.4143L17.3142 15.9l8.393 8.3929c.3904.3905.3904 1.0237 0 1.4142-.3906.3905-1.0238.3905-1.4143 0L15.9 17.3142l-8.3929 8.393c-.3905.3904-1.0237.3904-1.4142 0-.3905-.3906-.3905-1.0238 0-1.4143L14.4858 15.9l-8.193-8.1929c-.3904-.3905-.3904-1.0237 0-1.4142.3906-.3905 1.0238-.3905 1.4143 0L15.9 14.4858z"></path></svg> Risultato ricerca</h5><div class="itemdet scrollbar">' + data + '</div>';
            $('#lps').html(sur);
            document.querySelectorAll('.itemimagecont').forEach(function(item) {
                let currentSrc = item.getElementsByTagName('img').item(0).src
                var nc = currentSrc.replace('assets/page/', '');
                nc = currentSrc.replace('negozio/', '');
                item.getElementsByTagName('img').item(0).src = nc;
            });
        });
    }


    $(document).on('click', '.nuovodoc_dcc', function() {
        var cliente = $('#cliente_doc').val()
        if (cliente != '') {
            var idcl = parseInt(cliente.replace(/[^0-9\.]/g, ''), 10);
            if (idcl < 1) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Devi prima caricare un cliente'
                })
            } else if ($('#data_doc').val() == '') {
                Toast.fire({
                    icon: 'warning',
                    title: 'Inserici la data del documento'
                })
            } else if (doc_progpr == 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Carica almeno un prodotto'
                })
            } else {
                if (doc_prog_rg == 1) {
                    $('#imp_1').val($('#doc_tap').text())
                }
                var ndoc = $('#doc_ndoc').text();
                var datadoc = $('#data_doc').val();
                var tipo = $('#seldoc').val();
                var nfatt = $('#nfattag').val();
                var totale = $('#doc_tap').text();

                $.post(currentURL + 'assets/inc/documenti.php', {
                    azione: 'nuovodocn',
                    ndoc: ndoc,
                    datadoc: datadoc,
                    idcl: idcl,
                    totale: totale,
                    nfatt: nfatt
                }, function(response) {
                    if (response == 'si') {
                        //INIZIO CARICO PRODOTTI
                        for (ii = 1; ii <= doc_progpr; ii++) {
                            var prez = $('#cartcont_pr_' + ii).text();
                            var quant = $('#cartcont_qt_' + ii).val();
                            if (prez > 0 && quant >= 1) {
                                $.post(currentURL + "assets/inc/documenti.php", {
                                    azione: 'nuovorelpon',
                                    ndoc: ndoc,
                                    idp: $('#cart_ds_' + ii).attr('idprn'),
                                    desc: $('#cart_ds_' + ii).text(),
                                    quant: quant,
                                    prez: prez,
                                    iva: $('#cartcont_iva_' + ii).text(),
                                    sconto: $('#cartcont_rs_' + ii).text()
                                });
                            }
                        }
                        //FINE CARICO PRODOTTI
                        //INIZIO CARICO SCADENZE
                        for (ii = 1; ii <= doc_prog_rg; ii++) {
                            var datarata = $('#scad_' + ii).val();
                            $.post(currentURL + "assets/inc/documenti.php", {
                                azione: 'nuovaratan',
                                ndoc: ndoc,
                                idcl: idcl,
                                nrata: ii,
                                datarata: datarata,
                                importorata: $('#imp_' + ii).val()
                            });
                        }
                        //FINE CARICO SCADENZE
                        Toast.fire({
                            icon: 'info',
                            title: 'Documento creato'
                        })
                        location.reload();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore: ' + response
                        })
                    }

                })
            }
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Il documento va intestato a un cliente, inseriscine uno.'
            })
        }
    });

    function CaricaProdotto_doc(id_src) {
        if ($('#doc_contcart').attr("att") == 1) {
            doc_progpr = doc_progpr + 1;
            $.post(currentURL + 'assets/inc/documenti.php', {
                azione: 'prodottolista',
                idpr: id_src,
                prog: doc_progpr
            }, function(data) {
                $('#doc_contcart2').append(data);
                docReady(quantityInit);
                FDoc_CalcolaTotale();
                EventKeyUp_doc();
            })
        } else if ($('#doc_contcart').attr("att") == 0) {
            doc_progpr = doc_progpr + 1;
            $.post(currentURL + 'assets/inc/documenti.php', {
                azione: 'prodottolista',
                idpr: id_src,
                prog: doc_progpr
            }, function(data) {
                $('#doc_contcart').html('<table class="table" style=""><tbody id="doc_contcart2">' + data + '</tbody></table>');
                $('#doc_contcart').attr("att", 1)
                docReady(quantityInit);
                FDoc_CalcolaTotale();
                EventKeyUp_doc();
            })
        }
        $('#doc_cercaprodotto').val('')
        $("#doc_cercaprodotto").focus()
        $('#lps').html('');
    }

    function EventKeyUp_doc() {
        $('[id^=cartcont]').keyup(function() {
            if (typeof cartTimeout != 'undefined')
                window.clearTimeout(cartTimeout);
            element = $(this);
            cartTimeout = window.setTimeout(function() {
                cartCont_doc(element)
            }, 500);
        })

        // document.querySelectorAll('.iic').forEach(function(item) {
        //     let currentSrc = item.getElementsByTagName('img').item(0).src
        //     var nc = currentSrc.replace('assets/page/', '');
        //     nc = currentSrc.replace('negozio/', '');
        //     item.getElementsByTagName('img').item(0).src = nc;
        // });
    }

    function cartCont_doc(element) {
        id_riga = $(element).attr('id');
        id_riga = $(this)[0].id_riga.split('_');
        console.log(element)
        console.log($('#cartcont_qt_' + id_riga[2]).val())
        var q = $('#cartcont_qt_' + id_riga[2]).val();
        var s = $('#cartcont_rs_' + id_riga[2]).text();
        var p = $('#cartcont_pr_' + id_riga[2]).text();
        p = p.replace(",", ".");
        $('#cartcont_pr_' + id_riga[2]).html(p);
        $('#doc_tot' + id_riga[2]).html(number_format((q * parseFloat(p)), 2, '.', ''));
        if (s >= 1 || s != '') {
            $('#doc_tot' + id_riga[2]).html(number_format(Number((q * parseFloat(p))) * ((100 - Number(s)) / 100), 2, '.', ''));
        }
        FDoc_CalcolaTotale()
    }

    async function cartCont_docRitardato(element) {
        await delay(1);
        cartCont_doc(element)
    };

    function calcolarighe_carico() {
        for (ir = 1; ir <= doc_progpr; ir++) {
            var q = $('#cartcont_qt_' + ir).val();
            var s = $('#cartcont_rs_' + ir).text();
            var p = $('#cartcont_pr_' + ir).text();
            p = p.replace(",", ".");
            $('#cartcont_pr_' + ir).html(p);
            $('#doc_tot' + ir).html(number_format((q * parseFloat(p)), 2, '.', ''));


            if (s >= 1 || s != '') {
                $('#doc_tot' + ir).html(number_format(Number((q * parseFloat(p))) * ((100 - Number(s)) / 100), 2, '.', ''));
            }
        }
        FDoc_CalcolaTotale()
    }

    function FDoc_CalcolaTotale() {
        var qt = 0;
        var pt = 0;
        var qtt = 0;
        var ptt = 0;
        var sc = 0;
        var sct = 0;
        var psc = 0;

        for (ii = 1; ii <= doc_progpr; ii++) {
            if ($('#cartcont_qt_' + ii).val() != '') {
                qtt = $('#cartcont_qt_' + ii).val();
                ptt = $('#doc_tot' + ii).text();
                qt = (qt + parseInt(qtt));
                pt = (pt + parseFloat(ptt));
                psc += (qtt * parseFloat($('#cartcont_pr_' + ii).text()));
                sc = parseInt($('#cartcont_rs_' + ii).text());
                if (sc >= 1) {
                    sct = (sct + (psc - ptt));
                }
            }
        }
        $('#doc_sc').text(number_format(sct, 2, '.', ''));
        $('#doc_tq').text(qt);
        $('#doc_pt').text(number_format(psc, 2, '.', ''));
        var iva = (pt - ((100 * pt) / 122))
        $('#doc_iva').text(number_format(iva, 2, '.', ''));
        $('#doc_tap').text(number_format(pt, 2, '.', ''));

    }


    function EliminaRiga_bn(idr) {
        $('#idr' + idr).remove();
        FDoc_CalcolaTotale();
    }

    function CambiaNFattura_dc() {
        if (iddoc != 'nuovo') {
            $.post(currentURL + 'assets/inc/documenti.php', {
                azione: 'modificafatt',
                ndoc: $('#doc_ndoc').text(),
                nfatt: $('#nfattag').val()
            }, function(response) {
                if (response == 'si') {
                    Toast.fire({
                        icon: 'success',
                        title: 'N° Fattura modificato con successo'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Errore:' + response
                    })
                }
            })
        }
    }

    $(document).on('click', '.StampaDocumento_doc', function() {
        var id = $('#doc_ndoc').text();
        window.open(currentURL + 'assets/pdf/buono.php?create_pdf=1&iddoc=' + id, 'PDF Documento', 'width=800, height=800, status, scrollbars=1, location');
    });

    $(document).on('click', '.ForzaImporto_doc', function() {
        var newimporto_doc = $('#doc_tap').text();
        if (newimporto_doc >= 1) {
            $.post(currentURL + 'assets/inc/documenti.php', {
                azione: 'forzaimporto',
                ndoc: $('#doc_ndoc').text(),
                nuovoimporto: newimporto_doc
            }, function(response) {
                if (response == 'si') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Importo forzato con successo!'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Errore:' + response
                    })
                }
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore, impossibile risolvere il problema'
            })
        }
    });



    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>