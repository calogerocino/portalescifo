function CaricaVisualizzaOrdine(IDORdine) {
    // ========== CARICA CORRIERE ==========
    var url;
    var corriere = $('#zhhxsbwp').val();
    var tracking = $('#mdilkrwh').val();
    if (corriere == 'TNT') {
        url = "https://www.tnt.it/tracking/getTrack?WT=1&ConsigNos=" + tracking;
    } else if (corriere == 'BRT' || corriere == 'BRT 1' || corriere == 'BRT 2') {
        url = "https://vas.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&ChiSono=" + tracking;
    } else if (corriere == 'GLS') {
        url = "https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione=" + tracking + "&tipo_codice=nazionale";
    } else if (corriere == 'DHL') {
        url = "https://mydhl.express.dhl/it/it/tracking.html#/results?id=" + tracking;
    } else if (corriere == 'Poste Italiane') {
        url = "https://www.poste.it/cerca/index.html#/risultati-spedizioni/" + tracking;
    } else if (corriere == 'SDA') {
        url = "https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it&tracing.letteraVettura=" + tracking;
    } else if (corriere == 'SAVISE') {
        url = "https://www.oneexpress.it/it/cerca-spedizione/";
    }

    $('#rlzk3mqu').val(url);

    // ========== CARICA STATO ==========
    var valore = $('#header-stato').html();

    if (valore == 'In Stock') {
        $('#header-stato').attr('data-status', 'REFUNDING');
        CaricaRitardo();
    } else if (valore == 'Rimborsato' || valore == 'Annullato') {
        $('#header-stato').attr('data-status', 'REFUSED');
    } else if (valore == 'Da Rimborsare') {
        $('#header-stato').attr('data-status', 'REFUNDED');
    } else if (valore == 'Sospeso' || valore == 'Rientrato') {
        $('#header-stato').attr('data-status', 'GUARANTEE');
    } else if (valore == 'Preventivo' || valore == 'Attesa di pagamento' || valore == 'Importato') {
        $('#header-stato').attr('data-status', 'PENDING');
    } else if (valore == 'Evaso') {
        $('#header-stato').attr('data-status', 'SHIPPED');
    } else if (valore == 'Da Gestire') {
        $('#header-stato').attr('data-status', 'PREPARATION');
        CaricaRitardo();
    }

    // ========== CARICA SEGNALAZIONE ==========
    $.post(currentURL + 'assets/inc/modifica_ordine.php', {
        modifica: 'cercaticket',
        ido: IDORdine
    }, function (response) {
        if (response == 'si') {
            $('#header-segnalazione').text('Aperta');
            $('#header-segnalazione').attr('data-status', 'REFUSED');
            $('#header-segnalazione').attr('title', 'ATTENZIONE! Risulta esserci aperta una segnalazione');
        } else if (response == 'no') {
            $('#header-segnalazione').text('Nessuna');
            $('#header-segnalazione').attr('data-status', 'SHIPPED');
            $('#header-segnalazione').attr('title', 'Nessuna segnalazione aperta per l\'ordine');
        }
    })
}

function CaricaRitardo() {
    // ========== CARICA RITARDO ==========  
    var data1 = $('#DataOrdine_ov').text();

    anno1 = parseInt(data1.substr(6), 10);
    mese1 = parseInt(data1.substr(3, 2), 10);
    giorno1 = parseInt(data1.substr(0, 2), 10);

    var dataok1 = new Date(anno1, mese1 - 1, giorno1);
    var dataok2 = new Date();

    differenza = dataok2 - dataok1;
    giorni_differenza = Math.trunc(new String(differenza / 86400000));

    if (giorni_differenza >= 15) {
        $('#header-ritardo').text(giorni_differenza + ' giorni');
        $('#header-ritardo').attr('data-status', 'REFUSED');
        $('#header-ritardo').attr('title', 'ATTENZIONE! L\'ordine è in ritardo di: ' + giorni_differenza);
    } else {
        $('#header-ritardo').attr('data-status', 'SHIPPED');
        $('#header-ritardo').attr('title', ' Nessun ritardo per l\'ordine');
    }
}

$(document).on('click', '.ApriMarketplace_ov', function () {
    var idmarkett = $('#Marketplace_ov').text();
    var piattaforma = $('#Piattaforma_ov').text();
    var idpresta = $('#IDPrestashop_ov').text();

    if (piattaforma == "ManoMano") {
        window.open("https://toolbox.manomano.com/orders?order_id=" + idmarkett.substring(5, idmarkett.length), "");
    } else if (piattaforma == "ePrice") {
        window.open("https://marketplace.eprice.it/mmp/shop/order/" + idmarkett + "/information", "");
    } else if (piattaforma == "Sito") {
        window.open("https://scifostore.com/admin22SS/index.php?controller=AdminOrders&vieworder=&id_order=" + idpresta + "&token=e35fx2ZCKy5FVXzTl0kgHKZyASPseFFo1nMWT3GB9wI", "");
    } else if (piattaforma == "eBay") {
        window.open("https://www.ebay.it/sh/ord/details?orderid=" + idmarkett, "");
    }
});

$(document).on('click', '#IDPrestashop_ov', function () {
    var idpresta = $('#IDPrestashop_ov').text();
    window.open("https://scifostore.com/admin22SS/index.php?controller=AdminOrders&vieworder=&id_order=" + idpresta + "&token=e35fx2ZCKy5FVXzTl0kgHKZyASPseFFo1nMWT3GB9wI", "");
});

$(document).on('click', '.InviaWA_ov', function () {
    var numero = $('#RecapitoCliente_ov').text();
    window.open('https://api.whatsapp.com/send?phone=39' + numero + '&text=h&source=&data=' + '&hl=it', 'Google Maps', 'width=800, height=800, resizable, status, scrollbars=1, location');
});


$(document).on('click', '.ApriMappa_ov', function () {
    var citta = $('#IndirizzoCliente_ov').text();
    if (citta == '') {
        Toast.fire({
            icon: 'error',
            title: 'Errore: Nessuna città trovata'
        })
    } else {
        var query = citta.replace(' ', '+');
        window.open('https://www.google.com/maps/search/?api=1&query=' + query + '&hl=it', 'Google Maps', 'width=1080, height=1080, resizable, status, scrollbars=1, location');
    }
});

function InfoOrdineRicambi_ov(idpr, ido) {
    $.post(currentURL + 'assets/inc/ordinefornitore.php', {
        azione: 'cercainforicambio',
        idord: ido,
        idpr: idpr
    }, function (response) {
        Toast.fire({
            icon: 'info',
            title: response
        })
    })
}

$(document).on('click', '.prodottoord', function () {
    Toast.fire({
        icon: 'warning',
        title: 'In manutenzione, a breve potrai visualizzare il prodotto'
    })
});


$(document).on('click', '.StampaOrdine_ov', function () {
    window.open(currentURL + 'assets/pdf/ordine.php?create_pdf=1&idordine=' + $(this).attr('idord'), 'PDF Ordine', 'width=800, height=800, status, scrollbars=1, location');
});


$(document).on('click', '.prch_prenota', function() {
    let DestMail = $('#prch_dest').val();
    let piatt = $('#Piattaforma_ov').text();
    let ido = $('#OrdineID').text();

    let ref = $('.header-reford-X#PoP-1').text()
    let cliente = $('#ClienteNome_ov').text();

    if (DestMail != '-') {
        Swal.fire({
            title: 'Invio in corso ..',
            showConfirmButton: false
        });

        var utente = $('#PrenotaChiamata_ov').attr('utente');
        var corpo = 'E\' stata appena prenotata una chiamata da parte di <i>' + utente + '</i><br /> <b>Ordine:</b> ' + ref + '<br /> <b>Cliente:</b> ' + cliente + '<br /><br /> <b>Note:</b><br />' + tinymce.get("prch_note").getContent();

        var data = new FormData();

        data.append("idord", ido);
        data.append("iduser", $('#PrenotaChiamata_ov').attr('idu'));
        data.append("mittente", 'support');
        data.append("indirizzodest", DestMail + '@scifostore.com');
        data.append("oggetto", 'Prenotazione chiamata');
        data.append("corpo", corpo);
        data.append("file", $("#prch_file").prop("files")[0]);

        $.ajax({
            url: currentURL + "assets/mail/invio_mail.php",
            method: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            success: function(response) {
                Toast.fire({
                    icon: 'success',
                    title: response
                })

                if (($('#prch_cliente').prop('checked') == true) && (piatt == 'Sito' || piatt == 'Altro' || piatt == 'eBay')) {
                    MailCliente_Invio(cliente, ido)
                }
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore nel gestire la richiesta!'
                })
            }
        });
    } else {
        Toast.fire({
            icon: 'error',
            title: 'Seleziona un destinatario!'
        })
    }
});