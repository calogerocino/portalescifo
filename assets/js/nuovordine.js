var totprrs = 0;

function creanuovo_ord(tpo) {
    if (tpo == 0) {
        if ($('#nordprest').val() == '') {
            Swal.fire({ title: 'Errore!', text: 'Inserisci un ID ordine valido.', icon: 'error', confirmButtonText: 'Chiudi' })
        } else {
            bloccaui();
            nordprest($('#nordprest').val())
        }

    } else if (tpo == 1) {
        $('.pagcrea').prop('hidden', true);
        $('.pagnuovo').prop('hidden', false);
        $('#prodottoman').html('<div class="row"> <div class="col-md-4"> <div class="form-floating mb-3"><input class="form-control" id="codicepr_no" type="text" placeholder=" "><label for="codicepr_no">Codice prodotto</label></div></div><div class="col-md-2"> <div class="form-floating mb-3"> <input id="quantpr_no" type="text" class="form-control" placeholder=" "> <label for="quantpr_no">Quantita</label> </div></div><div class="col-md-2 mb-auto mt-auto"><button class="btn btn-outline-primary me-1 mb-1 mt-1 verificapr_no" type="button">Verifica</button></div></div><div class="row"> <div class="col-md-10"> <div class="mb-3"><input class="form-control form-control-sm" id="nomepr_no" type="text" placeholder="Descrizione prodotto" readonly /></div></div><div class="col-md-2"><button class="btn btn-outline-primary me-1 mb-1 mt-1 aggiungipr_no" type="button">Carica</button> </div></div>');
    }
}

function svuotacampi_no() {
    $('#cliente_no').val('');
    $('#indirizzo_no').val('');
    $('#cap_no').val('');
    $('#citta_no').val('');
    $('#cellulare_no').val('');
    $('#cellulare2_no').val('');
    $('#telefono_no').val('');
    $('#email_no').val('');
    $('#refordine_no').val('');
    $('#idpresta_no').val('');
    $('#nfattura_no').val('');
    $('#idmarket_no').val('');
    $('#track_no').val('');
    $('#corriere_no option[value="-"]').prop('selected', true);
    $('#piattaforma_no option[value="-"]').prop('selected', true);
    $('#tsped_no option[value="-"]').prop('selected', true);
    $('#prodottoman').html('');
    $('#tabellaprodotti').html('');
    $('#statoordine_on option[value="-"]').prop('selected', true);
    $('#dataordine_no').val('');
    $('#note_no').text('');
    $('#pagamento_no option[value="-"]').prop('selected', true);
    $('#importo_no').val('');
    $('#altezzacorr_no').val('');
    $('#larghezzacorr_no').val('');
    $('#profonditacorr_no').val('');
    $('#pesovolumecorr_no').val('');
    $('#pesocorr_no').val('');
    $('#prezzopagcorr_no').val('');
    $('#colloinf_no').prop('checked', false);
    $('#ritirook_no').prop('checked', false);
    $('#paesidis_no').prop('checked', false);
    $('#isolemin_no').prop('checked', false);
    totprrs = 0;
}

function nordprest(npreord) {
    $.post(currentURL + 'assets/inc/prestashop-control.php', { azione: 'upordine', idordine: npreord }, function (response) {
        $('.notizie').html('')
        var res = response.split('|/|');
        //CLIENTE
        var rescl = res[1].split('|-|');
        $('#cliente_no').val(rescl[0]);
        $('#indirizzo_no').val(rescl[1]);
        $('#cap_no').val(rescl[2]);
        $('#citta_no').val(rescl[3]);
        $('#cellulare_no').val(rescl[4]);
        $('#cellulare2_no').val('');
        $('#telefono_no').val(rescl[5]);
        $('#email_no').val(rescl[6]);
        //ORDINE
        var resor1 = res[0].split('|-|');
        $('#refordine_no').val(resor1[0]).prop('readonly', true);
        $('#idpresta_no').val(resor1[1]).prop('readonly', true);
        $('#nfattura_no').val(resor1[2]).prop('readonly', true);
        $('#pagamento_no').val(resor1[3]);
        $('#dataordine_no').val(resor1[4]);
        $('#importo_no').val(resor1[5]);
        var resor2 = res[2].split('|-|'); //NON UTILIZZATO (STATO ORDINE)
        $('#statoordine_on').val('Importato');
        var resor3 = res[3].split('|-|'); //NON UTILIZZATO (CORRIERE)
        $('#corriere_no').val('-');

        if (resor1[3].includes("ManoMano 1X")) {
            $('#piattaforma_no').val('ManoMano');
            $('#pagamento_no').val('ManoMano');
        } else if (resor1[3].includes("ePrice")) {
            $('#piattaforma_no').val('ePrice');
            $('#pagamento_no').val('ePrice');
            var res0 = resor1[3].split(' ');
            $('#idmarket_no').val(res0[1]);
        } else if (resor1[3].includes("eBay")) {
            $('#piattaforma_no').val('eBay');
            $('#pagamento_no').val('eBay');
        } else {
            $('#piattaforma_no').val('Sito');
        }
        $('#statoordine_on').val('12');

        var resor4 = res[4].split('|-|');
        $('#pesocorr_no').val(resor4[0]);
        $('#prezzopagcorr_no').val(resor4[1]);
        if ($('#prezzopagcorr_no').val() <= '0.000000') {
            $('.notizie').append('<div class="alert alert-warning" role="alert">Inserisci il prezzo pagato dal cliente</div>');
        }

        $.post(currentURL + 'assets/inc/prestashop-control.php', { azione: 'prodordprest', idordine: resor1[1] }, function (response) {
            var res = response.split('|-|')
            $('#tabellaprodotti').html(res[0]);
            totprrs = res[1];
        });

        $('.pagcrea').prop('hidden', true);
        $('.pagnuovo').prop('hidden', false);
        sbloccaui();
    })
}

function tornaindietro_no() {
    $('.pagcrea').prop('hidden', false);
    $('.pagnuovo').prop('hidden', true);
    svuotacampi_no()
}

$(document).on('click', '.invianuovoordine', function () {
    if ($('#corriere_no').val() == '-') {
        Swal.fire({ title: 'Errore!', text: 'Seleziona un corriere.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if ($('#track_no').val() == '') {
        Swal.fire({ title: 'Errore!', text: 'Inserisci un codice tracking.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if ($('#tsped_no').val() == '-') {
        Swal.fire({ title: 'Errore!', text: 'Seleziona un tipo di spedizione.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if ($('#prezzopagcorr_no').val() == '0.000000') {
        Swal.fire({ title: 'Errore!', text: 'Devi inserire un prezzo di spedizione superiore a \'0.000000\'.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if ($('#pesocorr_no').val() == '0.000000') {
        Swal.fire({ title: 'Errore!', text: 'Devi inserire un peso di spedizione superiore a \'0.000000\'.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if (($('#piattaforma_no').val() == 'ManoMano' || $('#piattaforma_no').val() == 'ePrice' || $('#piattaforma_no').val() == 'Ebay' || $('#piattaforma_no').val() == 'Amazon') && $('#idmarket_no').val() == '') {
        Swal.fire({ title: 'Errore!', text: 'Per la piattaforma scelta devi inserire un numero ordine.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else if ($('#pagamento_no').val() == '') {
        Swal.fire({ title: 'Errore!', text: 'E\' necessario inserire il tipo di pagamento.', icon: 'error', confirmButtonText: 'Chiudi' })
    } else {
        //CARICA ORDINE
        $('.invianuovoordine').attr('disabled', true)
        var idclientenew = '';
        var idordinenew = '';
        var tspedcorr = '';

        if ($('#colloinf_no').is(':checked')) { tspedcorr = tspedcorr + '1' } else { tspedcorr = tspedcorr + '0' }
        if ($('#ritirook_no').is(':checked')) { tspedcorr = tspedcorr + '1' } else { tspedcorr = tspedcorr + '0' }
        if ($('#paesidis_no').is(':checked')) { tspedcorr = tspedcorr + '1' } else { tspedcorr = tspedcorr + '0' }
        if ($('#isolemin_no').is(':checked')) { tspedcorr = tspedcorr + '1' } else { tspedcorr = tspedcorr + '0' }


        $.post(currentURL + 'assets/inc/caricaordine.php', { azione: 'cercacliente', cliente: $('#cliente_no').val(), indirizzo: $('#indirizzo_no').val(), cap: $('#cap_no').val(), citta: $('#citta_no').val(), telefono: $('#telefono_no').val(), cellulare: $('#cellulare_no').val(), email: $('#email_no').val() }, function (response) {
            idclientenew = response;


            if (idclientenew != '') {
                $.post(currentURL + 'assets/inc/caricaordine.php', {
                    azione: 'caricaordine',
                    refordine: $('#refordine_no').val(),
                    nfattura: $('#nfattura_no').val(),
                    idmarket: $('#idmarket_no').val(),
                    track: $('#track_no').val(),
                    piattaforma: $('#piattaforma_no').val(),
                    dataordine: $('#dataordine_no').val(),
                    statoordine: $('#statoordine_on').val(),
                    statoordine2: $('#statoordine_on option:selected').text(),
                    corriere: $('#corriere_no').val(),
                    tsped: $('#tsped_no').val(),
                    note: $('#note_no').val(),
                    pagamento: $('#pagamento_no').val(),
                    importo: $('#importo_no').val(),
                    idcliente: idclientenew,
                    idpresta: $('#idpresta_no').val(),
                }, function (response) {
                    var res = response.split('|-|'); //RISULTATO ORDINE
                    if (res[0] == 'SI') {
                        idordinenew = res[1];

                        //CARICA INFO CORRIERE
                        $.post(currentURL + 'assets/inc/caricaordine.php', {
                            azione: 'infocorriere',
                            idordine: idordinenew,
                            pesocorr: $('#pesocorr_no').val(),
                            pesovolumecorr: $('#pesovolumecorr_no').val(),
                            altezzacorr: $('#altezzacorr_no').val(),
                            larghezzacorr: $('#larghezzacorr_no').val(),
                            profonditacorr: $('#profonditacorr_no').val(),
                            prezzopagcorr: $('#prezzopagcorr_no').val(),
                            crpi: tspedcorr,
                        }, function (response) {
                        })

                        //CARICA PRODOTTI
                        for (ii = 0; ii <= totprrs; ii++) {
                            if ($('#ipr' + ii).val() == '' || $('#ipr' + ii).val() == undefined) {

                            } else {
                                $.post(currentURL + 'assets/inc/caricaordine.php', { azione: 'caricaprodotti', idpr: $('#ipr' + ii).val(), qpr: $('#qpr' + ii).val(), tpr: $('#tpr' + ii).val(), idordine: idordinenew }, function (response) {
                                    if (response != 'SI') { } else { }
                                });
                            }
                        }

                        if ($('#idpresta_no').val() != '') {
                            $.post(currentURL + 'assets/inc/prestashop-control.php', { azione: 'inviaordine', idordine: $('#idpresta_no').val(), idstato: 3 }, function (response) {
                                if (response != 'SI') { } else { Toast.fire({ icon: 'success', title: 'Impostato correttamente su prestasop. ID Ordine: ' + $('#idpresta_no').val() }) }
                            });
                        }





                    } else if (res[0] == 'NO') {
                        Swal.fire({ title: 'Errore!', text: 'Errore nel caricamento dell\'ordine, controlla bene i dati.', icon: 'error', confirmButtonText: 'Chiudi' })
                    }


                    Swal.fire({
                        title: 'Caricamento in corso!',
                        text: "Prosegui con il caricamento dell\'ordine ID: " + idordinenew,
                        icon: 'success',
                        // showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        //cancelButtonColor: '#d33',
                        confirmButtonText: 'Procedi'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            svuotacampi_no();
                            $('.pagcrea').prop('hidden', false);
                            $('.pagnuovo').prop('hidden', true);
                        }
                    })
                })
            } else {
                Swal.fire({ title: 'Errore!', text: 'Errore nel caricamento o nella ricerca del cliente, controlla bene i dati.', icon: 'error', confirmButtonText: 'Chiudi' })
            }

        })
    }
});


function insclienteno() {
    bloccaui();
    var x = $('input#cliente_no').val();
    if (x.length >= 4) {
        $.ajax({
            url: currentURL + "assets/inc/caricaordine.php",
            method: "POST",
            data: {
                azione: 'selezionacliente',
                cliente: x
            },
            success: function (data) {
                if (data.length >= 8) {
                    var res = data.split(';');
                    $('input#cliente_no').val(res[0]);
                    $('input#indirizzo_no').val(res[1]);
                    $('input#cap_no').val(res[2]);
                    $('input#citta_no').val(res[3]);
                    $('input#cellulare_no').val(res[4]);
                    $('input#cellulare2_no').val(res[5]);
                    $('input#telefono_no').val(res[6]);
                    $('input#email_no').val(res[7]);
                }
                sbloccaui();
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                sbloccaui();
            }
        });
    }
}

$(document).on('click', '.verificapr_no', function () {
    bloccaui();
    var codiceprno = $('#codicepr_no').val();
    $.ajax({
        url: currentURL + "assets/inc/caricaordine.php",
        method: "POST",
        data: {
            azione: 'datiprod',
            codicepr: codiceprno
        },
        success: function (response) {
            $('#nomepr_no').val(response);
            sbloccaui();
        },
        error: function () {
            showNotification('top', 'right', 'Errore nella ricerca del prodotto', 'danger', 'close');
            sbloccaui();
        }

    });
});

$(document).on('click', '.aggiungipr_no', function () {
    var codicepr = $('#codicepr_no').val();
    var nomepr = $('#nomepr_no').val();
    var quantpr = $('#quantpr_no').val();

    if (quantpr != '') {
        bloccaui();
        $.ajax({
            url: currentURL + "assets/inc/caricaordine.php",
            method: "POST",
            data: {
                azione: 'addprod',
                codicepr: codicepr,
                nomepr: nomepr,
                quantpr: quantpr,
                totprrs: totprrs
            },
            success: function (response) {
                $('#tabellaprodotti').append(response)

                $('#codicepr_no').val('');
                $('#nomepr_no').val('');
                $('#quantpr_no').val('');
                totprrs = (totprrs + 1);
                sbloccaui();
            },
            error: function () {
                Swal.fire({ title: 'Errore!', text: 'Errore, controlla bene tutti i dati inseriti.', icon: 'error', confirmButtonText: 'Chiudi' })
                sbloccaui();
            }

        });
    } else {
        Swal.fire({ title: 'Errore!', text: 'Inserisci una quantità valida.', icon: 'error', confirmButtonText: 'Chiudi' })
    }
});

function cercacapcitta(val) {
    $.post(currentURL + 'assets/inc/nuovordine.php', { azione: 'capcitta', cap: val }, function (data) {
        $('#citta_no').val(data);
    })
}