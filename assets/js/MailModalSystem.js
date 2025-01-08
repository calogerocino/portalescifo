// MAIL MODAL SYSTEMS

var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

$(document).on('click', '.mms-mail_send', function () {
    if ($('#mms-mail_mitt').val() != undefined || $('#mms-mail_mitt').val() != '') {
        if (regex.test($('#mms-mail_dest').val())) {
            Swal.fire({
                title: 'Invio in corso ..',
                showConfirmButton: false
            });

            var data = new FormData();
            if ($('#mms-idordine').val != '') {
                data.append("idord", $('#mms-idordine').text());
                data.append("iduser", $('#mms-idoperatore').text());
                tinymce.get("tmce-MMS").setContent($('#mms-mail_mess-supp').val() + tinymce.get("tmce-MMS").getContent())
            }
            data.append("mittente", $('#mms-mail_mitt').val());
            data.append("indirizzodest", $('#mms-mail_dest').val());
            data.append("oggetto", $('#mms-mail_ogg').val());
            data.append("corpo", tinymce.get("tmce-MMS").getContent());
            data.append("file", $("#mms-attachmentButton").prop("files")[0]);

            $.ajax({
                url: currentURL + "assets/mail/invio_mail.php",
                method: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response
                    })
                    $('#mms-mailmodal').modal('toggle');
                    $('#mmsr-mailmodal').modal('toggle');
                }
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Prima di inviare una mail devi inserire una mail valida per il destinatario!'
            })
        }
    } else {
        Toast.fire({
            icon: 'error',
            title: 'Prima di inviare una mail devi inserire un mittente dalla lista!'
        })
    }
});

$("#mms-mail_dest").autocomplete({
    source: ['assistenza@scifostore.com', 'info@scifostore.com', 'amministrazione@scifostore.com', 'ricambi@scifostore.com', 'eprice@scifostore.com', 'manomano@scifostore.com', 'ebay@scifostore.com', 'leroymerlin@scifostore.com', 'spedizioni@scifostore.com']
});

$(document).on('click', '.mmsr-aprimail', function () {
    tinymce.init({
        menubar: false,
        statusbar: false,
        height: "370",
        readonly: 1,
        selector: '#tmce-MMSR'
    });
    $.post(currentURL + "assets/mail/leggi-mailid.php", {
        idmail: $(this).attr('idmail')
    }, function (response) {
        var res = response.split('|-|');
        $('#mmsr-mail_mitt').val(res[2]);
        $('#mmsr-mail_ogg').val(res[1]);
        tinymce.get("tmce-MMSR").setContent(res[0]);

    })
});

$(document).on('click', '.mmsr-mail_reply', function () {
    tinymce.init({
        menubar: false,
        statusbar: false,
        height: "370",
        selector: '#tmce-MMS'
    });


    $('#mmsr-mailmodal').modal('hide');
    $('#mms-mailmodal').modal('show');

    $('#mms-mail_mitt').val('support');
    $('#mms-mail_mitt').attr('readonly', true);

    $('#mms-mail_dest').val($('#mmsr-mail_mitt').val().split('<')[1].split('>')[0]);
    $('#mms-mail_ogg').val($('#mmsr-mail_ogg').val());
    tinymce.get("tmce-MMS").setContent('<br /><br /><div style="color: #b5b5b5;">##- Digita la risposta sopra questa riga -##</div><br /><br />' + tinymce.get("tmce-MMSR").getContent());
    $('#mmsr-mailmodal').modal('toggle');
});


$(document).on('click', '.mms-mess_rapidi', function () {
    ($('#MessaggiRapidi_mail').is(":hidden") == true ? $('#MessaggiRapidi_mail').attr('hidden', false) : $('#MessaggiRapidi_mail').attr('hidden', true))
});

$("#mms-risp_rap1").change(function () {
    const expr = $(this).val();
    var val = 'Gentile cliente,<br /><br />';
    switch (expr) {
        case 'rf':
            $('#mms-mail_ogg').val('Rilascio feedback')
            val = val + 'Spero vivamente che lei ha apprezzato l\'esperienza di acquisto.' +
                '<br><br>Con la presente le chiedo di inviarci un commento di feedback per valutare la sua esperienza con scifostore, Le saremo grati qualora volesse condividere con noi la Sua esperienza, fornendoci le sue impressioni ed opinioni.' +
                '<br><br>Recensione <a href="https://www.facebook.com/scifostore/reviews/?ref=page_internal">Facebook</a>' +
                '<br>Recensione <a href="https://g.page/r/CS5xQz8WGU2AEAg/review">Google</a>' +
                '<br>Recensione <a href="https://it.trustpilot.com/evaluate/www.scifostore.com">TrustPilot</a>' +
                '<br><br>Il suo aiuto ci fornirà ulteriori idee per migliorare il nostro servizio per i clienti che faranno ordini con noi in futuro.'
            break;
        case 'rfwa':
            $('#mms-mail_ogg').val('Rilascio feedback WhatsApp')
            val = val + 'Spero vivamente che lei ha apprezzato l\'esperienza di acquisto.' +
                '<br><br>Con la presente le chiedo di inviarci un commento di feedback per valutare la sua esperienza con scifostore, Le saremo grati qualora volesse condividere con noi la Sua esperienza, fornendoci le sue impressioni ed opinioni.' +
                '<br><br>Recensione Facebook -> https://www.facebook.com/scifostore/reviews/?ref=page_internal' +
                '<br>Recensione Google -> https://g.page/r/CS5xQz8WGU2AEAg/review' +
                '<br>Recensione TrustPilot -> https://it.trustpilot.com/evaluate/www.scifostore.com' +
                '<br><br>Il suo aiuto ci fornirà ulteriori idee per migliorare il nostro servizio per i clienti che faranno ordini con noi in futuro.'
            break;
        case 'bfpi':
            $('#mms-mail_ogg').val('Bonifico bancario Poste Italiane')
            val = val + '<br><br>Con la presente le chiedo di il pagamento per l ordine appena eseguito con un nostro operatore' +
                '<br><br><b>IBAN:</b> <i>IT92I0760116700001041338680</i>' +
                '<br><b>INTESTATO A:</b> <i>scifostore di Scifo Gaetano</i>' +
                '<br><b>CAUSALE:</b> <i>Pagamento Ordine scifostore</i>' +
                '<br><b>BIC/ SWIFT:</b> <i>BPPIITRRXXX</i>' +
                '<br><b>IMPORTO:</b> <i>€ </i>' +
                '<br>Si prega di inviare copia del bonifico, anche come foto su WhatsApp 3926563467.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
            break;
        case 'bfuc':
            $('#mms-mail_ogg').val('Bonifico bancario UniCredit')
            val = val + 'Con la presente le chiedo di il pagamento per l ordine appena eseguito con un nostro operatore' +
                '<br><br><b>IBAN:</b> <i>IT32N0200883440000104759822</i>' +
                '<br><b>INTESTATO A:</b> <i>scifostore di Scifo Gaetano</i>' +
                '<br><b>CAUSALE:</b> <i>Pagamento Ordine scifostore</i>' +
                '<br><b>BIC/ SWIFT:</b> <i>UNCRITM1K09</i>' +
                '<br><b>IMPORTO:</b> <i>€ </i>' +
                '<br><br>Si prega di inviare copia del bonifico, anche come foto su WhatsApp 3926563467.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
            break;
        case 'bfbn':
            $('#mms-mail_ogg').val('Bonifico bancario BNL')
            val = val + 'Con la presente le chiedo di il pagamento per l ordine appena eseguito con un nostro operatore' +
                '<br><br><b>IBAN:</b> <i>IT89Q0100516700000000009002</i>' +
                '<br><b>INTESTATO A:</b> <i>scifostore di Scifo Gaetano</i>' +
                '<br><b>CAUSALE:</b> <i>Pagamento Ordine scifostore</i>' +
                '<br><b>BIC/ SWIFT:</b> <i>BNLIITRR</i>' +
                '<br><b>IMPORTO:</b> <i>€ </i>' +
                '<br><br>Si prega di inviare copia del bonifico, anche come foto su WhatsApp 3926563467.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
            break;
        case 'bfcr':
            $('#mms-mail_ogg').val('Bonifico bancario Credem')
            val = val + 'Con la presente le chiedo di il pagamento per l ordine appena eseguito con un nostro operatore' +
                '<br><br><b>IBAN:</b> <i>IT74P0303216700010000453694</i>' +
                '<br><b>INTESTATO A:</b> <i>scifostore di Scifo Gaetano</i>' +
                '<br><b>CAUSALE:</b> <i>Pagamento Ordine scifostore</i>' +
                '<br><b>BIC/ SWIFT:</b> <i>BACRIT21445</i>' +
                '<br><b>IMPORTO:</b> <i>€ </i>' +
                '<br><br>Si prega di inviare copia del bonifico, anche come foto su WhatsApp 3926563467.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
            break;
        case 'bfrp':
            $('#mms-mail_ogg').val('Bonifico bancario Ricarica Postepay')
            val = val + 'Con la presente le chiedo di il pagamento per l ordine appena eseguito con un nostro operatore' +
                '<br><br><b>ITitolare:</b> <i>Gaetano Scifo</i>' +
                '<br><b>INumero della carta:</b> <i>5333171042239711</i>' +
                '<br><b>ICodice Fiscale:</b> <i>SCFGTN61R29H792P</i>' +
                '<br><b>IIMPORTO:</b> <i>€ </i>' +
                '<br><br>Si prega di inviare copia della ricarica, anche come foto su WhatsApp 3926563467.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci'
            break;
        case 'bfrp':
            $('#mms-mail_ogg').val('Codice Univoco')
            val = val + 'Le ricordiamo che il codice fiscale o la PIVA non è più necessaria per la fatturazione, ma con le nuove normative, abbiamo la necessità di avere il suo codice univoco.'
            break;
        case 'rb':
            $('#mms-mail_ogg').val('Richiesta di rimborso')
            val = val + 'Con la presente le chiedo di complilare il seguente modulo per aprire una richiesta di rimborso.' +
                '<br><br>Può compilare il modulo online attraverso il seguente <a href="https://www.scifostore.com/articoli-giardinaggio/28-modulo-rimborso">link</a>' +
                '<br><br>Dopo aver compilato il modulo la preghiamo di attendere la chiamata da parte di un nostro operatore.' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci.'
            break;
        case 'librimb':
            $('#mms-mail_ogg').val('Richiesta di rimborso')
            val = val + 'a seguito alla sua richiesta di rimborso, al fine di poterla perfezionale con il pagamento, la inviatiamo a inviarci liberatoria contenente l\'iban bancario intestato alla persona che ha effettuato l\'ordine correlata da documento d\'identità in corso di validità.' +
                '<br><br>Dopo aver inviato tutte le informazioni la preghiamo di attendere il contatto da parte di un nostro operatore' +
                '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci' +
                '<br><br>Le auguro una buona giornata,'
            break;
        case 'vr':
            $('#mms-mail_ogg').val('Ricontatto')
            val = val + '<br><br>Grazie di aver contattato l\'Assistenza clienti di scifostore.' +
                '<br><br>Siamo consapevoli del fatto che necessiti di una rapida risposta e, in genere, rispondiamo entro 24, 36 ore. Tuttavia, se il problema che ci hai posto, e il numero di richieste ricevute sarà elevato, potresti dover attendere fino a 72 ore per ricevere una risposta.' +
                '<br><br>Inviare più di un messaggio riguardante lo stesso argomento potrebbe rallentare ulteriormente il processo di assistenza. In ogni caso, faremo tutto il possibile per risponderti quanto prima.' +
                '<br><br>Ti ringraziamo per la pazienza e la collaborazione dimostrataci.'
            break;
        case 'sped':
            $('#mms-mail_ogg').val('Richiesta di spedizione')
            val = val + 'Per avere maggiori informazioni sulla sua spedizione, le consiglio di chiamare il nostro numero 0934588985 e premere il tasto 1-1, oppure manda una mail a spedizioni@scifostore.com inserendo il tuo numero dell\'ordine. Ci daremo un\'occhiata.'
            break;
        case 'ddsped':
            $('#mms-mail_ogg').val('Dati di spedizione')
            val = val + 'La contatto dal Supporto Clienti scifostore e sono lieto fornirle la mia assistenza.' +
                '<br /><br />In merito alla sua richiesta per informazioni sulla suo codice tracking le scrivo il codice tracking e il corriere.' +
                '<br /><br /><b>Tracking:</b> ' + $('#mdilkrwh').val() +
                '<br /><b>Corriere:</b> ' + $('#zhhxsbwp').val() +
                '<br /><b>Link:</b> ' + $('#rlzk3mqu').val() +
                '<br /><br />Le ricordiamo che al ricevimento della merce dovrà firmare la consegna con riserva, così in caso di danneggiamento della merce provvederemo a spedire nuovamente il bene.' +
                '<br /><br />Per qualsiasi dubbio o chiarimento, non esiti a contattarci, nel caso avesse necessità di ulteriore supporto.'
            break;
        default:
            console.log(`Spiacente, siamo fuori di ${expr}.`);
    }
    tinymce.get("tmce-MMS").setContent(val + '<br><br>Cordiali saluti')
});
function MMS_ClearModal() {
    $('#mms-mail_mitt').val('');
    $('#mms-mail_dest').val('');
    $('#mms-mail_ogg').val('');
    $('#mms-mail_mitt').prop('readonly', false)
    $('#mms-mail_dest').prop('readonly', false)
    $('#Impostazioni_mail').prop('hidden', true)
    $('#MessaggiRapidi_mail').prop('hidden', true)
    $('#mail_cliente').prop('checked', false);
    tinymce.get("tmce-MMS").setContent('');
    $("#mms-attachmentButton").val('');
}

$('#mms-mailmodal').on('hidden.bs.modal', function () {
    MMS_ClearModal();
})