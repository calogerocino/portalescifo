
$(document).on('click', '.NuovoDipendente', function () {
    if ($('#dpmod_NomeCognome').text() == '') {
        Toast.fire({
            icon: 'warning',
            title: 'Prima di procedere, inserisci il nome del dipendente'
        })
    } else if ($('#Stato').val() == '-') {
        Toast.fire({
            icon: 'warning',
            title: 'Prima di procedere, inserisci lo stato del dipendente'
        })
    } else if ($('#CodFisc').val() == '' || !validaCodiceFiscale($('#CodFisc').val())) {
        Toast.fire({
            icon: 'warning',
            title: 'Verifica la correttezza del codice fiscale!'
        })
    } else {
        bloccaui();
        $.post(currentURL + "assets/inc/schedadipendente.php", {
            azione: 'caricanuovo',
            dipe: $('#dpmod_NomeCognome').text(), //CLIENTE
            indirizzo: $('#Indirizzo').val(), //INDIRIZZO
            citta: $('#Citta').val(), //CITTA
            cellulare: $('#Cellulare').val(), //CELLULARE
            mail: $('#Mail').val(), //MAIL
            pec: $('#pec').val(), //PEC
            codfisc: $('#CodFisc').val(), //COD FISC
            banca: $('#Banca').val(), //BANCA
            iban: $('#Iban').val(), //IBAN
            bic: $('#Bic').val(), //BIC
            stato: $('#Stato').val(), //BIC
        }, function (r) {
            if (r == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Dipendete aggiunto correttamente'
                })

                $('#univ-offcanvas').offcanvas('hide')
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Dipendete non aggiunto. Errore: ' + r
                })
            }
            sbloccaui();
        })
    }
});

function CaricaDati_sd() {
    bloccaui();
    // CARICA DATI FORNITORE
    $.post(currentURL + "assets/inc/schedadipendente.php", {
        azione: 'datidipendente',
        iddip: IDDipen
    }, function (r) {
        (ModalitaDebug ? console.log(r) : '')
        var rr = r.split('|-|');
        $('#dpmod_NomeCognome').text(rr[1]); //CLIENTEv
        $('#Indirizzo').val(rr[2]); //INDIRIZZO
        $('#Citta').val(rr[3]); //CITTA
        $('#Cellulare').val(rr[4]); //CELLULARE
        $('#Mail').val(rr[5]); //MAIL
        $('#pec').val(rr[6]); //PEC
        $('#CodFisc').val(rr[7]); //COD FISC
        $('#Banca').val(rr[8]); //BANCA
        $('#Iban').val(rr[9]); //IBAN
        $('#Bic').val(rr[10]); //BIC
        $('#Stato option[value=' + rr[11] + ']').attr('selected', 'selected');
        EventKeyUp_sd();
    })

    BustePaghe() // CERCA BUSTE PAGHE
    sbloccaui();
}

function BustePaghe() {
    $.post(currentURL + "assets/inc/schedadipendente.php", {
        azione: 'bustedipendente',
        iddip: IDDipen
    }, function (r) {
        (ModalitaDebug ? console.log(r) : '')
        $(".ListaBustePaghe_sd").html(r);
    })

    // CREA ESTRATTO CONTO
    $.post(currentURL + "assets/inc/schedadipendente.php", {
        azione: 'econtodipendente',
        iddip: IDDipen
    }, function (r) {
        (ModalitaDebug ? console.log(r) : '')
        $(".EstrattoConto_sd").html(r);
    })
}

function EventKeyUp_sd() {
    $('[id^=dpmod_]').keyup(function () {
        if (typeof modTimeout != 'undefined')
            window.clearTimeout(modTimeout);
        element = $(this);
        modTimeout = window.setTimeout(function () {
            dpmod(element)
        }, 500);
    })
}

function dpmod(element) {
    var r = $('#dpmod_NomeCognome').text();
    campo = 'Dipendente'
    $.post(currentURL + "assets/inc/schedadipendente.php", {
        azione: 'aggiornadipendente',
        valore: r,
        campo: campo,
        iddip: IDDipen
    }, function (r) {
        if (r == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Dipendente aggiornato correttamente'
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Dipendente non aggiornato. Errore: ' + r
            })
        }
        sbloccaui();
    })
}

function AggiornaDati_sd(campo) {
    if (IDDipen != 'nuovo') {
        bloccaui();
        let valore = $('#' + campo).val();
        $.post(currentURL + "assets/inc/schedadipendente.php", {
            azione: 'aggiornadipendente',
            valore: valore,
            campo: campo,
            iddip: IDDipen
        }, function (r) {
            if (r == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Dipendente aggiornato correttamente'
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Dipendente non aggiornato. Errore: ' + r
                })
            }
            sbloccaui();
        })
    }
}

function AggiornaDati_cf_sd() {
    if (IDDipen != 'nuovo') {
        (validaCodiceFiscale($('#CodFisc').val()) ? AggiornaDati_sd('CodFisc') : Toast.fire({
            icon: 'error',
            title: 'Impossibile aggiornare il codice fiscale, controlla che sia corretto'
        }))
    }
}

$(document).on('click', '.StampaEstrattoConto_sd', function () {
    window.open(currentURL + 'assets/pdf/e-conto-dip.php?create_pdf=1&iddip=' + IDDipen, 'PDF Documento', 'width=800, height=800, status, scrollbars=1, location');
});



$(document).on('click', '.AggiungiNuovaBusta_sd', function () {
    let d = $('#DataEmissioneBusta_sd').val();
    let i = $('#ImportoBusta_sd').val();
    if (isNaN(i) || i == '') {
        Toast.fire({
            icon: 'error',
            title: 'L\'importo non è numerico, si prega di controllare'
        })
    } else if (d == '') {
        Toast.fire({
            icon: 'error',
            title: 'Inserisci la data della busta paga!'
        })
    } else {
        bloccaui();
        $.post(currentURL + 'assets/inc/schedadipendente.php', { azione: 'inseriscibusta', iddip: IDDipen, data: d, importo: i }, function (r) {
            if (r == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Busta inserita con successo.'
                })
                BustePaghe();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Busta non inserita. Errore: ' + r
                })
            }
            sbloccaui()
        })
    }
});

function CambiaImportoBusta(IDB, s) {
    if ($('#B-' + IDB).val() > parseFloat(s)) {
        Toast.fire({
            icon: 'error',
            title: 'Non è possibile inserire un importo superiore a ' + s
        })
    } else {
        bloccaui()
        $.post(currentURL + 'assets/inc/schedadipendente.php', { azione: 'aggiornaimportobusta', valore: $('#B-' + IDB).val(), id: IDB, iddip: IDDipen }, function (r) {
            if (r == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Importo aggiornato con successo.'
                })
                BustePaghe();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Importo non aggiornato. Errore: ' + r
                })
            }
            sbloccaui()
        })
    }
}

$(document).on('click', '.AllegatoBusta_sd', function () {
    var allegato = $(this).attr('allegatobusta');
    $.get(currentURL + 'upload/dipendente/' + IDDipen + '/' + $(this).attr('allegatobusta') + '.pdf')
        .done(function () {
            window.open(currentURL + "upload/dipendente/" + IDDipen + "/" + allegato + '.pdf', 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
        }).fail(async function () {
            const {
                value: file
            } = await Swal.fire({
                title: 'Seleziona file',
                input: 'file',
                enctype: 'multipart/form-data',
                inputAttributes: {
                    'accept': 'application/pdf',
                    'aria-label': 'Carica il file'
                }
            })
            if (file) {
                var data = new FormData();
                data.append("userfile", file);
                data.append('cartella', 'dipendente');
                data.append('dipendente', IDDipen);
                data.append('nomefile', allegato);
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
        })
});

function validaCodiceFiscale(cf) {
    var validi, i, s, set1, set2, setpari, setdisp;
    if (cf == '') return '';
    cf = cf.toUpperCase();
    if (cf.length != 16)
        return false;
    validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (i = 0; i < 16; i++) {
        if (validi.indexOf(cf.charAt(i)) == -1)
            return false;
    }
    set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
    s = 0;
    for (i = 1; i <= 13; i += 2)
        s += setpari.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));
    for (i = 0; i <= 14; i += 2)
        s += setdisp.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));
    if (s % 26 != cf.charCodeAt(15) - 'A'.charCodeAt(0))
        return false;
    return true;
}


// LISTA DIPENDENTI
function AggiornaLista_ld() {
    bloccaui();
    var ricerca = $('#Cerca_ld').val();
    if (ricerca != '') {
        ricerca = ' WHERE Dipendente LIKE \'%' + ricerca + '%\''
    } else {
        ricerca = ''
    }
    $.post(currentURL + "assets/inc/schedadipendente.php", { azione: 'aggiornalista', ricerca: ricerca }, function (r) {
        (ModalitaDebug ? console.log(r) : '')
        $("#DipendentiTableBody").html(r);
        docReady(listInit);
        sbloccaui();
    })
}

$(document).on('click', '.ApriDipendente_ld', function () {
    CaricaFrame(currentURL + 'amministrazione/dipendente.php?iddip=' + $(this).attr('iddip'), '<i class="fa-solid fa-briefcase"></i> Visualizza scheda dipendente', 'Visualizza o modifica un dipendente esistente, attraverso tutte le informazioni che trovi qui sotto', '80%')
});

$(document).on('click', '.NuovoDipendente_ld', function () {
    CaricaFrame(currentURL + 'amministrazione/dipendente.php?nuovo', '<i class="fa-solid fa-briefcase"></i> Crea scheda dipendente', 'Crea un nuovo dipendente, inserendo tutte le informazioni necessarie nei campi qui sotto', '80%')
});

$(document).on('click', '.ApriAllegato_sd', function () {
    window.open(currentURL + "upload/dipendente/" + IDDipen + "/" + $(this).attr('all'), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

$(document).on('click', '.CaricaDocumento_sd', async function () {
    const {
        value: file
    } = await Swal.fire({
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
        data.append('cartella', 'dipendente');
        data.append('dipendente', IDDipen);
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

function EliminaAllegato_sd(file, id) {
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
                file: 'dipendente/' + IDDipen + '/' + file,
            }, function (data) {
                (ModalitaDebug ? console.log(data) : '')
                if (data == 'ok') {
                    $('#alsd' + id).remove()
                    Toast.fire({
                        icon: 'success',
                        title: 'Eliminato con successo'
                    })
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
