function caricatutto() {
    bloccaui();
    // CARICA DATI CLIENTE
    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'daticlienteid',
        idcl: IDCL_sc
    }, function(data) {
        var res = data.split('|-|');

        $('#cl_id').val(res[0]); //ID
        $('#clmod_ragsoc').text(res[1]); //CLIENTE
        $('#clmod_nick').text(res[2]); //NICK
        $('#indirizzo').val(res[3]); //INDIRIZZO
        $('#citta').val(res[4]); //CITTA
        $('#cellulare').val(res[5]); //CELLULARE
        $('#mail').val(res[6]); //MAIL
        $('#piva').val(res[7]); //PIVA
        $('#codfisc').val(res[8]); //COD FISC
        $('#sdi').val(res[9]); //SDI
        $('#pec').val(res[10]); //PEC
        $('#fido').val(res[11]); //FIDO
        $('#banca').val(res[12]); //BANCA
        $('#iban').val(res[13]); //IBAN
        $('#saldo').val(res[14]); //SALDO

        EventKeyUp_sc();
    })

    // CERCA BUONI O FATTURE
    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'venditecliente',
        idcl: IDCL_sc
    }, function(data) {
        $("#venditecliente").html(data);
    })

    //CERCA RIPARAZIONI
    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'visacconti',
        clausola: ' WHERE c.ID=' + IDCL_sc
    }, function(data) {
        $(".tabellarip").html(data);
    })

    //CERCA STORICO PRODOTTI
    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'storicoprodotti',
        idcl: IDCL_sc
    }, function(data) {
        $(".storicoprodotti").html(data);
    })

    AggiornaCliente_sc(IDCL_sc); //CERCA ACCONTI CLIENTI
    CercaScadenze_sc(IDCL_sc); //CERCA SCADENZE
    sbloccaui();
}

function AggiornaCliente_sc(idcl) {
    //CERCA ACCONTI CLIENTI
    var saldo1 = 0;
    var saldo2 = 0

    $.post(currentURL + "assets/inc/documenti.php", {
        azione: 'acconticlientet2',
        idcl: idcl
    }, function(data) {
        $("#tabellaacconti_ac").html(data);
    })
}


function CercaScadenze_sc(idcl) {
    //CERCA SCADENZE
    $.post(currentURL + "assets/inc/documenti.php", {
            azione: 'scadenzecliente',
            idcl: idcl
        },
        function(data) {
            $("#scadenzelist").html(data);
        }
    );
}

function aggiungimodpag_doc() {
    $.post(currentURL + "assets/inc/documenti.php", {
        azione: 'modpag'
    }, function(data) {
        $("#tippag_ac").html(data);
    })
}


function AggiornaDati_sc(campo) {
    if (IDCL_sc != 'nuovo') {
        bloccaui();
        var valore = $('#' + campo).val();
        //var idcl = $('#idcl').val();

        if (campo == 'fido') {
            valore = valore.replace(",", ".");
            valore = parseFloat(valore);
        }

        // AGGIORNA CLIENTE
        $.post(currentURL + "assets/inc/schedacliente.php", {
            azione: 'aggiornacliente',
            valore: valore,
            campo: campo,
            idcl: IDCL_sc
        }, function(data) {
            if (data == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Cliente aggiornato correttamente'
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Cliente non aggiornato. Errore: ' + data
                })
            }
            sbloccaui();
        })
    }
}

$(document).on('click', '.nuovocliente_sc', function() {
    bloccaui();
    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'caricanuovo',
        cliente: $('#clmod_ragsoc').text(), //CLIENTE
        nick: $('#clmod_nick').text(), //NICK
        indirizzo: $('#indirizzo').val(), //INDIRIZZO
        citta: $('#citta').val(), //CITTA
        cellulare: $('#cellulare').val(), //CELLULARE
        mail: $('#mail').val(), //MAIL
        piva: $('#piva').val(), //PIVA
        codfisc: $('#codfisc').val(), //COD FISC
        sdi: $('#sdi').val(), //SDI
        pec: $('#pec').val(), //PEC
        fido: $('#fido').val(), //FIDO
        banca: $('#banca').val(), //BANCA
        iban: $('#iban').val(), //IBAN
    }, function(data) {
        if (data == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Cliente aggiunto correttamente'
            })
            loation.reload()
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Cliente non aggiunto, errore' + data
            })
        }
        sbloccaui();
    })
});

$(document).on('click', '.econto_sc', function() {
    if (IDCL_sc == '' || IDCL_sc == null) {
        Toast.fire({
            icon: 'error',
            title: 'Devi caricare un cliente prima di visualizzare gli acconti!'
        })
    } else {
        window.open(currentURL + 'assets/pdf/e-conto.php?create_pdf=1&idcl=' + IDCL_sc, 'PDF Documento', 'width=800, height=800, status, scrollbars=1, location');
    }
});

function EventKeyUp_sc() {
    $('[id^=clmod_]').keyup(function() {
        if (typeof modTimeout != 'undefined')
            window.clearTimeout(modTimeout);
        element = $(this);
        modTimeout = window.setTimeout(function() {
            clmod(element)
        }, 500);
    })
}

function clmod(element) {
    id_rigar = element.attr('id');
    id_riga = $(this)[0].id_rigar.split('_');
    var r = $('#clmod_' + id_riga[1]).text();
    if (id_rigar == 'clmod_nick') {
        campo = 'nick'
    } else if (id_rigar == 'clmod_ragsoc') {
        campo = 'cliente'
    }

    $.post(currentURL + "assets/inc/schedacliente.php", {
        azione: 'aggiornacliente',
        valore: r,
        campo: campo,
        idcl: IDCL_sc
    }, function(data) {
        if (data == 'si') {
            Toast.fire({
                icon: 'success',
                title: 'Cliente aggiornato correttamente'
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Cliente non aggiornato. Errore: ' + data
            })
        }
        sbloccaui();
    })
}

//ACCONTI SYSTEM
$(document).on('click', '.salvaacc_ac', function() {
    bloccaui();
    $('.salvaacc_ac').prop('disabled', true);
    var comm = $('#commentoacconto').val();
    var acc = $('#accontocons').val();
    var dataacc = $('#dataacconto').val();
    var pagacc = $('#tippag_ac').val();

    if (IDCL_sc == '' || IDCL_sc == null) {
        Toast.fire({
            icon: 'warning',
            title: 'Prima di aggiungere un acconto devi caricare il cliente'
        })
    } else if (acc == '' || acc == null) {
        Toast.fire({
            icon: 'warning',
            title: 'Devi inserire l\'importo dell\'acconto'
        })
    } else {
        acc = parseFloat(acc).toFixed(2);


        $.post(currentURL + "assets/inc/documenti.php", {
            azione: 'addacconto',
            idcl: IDCL_sc,
            comm: comm,
            acc: acc,
            dataacc: dataacc,
            pagacc: pagacc
        }, function(data) {
            console.log(data)
            if (data == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Acconto aggiunto con successo'
                })
                $('commentoacconto').val('');
                $('accontocons').val('');
                $("#ttabellaacconti_ac").html('');
                AggiornaCliente_sc(IDCL_sc);
                CercaScadenze_sc(IDCL_sc);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + data
                })
            }
            sbloccaui();
        })
    }
});
$(document).on('click', '.eliminaacc', function() {
    var data = new FormData();

    data.append("mittente", 'noreply');
    data.append("indirizzodest", 'assistenza@scifostore.com');
    data.append("oggetto", 'Richiesta eliminazione Acconto Fornitore');
    data.append("corpo", 'E\' stata richiesta l\'eliminazione dell\'acconto sul cliente ID:' + IDCL_sc);

    $.ajax({
        url: currentURL + "assets/mail/invio_mail.php",
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        success: function(response) {
            Swal.fire({
                title: 'OK!',
                text: 'Richiesta effettuata con successo \n riceverai una conferma ad avvenuta eliminazione...',
                icon: 'info',
                confirmButtonText: 'Chiudi'
            })
        },
        error: function() {
            Toast.fire({
                icon: 'error',
                title: 'Errore nel gestire la richiesta'
            })
        }
    });
});