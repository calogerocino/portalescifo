function svuotacampi_fa() {
    $('#fa_fornitore').val('');
    $('#fa_datadoc').val('');
    $('#ndoc_fatt').val('');
    $('#imp22').val('');
    $('#imp10').val('');
    $('#imp4').val('');
    $('#impese').val('');
    $('#totale22').val('');
    $('#totale10').val('');
    $('#totale4').val('');
    $('#totaleese').val('');
    $('#tippag option[value="-"]').attr('selected', 'selected')
    $('#totalefattura').html('');
    $('#note').val('');
    $('#fa_nomefile').val('');

    $('#fa_tippag').attr('disabled', false);
    $('#fa_fornitore').prop('readonly', false);
    $('#imp22').prop('readonly', false);
    $('#imp10').prop('readonly', false);
    $('#imp4').prop('readonly', false);
    $('#impese').prop('readonly', false);
    $('.NuovoPagamento_fa').prop('hidden', false)
}

$(document).on('click', '.CreaNuovaFattura_fa', function () {
    CaricaFrame(currentURL + 'contabilita/fattura.php', '<i class="fa-regular fa-file-invoice"></i> Crea fattura', 'Crea una fattura emessa da un tuo fornitore!', '50%')
});


$(document).on('click', '.NuovoPagamento_fa', function () {
    window.open(currentURL + 'contabilita/carica.php', 'Carica nuovo', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

var statopag;
var datapag;
var clausola;

function AggiornaFatture_fa() {
    bloccaui();
    var datedalal = $("#DataPickerRangeFatture").val();
    statopag = $('#searchtipo').val();
    var ricerca = $("input#search").val();
    ricerca = parseInt(ricerca.replace(/[^0-9\.]/g, ''), 10);

    if (statopag == '0') {
        statopag = 'WHERE (fa.TotDoc = fa.Saldo) ';
    } else if (statopag == '1') {
        statopag = 'WHERE (fa.Saldo < fa.TotDoc) ';
    } else if (statopag == '2') {
        statopag = ' ';
    }

    if (datedalal == '') {
        datapag = ControllaDataPagamento_fa();
    } else {
        var split1 = datedalal.split(" -> ")
        var resdal = split1[0].split("/");
        var resal = split1[1].split("/");
        datapag = " AND fa.DataDoc BETWEEN '" + resdal[2] + "-" + resdal[1] + "-" + resdal[0] + "' AND '" + resal[2] + "-" + resal[1] + "-" + resal[0] + "'";
    }

    if (isNaN(ricerca)) {
        clausola = '';
    } else {
        clausola = ' AND (fa.Fornitore=\'' + ricerca + '\') ';
    }

    $.post(currentURL + "assets/inc/fatture.php", { azione: 'aggiorna', statopag: statopag, clausola: clausola, datapag: datapag }, function (data) {
        var res = data.split('|-|')
        $('#BodyTabellaFatture').html(res[0]);
        $('#FootTabellaFatture').html('<tr><th colspan="4" class="text-end">€ ' + res[1] + '</th><th></th></tr>');
        docReady(listInit);
        sbloccaui();
    })
}

function ControllaDataPagamento_fa() {
    var fetchdata = new Date();
    var anno = fetchdata.getFullYear();
    var mese = fetchdata.getMonth() + 1;
    var mesemuno = fetchdata.getMonth();
    var mesepuno = fetchdata.getMonth() + 2;
    var radiosdate = $('#searchdate').val();

    if (radiosdate == 'tt') {
        RimuoviCampi_fa();
        return '';
    } else if (radiosdate == 'ac') {
        RimuoviCampi_fa();
        return ' AND YEAR(fa.DataDoc) = \'' + anno + '\'';
    } else if (radiosdate == 'mc') {
        RimuoviCampi_fa();
        return ' AND YEAR(fa.DataDoc) = \'' + anno + '\' AND MONTH(fa.DataDoc) = \'' + mese + '\'';
    } else if (radiosdate == 'ms') {
        RimuoviCampi_fa();
        return ' AND YEAR(fa.DataDoc) = \'' + anno + '\' AND MONTH(fa.DataDoc) = \'' + mesepuno + '\'';
    } else if (radiosdate == 'mp') {
        RimuoviCampi_fa();
        return ' AND YEAR(fa.DataDoc) = \'' + anno + '\' AND MONTH(fa.DataDoc) = \'' + mesemuno + '\'';
    } else if (radiosdate == 'da') (
        Aggiungicampi_fa()
    )
}

function RimuoviCampi_fa() {
    $('#dalalricerca').prop('hidden', true)
}

function Aggiungicampi_fa() {
    $('#dalalricerca').prop('hidden', false)
}


function NuovaModalitaPagamento_fa() {
    bloccaui();
    $.post(currentURL + 'assets/inc/fatture.php', { azione: 'modpag' }, function (data) {
        $("#fa_tippag").html(data);
        sbloccaui();
    })
}

async function NuovoPagamento_fa() {
    var value = $("#fa_tippag").val()
    if (value == 'nuovo') {
        const { value: nuovopag } = await Swal.fire({
            title: 'Inserisci la nuova modalità di pagamento',
            input: 'text',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Non puoi lasciare il campo vuoto!'
                }
            }
        })
        if (nuovopag) {
            $.post(currentURL + "assets/inc/documenti.php", { azione: 'nuovotipopagamento', dato: nuovopag }, function (response) {
                if (response == 'no') {
                    Toast.fire({ icon: 'error', title: 'Errore nel caricamento, si prega di controllare i dati inseriti' })
                } else {
                    Toast.fire({ icon: 'success', title: 'Nuovo tipo di pagamento salvato con successo' })
                    NuovaModalitaPagamento_fa();
                }
            })
        }
    }
}

function CalcolaTotale_fa() {
    var tot = 0;
    if ($('#totale22').val() > 0) {
        tot = tot + parseFloat($('#totale22').val())
    }
    if ($('#totale10').val() > 0) {
        tot = tot + parseFloat($('#totale10').val())
    }
    if ($('#totale4').val() > 0) {
        tot = tot + parseFloat($('#totale4').val())
    }
    if ($('#totaleese').val() > 0) {
        tot = tot + parseFloat($('#totaleese').val())
    }
    $('#totalefattura').html(tot.toFixed(2))
}

$(document).on('click', '.CaricaAllegato_fa', async function () {
    //CARICA FILE
    const { value: file } = await Swal.fire({
        title: 'Seleziona file',
        input: 'file',
        enctype: 'multipart/form-data',
        inputAttributes: {
            'accept': 'image/*, application/pdf',
            'aria-label': 'Carica il file'
        }
    })

    if (file) {
        var data = new FormData();
        data.append("userfile", file);
        data.append('cartella', 'fatture');
        NotificaCaricamento();
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: currentURL + "upload/carica-allegato.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', progress, false);
                }
                return myXhr;
            },
            success: function (data) {
                if (data == 'fileno') {
                    Swal.fire({ icon: 'error', title: 'File non esistente o sconosciuto' })
                } else if (data == 'carno') {
                    Swal.fire({ icon: 'error', title: 'Errore generico nel salvataggio del file!' })
                } else {
                    $("#fa_nomefile").val(data);
                    $('div#formupload').hide();
                    $('div#formvisualizza').show();
                    Swal.fire({ icon: 'success', title: 'File caricato correttamente!' })
                    if ($('#idfatt').val() != '') {
                        $.post(currentURL + 'assets/inc/fatture.php', { azione: 'aggiornaallegato', idfatt: $('#idfatt').val(), allegato: $("#fa_nomefile").val() }, function (response) {
                            if (response == 'ok') { } else {
                                Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                            }
                        })
                    }
                }
            }
        });
        e.preventDefault();
    }


});

$(document).on('click', '.NuovoDocumento_fa', function () {
    if ($('#fa_fornitore').val() == '') {
        Swal.fire({ icon: 'error', title: 'Inserisci il fornitore prima di procedere' })
    } else if ($('#ndoc_fatt').val() == '') {
        Swal.fire({ icon: 'error', title: 'Inserisci il numero documento prima di procedere' })
    } else if ($('#fa_datadoc').val() == '') {
        Swal.fire({ icon: 'error', title: 'Inserisci la data documento prima di procedere' })
    } else if ($('#fa_tippag').val() == '-') {
        Swal.fire({ icon: 'error', title: 'Inserisci un metodo di pagamento prima di procedere' })
    } else if ($('#totalefattura').html() == '') {
        Swal.fire({ icon: 'error', title: 'Devi riempire almeno un campo importo prima di procedere' })
    } else {
        bloccaui();
        var fornitore = parseInt($('#fa_fornitore').val().replace(/[^0-9\.]/g, ''), 10);
        $.ajax({
            type: "POST",
            url: currentURL + "assets/inc/fatture.php",
            data: {
                azione: 'carica',
                fornitore: fornitore,
                tipodoc: 'Fattura',
                ndoc: $('#ndoc_fatt').val(),
                datadoc: $('#fa_datadoc').val(),
                imp22: $('#totale22').val(),
                imp10: $('#totale10').val(),
                imp4: $('#totale4').val(),
                impese: $('#totaleese').val(),
                note: $('#note').val(),
                pagamento: $('#tippag').val(),
                allegato: $('#fa_nomefile').val(),
                totdoc: $('#totalefattura').html(),
            },
            success: function (response) {
                if (response == 'si') {
                    Toast.fire({ icon: 'success', title: 'Fattura caricata correttamente!' });
                    svuotacampi_fa();
                } else {
                    Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                }
                sbloccaui();
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Errore nella richiesta!' })
                sbloccaui();
            }
        });
    }
})


$(document).on('click', '.ApriFornitore_fa', function () {
    alert('Sistema qui');
});

$(document).on('click', '.ApriFattura_fa', function () {
    CaricaFrame(currentURL + 'contabilita/fattura.php', '<i class="fa-regular fa-file-invoice"></i> Visualizza/Modifica fattura', 'Visualizza o modifica una fattura emessa da un tuo fornitore!', '50%')
    ApriFattura($(this).attr('idd'))
});

function ApriFattura(id) {
    bloccaui();
    $('.NuovoDocumento_fa').prop('hidden', true);

    $.post(currentURL + "assets/inc/fatture.php", { azione: 'apridoc', idd: id }, function (data) {
        var res = data.split('|-|');
        $('#fa_fornitore').val(res[0]);
        $('#fa_fornitore').prop('readonly', true);
        $('#ndoc_fatt').val(res[1]);
        $('#fa_datadoc').val(res[2]);
        $('#totale22').val(res[3]);
        $('#imp22').val(Math.round(($('#totale22').val() * 100) / 122).toFixed(2));

        $('#totale10').val(res[4]);
        $('#imp10').val(Math.round(($('#totale10').val() * 100) / 122).toFixed(2));

        $('#totale4').val(res[5]);
        $('#imp4').val(Math.round(($('#totale4').val() * 100) / 122).toFixed(2));

        $('#totaleese').val(res[6]);
        $('#impese').val(Math.round(($('#totaleese').val() * 100) / 122).toFixed(2));

        $('#note').val(res[7]);
        $('#fa_tippag option[value="' + res[8] + '"]').attr('selected', 'selected');
        $('#fa_tippag').attr('disabled', true);
        $('#fa_nomefile').val(res[9]);
        $('#totalefattura').html(res[10]);
        $('#idfatt').val(res[11]);

        $('.NuovoDocumento_fa').prop('hidden', true)

        var allegato = $('#fa_nomefile').val();

        $('#imp22').prop('readonly', true);
        $('#imp10').prop('readonly', true);
        $('#imp4').prop('readonly', true);
        $('#impese').prop('readonly', true);
        $('.NuovoPagamento_fa').prop('hidden', true);
        if (allegato != '') {
            $('#formupload').attr('hidden', true);
            $('#formvisualizza').attr('hidden', false);
        } else {
            $('#formupload').attr('hidden', false);
            $('#formvisualizza').attr('hidden', true);
        }
        sbloccaui();
    })
}

$(document).on('click', '.VisualizzaAllegato_fa', function () {
    var filename = $('#fa_nomefile').val();
    window.open(currentURL + "upload/fatture/" + filename, 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

//$(document).on('click', '.fa_aggdoc', function () {
// bloccaui();
// $.post(currentURL + "assets/inc/fatture.php", { azione: 'modificafatt', nfatt: $('#ndoc_fatt').val(), datafatt: $('#fa_datadoc').val(), idfatt: $('#idfatt').val() }, function (data) {
//     if (data == 'ok') {
//         showNotification('top', 'right', 'Aggiornato con successo!', 'success', 'done');
//         sbloccaui();
//     } else {
//         showNotification('top', 'right', data, 'danger', 'close');
//         sbloccaui();
//     }

// })
//});

function AggiornaFattura(campo) {
    if ($('#idfatt').val() != '0') {
        bloccaui();
        if (campo == 'ndoc_fatt') {
            ncampo = 'NDoc';
        } else if (campo == 'fa_datadoc') {
            ncampo = 'DataDoc';
        } else if (campo == 'note') {
            ncampo = 'Note';
        } else if (campo == 'totale22') {
            ncampo = 'Imp22';
        } else if (campo == 'totale10') {
            ncampo = 'Imp10';
        } else if (campo == 'totale4') {
            ncampo = 'Imp4';
        } else if (campo == 'totaleese') {
            ncampo = 'ImpEse';
        }


        $.post(currentURL + "assets/inc/fatture.php", { azione: 'modificafatt', campo: ncampo, valore: $('#' + campo).val(), idfatt: $('#idfatt').val(), totale: $('#totalefattura').html() }, function (data) {
            if (data == 'ok') {
                Toast.fire({ icon: 'success', title: 'Campo aggiornato con successo!' })
                sbloccaui();
            } else {
                Toast.fire({ icon: 'error', title: 'Errore: ' + data })
                sbloccaui();
            }
        })
    }
}

$(document).on('click', '.eliminafatt', function () {
    bloccaui();
    var id = $(this).attr("id");

    $.post(currentURL + "assets/inc/fatture.php", { azione: 'elimina', id: id}, function (data) {
        if (data == 'si') {
            Toast.fire({ icon: 'success', title: 'Fattura eliminata con successo' })
            AggiornaFatture_fa()
        } else {
            Toast.fire({ icon: 'error', title: 'Errore durante l\'eliminazione della fattura. Errore:' + data })
        }
        sbloccaui();
    });
});
