// STATI 0=NON CONFERMATO 1=CONFERMATO 2=CHIUSO
var i = 1;

function CaricaLista_prev() {
    bloccaui();
    $.post(currentURL + 'assets/inc/preventivi.php', { azione: 'listaprev' }, function (data) {
        $('#tabellapreventivi').html(data);
        docReady(listInit);
        sbloccaui();
    })
}

function apri_prev(idpre_prev = 'nuovo') {
    CaricaFrame(currentURL + 'officina/preventivo.php?idprev=' + idpre_prev, '<i class="fa-regular fa-receipt"></i> Visualizza preventivo', 'Visualizza attraverso questa scheda le informazioni sul preventivo!.', '80%')
}


$(document).on('click', '.Stampa_prev', function () {
    Toast.fire({ icon: 'warning', title: 'Funzione in via di sviluppo' });
});

function Carica_prev(idpre) {
    bloccaui();
    $('.prev_InviaPrev').hide();
    $('.Stampa_prev').show();

    $.post(currentURL + 'assets/inc/preventivi.php', { azione: 'carprev', idpre: idpre }, function (data) {

        var res = data.split('|-|')
        var dt = res[2].split('-')
        $('#prev_Cliente').html(res[0]);
        $('#Numprev_prev').html(res[1]);
        $('#Data_prev').html(dt[2] + '/' + dt[1] + '/' + dt[0]);
        $('#prev_saldo').html(res[3]);
        $('#prev_Stato').html((res[4] == '0' ? 'Non Assegnato' : res[4] == '1' ? 'Confermato' : 'Chiuso'));
        $('#vbn_contcart').html('<div id="vbn_contcart2">' + res[5] + '</div>');
        vbn_progpr =  res[6]
        $('#vbn_contcart').attr("att", 1)
        LeggiPulsantiMP();
        eventkeyup_PREV();

        sbloccaui();
    })
}


// $(document).on('click', '.nucl_prev', function () {
//     var child = window.open(currentURL + 'assets/page/schedacliente.php?nuovo', '', 'toolbar=0,status=0,width=626,height=436');
//     child.onunload = function () {
//         var myArr = new Array();
//         $.post(currentURL + 'assets/inc/autocomplete.php', {
//             azione: 'clienti'
//         }, function (data) {
//             var res = data.split(',')
//             $.each(res, function (index, value) {
//                 myArr.push(value)
//             });
//             $(".auto").autocomplete({
//                 source: myArr
//             });
//         });
//     };
// });


$(document).on('click', '.prev_ProdottoManuale', function () {
    vbn_progpr = vbn_progpr + 1;

    var tr = '<div class="row gx-card mx-0 align-items-center border-bottom border-200" id="idr' + vbn_progpr + '"><div class="col-5 py-3"><div class="d-flex align-items-center"><a href="javascript:void(0)"><img class="img-fluid rounded-1 me-3 d-none d-md-block" src="' + $(location).attr('protocol') + '//' + $(location).attr('hostname') + '/upload/image/noimg.png" alt="" width="128px" height="auto"></a><div class="flex-1"><h5 class="fs-0"><a class="text-900" href="javascript:void(0)" id="cart_ds_' + vbn_progpr + '" contenteditable="">Inserisci qui il nome del prodotto</a></h5><div class="fs--2 fs-md--1"><a class="text-danger" href="javascript:void(0)" onclick="EliminaRiga_bn(' + vbn_progpr + ')">Rimuovi</a></div></div></div></div><div class="col-7 py-3"><div class="row align-items-center"><div class="col-2 ps-0 mb-2 mb-md-0 text-600" contenteditable="" id="cartcont_pr_' + vbn_progpr + '">0,00</div><div class="col-3 d-flex justify-content-end justify-content-md-center"><div><div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" data-field="cartcont_qt_' + vbn_progpr + '" id="bt_minus_' + vbn_progpr + '">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="1" aria-label="Amount (to the nearest dollar)" style="width: 50px" id="cartcont_qt_' + vbn_progpr + '" /><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" data-field="cartcont_qt_' + vbn_progpr + '"  id="bt_plus_' + vbn_progpr + '">+</button></div></div></div><div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' + vbn_progpr + '">22</div><div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' + vbn_progpr + '" title="0">0</div><div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="vbn_tot' + vbn_progpr + '">0,00</div><div id="vbn_idpr' + vbn_progpr + '" hidden>fuoricat</div></div></div></div>'
    if ($('#vbn_contcart').attr("att") == 1) {
        $('#vbn_contcart2').append(tr);
        LeggiPulsantiMP();
        eventkeyup_PREV();
    } else if ($('#vbn_contcart').attr("att") == 0) {
        $('#vbn_contcart').html('<table class="table" style=""><tbody id="vbn_contcart2">' + tr + '</tbody></table>');
        $('#vbn_contcart').attr("att", 1)
        LeggiPulsantiMP();
        eventkeyup_PREV();
    }
});

function eventkeyup_PREV() {
    $('[id^=cartcont]').keyup(function () {
        if (typeof cartTimeout != 'undefined')
            window.clearTimeout(cartTimeout);
        element = $(this);
        cartTimeout = window.setTimeout(function () {
            cartCont_PREV(element)
        }, 500);
    })
}

function cartCont_PREV(element) {
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
    fvbn_calctot_PREV()
}


function fvbn_calctot_PREV() {
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

    if (qt == 0) {
        $('#vbn_contcart').html('<div style="text-align:center;padding-top: 100px;padding-bottom: 100px;color: #555;"><i class="fa-regular fa-shopping-cart" style="font-size: 150px;"></i><div style="margin-top: 30px;font-size: 30px;"><span>Il carrello è vuoto</span></div></div>');
        vbn_progpr = 0
        $('#vbn_contcart').attr("att", 0)
    }

    $('#prev_sconto').text(number_format(sct, 2));
    $('#prev_quantita').text(qt);
    $('#prev_totale').text(number_format(pt, 2, ',', '.'));
    $('#prev_saldo').text(number_format(pt, 2, ',', '.'));

}

$(document).on('click', '.prev_InviaPrev', function () {
    if (vbn_progpr >= 1) {
        var value = $('#prev_idcl').val();
        var cc = parseInt(value.replace(/[^0-9\.]/g, ''), 10);
        if (!isNaN(cc)) {
            bloccaui();
            $.ajax({
                url: currentURL + "assets/inc/preventivi.php",
                method: "POST",
                data: {
                    azione: 'creaprev',
                    idcl: cc,
                    tt: $('#prev_saldo').text(), //totale preventivo
                },
                success: function (idprev) {
                    for (ii = 1; ii <= vbn_progpr; ii++) {
                        if ($('#cartcont_qt_' + ii).val() != '') {
                            var descrizione = ($('#vbn_idpr' + ii).text() == 'fuoricat' ? $('#cart_ds_' + ii).text() : '');
                            $.post(currentURL + 'assets/inc/preventivi.php', { azione: 'newprevprod', qt: $('#cartcont_qt_' + ii).val(), pz: $('#vbn_tot' + ii).text(), sc: $('#cartcont_rs_' + ii).text(), iva: $('#cartcont_iva_' + ii).text(), tit: descrizione, idpr: $('#vbn_idpr' + ii).text(), idprev: idprev }, function (response) {
                                if (response == 'si') {
                                    snd.play();
                                    sbloccaui();
                                    Toast.fire({ icon: 'success', title: 'Vendita effettuata con successo' })
                                } else {
                                    Toast.fire({ icon: 'error', title: 'Errore: ' + response }); sbloccaui();
                                }
                            });
                        }
                    }
                    $('#univ-offcanvas"').modal('dismiss');
                },
                error: function () {
                    Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                    sbloccaui();
                }
            });
        } else {
            Toast.fire({ icon: 'error', title: 'Per creare il preventivo devi prima cercare il cliente' })
        }
    } else {
        Toast.fire({ icon: 'info', title: 'Devi caricare almeno un prodotto per poter creare un preventivo!' })
    }
});