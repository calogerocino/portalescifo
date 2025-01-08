function Azione_tms() {
    var tipo = $('#Azioni_tms option:selected').val();
    var idop = $('#Azioni_tms').attr('IDP');
    if (tipo == 1) {
        $('#riapri_tms').remove();
        InviaMailTicket(idop);
        CambiaStatoTicket('Attesa', 'SI');
    } else if (tipo == 2) {
        InviaMailTicket(idop);
        CambiaStatoTicket('Gestione', 'SI');
    } else if (tipo == 4) {
        InviaMailTicket(idop);
        CambiaStatoTicket('Risolto', 'NO');
    } else if (tipo == 3) {
        InviaMailTicket(idop);
        AggironaDateNOW();
    } else if (tipo == 5) {
        CreaNuovaSegnalazione();
    }
}

function CreaNuovaSegnalazione() {
    $("#Azioni_tms option[value='null']").prop('selected', true);
    if ($('#Oggetto_tms').val() == '') {
        Toast.fire({
            icon: 'warning',
            title: 'Inserisci l\'oggetto della segnalazione!'
        })
    } else if ($('#Note_tms').val() == '') {
        Toast.fire({
            icon: 'warning',
            title: 'Inserisci una nota della segnalazone!'
        })
    } else {
        $.post(currentURL + 'assets/inc/ticket.php', {
            idordine: $('#OrdineID').text(),
            azione: 'impostacome',
            stato: 'Nuovo',
            tipologia: $('#Oggetto_tms').val(),
            nota: $('#Note_tms').val()
        }, function (response) {
            if (response == 'Ticket creato con successo') {
                Toast.fire({
                    icon: 'success',
                    title: response
                })
                InviaMailTicket($('#Azioni_tms').attr('IDP'))
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response
                })
            }
        })
    }
}

function CambiaStatoTicket(stato, aperto) {
    $.post(currentURL + 'assets/inc/ticket.php', {
        azione: 'impostacome',
        idordine: $('#OrdineID').text(),
        stato: stato,
        aperto: aperto
    }, function (response) {
        if (response == 'Ticket aggiornato con successo') {
            Toast.fire({
                icon: 'success',
                title: response
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: response
            })
        }
    })
}

function AggironaDateNOW() {
    $.post(currentURL + 'assets/inc/ticket.php', {
        azione: 'aggiornadata',
        idordine: $('#OrdineID').text()
    })
}


// ANCORA DA SISTEMARE IL TUTTO DERIVATO
function InviaMailTicket(idop) {
    var corpo = tinymce.get("descrizione_tms").getContent();

    if (corpo != '') {
        $.post(currentURL + 'assets/mail/invio_mail.php', {
            idord: $('#OrdineID').text(),
            iduser: idop,
            mittente: 'support',
            indirizzodest: $('#MailCliente_ov').text(),
            oggetto: 'Segnalazione su ordine',
            corpo: corpo
        }, function (response) {
            if (response == 'Mail inviata con successo!') {
                Toast.fire({
                    icon: 'success',
                    title: response
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response
                })
            }
        })
    }
}

function CaricaSegnalazione() {
    $.post(currentURL + 'assets/inc/ticket.php', {
        azione: 'datitk',
        idordine: $('#OrdineID').text()
    }, function (data) {
        (ModalitaDebug ? console.log(data) : '')
        if (data != '') {
            var res = data.split('|-|');
            $('#StatoTicket_tms').html(res[0])

            if (res[0] == 'Nuovo') {
                $('#StatoTicket_tms').attr('data-status', 'PENDING');
                $('#riapri_tms').remove()
                $('#crea_tms').remove()
            } else if (res[0] == 'Risolto') {
                $('#StatoTicket_tms').attr('data-status', 'SHIPPED');
                $('#gest_tms').remove()
                $('#mes_tms').remove()
                $('#ris_tms').remove()
                $('#crea_tms').remove()
            } else if (res[0] == 'Gestione') {
                $('#StatoTicket_tms').attr('data-status', 'REFUNDING');
                $('#gest_tms').remove()
                $('#riapri_tms').remove()
                $('#crea_tms').remove()
            }
            $('#Oggetto_tms').val(res[1])
            res[2] != '' ? $('#operatoreatt_tms').html(res[2]) : 'N.D.'
            $('#Note_tms').val(res[4])

            var datares = res[5].slice(0, 10).split('-');
            var datares1 = res[6].split('-');
            $('#dataap_tms').html(datares[2] + '/' + datares[1] + '/' + datares[0] + ' ' + res[5].slice(11, 19))
            res[6] != '' ? $('#ultcont_tms').html(datares1[2] + '/' + datares1[1] + '/' + datares1[0]) : 'N.D.'
        } else {
            $('#StatoTicket_tms').html('CREAZIONE')
            $('#StatoTicket_tms').attr('data-status', 'GUARANTEE');
            $('#riapri_tms').remove()
            $('#gest_tms').remove()
            $('#mes_tms').remove()
            $('#ris_tms').remove()
        }
    })
}

function PreChiusuraModal() {
    $('#dataap_tms').text('N.D.');
    $('#ultcont_tms').text('N.D.');
    $('#StatoTicket_tms').text('N.D.');
    $('#operatoreatt_tms').text('N.D.');
    $('#operatoreatt_tms').html('N.D.');
}

function AggiornaNoteInterne() {
    if ($('#StatoTicket_tms').text() != 'CREAZIONE') {
        $.post(currentURL + 'assets/inc/ticket.php', {
            azione: 'aggiornanota',
            idordine: $('#OrdineID').text(),
            notaint: $('#Note_tms').val()
        }, function (response) {
            if (response == 'ok') {
                Toast.fire({
                    icon: 'success',
                    title: 'Nota interna aggiornata con successo'
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response
                })
            }
        })
    }
}

// ========== RISPOSTE PRE-IMPOSTATE ==========
function RispostaAuto_tsm() {
    $("#autorisp2 option[value='null']").prop('selected', true);
    var x = $('select#autorisp').val();
    var y = $('.header-reford-X#PoP-1').text();
    var z = $('#Oggetto_tms').val();

    var risposta = '<div style="color: #b5b5b5;">##- Digita la risposta sopra questa riga -##</div>'; //fare lo split qua per dividere ....
    risposta = risposta + '<p dir="ltr" style="color: #2b2e2f; font-family: \'Lucida Sans Unicode\', \'Lucida Grande\', \'Tahoma\', Verdana, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0">'

    if (x == 1) {
        risposta = risposta + 'Buongiorno,'
        risposta = risposta + '<br><br>Mi occuperò della sua richiesta.'
        risposta = risposta + '<br><br>Provvederò ad analizzare la sua richiesta e darle una risposta entro 48h.'
        risposta = risposta + '<br><br>Non esiti a contattarmi nuovamente se ci sono problemi'
        risposta = risposta + '<br><br>Cordiali saluti'
    } else if (x == 2) {
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>Con la presente le chiedo di inviare delle prove tramite delle foto o delle immagini per poter procedere con la sua pratica in sospeso.'
        risposta = risposta + '<br><br>Al ricevimento delle prove la sua pratica verrà elaborata, e entro 48h-72h verrà contattato da un nostro operatore.'
        risposta = risposta + '<br><br>Può inviare le foto tramite WhatsApp 3505688846 inserendo il n° ordine: <b>' + y + '</b>'
        risposta = risposta + '<br><br>Si prega di rispondere a questa mail una volta inviate le foto.'
        risposta = risposta + '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci.'
        risposta = risposta + '<br><br>Cordiali saluti'
    } else if (x == 3) {
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>Un nostro operatore ha cercato di contattarvi ma non ha ricevuto risposta'
        risposta = risposta + '<br><br>Rimaniamo a disposizione per qualsiasi ulteriore assistenza.'
        risposta = risposta + '<br><br>Grazie.'
        risposta = risposta + '<br><br>Cordiali saluti'
    } else if (x == 4) {
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>la presente per riferirle che la Sua segnalazione è stata risolta. '
        risposta = risposta + '<br><br><b>Riepilogo della segnalazione:</b> ' + z + '.'
        risposta = risposta + '<br><b>Soluzione:</b>   '
        risposta = risposta + '<br><br>Mi permetto di chiudere il ticket, per qualsiasi altra informazione non esisti a contattarci.'
        risposta = risposta + '<br><br>Distinti saluti,'
    } else if (x == 5) {
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>la presente per riferirle che un nostro operatore ha aperto una segnalazione sulla spedizione con riferimento #' + y
        risposta = risposta + '<br><br><b><b>Riepilogo della segnalazione:</b></b> ' + z + '.'
        risposta = risposta + '<br><b>Problema:</b>   '
        risposta = risposta + '<br><br>Al fine di risolvere la questione, vi chiediamo cortesente di attendere la chiamata di un nostro operatore entro 48/72h.'
        risposta = risposta + '<br><br>Distinti saluti,'
    } else if (x == 6) { // CHIEDI INFORMAZIONI
        risposta = risposta + 'Buongiorno!'
        risposta = risposta + '<br><br>la contatto a fronte della segnalazione aperta con un nostro collega'
        risposta = risposta + '<br><br>Referenza dell\'ordine: <b>' + y + '</b>'
        risposta = risposta + '<br><br><b>Problema:</b> <i>' + z + '</i>'
        risposta = risposta + '<br><br><i>inserire richiesta</i>'
        risposta = risposta + '<br><br>La ringrazio anticipatamente!'
        risposta = risposta + '<br><br>Le auguro una buona giornata,'
    } else if (x == 7) { // MODULO RIMBORSO
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>Con la presente le chiedo di complilare il seguente modulo per aprire una richiesta di rimborso.'
        risposta = risposta + '<br><br>Può compilare il modulo online attraverso il seguente link -> <a href="https://www.scifostore.com/articoli-giardinaggio/28-modulo-rimborso">premi qui</a>'
        risposta = risposta + '<br><br>Dopo aver compilato il modulo la preghiamo di attendere la chiamata da parte di un nostro operatore'
        risposta = risposta + '<br><br>Il riferimento associato al suo ordine è: ' + y
        risposta = risposta + '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
        risposta = risposta + '<br><br>Le auguro una buona giornata,'
    } else if (x == 8) { // LIBERATORIA RIMBORSO
        risposta = risposta + 'Gentile Cliente,'
        risposta = risposta + '<br><br>a seguito alla sua richiesta di rimborso, al fine di poterla perfezionale con il pagamento, la inviatiamo a inviarci liberatoria contenente l\'iban bancario intestato alla persona che ha effettuato l\'ordine correlata da documento d\'identità in corso di validità.'
        risposta = risposta + '<br><br>Dopo aver inviato tutte le informazioni la preghiamo di attendere il contatto da parte di un nostro operatore'
        risposta = risposta + '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
        risposta = risposta + '<br><br>Le auguro una buona giornata,'
    }

    risposta = risposta + '</p>'
    if (x == 'null') {
        risposta = '';
    }
    tinymce.get('descrizione_tms').setContent(risposta);
}

function RispostaAuto2_tsm() {
    $("#autorisp option[value='null']").prop('selected', true);
    var x = $('#autorisp2').val();
    var y = $('.header-reford-X#PoP-1').text();
    var z = $('#Oggetto_tms').val();

    var coupon = '';
    var importo = '';

    if (x == 100) {
        coupon = 'LMKT7AU6';
        importo = '5,00 €';
    } else if (x == 101) {
        coupon = '2V65731E';
        importo = '10,00 €';
    } else if (x == 102) {
        coupon = 'L9XCXPW5';
        importo = '15,00 €';
    } else if (x == 103) {
        coupon = '9DXV73D6';
        importo = '20,00 €';
    }

    var risposta = '<div style="color: #b5b5b5;">##- Digita la risposta sopra questa riga -##</div>'; //fare lo split qua per dividere ....
    risposta = risposta + '<p dir="ltr" style="color: #2b2e2f; font-family: \'Lucida Sans Unicode\', \'Lucida Grande\', \'Tahoma\', Verdana, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0">'
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>Con la presente le informo che ho attivato un codice Coupon dal valore di ' + importo + ' da utilizzare attraverso il nostro <a href="https://scifostore.com">sito web</a>.'
    risposta = risposta + '<br><br>Codice: <b>' + coupon + '</b>'
    risposta = risposta + '<br><br>Può utilizzare il codice quando desidera, non ha nessuna scadenza'
    risposta = risposta + '<br><br>Spero che il gesto sia stato apprezzato.'
    risposta = risposta + '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci.'
    risposta = risposta + '<br><br>Cordiali saluti,'
    risposta = risposta + '</p>'


    if (x == 'null') {
        risposta = '';
    }

    tinymce.get('descrizione_tms').setContent(risposta);
}



function EliminaAllegato_tms(file, id, idp) {
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
                file: 'segnalazione/' + idp + '/' + file,
            }, function (data) {
                (ModalitaDebug ? console.log(data) : '')
                if (data == 'ok') {
                    $('#tms_' + id).remove()
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

$(document).on('click', '.ApriAllegato_tms', function () {
    window.open(currentURL + "upload/segnalazione/" + $(this).attr('idp') + "/" + $(this).attr('all'), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});
