function aggiornaordini() {
  listaordini();
  var table = $('#dataTable').DataTable({
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      $('tr', nRow).addClass('btn-reveal-trigger');
      $('td', nRow).addClass('align-middle');
      $("td:nth-child(5)", nRow).addClass("py-2 align-middle text-center fs-0 white-space-nowrap");
      $("td:nth-child(6)", nRow).addClass("py-2 align-middle white-space-nowrap text-end");
      // $('td', nRow).addClass('py-2 align-middle text-center fs-0 white-space-nowrap');   SOLO PER LO STATO 


      if (aData[9].includes('Importato')) {
        $('td', nRow).css('background-color', 'var(--falcon-yellow)');
      } else if (aData[3] == "0" || aData[3] == "") {
        $('td', nRow).css('background-color', 'var(--falcon-yellow)');
      } else if (!aData[5].includes('-')) {
        $('td', nRow).css('background-color', 'var(--falcon-yellow)');
      }



      $("td:nth-child(1)", nRow).on('click', function () {
        CaricaFrame('ordini/fastord.php?idordine=' + aData[0], '<i class="fa-regular fa-bags-shopping"></i> Visualizza ordine', 'Visualizza una pagina rapida delle informazioni in merito a un ordine', '85%')
      });
      $("td:nth-child(1)", nRow).css('cursor', 'pointer');

      $("td:nth-child(3)", nRow).on('click', function () {
        CaricaFrame('ordini/fastord.php?idordine=' + aData[0], '<i class="fa-regular fa-bags-shopping"></i> Visualizza ordine', 'Visualizza una pagina rapida delle informazioni in merito a un ordine', '85%')
      });
      $("td:nth-child(3)", nRow).css('cursor', 'pointer');

      $("td:nth-child(4)", nRow).on('click', function () {
        CaricaFrame('ordini/fastord.php?idordine=' + aData[0], '<i class="fa-regular fa-bags-shopping"></i> Visualizza ordine', 'Visualizza una pagina rapida delle informazioni in merito a un ordine', '85%')
      });
      $("td:nth-child(4)", nRow).css('cursor', 'pointer');
    },
    "ajax": {
      url: currentURL + "ordini/post_list.php",
      type: "POST",
      error: function () {
        $("#post_list_processing").css("display", "none");
      }
    },
    "order": [
      [0, "desc"]
    ],
    "columnDefs": [
      {
        "targets": [0],
        "visible": false
      }, {
        "targets": [2],
        "visible": false
      }, {
        "targets": [6],
        "visible": false
      }, {
        "targets": [7],
        "visible": false
      }, {
        "targets": [8],
        "visible": false
      }, {
        "targets": [10],
        "visible": false
      }, {
        "targets": [11],
        "visible": false
      }, {
        "targets": [12],
        "visible": false
      },
      {
        "orderable": false,
        "targets": [1]
      }, {
        "orderable": false,
        "targets": [3]
      }, {
        "orderable": false,
        "targets": [4]
      }, {
        "orderable": false,
        "targets": [5]
      }, {
        "orderable": false,
        "targets": [9]
      }, {
        "orderable": false,
        "targets": [13]
      },
      {
        "render": function (data, type, row) {
          var corriere = row[6];
          var url;
          if (corriere == 'TNT') {
            url = "https://www.tnt.it/tracking/getTrack?WT=1&ConsigNos=" + row[3];
          } else if (corriere == 'BRT' || corriere == 'BRT 1' || corriere == 'BRT 2') {
            url = "https://vas.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&ChiSono=" + row[3];
          } else if (corriere == 'GLS') {
            url = "https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione=" + row[3] + "&tipo_codice=nazionale";
          } else if (corriere == 'DHL') {
            url = "https://mydhl.express.dhl/it/it/tracking.html#/results?id=" + row[3];
          } else if (corriere == 'Poste Italiane') {
            url = "https://www.poste.it/cerca/index.html#/risultati-spedizioni/" + row[3];
          } else if (corriere == 'SDA') {
            url = "https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it&tracing.letteraVettura=" + row[3];
          } else if (corriere == 'SAVISE') {
            url = "https://www.oneexpress.it/it/cerca-spedizione/";
          }
          return "<a href=\"javascript:;\" onClick=\"window.open('" + url + "', 'Tracciatura ordine', 'width=900, height=600, resizable, status, scrollbars=1, location');\"> " + data + "</a><br> <strong><span cp=\"" + row[3] + "\" id=\"PoP-" + row[0] + "\" style=\"cursor:pointer;\">" + row[6] + "</span></strong>";
        },
        "targets": 3
      },
      {
        "render": function (data, type, row) {
          var p1, p2, p3, p4, p5;
          if (row[9] != 'Evaso' && row[9] != 'Rientrato') {
            p1 = '<li><a class="dropdown-item sendinv" id="' + row[0] + '" idps="' + row[11] + '" idm="' + row[10] + '" track="' + row[3] + '" piatt="' + row[4] + '" corr="' + row[6] + '" href="javascript:void(0)" title="Evadi"><i class="fa-regular fa-truck"></i> Evadi</a></li>';
          } else { p1 = ''; }

          if (row[9] != 'Rimborsato') {
            p2 = '<li><a class="dropdown-item rimborsp" id="' + row[0] + '" idps="' + row[11] + '" href="javascript:void(0)" title="Rimborsa"><i class="fa-regular fa-wallet"></i> Rimborsa</a></li>';
          } else { p2 = ''; }

          if (row[9] != 'Da Rimborsare') {
            p3 = '<li><a class="dropdown-item darimb" id="' + row[0] + '" idps="' + row[11] + '" href="javascript:void(0)" title="Da rimborsare"><i class="fa-regular fa-hand-holding-usd"></i> Annulla</a></li>';
          } else { p3 = ''; }

          if (row[9] != 'Rientrato' && row[9] == 'Evaso') {
            p4 = '<li><a class="dropdown-item rientro" id="' + row[0] + '" href="javascript:void(0)" title="Rientrato"><i class="fa-regular fa-undo-alt"></i> Rientrato</a></li>';
          } else { p4 = ''; }

          if (row[4] == 'ManoMano' && (row[9] == 'Da Gestire' || row[9] == 'In Stock')) {
            p5 = '<li><a class="dropdown-item inviamm" piatt="' + row[4] + '" track="' + row[3] + '" idm="' + row[10] + '" corr="' + row[6] + '" href="javascript:void(0)" title="ManoMano"><i class="fa-regular fa-mail-bulk"></i> ManoMano</a></li>';
          } else { p5 = ''; }

          return '<div class="dropdown font-sans-serif position-static"><button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" id="order-dropdown-0" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg></button><div class="dropdown-menu dropdown-menu-end border py-0" aria-labelledby="order-dropdown-0"><div class="bg-white py-2">' + p1 + p2 + p3 + p4 + p5 + '<div class="dropdown-divider"></div><li><a class="dropdown-item on_stampa" id="' + row[0] + '" href="javascript:void(0)" title="Stampa"><i class="fa-regular fa-print"></i> Stampa</a></li><a class="dropdown-item text-success" href="javascript:void(0);" onclick="cambiopagina(\'ordini\', \'ordine\',\'?idordine=' + row[0] + '\')">Modifica</a></div></div></div>';


        },
        "targets": 13
      },
      {
        "render": function (data, type, row) {
          var Stato = row[9];
          var colore;
          if (Stato == 'Evaso') {
            svg = '<svg class="svg-inline--fa fa-check fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" transform="translate(-256 -256)"></path></g></g></svg>'
            colore = "success";
          } else if (Stato == 'Importato') {
            svg = '<i class="fa-regular fa-download"></i>'
            colore = "light";
          } else if (Stato == 'Da Gestire') {
            svg = '<svg class="svg-inline--fa fa-redo fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="redo" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M500.33 0h-47.41a12 12 0 0 0-12 12.57l4 82.76A247.42 247.42 0 0 0 256 8C119.34 8 7.9 119.53 8 256.19 8.1 393.07 119.1 504 256 504a247.1 247.1 0 0 0 166.18-63.91 12 12 0 0 0 .48-17.43l-34-34a12 12 0 0 0-16.38-.55A176 176 0 1 1 402.1 157.8l-101.53-4.87a12 12 0 0 0-12.57 12v47.41a12 12 0 0 0 12 12h200.33a12 12 0 0 0 12-12V12a12 12 0 0 0-12-12z" transform="translate(-256 -256)"></path></g></g></svg>'
            colore = "primary";
          } else if (Stato == 'Sospeso' || Stato == 'Rientrato') {
            svg = '<i class="fa-regular fa-hand"></i>'
            colore = "warning";
          } else if (Stato == 'In Stock') {
            svg = '<svg class="svg-inline--fa fa-stream fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="stream" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M16 128h416c8.84 0 16-7.16 16-16V48c0-8.84-7.16-16-16-16H16C7.16 32 0 39.16 0 48v64c0 8.84 7.16 16 16 16zm480 80H80c-8.84 0-16 7.16-16 16v64c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-64c0-8.84-7.16-16-16-16zm-64 176H16c-8.84 0-16 7.16-16 16v64c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-64c0-8.84-7.16-16-16-16z" transform="translate(-256 -256)"></path></g></g></svg>'
            colore = "info";
          } else if (Stato == 'Rimborsato' || Stato == 'Annullato' || Stato == 'Da Rimborsare') {
            svg = '<svg class="svg-inline--fa fa-ban fa-w-16 ms-1" data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;"><g transform="translate(256 256)"><g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" transform="translate(-256 -256)"></path></g></g></svg>'
            colore = "secondary";
          } else if (Stato == 'Attesa di pagamento') {
            svg = '<i class="fa-regular fa-money-from-bracket"></i>'
            colore = "warning";
          }



          var res = data.split('-')
          return '<span class="badge badge rounded-pill d-block badge-soft-' + colore + '" title="' + row[8] + '">' + Stato + ' ' + svg + '</span>';
        },
        "targets": 9
      },
      {
        "render": function (data, type, row) {
          return '<strong>' + data + '</strong><br>' + row[2];
        },
        "targets": 4
      },
      {
        "render": function (data, type, row) {
          var res = data.split('-')
          return '<strong>' + res[2] + '/' + res[1] + '/' + res[0] + '</strong>';
        },
        "targets": 5
      },
      {
        "render": function (data, type, row) {
          return '<a href="javascript:void(0)"> <strong>#' + row[0] + '</strong></a> di<br> <strong>' + row[1] + '</strong></a>';
        },
        "targets": 1
      }
    ],
    responsive: true,
    "bProcessing": true,
    "serverSide": true,
    "pageLength": 10,
    "stateSave": true
  });
}

$(document).on('click', '.sendinv', function () {
  var id = $(this).attr("id");
  var idps = $(this).attr("idps");
  var idm = $(this).attr("idm");
  var track = $(this).attr("track");
  var piatt = $(this).attr("piatt");
  var corr = $(this).attr("corr");
  $.post(currentURL + "assets/inc/modifica_ordine.php", { modifica: 'evadiordine', idordine: id }, function (response) {
    if (response == 'ok') {
      aggiornatracking(idps, track, corr)
      if (piatt == "ManoMano") {
        manomano(idm, track, corr)
      }
    } else {
      Toast.fire({ icon: 'error', title: 'Errore: ' + response })
    }
  })
  listaordini();
});

$(document).on('click', '.contr_market', function () {
  var id = $(this).attr("id");
  var ap = $(this).attr("ap");
  var modifica, cosa;
  if (ap == 'no') {
    modifica = 'n_controversia';
    cosa = 'aperta';
  } else if (ap == 'si') {
    modifica = 'a_controversia';
    cosa = 'chiusa';
  }

  $.post(currentURL + "assets/inc/modifica_ordine.php", { modifica: modifica, idordine: id }, function (response) {
    if (response == 'ok') {
      Toast.fire({ icon: 'success', title: 'Controversia ' + cosa + ' per l\'ordine ' + id })
    } else {
      Toast.fire({ icon: 'error', title: 'Errore: ' + response })
    }
  })
  listaordini();
});

$(document).on('click', '.bloccasp', function () {
  bloccaui();
  var id = $(this).attr("id");
  var idps = $(this).attr("idps");
  $.ajax({
    url: currentURL + "assets/inc/modifica_ordine.php",
    method: "POST",
    data: {
      modifica: "bloccasped",
      idordine: id
    },
    success: function (response) {
      Toast.fire({ icon: 'success', title: response })
      modrimbsospordine(idps, 43);
      sbloccaui();
    },
    error: function () {
      Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      sbloccaui();
    }
  });
});

$(document).on('click', '.rientro', function () {
  bloccaui();
  var id = $(this).attr("id");
  $.ajax({
    url: currentURL + "assets/inc/modifica_ordine.php",
    method: "POST",
    data: {
      modifica: "rientro",
      idordine: id
    },
    success: function (response) {
      Toast.fire({ icon: 'success', title: response })
      sbloccaui();
    },
    error: function () {
      Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      sbloccaui();
    }
  });
});

$(document).on('click', '.rimborsp', function () {
  bloccaui();
  var id = $(this).attr("id");
  var idps = $(this).attr("idps");
  $.ajax({
    url: currentURL + "assets/inc/modifica_ordine.php",
    method: "POST",
    data: {
      modifica: "rimborsped",
      idordine: id
    },
    success: function (response) {
      Toast.fire({ icon: 'success', title: response })
      modrimbsospordine(idps, 7);
      inviomail(id);
      sbloccaui();
    },
    error: function () {
      Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      sbloccaui();
    }
  });
});

function inviomail(id) {
  var corpo = 'E\' stato eseguito un rimborso per l\'ordine ID: ' + id;
  $.post(currentURL + "assets/mail/invio_mail.php", { mittente: 'noreply', indirizzodest: 'info@scifostore.com', oggetto: 'Rimborso ordine', corpo: corpo }, function (response) {
    Toast.fire({ icon: 'success', title: response })
  })
}

$(document).on('click', '.darimb', function () {
  bloccaui();
  var id = $(this).attr("id");
  var idps = $(this).attr("idps");
  $.ajax({
    url: currentURL + "assets/inc/modifica_ordine.php",
    method: "POST",
    data: {
      modifica: "darimb",
      idordine: id
    },
    success: function (response) {
      Toast.fire({ icon: 'success', title: response })
      modrimbsospordine(idps, 60);
      sbloccaui();
    },
    error: function () {
      Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
      sbloccaui();
    }
  });
});

$(document).on('click', '.on_stampa', function () {
  var id = $(this).attr("id");
  window.open(currentURL + 'assets/pdf/ordine.php?create_pdf=1&idordine=' + id, 'PDF Ordine', 'width=800, height=800, status, scrollbars=1, location');
});

$(document).on('click', '.chiamacl', function () {
  var id = $(this).attr("id");
  $.ajax({
    url: currentURL + "assets/inc/telefono_cliente.php",
    method: "POST",
    data: {
      tipo: 'daid',
      idordine: id
    },
    success: function (cellulare) {
      var ris = cellulare.split(';');
      $.ajax({
        type: "POST",
        dataType: 'text',
        url: 'http://@' + ris[0] + '/servlet?key=number=0' + ris[1] + ';ENTER',
        username: 'admin',
        password: '2018W01300',
        crossDomain: true,
        xhrFields: {
          withCredentials: true
        }
      })
    }
  });
});

$(document).on('click', '.mailcl', function () {
  var id = $(this).attr("ido");

  $.post(currentURL + 'assets/inc/telefono_cliente.php', { tipo: 'mail', idordine: id }, function (mail) {
    $('#mms-mail_dest').val(mail)
    $('#mms-mail_dest').prop('readonly', true)
  })
});


$(document).on('click', '.inviamm', function () {
  var piattaforma = $(this).attr("piatt");
  var track = $(this).attr("track");
  var idm = $(this).attr("idm");
  var corr = $(this).attr("corr");
  if (piattaforma == "ManoMano") {
    manomano(idm, track, corr)
  } else {
    Toast.fire({ icon: 'error', title: 'Non è un ordine ManoMano' })
  }
});


function aggiornatracking(idordine, trk, corr) {
  $.ajax({
    url: currentURL + "assets/inc/prestashop-control.php",
    method: "POST",
    data: {
      azione: 'inviaordine',
      idordine: idordine,
      idstato: 4
    },
    success: function () {
      $.post(currentURL + "assets/inc/prestashop-control.php", { azione: 'edittracking', idordine: idordine, tracking: trk, corriere: corr }, function (response) {
        if (response == 'si') {
          Toast.fire({ icon: 'success', title: 'Ordine evaso' })
        } else {
          Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
      })
    },
    error: function () {
    }

  });
}

function manomano(idordine, tracking, corriere) {
  var trkurl = '';
  if (corriere == 'TNT') {
    trkurl = "https://www.tnt.it/tracking/getTrack?WT=1%26ConsigNos=" + tracking;
  } else if (corriere == 'BRT' || corriere == 'BRT 1' || corriere == 'BRT 2') {
    trkurl = "https://vas.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm%26ChiSono=" + tracking;
    corriere = "Bartolini%20(parcel%20ID)"
  } else if (corriere == 'GLS') {
    trkurl = "https://www.gls-italy.com/?option=com_gls%26view=track_e_trace%26mode=search%26numero_spedizione=" + tracking + "%26tipo_codice=nazionale";
  } else if (corriere == 'DHL') {
    trkurl = "https://www.dhl.com/it-it/home/tracking/tracking-express.html?submit=1%26tracking-id=" + tracking;
    corriere = "DHL%20(IT)"
  } else if (corriere == 'Poste Italiane') {
    trkurl = "https://www.poste.it/cerca/index.html%23/risultati-spedizioni/" + tracking;
  } else if (corriere == 'SDA') {
    trkurl = "https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it%26tracing.letteraVettura=" + tracking;
  } else if (corriere == 'SAVISE') {
    trkurl = "https://www.oneexpress.it/it/cerca-spedizione/";
  }

  var someDate = new Date();
  var someDate1 = new Date();
  var numberOfDaysToAdd = 4;
  someDate.setDate(someDate.getDate() + numberOfDaysToAdd);
  var dd = someDate.getDate();
  var mm = someDate.getMonth() + 1;
  var y = someDate.getFullYear();
  var someFormattedDate = y + '-' + mm + '-' + dd;

  var form = new FormData();
  form.append("login", "vivai.scifo");
  form.append("password", "3497732IT2K21");

  // var settings = {
  //   "url": "https://ws.monechelle.com?login=vivai.scifo&password=3497732IT2K21&method=create_shipping&order_ref=" + idordine + "&tracking_number=" + tracking + "&tracking_url=" + trkurl + "&shipping_date=" + someDate1.getFullYear() + "-" + (someDate1.getMonth() + 1) + "-" + someDate1.getDate() + "&estimated_delivery_date=" + someFormattedDate + "&carrier=" + corriere,
  //   "method": "POST",
  //   "timeout": 0,
  //   "headers": {
  //     "User-Agent": "vivai.scifo-V1.1"
  //   },
  //   "processData": false,
  //   "mimeType": "multipart/form-data",
  //   "contentType": false,
  //   "data": form
  // };

  var settings = {
    "url": "https://partnersapi.manomano.com/orders/v1/shippings",
    "method": "GET",
    "timeout": 0,
    "headers": {
      "x-api-key": "7P2mtEpyvOpTFsDcD8FHVtSdwe2xo6MS",
      "x-thirdparty-name": "PixelSmart_v3"
    },
    "data": "[\n  {\n    \"carrier\": \"" + corriere + "\",\n    \"order_reference\": \"" + idordine + "\",\n    \"seller_contract_id\": 3497732,\n    \"tracking_number\": \"" + tracking + "\",\n    \"tracking_url\": \"" + trkurl + "\",\n ]",
  };

  $.ajax(settings).done(function (response) {
    console.log(response);
  });
}

function modrimbsospordine(idordine, idstato) {
  $.ajax({
    url: currentURL + "assets/inc/prestashop-control.php",
    method: "POST",

    data: {
      azione: 'inviaordine',
      idordine: idordine,
      idstato: idstato
    },
    success: function () {
      Toast.fire({ icon: 'success', title: 'Stato ordine aggiornato su PrestaShop' })
    },
    error: function () {
    }

  });
}

function listaordini() {
  $.post(currentURL + "assets/inc/modifica_ordine.php", { modifica: 'totlistacorr' }, function (data) {
    $('#totcorrieri').html(data)
  });
}
