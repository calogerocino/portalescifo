function PopolaAvvocati() {
    $.post(currentURL + "assets/inc/pratiche.php", { azione: 'popolaavvocati' }, function (data) { $("#avvocatisel").html(data); AggiornaPratiche(); });
}

function AggiornaPratiche() {
    bloccaui();
    var clausolaavv = $('#searchavv option:selected').val();
    var clausolastato = $('#searchstato option:selected').val();
    var clausoladesc = $('#searchdesc').val();

    if (clausolaavv != '') {
        clausolaavv = ' AND a.id =\'' + clausolaavv + '\' ';
    } else {
        clausolaavv = '';
    }

    if (clausoladesc != '') {
        clausoladesc = ' AND p.descrizione LIKE \'%' + clausoladesc + '%\' ';
    } else {
        clausoladesc = '';
    }

    clausolastato = " WHERE p.Stato = " + clausolastato + clausolaavv + clausoladesc;


    $.post(currentURL + 'assets/inc/pratiche.php', { azione: 'aggiornalista', clausola: clausolastato }, function (data) {
        $("#PraticheTableBody").html(data);
        docReady(listInit);
        sbloccaui();
    })
}

$(document).on('click', '.pr_listaprac_print', function () {
    window.open(currentURL + 'assets/pdf/listapratiche.php?create_pdf=1&ids=' + $('#searchstato option:selected').val(), 'Documento PDF', 'width=800, height=800, status, scrollbars=1, location');
});

$(document).on('click', '.abpratica', function () {
    if ($('.abpratica').html() == 'Disabilita modifica') {
        bspratica('blocca');
        $('.abpratica').html('Abilita modifica');
    } else if ($('.abpratica').html() == 'Abilita modifica') {
        bspratica('sblocca');
        $('.abpratica').html('Disabilita modifica');
    }
});

$(document).on('click', '.abavv', function () {
    if ($('.abavv').html() == 'Disabilita modifica') {
        bsavv('blocca');
        $('.abavv').html('Abilita modifica');
    } else if ($('.abavv').html() == 'Abilita modifica') {
        bsavv('sblocca');
        $('.abavv').html('Disabilita modifica');
    }
});


function CaricaPratica(idpratica) {
    $.post((currentURL + "assets/inc/pratiche.php"), { azione: "caricapratica", idpratica: idpratica }, function (response) {
        (ModalitaDebug ? console.log(response) : '')
        var res = response.split('|--|');
        var res1 = res[0].split('|-|');
        var res2 = res[1].split('|-|');

        $('#idpratica').val(res1[0]);
        $('#descprat').val(res1[1]);
        $('#noteprat').val(res1[2]);
        $('#changestato').val(res1[3]);

        $('#idavv').val(res2[0]);
        $('#nomeavv').val(res2[1]);
        $('#studioavv').val(res2[2]);
        $('#indavv').val(res2[7]);
        $('#cellulare1avv').val(res2[3]);
        $('#cellulare2alt').val(res2[4]);
        $('#emailavv').val(res2[5]);
        $('#pecavv').val(res2[6]);

        $("#tabellasentenze").html(res[2]);

    });
}

function EliminaAllegato_pr(file, id) {
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
                file: 'pratiche/' + file,
            }, function (data) {
                (ModalitaDebug ? console.log(data) : '')
                if (data == 'ok') {
                    $('#pr_' + id).remove()
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

function bspratica(comando) {
    if (comando == 'blocca') {
        comandos = true
    } else if (comando == 'sblocca') {
        comandos = false
    }

    $('#descprat').prop('readonly', comandos);
    $('#noteprat').prop('readonly', comandos);
}

function bsavv(comando) {
    if (comando == 'blocca') {
        comandos = true
    } else if (comando == 'sblocca') {
        comandos = false
    }

    $('#nomeavv').prop('readonly', comandos);
    $('#studioavv').prop('readonly', comandos);
    $('#indavv').prop('readonly', comandos);
    $('#cellulare1avv').prop('readonly', comandos);
    $('#cellulare2alt').prop('readonly', comandos);
    $('#emailavv').prop('readonly', comandos);
    $('#pecavv').prop('readonly', comandos);
}


function AggiornaAvvocato(campo) {
    if ($('#idpratica').val() != '0' && $('#idpratica').val() != '-1') {
        if (campo == 'nomeavv') {
            ncampo = 'nome';
        } else if (campo == 'studioavv') {
            ncampo = 'studio';
        } else if (campo == 'indavv') {
            ncampo = 'indirizzo';
        } else if (campo == 'cellulare1avv') {
            ncampo = 'cellulare1';
        } else if (campo == 'cellulare2alt') {
            ncampo = 'cellulare2';
        } else if (campo == 'emailavv') {
            ncampo = 'email';
        } else if (campo == 'pecavv') {
            ncampo = 'pec';
        }

        $.post((currentURL + "assets/inc/pratiche.php"), { azione: "aggiornaavvcato", idavv: $('#idavv').val(), campo: ncampo, nuovodato: $('#' + campo).val() }, function (response) {
            if (response == 'ok') {
                Toast.fire({
                    icon: 'success',
                    title: 'Dati avvoccato aggiornati con successo'
                })
                $('.abavv').click();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + response
                })
            }
        });
    } else if ($('#idpratica').val() == '0') {
        $.post((currentURL + "assets/inc/pratiche.php"), { azione: "caricaavvocato", nome: $('#' + campo).val() }, function (response) {
            var res = response.split('|-|');
            $('#idavv').val(res[0]);
            $('#nomeavv').val(res[1]);
            $('#studioavv').val(res[2]);
            $('#cellulare1avv').val(res[3]);
            $('#cellulare2alt').val(res[4]);
            $('#emailavv').val(res[5]);
            $('#pecavv').val(res[6]);
            $('#indavv').val(res[7]);
            bsavv('blocca')
        });
    }
}

function AggiornaPratica(campo) {
    if ($('#idpratica').val() != '0' && $('#idpratica').val() != '-1') {
        if (campo == 'descprat') {
            ncampo = 'descrizione';
        } else if (campo == 'noteprat') {
            ncampo = 'note';
        }

        $.post((currentURL + "assets/inc/pratiche.php"), { azione: "aggiornapratica", idpratica: $('#idpratica').val(), campo: ncampo, nuovodato: $('#' + campo).val() }, function (response) {
            if (response == 'ok') {
                InvioMailPratica('aggiornata', 'Sono stati aggiornati i dettagli di questa pratica. Apri per maggiori informazioni!')
                Toast.fire({
                    icon: 'success',
                    title: 'Pratica aggiornata con successo'
                })
                $('.abpratica').click();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + response
                })
            }
        });
    }
}

$(document).on('click', '.addsent', function () {
    if ($('.addsent').html() == 'Aggiungi sentenza') {
        $('#creanuovasentenza').prop('hidden', false);
        $('.addsent').html('Crea sentenza');
    } else if ($('.addsent').html() == 'Crea sentenza') {

        $.post((currentURL + "assets/inc/pratiche.php"), { azione: "creassenteza", idpratica: $('#idpratica').val(), data: $('#datasent').val(), sede: $('#sedesent').val(), note: $('#notesent').val() }, function (response) {
            if (response == 'ok') {
                InvioMailPratica('aggiornata', 'E\' stata creata la sentenza inerente a questa pratica!')
                Toast.fire({
                    icon: 'success',
                    title: 'Sentenza creata con successo'
                })
                cambiopagina('amministrazione', 'pratica', '?idpratica=' + $('#idpratica').val())
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + response
                })
            }
        });

        $('#creanuovasentenza').prop('hidden', true);
        $('.addsent').html('Aggiungi sentenza');
    }
});

function ChiudiSentenza(idsent) {

    $.post((currentURL + "assets/inc/pratiche.php"), { azione: "chiudisentenza", idsent: idsent }, function (response) {
        if (response == 'ok') {
            InvioMailPratica('aggiornata', 'E\' stata conclusa la sentenza inerente a questa pratica!')
            Toast.fire({
                icon: 'success',
                title: 'Sentenza conclusa con successo'
            })
            cambiopagina('amministrazione', 'pratica', '?idpratica=' + $('#idpratica').val())
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + response
            })
        }
    });
}

$(document).on('click', '.nuovapratica', function () {
    var idavv = $('#idavv').val();
    var descpratica = $('#descprat').val();
    var notepratica = $('#noteprat').val();

    if (idavv == '') {
        Swal.fire({
            title: 'Attenzione!',
            text: 'Per procedere devi prima caricare un avvocato',
            icon: 'error',
            confirmButtonText: 'Sistemo'
        })
    } else if (descpratica == '') {
        Swal.fire({
            title: 'Attenzione!',
            text: 'Per procedere devi prima inserire la descrizione della pratica',
            icon: 'error',
            confirmButtonText: 'Sistemo'
        })
    } else if (notepratica == '') {
        Swal.fire({
            title: 'Attenzione!',
            text: 'Per procedere devi prima inserire delle note alla pratica',
            icon: 'error',
            confirmButtonText: 'Sistemo'
        })
    } else {
        $.post((currentURL + "assets/inc/pratiche.php"), { azione: "nuovapratica", idavv: idavv, descrizione: descpratica, note: notepratica }, function (response) {
            if (response == 'ok') {
                InvioMailPratica('creata', 'Nuova pratica creata, apri le pratiche legali e visualizza per maggiori dettagli.')
                Toast.fire({
                    icon: 'success',
                    title: 'Pratica creata con successo'
                })
                cambiopagina('amministrazione', 'pratica', '?nuova=1');
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + response
                })
            }
        });
    }
});

function InvioMailPratica(cosa, m) {
    var data = new FormData();
    data.append("mittente", 'noreply');
    data.append("indirizzodest", 'amministrazione@scifostore.com');
    data.append("oggetto", 'E\' stata ' + cosa + ' una pratica legale');
    data.append("corpo", "Hai ricevuto questa mail perchè è stata " + cosa + " la pratica <b>'" + $('#descprat').val() + "'</b><br /><b>Modifica:</b> " + m);
    $.ajax({
        url: currentURL + "assets/mail/invio_mail.php",
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        success: function (res) {
        }
    });
}

function CambiaStatoPratica() {
    $.post(currentURL + "assets/inc/pratiche.php", { azione: 'cambiastatopratica', idpratica: $('#idpratica').val(), stato: $('#changestato option:selected').val() }, function (response) {
        if (response == 'ok') {
            InvioMailPratica('aggiornata', 'Lo stato di questa pratica è stato modificato!')
            Toast.fire({
                icon: 'success',
                title: 'Pratica aggiornata con successo'
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + response
            })
        }
    });
}

$(document).on('click', '.carall_pr', async function () {
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
        data.append('cartella', 'pratiche');
        data.append('idpratica', '[' + $('#idpratica').val() + '] ');
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
                    Swal.fire({ icon: 'error', title: 'File non esistente o sconosciuto' })
                } else if (data == 'carno') {
                    Swal.fire({ icon: 'error', title: 'Errore generico nel salvataggio del file!' })
                } else {
                    InvioMailPratica('aggiornata', 'E\' stato aggiunto un nuovo allegato!')
                    Swal.fire({ icon: 'success', title: 'File caricato correttamente!' })
                }
            }
        });
        e.preventDefault();
    }
});

$(document).on('click', '.pr_apriall', function () {
    window.open(currentURL + "upload/pratiche/" + $(this).attr('all'), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

