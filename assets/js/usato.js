function aggiornaus() {
  bloccaui();
  var clausola = $('#sceltastato option:selected').text();
  $.ajax({
      url: currentURL + "assets/inc/usato.php",
      method: "POST", //First change type to method here
      data: {
          azione: 'aggiorna',
          clausola: clausola
      },
      success: function(data) {
          $("div.tabellausato").html(data);
          sbloccaui();
      },
      error: function() {
          Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta'})
          sbloccaui();
      }
  });
}

function cambiavalore() {
  if ($('')) {
      var permuta = parseFloat($('#permuta').val());
      var riparazione = Number($('#riparazione').val());
      $('#vendita').val('€ ' + (permuta + riparazione));
  }
}

$('#us_seriale').on('change', function() { //TUTTO DA FARE, QUANDO METTO IL SERIALE SI CARICA IN AUTOMATICO LA MACCHINA SE E' REGISTRATA
  $.post(currentURL + "assets/inc/regmacch.php", {
      azione: 'caricamacchina',
      seriale: $('#us_seriale').val()
  }, function(response) {
      if (response == 'si') {
        Toast.fire({ icon: 'success', title: 'Puoi procedere con la registrazione' })
          $('#bttsalvamacch').prop('hidden', false);
      } else if (response == 'no') {
        Toast.fire({ icon: 'error', title: 'Macchina già registrata' })
      }
  });
});

$(document).on('click', '.us_salvaus', function() {
  bloccaui();
  $.post(currentURL + "assets/inc/usato.php", {
      azione: 'nuovous',
      marchio: $('#marchio').val(),
      prodotto: $('#prodotto').val(),
      modello: $('#modello').val(),
      seriale: $('#us_seriale').val(),
      note: $('#note').val(),
      valore: $('#vendita').val().replace(/[^0-9]?/g, ""),
      permuta: $('#permuta').val(),
      riparazione: $('#riparazione').val(),
      idr: idr
  }, function(response) {
      if (response == 'si') {
          sbloccaui();
          Toast.fire({ icon: 'success', title: 'Caricato con successo' })
          $('.us_indietroscheda').click()
      } else {
          sbloccaui();
          Toast.fire({ icon: 'error', title: 'Errore: ' + response })
      }
  })
});

$(document).on('click', '.us_modificaus', function() {
  bloccaui();
  $.post(currentURL + "assets/inc/usato.php", {
      azione: 'modificaus',
      idus: $('#idusato').val(),
      marchio: $('#marchio').val(),
      prodotto: $('#prodotto').val(),
      modello: $('#modello').val(),
      seriale: $('#us_seriale').val(),
      note: $('#note').val(),
      valore: $('#vendita').val().replace(/[^0-9]?/g, ""),
      permuta: $('#permuta').val(),
      riparazione: $('#riparazione').val()
  }, function(response) {
      if (response == 'si') {
          sbloccaui();
          showNotification('top', 'right', 'Aggiornato con successo', 'success', 'done');
          $('.us_indietroscheda').click()
      } else {
          sbloccaui();
          Toast.fire({ icon: 'error', title: 'Errore: ' + response })
      }
  })
});

$(document).on('click', '.us_indietroscheda', function() {
  idr = '';
  $('#idusato').val('');
  $('#marchio').val('');
  $('#prodotto').val('');
  $('#modello').val('');
  $('#us_seriale').val('');
  $('#note').val('');
  $('#vendita').val('');
  $('#permuta').val('');
  $('#riparazione').val('');

  $('#salvaschedaid').prop('hidden', false);
  $('#modificascheda').prop('hidden', false);
  aggiornaus();
  $('#listausato').prop('hidden', false);
  $('#aggiungiusato').prop('hidden', true);
});

$(document).on('click', '.us_nuovous', function() {
  idr = '';
  $('#idusato').val('');
  $('#marchio').val('');
  $('#prodotto').val('');
  $('#modello').val('');
  $('#us_seriale').val('');
  $('#note').val('');
  $('#vendita').val('');
  $('#permuta').val('');
  $('#riparazione').val('');

  $('#listausato').prop('hidden', true);
  $('#aggiungiusato').prop('hidden', false);

  $('#salvaschedaid').prop('hidden', false);
  $('#modificascheda').prop('hidden', true);
});

function bloccadettmacch() {
  $('#prodotto').prop('readonly', true);
  $('#modello').prop('readonly', true);
  $('#marchio').prop('readonly', true);
  $('#us_seriale').prop('readonly', true);
}

function sbloccadettmacch() {
  $('#prodotto').prop('readonly', false);
  $('#modello').prop('readonly', false);
  $('#marchio').prop('readonly', false);
  $('#us_seriale').prop('readonly', false);
}

$(document).on('click', '.aprius', function() {
  var idus = $(this).attr('idus');
  $.post(currentURL + "assets/inc/usato.php", {
      azione: 'cercausato',
      idus: idus
  }, function(data) {
      $('#listausato').prop('hidden', true);
      $('#aggiungiusato').prop('hidden', false);
      $('#salvaschedaid').prop('hidden', true);
      $('#modificascheda').prop('hidden', false);
      var res = data.split('|-|')
      $('#idusato').val(res[0]);
      $('#marchio').val(res[1]);
      $('#prodotto').val(res[2]);
      $('#modello').val(res[3]);
      $('#us_seriale').val(res[4]);
      $('#note').val(res[5]);
      $('#vendita').val(res[6]);
      $('#permuta').val(res[7]);
      $('#riparazione').val(res[8]);
  })
});

$(document).on('click', '.cambiastato', function() {
  $.post(currentURL + "assets/inc/usato.php", {
      azione: 'cambiastato',
      idusato: $(this).attr('idus'),
      stato: $(this).attr('stato')
  }, function(response) {
      if (response == 'si') {
          showNotification('top', 'right', 'Aggiornato con successo', 'success', 'done');
          aggiornaus();
      } else {
          Toast.fire({ icon: 'error', title: 'Errore: ' + response })
      }
  })
});


function showNotification(from, align, testo, tipo, icona) {
  $.notify({
      icon: icona,
      message: testo

  }, {
      type: tipo,
      timer: 2000,
      placement: {
          from: from,
          align: align
      }
  });
}