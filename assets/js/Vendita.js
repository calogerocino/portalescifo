var vbn_progpr = 0;

function CaricaProdotto_bn(id_src) {
    // var regex = /\[([^)]+)\]/;
    // var res = regex.exec($('#vbn_cercaprodotto').val());
    if ($('#vbn_contcart').attr("att") == 1) {
        vbn_progpr = vbn_progpr + 1;
        $.post(currentURL + 'assets/inc/banconeg.php', { azione: 'prodottolista', idpr: id_src, prog: vbn_progpr }, function (data) {
            $('#vbn_contcart2').append(data);
            LeggiPulsantiMP()
            CalcolaTotale_bn();
            fvbn_calctot_PREV()
            eventkeyup();
        })
    } else if ($('#vbn_contcart').attr("att") == 0) {
        vbn_progpr = vbn_progpr + 1;
        $.post(currentURL + 'assets/inc/banconeg.php', { azione: 'prodottolista', idpr: id_src, prog: vbn_progpr }, function (data) {
            $('#vbn_contcart').html('<div id="vbn_contcart2">' + data + '</div>');
            LeggiPulsantiMP();
            $('#vbn_contcart').attr("att", 1)
            CalcolaTotale_bn();
            fvbn_calctot_PREV()
            eventkeyup();
        })
    }
    $('#vbn_cercaprodotto').val('')
    $("#vbn_cercaprodotto").focus()
    $('#lps').html('');
}

function CercaProdotto_bn() {
    clausola = ''
    var ctn_s_descr = $('#vbn_cercaprodotto').val();
    var ctn_s_mag = ControllaMagazzinoSelezionato();
    var sql;
    if (ctn_s_mag != '0') {
        clausola = clausola + "tipo=" + ctn_s_mag + " AND ";
    }

    // if (ctn_s_descr.startsWith("N0") || ctn_s_descr.startsWith("RC") || ctn_s_descr.startsWith("RO")) {
    //     sql = "SELECT * FROM neg_magazzino WHERE sku LIKE '%" + ctn_s_descr + "%' OR ean LIKE '" + ctn_s_descr + "%'" + clausola + " ORDER BY ID DESC"
    // } else {
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
            sql = "SELECT *, MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE) as score FROM neg_magazzino WHERE (MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE)>0.001 " + mclausola + clausola + ") OR (sku LIKE '%" + ctn_s_descr + "%' OR ean LIKE '" + ctn_s_descr + "%'" + clausola + ") AND Mostra=1 ORDER BY score DESC"
    } else {
            if (clausola.length <= 2) {
                clausola = ''
                if (ctn_s_mag == '1') {
                    clausola = clausola + "(sku LIKE 'RC%' OR sku LIKE 'RO%') AND ";
                } else {
                    clausola = clausola + "sku LIKE 'N0%' AND ";
                }
            }
            clausola = clausola + "1";
            sql = "SELECT * FROM neg_magazzino WHERE " + clausola + " AND Mostra=1 ORDER BY NOME ASC"
        }
    // }
    $.post(currentURL + 'assets/inc/banconeg.php', {
        azione: 'cercaprodotto',
        sql: sql
    }, function (data) {
        var sur = ' <h5 class="text-primary font-weight-bold">Risultato ricerca</h5><div class="itemdet scrollbar">' + data + '</div>';
        $('#lps').html(sur);
    });
}

function EliminaRiga_bn(idr) {
    $('#idr' + idr).remove();
    CalcolaTotale_bn();
}

function eventkeyup() {
    $('[id^=cartcont]').keyup(function () {
        if (typeof cartTimeout != 'undefined')
            window.clearTimeout(cartTimeout);
        element = $(this);
        cartTimeout = window.setTimeout(function () {
            cartCont(element)
        }, 500);
    })
}

function cartCont(element) {
    id_riga = element.attr('id');
    id_riga = $(this)[0].id_riga.split('_');
    var q = $('#cartcont_qt_' + id_riga[2]).val();
    var s = $('#cartcont_rs_' + id_riga[2]).text();
    var p = $('#cartcont_pr_' + id_riga[2]).text();
    p = p.replace(",", ".");
    $('#cartcont_pr_' + id_riga[2]).html(p);
    $('#vbn_tot' + id_riga[2]).html(number_format((q * parseFloat(p)), 2, ',', '.'));

    if (s >= 1 || s != '') {
        $('#vbn_tot' + id_riga[2]).html(number_format(Number((q * parseFloat(p))) * ((100 - Number(s)) / 100), 2));
    }
    CalcolaTotale_bn()
    fvbn_calctot_PREV()
}

function CalcolaTotale_bn() {
    var qt = 0;
    var pt = 0;
    var qtt = 0;
    var ptt = 0;
    var sc = 0;
    var pr = 0;
    var sct = 0;

    for (ii = 1; ii <= vbn_progpr; ii++) {
        if ($('#cartcont_qt_' + ii).val() != '') {
            qtt = $('#cartcont_qt_' + ii).val();
            ptt = $('#vbn_tot' + ii).text();
            qt = (qt + parseInt(qtt));
            pt = (pt + parseFloat(ptt));

            sc = parseInt($('#cartcont_rs_' + ii).text());
            if (sc >= 1) {
                pr = parseFloat($('#cartcont_pr_' + ii).text());
                sct = (sct + (pr - ptt));
            }
        }
    }

    if (qt == 0 || isNaN(qt)) {
        $('#vbn_contcart').html('<div style="text-align:center;padding-top: 100px;padding-bottom: 100px;color: #555;"><i class="fa-regular fa-shopping-cart" style="font-size: 150px;"></i><div style="margin-top: 30px;font-size: 30px;"><span>Il carrello è vuoto</span></div></div>');
        vbn_progpr = 0
        $('#vbn_contcart').attr("att", 0)
        $('#vbn_tq').text('0');
    }

    $('#vbn_sc').text(number_format(sct, 2));
    $('#vbn_tq').text(qt);
    $('#vbn_pt').text(number_format(pt, 2, ',', '.'));
    $('#vbn_tap').text(number_format(pt, 2, ',', '.'));
}

$(document).on('click', '.ApriProdotto_bn', function () {
    CaricaFrame(currentURL + 'assets/page/schedaprodotton.phpidpr=' + $(this).attr('idprn'), 'Dettagli prodotto', 'Visualizza e modifica e dettagli dei prodotti caricati in magazzino!', '85%')
});

$(document).on('click', '.vbn_nvend', function () {
    if (vbn_progpr >= 1) {
        var tp = $('#vbn_tpag').val();
        var sd;

        var value = $('#vbn_idcl').val();
        var cc = parseInt(value.replace(/[^0-9\.]/g, ''), 10);

        if (tp == 'vbn_cont' || tp == 'vbn_mon') {
            sd = 1;
            CreaVendita_bn(tp, sd);
        } else if (tp == 'vbn_bfc') {
            sd = 0;
            if (!isNaN(cc)) {
                CreaDocumento_bn('BC', cc);
                CreaVendita_bn(tp, sd);
            } else {
                Toast.fire({ icon: 'error', title: 'Per creare il buono devi prima cercare il cliente' })
            }
        } else if (tp == 'vbn_fat') {
            sd = 0;
            if (!isNaN(cc)) {
                CreaDocumento_bn('FA', cc);
                CreaVendita_bn(tp, sd);
            } else {
                Toast.fire({ icon: 'error', title: 'Per creare il buono devi prima cercare il cliente' })
            }
        }
    } else {
        Toast.fire({ icon: 'info', title: 'Devi caricare almeno un prodotto per poter effettuare la vendita!' })
    }
});

function CreaVendita_bn(tp, sd) {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/banconeg.php",
        method: "POST",
        data: {
            azione: 'nuovavendita',
            tp: tp, //tipopag
            tt: $('#vbn_tap').text(), //totale
            sd: sd //saldato 0-1
        },
        success: function (idba) {
            for (ii = 1; ii <= vbn_progpr; ii++) {
                if ($('#cartcont_qt_' + ii).val() != '') {
                    $.post(currentURL + 'assets/inc/banconeg.php', { azione: 'newvendprod', qt: $('#cartcont_qt_' + ii).val(), pz: $('#vbn_tot' + ii).text(), sc: $('#cartcont_rs_' + ii).text(), idpr: $('#vbn_idpr' + ii).text(), idba: idba }, function (response) {
                        if (response == 'si') {
                            snd.play(); sbloccaui();
                            Toast.fire({ icon: 'success', title: 'Vendita effettuata con successo' })
                        } else {
                            Toast.fire({ icon: 'error', title: 'Errore: ' + response }); sbloccaui();
                        }
                    });
                }
            }
            cambiopagina('magazzino', 'vendita', '');
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

function CreaDocumento_bn(doc, cc) {
    $.post(currentURL + "assets/inc/bancooff.php", { azione: "rbof", idcl: cc, totale: $('#vbn_tap').text(), tipo: doc }, function (response) {
        ProdottiDocumento_bn(response);
        vbn_docmail();
    })
}


function ProdottiDocumento_bn(ndoc) {
    for (ii = 1; ii <= i; ii++) {
        cd = '';
        ds = $('#cart_ds_' + ii).text();
        qt = $('#cartcont_qt_' + ii).val();
        pz = $('#vbn_tot' + ii).text();
        um = 'PZ';

        $.ajax({
            url: currentURL + "assets/inc/documenti.php",
            method: "POST",
            data: {
                azione: 'nuovorelpo',
                ndoc: ndoc,
                codice: cd,
                desc: ds,
                quant: qt,
                prez: pz,
                um: um,
                iva: '22'
            },
            success: function (response) {
                if (response == 'si') {
                    Toast.fire({ icon: 'success', title: 'Prodotto caricati con successo' })
                } else {
                    Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                }
            },
            error: function (response) {
                Toast.fire({ icon: 'error', title: response })
            }
        });
    }
}

function InvioMailDocumento_bn(bf) {
    var data = new FormData();

    data.append("mittente", 'noreply');
    data.append("indirizzodest", 'info@scifostore.com');
    data.append("oggetto", 'Nuovo ' + bf + ' dal negozio');
    data.append("corpo", bf + ' creato in negozio sul cliente ID: ' + $('#vbn_idcl').text());

    $.ajax({
        url: currentURL + "assets/mail/invio_mail.php",
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        success: function (response) {
            Toast.fire({ icon: 'success', title: bf + ' creato con successo' })
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore, si prega di contattare l\'amministratore.' })
        }
    });
}

function CercaFidoCliente() {
    var regex = /\[([^)]+)\]/;
    var src_cl = regex.exec($('#vbn_idcl').val());

    $.post(currentURL + 'assets/inc/schedacliente.php', { azione: 'saldoclienteid', idcl: src_cl[1] }, function (data) {
        var res = data.split('|-|');
        if (res[0] <= res[1]) {
            Swal.fire({
                title: 'Attenzione!',
                text: 'Cliente bloccato, ha superatore il Fido impostato',
                icon: 'warning',
                confirmButtonText: 'Chiudi'
            })
        }

    })
}

function LeggiPulsantiMP() {
    $('[data-type="plus"]').click(function (e) {
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        var currentVal = parseInt($('input[id=' + fieldName + ']').val());
        if (!isNaN(currentVal)) {
            $('input[id=' + fieldName + ']').val(currentVal + 1);
        } else {
            $('input[id=' + fieldName + ']').val(0);
        }
        element = $(this);
        cartCont(element)
    });
    $('[data-type="minus"]').click(function (e) {
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        var currentVal = parseInt($('input[id=' + fieldName + ']').val());
        if (!isNaN(currentVal) && currentVal > 0) {
            $('input[id=' + fieldName + ']').val(currentVal - 1);
        } else {
            $('input[id=' + fieldName + ']').val(0);
        }
        element = $(this);
        cartCont(element)
    });
}