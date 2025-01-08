function Carica_sf() {
    bloccaui();
    // CARICA DATI FORNITORE
    $.post(currentURL + "assets/inc/schedafornitore.php", {
        azione: 'datifornitoreid',
        idfor: IDForn_sf
    }, function (data) {
        var res = data.split('|-|');
        $('#idfor').val(res[0]); //ID
        $('#fomod_RagioneSociale').text(res[1]); //CLIENTE
        $('#citta').val(res[2]); //CITTA
        $('#indirizzo').val(res[3]); //INDIRIZZO
        $('#cellulare').val(res[4]); //CELLULARE
        $('#mail').val(res[5]); //MAIL
        $('#banca').val(res[6]); //BANCA
        $('#iban').val(res[7]); //IBAN
        $('#bic').val(res[8]); //BIC
        $('#piva').val(res[9]); //PIVA
        $('#codfisc').val(res[10]); //COD FISC
        $('#sdi').val(res[11]); //SDI
        $('#pec').val(res[12]); //PEC
        var r = res[13].split(';');
        $('#sco_pr').val(r[1]); //sconto prodotto
        $('#sco_ri').val(r[0]); //sconro ricambi
        EventKeyUp_sf();
    })

    // CERCA BUONI O FATTURE
    $.post(currentURL + "assets/inc/schedafornitore.php", {
        azione: 'venditefornitore',
        idfor: IDForn_sf
    }, function (data) {
        var res = data.split('|-|');
        $(".TabellaFornitore_fo").html(res[0]);
        saldo1 = res[1];
    })

    // CREA ESTRATTO CONTO
    $.post(currentURL + "assets/inc/schedafornitore.php", {
        azione: 'econtofornitore',
        idfor: IDForn_sf
    }, function (data) {
        $(".TabellaEConto_fo").html(data);
    })
    sbloccaui();
}

function AggiornaDati_sf(campo) {
    if (IDForn_sf != 'nuovo') {
        bloccaui();
        var valore = '';
        if (campo == 'scontofornitore') {
            valore = $('#sco_pr').val() + ';' + $('#sco_ri').val();
        } else {
            valore = $('#' + campo).val();
        }
        $.post(currentURL + "assets/inc/schedafornitore.php", {
            azione: 'aggiornafornitore',
            valore: valore,
            campo: campo,
            idfor: IDForn_sf
        }, function (data) {
            if (data == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Fornitore aggiornato correttamente'
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Fornitore non aggiornato. Errore: ' + data
                })
            }
            sbloccaui();
        })
    }
}

$(document).on('click', '.EstrattoConto_fo', function () {
    if (IDForn_sf == '' || IDForn_sf == null) {
        Toast.fire({
            icon: 'error',
            title: 'Devi caricare un fornitore prima di visualizzare l\'estratto conto!'
        })
    } else {
        window.open(currentURL + 'assets/pdf/e-conto-forn.php?create_pdf=1&idfo=' + IDForn_sf, 'PDF Documento', 'width=800, height=800, status, scrollbars=1, location');
    }
});

$(document).on('click', '.NuovoFornitore_fo', function () {
    bloccaui();
    $.post(currentURL + "assets/inc/schedafornitore.php", {
        azione: 'caricanuovo',
        ragsoc: $('#fomod_RagioneSociale').text(), //CLIENTE
        indirizzo: $('#indirizzo').val(), //INDIRIZZO
        citta: $('#citta').val(), //CITTA
        cellulare: $('#cellulare').val(), //CELLULARE
        mail: $('#mail').val(), //MAIL
        piva: $('#piva').val(), //PIVA
        codfisc: $('#codfisc').val(), //COD FISC
        sdi: $('#sdi').val(), //SDI
        pec: $('#pec').val(), //PEC
        banca: $('#banca').val(), //BANCA
        iban: $('#iban').val(), //IBAN
        bic: $('#bic').val(), //BIC
    }, function (data) {
        if (data == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Fornitore aggiunto correttamente'
            })
            $('#univ-offcanvas').offcanvas('hide')
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Fornitore non aggiunto. Errore: ' + data
            })
        }
        sbloccaui();
    })
});

function EventKeyUp_sf() {
    $('[id^=fomod_]').keyup(function () {
        if (typeof modTimeout != 'undefined')
            window.clearTimeout(modTimeout);
        element = $(this);
        modTimeout = window.setTimeout(function () {
            fomod(element)
        }, 500);
    })
}

function fomod(element) {
    var r = $('#fomod_RagioneSociale').text();
    campo = 'Ragione_Sociale'
    $.post(currentURL + "assets/inc/schedafornitore.php", {
        azione: 'aggiornafornitore',
        valore: r,
        campo: campo,
        idfor: IDForn_sf
    }, function (data) {
        if (data == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Fornitore aggiornato correttamente'
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Fornitore non aggiornato. Errore: ' + data
            })
        }
        sbloccaui();
    })
}

$(document).on('click', '.ApriAllegato_fo', function () {
    window.open(currentURL + "upload/fornitore/" + IDForn_sf + "/" + $(this).attr('all'), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

function EliminaAllegato_fo(file, id) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi eliminare questo file? L\'azione sarà irreversibile!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, procedi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(currentURL + "upload/elimina-file.php", {
                file: 'fornitore/' + IDForn_sf + '/' + file,
            }, function (data) {
                (ModalitaDebug ? console.log(data) : '')
                if (data == 'ok') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Eliminato con successo'
                    })
                    $('#afo_' + id).remove()
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Errore: ' + data
                    })
                }
            });
        }
    })
}

$(document).on('click', '.CaricaAllegato_fo', async function () {
    const {
        value: file
    } = await Swal.fire({
        title: 'Seleziona file',
        input: 'file',
        html: '<div class="form-floating mb-3"><select class="form-select" id="tipodocumento" aria-label="Scelta del documento"><option value="0">Listino</option><option value="1">Catalogo</option><option value="2">Contabile</option><option value="3">Contratto</option><option value="9" selected>Altro</option></select><label for="tipodocumento">Tipo documento</label></div>',
        enctype: 'multipart/form-data',
        inputAttributes: {
            'accept': 'image/*, application/pdf',
            'aria-label': 'Carica il file'
        }
    })
    if (file) {
        var data = new FormData();
        data.append("userfile", file);
        data.append('cartella', 'fornitore');
        data.append('fornitore', IDForn_sf);
        data.append('idtipo', $('#tipodocumento').val());
        NotificaCaricamento();
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: currentURL + "upload/carica-allegato.php",
            data: data,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', progress, false);
                }
                return myXhr;
            },
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                if (data == 'fileno') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File non esistente o sconosciuto'
                    })
                } else if (data == 'carno') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore generico nel salvataggio del file!'
                    })
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'File caricato correttamente!'
                    })
                }
            }
        });
    }
});



// LISTA FORNITORE
function AggiornaLista_fo() {
    bloccaui();
    var ricerca = $('#fo_search').val();
    ricerca = ricerca.replace(/\s*\(.*?\)\s*/g, '');
    var idcl = parseInt(ricerca.replace(/[^0-9\.]/g, ''), 10);
    var clausola = '';
    if (ricerca != '') {
        clausola = " WHERE ID=" + idcl;
    } else {
        clausola = "";
    }

    $.ajax({
        url: currentURL + "assets/inc/fornitori.php",
        method: "POST",
        data: {
            azione: 'aggiorna',
            clausola: clausola
        },
        success: function (data) {
            var res = data.split('|-|')
            $("#FornitoriTableBody").html(res[0]);
            $("#FornitoriTableFooter").html(res[1]);
            docReady(listInit);
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

$(document).on('click', '.aprifornitore', function () {
    CaricaFrame(currentURL + 'assets/page/schedafornitore.php?idfor=' + $(this).attr('idfor'), '<i class="fa-regular fa-parachute-box"></i> Visualizza fornitore', 'Visualizza o modifica fornitore esistente, attraverso tutte le informazioni che trovi qui sotto', '80%')
});

$(document).on('click', '.fa_nuovofornitore', function () {
    CaricaFrame(currentURL + 'assets/page/schedafornitore.php?nuovo', '<i class="fa-regular fa-parachute-box"></i> Visualizza fornitore', 'Crea un nuovo fornitore, inserendo tutte le informazioni necessarie nei campi qui sotto', '80%')
});