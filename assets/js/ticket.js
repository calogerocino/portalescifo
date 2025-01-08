var rimb_open = true;

function aggiornaticket2() {
  bloccaui();
  var clausola1 = $('#searchcode').val();
  var cl_spec = '';

  if (rimb_open == false) {
    cl_spec = 'AND Tipologia=\'Rimborso totale o parziale\'';
    $('#col_tk').append('<th id="ir_tk">Info rimborso</th>');
  } else if (rimb_open == true) {
    cl_spec = '';
    $('#ir_tk').remove();
  }

  if (clausola1.length >= 1) {
    clausola1 = " AND (t.Nticket LIKE '%" + clausola1 + "%' OR c.Cliente LIKE '%" + clausola1 + "%' OR o.NOrdine LIKE '%" + clausola1 + "%')" + cl_spec;
  } else {
    clausola1 = "" + cl_spec;
  }

  $.ajax({
    url: currentURL + "assets/inc/ticket.php",
    method: "POST",
    data: {
      azione: 'aggiornalista',
      idordine: '',
      clausola1: clausola1,
      clspec: cl_spec
    },
    success: function (data) {
      $("#tabellaticket").html(data);
      docReady(listInit);
      sbloccaui();
    },
    error: function () {
      Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      sbloccaui();
    }
  });
}

function switc_rimb() {
  if (rimb_open == false) {
    rimb_open = true;
  } else if (rimb_open == true) {
    rimb_open = false;
  }
  aggiornaticket2()
}

//TICKET SPOSTATI

function nuovoticketpage(idordine) {
  $(".auto2").autocomplete({
    source: ["Prodotto diverso", "Prodotto danneggiato", "Prodotto non funzionante", "Garanzia Cemento", "Prodotti mancanti", "Rimborso ordine", "Spedizione non evasa"]
  });

  if (idordine != '') {
    $.ajax({
      url: currentURL + "crm/check-ordine.php",
      method: "POST",
      data: {
        azione: 'nordine',
        idordine: idordine
      },
      success: function (response) {
        var res = response.split(';');
        $('input#NOrdine').val(res[0]);
        $('input#email').val(res[1]);
        // $('a#telefono').val(res[2]);
        // chiamatamail(res[1]);
      },
      error: function () { }
    });

    $.ajax({
      url: currentURL + "assets/inc/ticket.php",
      method: "POST",
      data: {
        azione: 'datitk',
        idordine: idordine
      },
      success: function (response) {
        var res = response.split('|-|');
        $('input#oggetto').val(res[1]);
        if (res[2] != '') {
          $('#assegnatario').val(res[2]);
        } else {
          $('#assegnatario').val('Non Assegnato');
        }
        $('textarea#notint').html(res[4]);
        $('input#dataap').val(res[5]);
        if (res[5] != '' || res[5] != null) {
          $("div#dataultimocontatto").prop("hidden", false);
          $('input#datauc').val(res[5]);
        }
        if (res[1] == undefined) {
          $("input#NOrdine").prop("readonly", true);
          $("input#oggetto").prop("readonly", false);
          // $('div#formupload').show();
          $("div#dataapertura").prop("hidden", true);
          $("div#dataultimocontatto").prop("hidden", true);

          $('#riapri').remove();
          $('#gestione').remove();
          $('#messaggio').remove();
          $('#risolto').remove();
          $('#disablednotainterna').attr('hidden', true);
        } else if (res[0] == 'Risolto') {

          $('#nuovo').remove();
          $('#gestione').remove();
          $('#messaggio').remove();
          $('#risolto').remove();
          $('#disablednotainterna').attr('hidden', false);
        } else {
          $("input#NOrdine").prop("readonly", true);
          $("input#oggetto").prop("readonly", true);
          // $('div#formupload').show();
          $("div#dataapertura").prop("hidden", false);

          $('#riapri').remove();
          $('#nuovo').remove();
          $('#disablednotainterna').attr('hidden', false);
        }
      },
      error: function () {
        alert('errore')
      }
    });


  } else {
    $('#riapri').remove();
    $('#gestione').remove();
    $('#messaggio').remove();
    $('#risolto').remove();
    $('#disablednotainterna').attr('hidden', true);
  }
}


function idordinetk() {
  var x = $('input#NOrdine').val();
  $.ajax({
    url: currentURL + "crm/check-ordine.php",
    method: "POST", //First change type to method here
    data: {
      azione: 'idordine',
      nordine: x // Second add quotes on the value.
    },
    success: function (data) {
      var res = data.split(';');
      $('input#email').val(res[0]);
      // $('a#telefono').val(res[2]);
    },
    error: function () {
      showNotification('top', 'right', 'Errore!', 'danger', 'close');
    }
  });
}

// $(document).on('click', '.tk_chiama', function () {
//   var numero = $('a#telefono').val();
//   if (numero != '') {    
//     $.ajax({
//       type: 'POST',
//       crossDomain: true,
//       dataType: 'jsonp',
//       url: "https://<?php echo $session_ipt; ?>/servlet?key=number=0" + numero,
//       data: '{"username": "admin", "password" : "2018W01300"}'
//     })
//     Toast.fire({
//       icon: 'info',
//       title: 'Chiamata avviata!'
//     })

//   } else {
//     alert('Non c\'è nessun numero di telefono caricato');
//   }
// });


function rispostaauto() {
  var x = $('select#autorisp').val();
  var y = $('input#NOrdine').val();
  var z = $('input#oggetto').val();
  var idordine = $('input#idordine').val();

  var risposta = '<div style="color: #b5b5b5;">##- Digita la risposta sopra questa riga -##</div>'; //fare lo split qua per dividere ....
  risposta = risposta + '<p dir="ltr" style="color: #2b2e2f; font-family: \'Lucida Sans Unicode\', \Lucida Grande\', \'Tahoma\', Verdana, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0">'

  if (x == 1) {
    risposta = risposta + 'Buongiorno,'
    risposta = risposta + '<br><br>Sono <?php echo $session_uname; ?> e mi occuperò della sua richiesta.'
    risposta = risposta + '<br><br>Provvederò ad analizzare la sua richiesta e darle una risposta entro 48h.'
    risposta = risposta + '<br><br>Non esiti a contattarmi nuovamente se ci sono problemi'
    risposta = risposta + '<br><br>Cordiali saluti'
  } else if (x == 2) {
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>Con la presente le chiedo di inviare delle prove tramite delle foto o delle immagini per poter procedere con la sua pratica in sospeso.'
    risposta = risposta + '<br><br>Al ricevimento delle prove la sua pratica verrà elaborata, e entro 48h-72h verrà contattato da un nostro operatore.'
    risposta = risposta + '<br><br>Può inviare le foto tramite WhatsApp 3926563467 inserendo il n° ordine: <b>' + y + '</b>'
    risposta = risposta + '<br><br>Si prega di rispondere a questa mail una volta inviate le foto.'
    risposta = risposta + '<br><br>Per qualsiasi altra necessità o informazione non esiti a contattarci.'
    risposta = risposta + '<br><br>Cordiali saluti'
  } else if (x == 3) {
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>Il nostro operatore <?php echo $session_uname; ?> ha cercato di contattarvi ma non ha ricevuto risposta'
    risposta = risposta + '<br><br>Rimaniamo a disposizione per qualsiasi ulteriore assistenza.'
    risposta = risposta + '<br><br>Grazie.'
    risposta = risposta + '<br><br>Cordiali saluti'
  } else if (x == 4) {
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>la presente per riferirle che la Sua segnalazione è stata risolta. '
    risposta = risposta + '<br><br>Riepilogo della segnalazione: ' + z + '.'
    risposta = risposta + '<br>Soluzione: '
    risposta = risposta + '<br><br>Mi permetto di chiudere il ticket, per qualsiasi altra informazione non esisti a contattarci.'
    risposta = risposta + '<br><br>Distinti saluti,'
  } else if (x == 5) {
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>la presente per riferirle che nostro operatore ha aperto una segnalazione sulla spedizione con riferimento #' + idordine
    risposta = risposta + '<br><br>Riepilogo della segnalazione: ' + z + '.'
    risposta = risposta + '<br>Problema: '
    risposta = risposta + '<br><br>Al fine di risolvere la questione, vi chiediamo cortesente di attendere la chiamata di un nostro operatore entro 48/72h.'
    risposta = risposta + '<br><br>Distinti saluti,'
  } else if (x == 6) { // CHIEDI INFORMAZIONI
    risposta = risposta + 'Buongiorno!'
    risposta = risposta + '<br><br>la contatto a fronte della segnalazione aperta con un nostro collega'
    risposta = risposta + '<br><br>Referenza dell\'ordine: <b>' + y + '</b>'
    risposta = risposta + '<br><br>Problema: <i>' + z + '</i>'
    risposta = risposta + '<br><br><i>inserire richiesta</i>'
    risposta = risposta + '<br><br>La ringrazio anticipatamente!'
    risposta = risposta + '<br><br>Le auguro una buona giornata,'
  } else if (x == 7) { // MODULO RIMBORSO
    risposta = risposta + 'Gentile Cliente,'
    risposta = risposta + '<br><br>Con la presente le chiedo di complilare il seguente modulo per aprire una richiesta di rimborso.'
    risposta = risposta + '<br><br>Può compilare il modulo online attraverso il seguente link -> <a href="https://www.scifostore.com/articoli-giardinaggio/28-modulo-rimborso">premi qui</a>'
    risposta = risposta + '<br><br>Dopo aver compilato il modulo la preghiamo di attendere la chiamata da parte di un nostro operatore'
    risposta = risposta + '<br><br>Il riferimento associato al suo ordine è: ' + y
    risposta = risposta + 'Per qualsiasi altra necessità o informazione non esiti a contattarci'
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
  tinymce.get('risppub').setContent(risposta);
}

function rispostaauto2() {
  var x = $('select#autorisp2').val();
  var y = $('input#NOrdine').val();
  var z = $('input#oggetto').val();
  var idordine = $('input#idordine').val();

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
  risposta = risposta + '<p dir="ltr" style="color: #2b2e2f; font-family: \'Lucida Sans Unicode\', \Lucida Grande\', \'Tahoma\', Verdana, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0">'
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

  tinymce.get('risppub').setContent(risposta);
}

function aggiornanotaint() {
  var idordine = $('input#idordine').val();
  var notaint = $('textarea#notint').val();

  $.ajax({
    url: currentURL + "assets/inc/ticket.php",
    method: "POST", //First change type to method here
    data: {
      azione: 'aggiornanota',
      idordine: idordine,
      notaint: notaint // Second add quotes on the value.
    },
    success: function (response) {
      showNotification('top', 'right', response, 'success', 'done');
    },
    error: function () {
      showNotification('top', 'right', "Errore nella lettura delle mail, riprovare!", 'danger', 'error');
    }

  });

}


$(document).on('click', '.tk_bttticket', function () {
  var tipo = $(this).attr("tipo");

  if (tipo == 1) {
    if ($('#oggetto').val() != '') {
      inviamailtk();
      cambiaticket('Nuovo', 'SI');

      $('#nuovo').remove();
      $('#disablednotainterna').attr('hidden', false);

      $('#iddropdowbutt').html('<button name="gestione" id="gestione" class="dropdown-item bttticket" tipo="2" href="javascript:void(0)">Invia come <b>Gestione</b></button><button name="messaggio" id="messaggio" class="dropdown-item bttticket" tipo="3" href="javascript:void(0)">Invia solo <b>Messaggio</b></button><button name="risolto" id="risolto" class="dropdown-item bttticket" tipo="4" href="javascript:void(0)">Invia come <b>Risolto</b></button>');
    } else {
      showNotification('top', 'right', 'Prima di procedere devi inserire l\'oggetto del ticket', 'danger', 'error');
    }
  } else if (tipo == 2) {
    inviamailtk();
    cambiaticket('Gestione', 'SI');
  } else if (tipo == 4) {
    inviamailtk();
    cambiaticket('Risolto', 'NO');
  } else if (tipo == 3) {
    inviamailtk();
    aggiornadata();
  } else if (tipo == 5) {
    $('#riapri').remove();
    $('#iddropdowbutt').html('<button name="gestione" id="gestione" class="dropdown-item bttticket" tipo="2" href="javascript:void(0)">Invia come <b>Gestione</b></button><button name="messaggio" id="messaggio" class="dropdown-item bttticket" tipo="3" href="javascript:void(0)">Invia solo <b>Messaggio</b></button><button name="risolto" id="risolto" class="dropdown-item bttticket" tipo="4" href="javascript:void(0)">Invia come <b>Risolto</b></button>');
    inviamailtk();
    cambiaticket('Attesa', 'SI');
  }
});

function aggiornadata() {
  var idordine = $('input#idordine').val();

  $.ajax({
    url: currentURL + "assets/inc/ticket.php",
    method: "POST",
    data: {
      azione: 'aggiornadata',
      idordine: idordine
    },
    success: function () { },
    error: function () { }
  });
}

function cambiaticket(stato, aperto) {
  var idordine = $('input#idordine').val();
  var tipologia = $('input#oggetto').val();
  var notaint = $('textarea#notint').val();

  $.ajax({
    url: currentURL + "assets/inc/ticket.php",
    method: "POST", //First change type to method here
    data: {
      azione: 'impostacome',
      idordine: idordine,
      stato: stato,
      aperto: aperto,
      tipologia: tipologia,
      //notaint: notaint // Second add quotes on the value.
    },
    success: function (response) {
      showNotification('top', 'right', response, 'success', 'done');
    },
    error: function () {
      showNotification('top', 'right', "Errore nella richiesta, riprovare!", 'danger', 'error');
    }
  });
}

function inviamailtk() {
  var idord = $('input#idordine').val();
  var indirizzodest = $('input#email').val();
  var oggetto = $('input#oggetto').val();
  var corpo = tinymce.get("risppub").getContent();
  var iduser = '<?php echo $session_idu; ?>';
  if (corpo != '') {

    $.ajax({
      url: currentURL + "assets/mail/invio_mail.php",
      method: "POST", //First change type to method here
      data: {
        idord: idord,
        iduser: iduser,
        mittente: 'support',
        indirizzodest: indirizzodest,
        oggetto: 'Segnalazione su ordine',
        corpo: corpo
      },
      success: function (response) {
        showNotification('top', 'right', response, 'success', 'done');
      },
      error: function () {
        Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      }
    });
  }
}