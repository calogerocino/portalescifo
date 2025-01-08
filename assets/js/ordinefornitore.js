function aggiornaor(azione) {
    var clausola = '';
    bloccaui();
    //$('#dataricerca').val('0000-00-00')
    var ctn_c_mag = ControllaMagazzinoSelezionato();
    if (azione == 'nonass') {
        $('#dataricerca').attr('hidden', true)
        clausola = ' WHERE o.Fornitore=\'NON ASSEGNATO\' AND o.NOrdine=\'0\'';
        cercatutto_ord(clausola, ctn_c_mag);
        $('#searchforn option').removeAttr('selected').filter('[value=-]').attr('selected', true)
    } else if (azione == 'cons') {
        $('#dataricerca').attr('hidden', true)
        $.post(currentURL + "assets/inc/ordinefornitore.php", {
            azione: 'consigli',
            mm: ctn_c_mag
        }, function (data) {
            console.log(data)
            $('#tabellaricambi').html(data);
            docReady(listInit);
            sbloccaui();
        })
        $('#searchforn option').removeAttr('selected').filter('[value=-]').attr('selected', true)
    } else if (azione == 'drc') {
        $('#dataricerca').attr('hidden', false)
        clausola = ' WHERE o.Fornitore=\'' + $('#searchforn').val() + '\' AND o.DataOrdine=\'' + $('#dataricerca').val() + '\'';
        cercatutto_ord(clausola, ctn_c_mag);
        cercaorddata($('#searchforn').val());
    } else {
        $('#dataricerca').attr('hidden', false)
        clausola = ' WHERE o.Fornitore=\'' + $('#searchforn').val() + '\' AND o.NOrdine=\'0\'';
        cercatutto_ord(clausola, ctn_c_mag);
        cercaorddata($('#searchforn').val());
    }
}

function cercaorddata(forn) {
    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'ord-data',
        forn: forn
    }, function (data) {
        $('#questedate').html(data);
    })
}

function cercatutto_ord(clausola, mm) {
    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'aggiorna',
        clausola: clausola,
        mm: mm
    }, function (data) {
        $('#tabellaricambi').html(data);
        if (clausola.includes("AND o.DataOrdine")) {
            $('.neworder').attr('hidden', true)
        } else {
            $('.neworder').attr('hidden', false)
        }
        docReady(listInit);
        sbloccaui();
    })
}

function fornitoriadd() {
    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'fornitori',
    }, function (data) {
        $('#searchforn').append(data);
    })
}

function assegnafornitore(idr) {
    bloccaui();
    var res = $('#fornitore' + idr).val().split(';');
    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'aggiornafornitore',
        fornitore: res[0],
        prezzo: res[1],
        codice: res[2],
        idr: idr
    }, function (response) {
        if (response == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Fornitore aggiornato con successo!'
            })
            aggiornaor('nonass');
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + response
            })
            bloccaui();
        }
    })
}

function cambiaquant(idr) {
    bloccaui();

    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'aggiornaquantita',
        quantita: $('#' + idr).val(),
        idr: idr
    }, function (response) {
        if (response == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Quantita aggiornata con successo!'
            })
            aggiornaor();
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + response
            })
            sbloccaui();
        }
    })
}


$(document).on('click', '.neworder', function () {
    bloccaui();

    $.post(currentURL + "assets/inc/ordinefornitore.php", {
        azione: 'neworder',
        forn: $('#searchforn').val()
    }, function (response) {
        if (response == 'si') {
            Toast.fire({ icon: 'success', title: 'Ordine inserito con successo!' })
            aggiornaor();
            window.open(currentURL + "assets/pdf/ordinefornitore.php?create_pdf&fornitore=" + $('#searchforn').val(), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
            sbloccaui();
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            sbloccaui();
        }
    })
});

$(document).on('click', '.or_newrequest', function () {
    Swal.mixin({
        input: 'text',
        confirmButtonText: 'Avanti &rarr;',
        showCancelButton: true,
        progressSteps: ['1', '2', '3', '4']
    }).queue([
        {
            title: 'Descrizione',
            text: 'Inserisci una breve descrizione del ricambio',
            input: 'text',
            inputPlaceholder: 'Campo obbligatorio'
        },
        {
            title: 'Quantita',
            text: 'Inserisci la quantità desiderata',
            input: 'number',
            inputPlaceholder: 'Campo obbligatorio'
        },
        {
            title: 'Codice',
            text: 'Inserisci il codice fornitore trovato',
            input: 'text',
            inputPlaceholder: 'Campo facoltativo'
        },
        {
            title: 'Note',
            text: 'Inserisci delle note facoltative',
            input: 'text',
            inputPlaceholder: 'Campo facoltativo'
        },
    ]).then((result) => {
        if (result.value) {
            // 0 quant - 1 cod - 2 forn
            $.post(currentURL + "assets/inc/ordinefornitore.php", {
                azione: 'daordinemanuale',
                quantita: result.value[1],
                codice: result.value[2],
                nome: result.value[0],
                note: result.value[3]
            }, function (response) {
                if (response == 'si') {
                    //Toast.fire({ icon: 'success', title: 'Ordine inserito con successo!' })
                    Toast.fire({ icon: 'warning', title: 'Ancora non funzionante, sistema in manutenzione!' })
                } else {
                    Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                }
                aggiornaor();
                sbloccaui();
            })
        }
    })
});