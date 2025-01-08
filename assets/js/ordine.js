function chiamamail() {
    //CONVERSAZIONI MAIL
    var mail = $('input#email').val();
    var dachi = $('#sceglimail').val();

    if (mail != '') {
        $.ajax({
            url: currentURL + "assets/mail/leggi-mail.php",
            method: "POST",

            data: {
                mail: mail,
                dachi: dachi
            },
            success: function (data) {
                tinymce.get('conversazionisupp').setContent(data);
            },
        });
    }
}

function cercasinistro() {
    var idooo = $('#idordine').val();
    $.post(currentURL + "assets/inc/modifica_ordine.php", {
        modifica: 'cercasinistro',
        ido: idooo
    }, function (data) {
        if (data == 'no') {
            $('#bttsinistro').prop('hidden', false);
            $('#dataapertura').prop('readonly', false)
            $('#importos').prop('readonly', false)
            $('#stato').prop('disabled', true)
        } else {
            var res = data.split('|-|')
            $('#bttsinistro').prop('hidden', true);
            $('#dataapertura').val(res[0])
            $('#dataapertura').prop('readonly', true)
            $('#importos').val(res[1])
            $('#importos').prop('readonly', true)
            $('#stato option[value=' + res[2] + ']').attr('selected', 'selected');
            $('#stato').prop('disabled', false)
        }
    })
}

//===INIZIO CLIENTE===
function dabilitacliente_ord() {
    document.getElementById('cliente').disabled = true;
    document.getElementById('indirizzo').disabled = true;
    document.getElementById('cap').disabled = true;
    document.getElementById('citta').disabled = true;
    document.getElementById('cellulare').disabled = true;
    document.getElementById('cellulare2').disabled = true;
    document.getElementById('telefono').disabled = true;
    document.getElementById('email').disabled = true;
    document.getElementById('bttsalvacliente').hidden = true;
    document.getElementById('bttdelcliente').hidden = true;
}

function abilitacliente_ord() {
    document.getElementById('cliente').disabled = false;
    document.getElementById('indirizzo').disabled = false;
    document.getElementById('cap').disabled = false;
    document.getElementById('citta').disabled = false;
    document.getElementById('cellulare').disabled = false;
    document.getElementById('cellulare2').disabled = false;
    document.getElementById('telefono').disabled = false;
    document.getElementById('email').disabled = false;
}

function abilitamodcliente_ord() {
    abilitacliente_ord();
    document.getElementById('bttsalvacliente').hidden = false;
    document.getElementById('bttdelcliente').hidden = false;
}

$(document).on('click', '.abcliente', function () {
    abilitamodcliente_ord()
});

$(document).on('click', '.sacliente', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/modifica_ordine.php",
        method: "POST",
        data: {
            modifica: 'cliente',
            cliente: $('#cliente').val(),
            via: $('#indirizzo').val(),
            cap: $('#cap').val(),
            citta: $('#citta').val(),
            telefono: $('#telefono').val(),
            cellulare: $('#cellulare').val(),
            cellulare2: $('#cellulare2').val(),
            email: $('#email').val(),
            idcliente: $('#idcliente').val()
            // Second add quotes on the value.
        },
        success: function (response) {
            Toast.fire({ icon: 'success', title: response })
            dabilitacliente_ord();
            sbloccaui();
        },
        error: function (response) {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            sbloccaui();
        }
    });
});

$(document).on('click', '.dabcliente', function () {
    dabilitacliente_ord()
});
//===FINE CLIENTE===

//===INIZIO DETTAGLI===
function dabilitadettagli() {
    document.getElementById('refordine').disabled = true;
    document.getElementById('idpresta').disabled = true;
    document.getElementById('nfattura').disabled = true;
    document.getElementById('idmarket').disabled = true;
    document.getElementById('track').disabled = true;
    document.getElementById('corriere').disabled = true;
    document.getElementById('piattaforma').disabled = true;
    document.getElementById('tsped').disabled = true;
    document.getElementById('statoordine').disabled = true;
    document.getElementById('bttsalvadett').hidden = true;
    document.getElementById('bttdeldett').hidden = true;
}

function abilitadettagli() {
    document.getElementById('track').disabled = false;
    document.getElementById('corriere').disabled = false;
    document.getElementById('tsped').disabled = false;
    document.getElementById('idmarket').disabled = false;
    document.getElementById('statoordine').disabled = false;
}

function abilitamoddettagli() {
    abilitadettagli();
    document.getElementById('bttsalvadett').hidden = false;
    document.getElementById('bttdeldett').hidden = false;
}

$(document).on('click', '.abordine', function () {
    abilitamoddettagli();
});

$(document).on('click', '.saordine', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/modifica_ordine.php",
        method: "POST",
        data: {
            modifica: 'dettagli',
            tracking: $('#track').val(),
            corriere: $('#corriere').children("option:selected").val(),
            tsped: $('#tsped').children("option:selected").val(),
            idordine: $('#idordine').val(),
            staoord: $('#statoordine').val(),
            staoord2: $('#statoordine option:selected').text(),
            idmarket: $('#idmarket').val(),
        },
        success: function (response) {
            Toast.fire({ icon: 'success', title: response })
            dabilitadettagli()
            document.getElementById('statoordine').disabled = true;
            sbloccaui();
        },
        error: function (response) {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            sbloccaui();
        }
    });
});

$(document).on('click', '.dabordine', function () {
    dabilitadettagli()
});

//===FINE DETTAGLI===

//===INIZIO PAGAMENTO===
function dabilitapagamento() {
    document.getElementById('pagamento').disabled = true;
    document.getElementById('importo').disabled = true;
}
//===FINE PAGAMENTO===

//===INIZIO STATO===
function dabilitastato() {
    document.getElementById('dataordine').disabled = true;
    document.getElementById('statoordine').disabled = true;
    document.getElementById('dataevasione').disabled = true;
}
//===FINE STATO===

//MAIL SUPPORT E NORMALI
tinymce.init({
    menubar: false,
    statusbar: false,
    height: "400",
    readonly: 1,
    toolbar: false,
    selector: '#conversazionisupp'
});

tinymce.init({
    menubar: false,
    statusbar: false,
    height: "400",
    readonly: 1,
    toolbar: false,
    selector: '#conversazionimail'
});

function sceglimailsupp() {
    chiamamail();
}

$(document).on('click', '.watext', function () {
    var cell = $('input#cellulare').val();
    var idord = $('input#idcliente').val();
    var url = currentURL + "assets/page/mail.php?cell=" + cell + "&id=" + idord;
    window.open(url, 'Invia Mail', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

//GESTISCI SINISTRO 
$(document).on('click', '.sinstro', function () {
    bloccaui();
    $.post(currentURL + "assets/inc/modifica_ordine.php", { modifica: 'nuovosinistro', ido: $('#idordine').val(), datapertura: $('#dataapertura').val(), importo: $('#importos').val() }, function (response) {
        if (response == 'si') {
            $('#bttsinistro').prop('hidden', true);
            $('#dataapertura').prop('readonly', true)
            $('#importos').prop('readonly', true)
            $('#stato').prop('disabled', false)
            showNotification('top', 'right', 'Salvato con successo!', 'success', 'done');
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
        sbloccaui();
    })
});

function cambiastato() {
    var stato = $('#stato').val()
    bloccaui();
    $.post(currentURL + "assets/inc/modifica_ordine.php", { modifica: 'cambiastato', ido: $('#idordine').val(), stato: stato }, function (response) {
        if (response == 'si') {
            Toast.fire({ icon: 'error', title: 'Aggiornato con successo!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
        sbloccaui();
    })
}

$(document).on('click', '.prodottoord', function () {
    window.open(currentURL + 'assets/page/schedaprodotton.php?idpr=' + $(this).attr('idpr'), 'Scheda prodotto', 'width=800, height=685, status, scrollbars=1, location');
});

function AggiornaNoteOrdine() {
    $.post(currentURL + 'assets/inc/modifica_ordine.php', { modifica: 'modificanote', idordine: $('#idordine').val(), note: $('#note').val() }, function (res) {
        if (res == 'ok') {
            Toast.fire({ icon: 'success', title: 'Note aggiornate con successo!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + res })
        }
    })
    $('#note')
}

function RimuoviProdottoOrdine(ido, idpr) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi elimare questo prodotto?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, procedi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(currentURL + 'assets/inc/modifica_ordine.php', { modifica: 'eliminarelpo', ido: ido, idpr: idpr }, function (res) {
                (res == 'si' ? Toast.fire({ icon: 'success', title: 'Eliminato con successo' }) : Toast.fire({ icon: 'error', title: 'Errore: ' + res }))
            })
        }
    })
}

function FCreaProdotto_vo() {
    $('#NuovoProdotto').prop('hidden', false)
}

$(document).on('click', '.VerificaProdotto_vo', function () {
    bloccaui();

    $.post(currentURL + "assets/inc/caricaordine.php", { azione: 'datiprod', codicepr: $('#CodiceProdotto_vo').val() }, function (res) {
        $('#DescrizioneProdotto_vo').val(res);
        sbloccaui();
    });
});

$(document).on('click', '.AggiungiProdotto_vo', function () {
    if ($('#QuantitaProdotto_vo').val() != '') {
        bloccaui();
        $.post(currentURL + "assets/inc/modifica_ordine.php", {
            modifica: 'aggiungiprodotto',
            codicepr: $('#CodiceProdotto_vo').val(),
            quantpr: $('#QuantitaProdotto_vo').val(),
            ido: $('#idordine').val()
        }, function (res) {
            console.log(res)
            $('#TabellaProdotti_vo').append(res)

            $('#CodiceProdotto_vo').val('');
            $('#DescrizioneProdotto_vo').val('');
            $('#QuantitaProdotto_vo').val('');
            sbloccaui();
        });
    } else {
        Toast.fire({ icon: 'error', title: 'Inserisci una quantità valida!' })
    }
});