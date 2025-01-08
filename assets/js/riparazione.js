var nscheda = 0;

function aggiornarip() {
    bloccaui();
    var colonna = $('#sceltadati').val();
    var campo = $('#selectcosa').val();
    var clausola = '';
    if (colonna != '-') {
        if (colonna == 'c.Cliente') {
            clausola = 'WHERE ' + colonna + ' LIKE \'%' + campo + '%\'';
        } else if (colonna == 'r.DataIngresso') {
            var data = campo.split('-');
            clausola = 'WHERE ' + colonna + '=\'' + data[2] + '/' + data[1] + '/' + data[0] + '\'';
        } else {
            clausola = 'WHERE ' + colonna + '=\'' + campo + '\'';
        }
    } else {
        clausola = " WHERE r.Stato='Lavorazione'";
    }

    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'aggiorna',
            clausola: clausola,
        },
        success: function (data) {
            $("div.tabellarip").html(data);
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

$('#selectcosa').on('change', function () {
    aggiornarip();
});

var id;
var cellulare;
var stato;
var chstato;


$(document).on('click', '.cambia', function () {
    chstato = '';

    id = $(this).attr("idr");
    cellulare = $('gest#' + id).attr("cell");
    stato = $('gest#' + id).attr("stato");
    intervento = $('gest#' + id).attr("int");

    var stpagato = '';

    if (intervento == 'RICONDIZIONAMENTO') {
        var stpagato = '   <a class="usato" idr="' + id + '" href="javascript:void(0)" title="Imposta come usato"><i class="fa-duotone fa-tag"></i></a>';
    } else {
        var stpagato = '   <a class="pagato" idr="' + id + '" href="javascript:void(0)" title="Imposta Pagato"><i class="fa-duotone fa-money-bill"></i></a>';
    }
    var stcompletato = '   <a class="completato" idr="' + id + '" href="javascript:void(0)" title="Imposta Completato"><i class="fa-duotone fa-clipboard-check"></i></a>'
    var stlavorazione = '   <a class="lavorazione" idr="' + id + '" href="javascript:void(0)" title="Imposta in Lavorazione"><i class="fa-duotone fa-wrench"></i></a>'
    var stsospeso = '   <a class="sospeso" idr="' + id + '" href="javascript:void(0)" title="Sospendi"><i class="fa-duotone fa-pause"></i></a>'
    var stannulla = '   <a class="annulla" idr="' + id + '" href="javascript:void(0)" title="Elimina"><i class="fa-duotone fa-trash-alt"></i></a>'

    if (stato == 'Lavorazione') {
        chstato = chstato + stcompletato + stsospeso + stannulla;
    } else if (stato == 'Completato') {
        chstato = chstato + stpagato + stlavorazione + stsospeso + stannulla;
    } else if (stato == 'Sospeso') {
        chstato = chstato + stlavorazione + stannulla;
    }

    var element = document.getElementById(id);
    element.innerHTML = '<gest id="' + id + '" cell="' + cellulare + '" stato="' + stato + '"><a class="back" idr="' + id + '" href="javascript:void(0)" title="back"><i class="fa-duotone fa-arrow-left"></i></a>' + chstato + '</gest>';
});

$(document).on('click', '.stampa_rip', function () {
    // alert('stampa');
    Swal.fire({
        title: 'Attenzione!',
        text: 'Stampa ancora in fase di test"',
        icon: 'info',
        confirmButtonText: 'Esci!'
    })
});

$(document).on('click', '.back', function () {
    id = $(this).attr("idr");
    var element = document.getElementById(id);
    element.innerHTML = '<gest id="' + id + '"><a class="cambia" idr="' + id + '" href="javascript:void(0)" title="Cambia Stato"><i class="fa-duotone fa-dice-d6"></i></a>   <a class="chiamacl" cellulare="' + cellulare + '" href="javascript:void(0)" title="Chiama Cliente"><i class="fa-duotone fa-phone-alt"></i></a>   <a class="stampa_rip" idr="' + id + '" href="javascript:void(0)" title="Stampa"><i class="fa-duotone fa-print"></i></a>   <a class="copiarip" idr="' + id + '" href="javascript:void(0)" title="Copia"><i class="far fa-copy"></i></a></gest>'; // UGUALE ALL INIZIO

});

$(document).on('click', '.copiarip', function () {
    bloccaui();
    idr = $(this).attr("idr");
    today = new Date()

    $('#listariparazioni').prop('hidden', true);
    $('#schedariparazione').prop('hidden', false);
    $('#modificacliente').prop('hidden', false);
    $('#salvacliente').prop('hidden', true);

    dabilitamodcliente_rip()
    $('#accontotab').prop('hidden', true);

    $('.prodottitab').prop('hidden', true);
    $('.garanziatab').prop('hidden', true);

    $('#noteintervento').prop('readonly', true);
    $('#datauscita').prop('disabled', true);
    $('#tecnico').prop('disabled', true);

    $.post(currentURL + "assets/inc/riparazione.php", { azione: 'ultimoid' }, function (data) { $("#nscheda").html(('Scheda N°: ' + (parseInt(data) + 1))); nscheda = parseInt(data) + 1; })

    $.post(currentURL + "assets/inc/riparazione.php", { azione: 'daticopiarip', idrip: idr }, function (data) {
        var res = data.split('|-|');
        $('#marchio').val(res[0]);
        $('#prodotto').val(res[1]);
        $('#modello').val(res[2]);
        $('#seriale').val(res[3]);

        $('#idcliente').val(res[4]); //ID
        $('#clientecl').val(res[5]); //CLIENTE
        $('#indcl').val(res[6]); //INDIRIZZO
        $('#cellcl').val(res[7]); //Cellulare
        $('#mailcl').val(res[8]); //Mail
        $('#codfisc').val(res[9]); //CODFISC
        $('#pec').val(res[10]); //PEC
        $('#pivaz').val(res[11]); //PIVA
        $('#sdizienda').val(res[12]); //SDI
        $('#cittacl').val(res[13]); //CITTA
        $('#nickcl').val(res[14]); //NICK

        $('#totquant').val('0');
        $('#totprezz').val('0');
        sbloccaui();
    })
});

$(document).on('click', '.chiamacl', function () {
    cellulare = $(this).attr("cellulare");
    $.ajax({
        url: currentURL + "assets/inc/telefono_cliente.php",
        method: "POST", //First change type to method here
        data: {
            tipo: 'nuovo'
        },
        success: function (ipt) {
            $.ajax({
                type: "POST",
                dataType: 'text',
                url: 'http://@' + ipt + '/servlet?key=number=0' + cellulare + ';ENTER',
                username: 'admin',
                password: '2018W01300',
                crossDomain: true,
                xhrFields: {
                    withCredentials: true
                }
            })
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
        }
    });
});

$(document).on('click', '.nuovousato', function () {
    cambiopagina('officina', 'usato', '');
});

function cambiadatirip() {
    var indice = $("#sceltadati option:selected").index();
    var risultato = '<div class="form-group"><div class="dataTables_filter">';
    risultato = risultato + '<label class="bmd-label">Selezionare</label>';
    if (indice == 1) {
        risultato = risultato + '<input id="selectcosa" name="testo" type="search" class="form-control form-control-sm" placeholder="Inserisci l\'ID Scheda" onchange="aggiornarip()">'
    } else if (indice == 2) {
        risultato = risultato + '<input id="selectcosa" name="testo" type="search" class="form-control form-control-sm" placeholder="Inserisci il cliente" onchange="aggiornarip()">'
    } else if (indice == 6) {
        risultato = risultato + '<input id="selectcosa" name="testo" type="search" class="form-control form-control-sm" placeholder="Scan Codice a barre" onchange="aggiornarip()">'
    } else if (indice == 7) {
        risultato = risultato + '<input id="selectcosa" name="testo" type="search" class="form-control form-control-sm" placeholder="Seriale macchina" onchange="aggiornarip()">'
    } else if (indice == 3) {
        risultato = risultato + '<select class="form-control form-control-sm selectpicker" data-style="btn btn-link" id="selectcosa" onchange="aggiornarip()">'
        risultato = risultato + '  <option value="-">-</option>'
        risultato = risultato + '  <option value="Manutenzione">Manutenzione</option>'
        risultato = risultato + '  <option value="Riparazione">Riparazione</option>'
        risultato = risultato + '  <option value="Tagliando">Tagliando</option>'
        risultato = risultato + '  <option value="Ricondizionamento">Ricondizionamento</option>'
        risultato = risultato + '  <option value="Ordine Ricambi">Ordine Ricambi</option>'
        risultato = risultato + '  <option value="Preventivo">Preventivo</option>'
        risultato = risultato + '</select>'
    } else if (indice == 5) {
        risultato = risultato + '<select class="form-control form-control-sm selectpicker" data-style="btn btn-link" id="selectcosa" onchange="aggiornarip()">'
        risultato = risultato + '  <option value="-">-</option>'
        risultato = risultato + '  <option value="Completato">Completato</option>'
        risultato = risultato + '  <option value="Lavorazione">Lavorazione</option>'
        risultato = risultato + '  <option value="Pagato">Pagato</option>'
        risultato = risultato + '  <option value="Sospeso">Sospeso</option>'
        risultato = risultato + '  <option value="Usato">Usato</option>'
        risultato = risultato + '  <option value="Annullato">Annullato</option>'
        risultato = risultato + '</select>'
    } else if (indice == 4) {
        risultato = risultato + '<input id="selectcosa" name="testo" type="search" class="form-control form-control-sm" onchange="aggiornarip()">'
    } else if (indice == 0) {
        risultato = risultato + ''
        aggiornarip();
    }
    risultato = risultato + '</div></div>';

    $('#sceltadatidiv').html(risultato);
}


$(document).on('click', '.nuovo', function () {
    bloccaui();
    $('#listariparazioni').prop('hidden', true);
    $('#schedariparazione').prop('hidden', false);
    $('#modificacliente').prop('hidden', true);
    $('#salvacliente').prop('hidden', false);
    $('#accontotab').prop('hidden', true);

    $('.prodottitab').prop('hidden', true);
    $('.garanziatab').prop('hidden', true);

    $('#noteintervento').prop('readonly', true);
    $('#datauscita').prop('disabled', true);
    $('#tecnico').prop('disabled', true);

    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'ultimoid'
        },
        success: function (data) {
            $("#nscheda").html(('Scheda N°: ' + (parseInt(data) + 1)));
            nscheda = parseInt(data) + 1;
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
});

$(document).on('click', '.indietroscheda', function () {
    svuotacampi();
});

var i = 1;

$(document).on('click', '.nuovariga_rip', function () {
    i = i + 1;
    var codice = '<tr id="' + i + '">';
    codice = codice + '<td><input id="cod' + i + '" type="text" class="form-control" onchange="cercaprodotto_rip(' + i + ')" value=""></td>';
    codice = codice + '<td><input id="desc' + i + '" type="text" class="form-control" value="" readonly></td>';
    codice = codice + '<td><select id="um' + i + '" type="text" class="form-control" value="" readonly>';
    codice = codice + '<option value="pz">PZ</option>';
    codice = codice + '<option value="mt">MT</option>';
    codice = codice + '<option value="lt">LT</option>';
    codice = codice + '<option value="kg">KG</option>';
    codice = codice + '</select></td>';
    codice = codice + '<td><input id="quant' + i + '" type="number" class="form-control" onchange="calcolaprezzo_rip(' + i + ')" value=""></td>';
    codice = codice + '<td><input id="prez' + i + '" type="text" class="form-control" onchange="calcolaprezzo_rip(' + i + ')" value=""></td>';
    codice = codice + '<td><input id="sco' + i + '" type="text" class="form-control" onchange="calcolaprezzo_rip(' + i + ')" value="0"></td>';
    codice = codice + '<td><input id="tot' + i + '" type="text" class="form-control" onchange="calcolaprezzo_rip(' + i + ')" value="" readonly></td>';
    if (neww == 1) {
        codice = codice + '<td id="nuovorelpo' + i + '"><a id="' + i + '" href="javascript:void(0)" title="Salva Voce" onclick="salvanuovoprodscheda(' + i + ')"><i class="fa-duotone fa-check"></i></a></td>';
    } else if (neww == 0) {
        codice = codice + '<td><a id="' + i + '" href="javascript:void(0)" title="Elimina Voce" onclick="eliminaprod_rip(' + i + ', 1)"><i class="fa-duotone fa-minus-circle"></i></a></td>';
    }
    codice = codice + '<td><input id="idpr' + i + '" type="text" class="form-control" value="" hidden></td>';
    codice = codice + '</tr>';

    $('tbody#addriga').append(codice);
});

function calcolaprezzo_rip(id) {
    var quant = $('#quant' + id).val();
    var sconto = $('#sco' + id).val();
    var prez = $('#prez' + id).val();
    prez = prez.replace(",", ".");
    $('#prez' + id).val(prez);
    $('#tot' + id).val((quant * parseFloat(prez)));

    if (sconto >= 1 || sconto != '') {
        $('#tot' + id).val(Number((quant * parseFloat(prez))) * ((100 - Number(sconto)) / 100));
    }

    calcolatotale();
}

function calcolatotale() {
    var quanttot = 0;
    var preztot = 0;
    var quanttt = 0;
    var prezt = 0;

    var checkprod = '';

    for (ii = 1; ii <= i; ii++) {
        checkprod = $('#cod' + ii).val();
        if (checkprod === undefined) {
        } else {
            quanttt = $('#quant' + ii).val();
            prezt = $('#tot' + ii).val();
            quanttot = (quanttot + parseInt(quanttt));
            preztot = (preztot + parseFloat(prezt));
        }
    }
    $('#totquant').html(quanttot);
    preztot = parseFloat(preztot).toFixed(2).replace('.', ',');
    $('#totprezz').html(preztot);
}

function cercaprodotto_rip(id) {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'cercaprodotto',
            codice: $('#cod' + id).val(),
        },
        success: function (data) {
            var res = data.split('|-|');
            $('#cod' + id).val(res[0]);
            $('#desc' + id).val(res[1]);
            //$('#um' + id).val(res[2]);
            $('#um' + id + ' select').val(res[2]);
            $('#prez' + id).val(res[3]);
            $('#idpr' + id).val(res[4]);
            $('#quant' + id).val('1');
            $('#tot' + id).val(res[3]);
            calcolatotale();

            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

function cercaprpop(id, codice2) {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'cercaprodotto',
            codice: codice2,
        },
        success: function (data) {
            var res = data.split('|-|');
            $('#cod' + id).val(res[0]);
            $('#desc' + id).val(res[1]);
            //$('#um' + id).val(res[2]);
            $('#um' + id + ' select').val(res[2]);
            $('#prez' + id).val(res[3]);
            $('#idpr' + id).val(res[4]);
            $('#quant' + id).val('1');
            $('#tot' + id).val(res[3]);
            calcolatotale();

            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
        }
    });
}

function clienteclchange() {
    var cliente = $('#clientecl').val();
    cliente = cliente.replace(/\s*\(.*?\)\s*/g, '');

    var idcl = parseInt(cliente.replace(/[^0-9\.]/g, ''), 10);

    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'daticliente',
            cliente: idcl
        },
        success: function (data) {
            var res = data.split('|-|');
            if (res[0].length >= 1) {

                $('#idcliente').val(res[0]); //ID
                $('#clientecl').val(res[1]); //CLIENTE
                $('#indcl').val(res[2]); //INDIRIZZO
                $('#cellcl').val(res[3]); //Cellulare
                $('#mailcl').val(res[4]); //Mail
                $('#codfisc').val(res[5]); //CODFISC
                $('#pec').val(res[6]); //PEC
                $('#pivaz').val(res[7]); //PIVA
                $('#sdizienda').val(res[8]); //SDI
                $('#cittacl').val(res[9]); //CITTA
                $('#nickcl').val(res[10]); //NICK

                $('#modificacliente').prop('hidden', false);
                $('#salvacliente').prop('hidden', true);

                dabilitamodcliente_rip()
            }
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
        }
    });
}

function dabilitamodcliente_rip() {
    $('#idcliente').prop('readonly', true)
    $('#clientecl').prop('readonly', true)
    $('#indcl').prop('readonly', true)
    $('#cellcl').prop('readonly', true)
    $('#mailcl').prop('readonly', true)
    $('#codfisc').prop('readonly', true)
    $('#pec').prop('readonly', true)
    $('#pivaz').prop('readonly', true)
    $('#sdizienda').prop('readonly', true)
    $('#cittacl').prop('readonly', true)
    $('#nickcl').prop('readonly', true)

    $('#bttsalvacliente').prop('hidden', true)
    $('#bttdelcliente').prop('hidden', true)
}

function abilitamodcliente_rip() {
    $('#idcliente').prop('readonly', false)
    $('#clientecl').prop('readonly', false)
    $('#indcl').prop('readonly', false)
    $('#cellcl').prop('readonly', false)
    $('#mailcl').prop('readonly', false)
    $('#codfisc').prop('readonly', false)
    $('#pec').prop('readonly', false)
    $('#pivaz').prop('readonly', false)
    $('#sdizienda').prop('readonly', false)
    $('#cittacl').prop('readonly', false)
    $('#nickcl').prop('readonly', false)

    $('#bttsalvacliente').prop('hidden', false)
    $('#bttdelcliente').prop('hidden', false)
}

$(document).on('click', '.abclienterip', function () {
    if ($('#clientecl').val() != '') {
        abilitamodcliente_rip()
    } else {
        showNotification('top', 'right', 'Prima di modificare devi caricare un cliente', 'danger', 'close');
    }
});

$(document).on('click', '.dabclienterip', function () {
    dabilitamodcliente_rip()
});

$(document).on('click', '.sanuoclienterip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'creanuovocliente',
            clientecl: $('#clientecl').val(),
            indcl: $('#indcl').val(),
            cellcl: $('#cellcl').val(),
            mailcl: $('#mailcl').val(),
            idcliente: $('#idcliente').val(),
            cittacl: $('#cittacl').val(),
            nickcl: $('#nickcl').val(),

            codfisc: $('#codfisc').val(),
            pec: $('#pec').val(),
            pivaz: $('#pivaz').val(),
            sdizienda: $('#sdizienda').val()
        },
        success: function (response) {
            if (response == 'no') {
                showNotification('top', 'right', 'Errore nel caricamento, si prega di controllare i dati inseriti.', 'danger', 'close');
                alert();
            } else {
                showNotification('top', 'right', 'Cliente creato con successo', 'success', 'done');
                $('#modificacliente').prop('hidden', false);
                $('#salvacliente').prop('hidden', true);
                $('#idcliente').val(response);
                dabilitamodcliente_rip()
            }
            sbloccaui();
        },
        error: function () {
            showNotification('top', 'right', 'Errore nel gestire la richiesta, controlla i dati!', 'danger', 'close');
            sbloccaui();
        }
    });
});

$(document).on('click', '.saclienterip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'aggiornacliente',
            clientecl: $('#clientecl').val(),
            indcl: $('#indcl').val(),
            cellcl: $('#cellcl').val(),
            mailcl: $('#mailcl').val(),
            idcliente: $('#idcliente').val(),
            cittacl: $('#cittacl').val(),
            nickcl: $('#nickcl').val(),

            codfisc: $('#codfisc').val(),
            pec: $('#pec').val(),
            pivaz: $('#pivaz').val(),
            sdizienda: $('#sdizienda').val()
        },
        success: function (response) {
            if (response == 'no') {
                showNotification('top', 'right', 'Errore nel caricamento, si prega di controllare i dati inseriti.', 'danger', 'close');
                alert();
            } else {
                showNotification('top', 'right', 'Cliente aggiornato con successo', 'success', 'done');
                dabilitamodcliente_rip()
            }
            sbloccaui();
        },
        error: function () {
            showNotification('top', 'right', 'Errore nel gestire la richiesta, controlla i dati!', 'danger', 'close');
            sbloccaui();
        }
    });
});

$(document).on('click', '.apricat', function () {
    window.open(currentURL + "officina/catalogo.php?idriga=" + i, 'Catalogo Officina.', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

function eliminaprod_rip(idi, tipo) {
    bloccaui();

    if (tipo == '1') { //solo HTML
        $('tr#' + idi).remove();
        sbloccaui();
    } else if (tipo == '2') { //HTML + DB
        var idpr = $('#idpr' + idi).val();;

        $.ajax({
            url: currentURL + "assets/inc/riparazione.php",
            method: "POST", //First change type to method here
            data: {
                azione: 'eliminaprod',
                idpr: idpr,
                nscheda: nscheda,
                quantita: $('#quant' + idi).val()
            },
            success: function (response) {
                if (response == 'si') {
                    showNotification('top', 'right', 'Prodotto eliminato con successo', 'success', 'done');
                    $('tr#' + idi).remove();
                    calcolatotale();

                    $.ajax({
                        url: currentURL + "assets/inc/riparazione.php",
                        method: "POST", //First change type to method here
                        data: {
                            azione: 'salvatotale',
                            totale: $('#totprezz').html(),
                            nscheda: nscheda,
                        },
                        success: function (response) {
                        },
                        error: function (response) {
                            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                        }
                    });
                } else {
                    Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                }
                sbloccaui();
            },
            error: function (response) {
                Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                sbloccaui();
            }
        });
    }


}

function salvanuovoprodscheda(idi) {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'nuovorelpo',
            idp: $('#idpr' + idi).val(),
            ido: nscheda,
            quantita: $('#quant' + idi).val(),
            sconto: $('#sco' + idi).val(),
            prezzo: $('#prez' + idi).val(),
            um: $('#um' + idi).val(),
        },
        success: function (response) {
            if (response == 'si') {
                showNotification('top', 'right', 'Prodotto salvato con successo', 'success', 'done');
                var element = document.getElementById('nuovorelpo' + idi);
                element.innerHTML = '<a id="' + i + '" href="javascript:void(0)" title="Elimina Voce" onclick="eliminaprod_rip(' + i + ', 1)"><i class="fa-duotone fa-minus-circle"></i></a>';
                $('#cod' + idi).prop('readonly', true);
                $('#quant' + idi).prop('readonly', true);
                $('#prez' + idi).prop('readonly', true);
                $('#sco' + idi).prop('readonly', true);
                $('#cod' + idi).prop('readonly', true);

                $.ajax({
                    url: currentURL + "assets/inc/riparazione.php",
                    method: "POST", //First change type to method here
                    data: {
                        azione: 'salvatotale',
                        totale: $('#totprezz').html(),
                        nscheda: nscheda,
                    },
                    success: function (response) {
                    },
                    error: function (response) {
                        Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                    }
                });
            } else {
                Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            }
            sbloccaui();
        },
        error: function (response) {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            sbloccaui();
        }
    });

    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'salvatotale',
            totale: $('#totprezz').html(),
            nscheda: nscheda,
        },
        success: function (response) {
        },
        error: function (response) {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    });
}


$(document).on('click', '.salvascheda', function () {
    bloccaui();
    if ($('#idcliente').val() == '') {
        Swal.fire({ title: 'Errore', text: 'Prima di procedere con il caricamento devi creare o caricare un cliente', icon: 'error', confirmButtonText: 'Conferma' })
    } else if ($('#motint').val() == '') {
        Swal.fire({ title: 'Errore', text: 'Prima di procedere devi inserire il motivo di entrata della macchina', icon: 'error', confirmButtonText: 'Conferma' })
    } else if ($('#intervento option:selected').val() == '-') {
        Swal.fire({ title: 'Errore', text: 'Prima di procedere devi inserire il tipo di intervento', icon: 'error', confirmButtonText: 'Conferma' })
    } else if ($('#dataingresso').val() == '') {
        Swal.fire({ title: 'Errore', text: 'Prima di procedere devi inserire la data di ingresso della macchina', icon: 'error', confirmButtonText: 'Conferma' })
    } else {
        $.ajax({
            url: currentURL + "assets/inc/riparazione.php",
            method: "POST", //First change type to method here
            data: {
                azione: 'creascheda',
                accessorinote: $('#accessorinote').val(),
                intervento: $('#intervento').val(),
                motivointervento: $('#motint').val(),
                idcl: $('#idcliente').val(),
                seriale: $('#seriale').val(),
                marchio: $('#marchio').val(),
                prodotto: $('#prodotto').val(),
                modello: $('#modello').val(),
                totale: $('#totprezz').html()
            },
            success: function (response) {
                if (response == 'si') {
                    showNotification('top', 'right', 'Scheda creata con successo', 'success', 'done');

                    // for (ii = 1; ii <= i; ii++) {
                    //     if ($('#idpr' + ii).val() == '' || $('#idpr' + ii).val() == undefined) {
                    //     } else {
                    //         $.ajax({
                    //             url: currentURL + "assets/inc/riparazione.php",
                    //             method: "POST", //First change type to method here
                    //             data: {
                    //                 azione: 'nuovorelpo',
                    //                 idp: $('#idpr' + ii).val(),
                    //                 ido: nscheda,
                    //                 quantita: $('#quant' + ii).val(),
                    //                 sconto: $('#sco' + ii).val(),
                    //                 prezzo: $('#prez' + ii).val(),
                    //                 um: $('#um' + ii).val(),
                    //             },
                    //             success: function (response) {
                    //                 if (response == 'si') {
                    //                     showNotification('top', 'right', 'Prodotti caricati con successo', 'success', 'done');
                    //                 } else {
                    //                     Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                    //                 }
                    //             },
                    //             error: function (response) {
                    //                 Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                    //             }
                    //         });
                    //     }
                    // }

                    svuotacampi();
                    aggiornarip();
                } else {
                    showNotification('top', 'right', 'Errore nel caricamento, si prega di controllare i dati inseriti. Errore: ' + response, 'danger', 'close');
                }
                sbloccaui();
            },
            error: function (response) {
                Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                sbloccaui();
            }
        });


    }
});

function svuotacampi() {
    $('#listariparazioni').prop('hidden', false);
    $('#schedariparazione').prop('hidden', true);
    $('#salvaschedaid').prop('hidden', false);
    neww = 0;

    $('#aggiungiacconto').prop('hidden', true);
    $('#accontotab').prop('hidden', false);

    $('#salvadettingr').prop('hidden', true);
    $('#salvadettrip').prop('hidden', true);
    $('#accessorinote').prop('readonly', false);

    $('.prodottitab').prop('hidden', false);
    $('.garanziatab').prop('hidden', false);
    $('#inviagaranzia').prop('hidden', false);
    $('#accrifgar').prop('hidden', true);

    abilitamodcliente_rip();
    abilitamoddettrip();
    abilitaingresso();
    dabilitauscita();

    $('#accessorinote').val('');
    $('#intervento').val('');
    $('#motint').val('');
    $('#seriale').val('');
    $('#marchio').val('');
    $('#prodotto').val('');
    $('#modello').val('');
    $('#noteintervento').val('');
    $('#datauscita').val('');
    $('#dataingresso').val('');
    $('#totprezz').text('0');
    $('#totquant').text('0');

    $('#prodrich').val('');
    $('#anommacc').val('');
    $('#notegar').val('');

    var codice = '<tr id="1">';;
    codice = codice + '<td><input id="cod1" type="text" class="form-control" onchange="cercaprodotto_rip(1)" value=""></td>';
    codice = codice + '<td><input id="desc1" type="text" class="form-control" value="" readonly></td>';
    codice = codice + '<td><select id="um1" type="text" class="form-control" value="" readonly>';
    codice = codice + '<option value="pz">PZ</option>';
    codice = codice + '<option value="mt">MT</option>';
    codice = codice + '<option value="lt">LT</option>';
    codice = codice + '<option value="kg">KG</option>';
    codice = codice + '</select></td>';
    codice = codice + '<td><input id="quant1" type="number" class="form-control" onchange="calcolaprezzo_rip(1)" value=""></td>';
    codice = codice + '<td><input id="prez1" type="text" class="form-control" onchange="calcolaprezzo_rip(1)" value=""></td>';
    codice = codice + '<td><input id="sco1" type="text" class="form-control" onchange="calcolaprezzo_rip(1)" value="0"></td>';
    codice = codice + '<td><input id="tot1" type="text" class="form-control" onchange="calcolaprezzo_rip(1)" value="" readonly></td>';
    codice = codice + '<td><a id="1" href="javascript:void(0)" title="Elimina Voce" onclick="eliminaprod_rip(' + i + ', 1)"><i class="fa-duotone fa-minus-circle"></i></a></td>';
    codice = codice + '<td><input id="idpr1" type="text" class="form-control" value="" hidden></td>'
    codice = codice + '</tr>';
    $('tbody#addriga').html(codice);

    $('#idcliente').val('');
    $('#clientecl').val('');
    $('#indcl').val('');
    $('#cellcl').val('');
    $('#mailcl').val('');
    $('#codfisc').val('');
    $('#pec').val('');
    $('#pivaz').val('');
    $('#sdizienda').val('');
    $('#nickcl').val('');
    $('#cittacl').val('');
}


//FUNZIONI APRI RIPARAZIONE
var idcl;
var neww = 0;
$(document).on('click', '.apririp', function () {
    bloccaui();
    var stato = '';
    svuotacampi();
    var idscheda = $(this).attr('idr');
    nscheda = idscheda;
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'caricascheda',
            idrip: idscheda
        },
        success: function (response) {
            var res = response.split('|-|');
            $('#marchio').val(res[0]);
            $('#prodotto').val(res[1]);
            $('#modello').val(res[2]);
            $('#seriale').val(res[3]);
            $('#accessorinote').val(res[4]);
            //var res2 = res[5].split('-');
            $('#dataingresso').val(res[5]);
            $('#intervento').val(res[6]);

            $('#motint').val(res[7]);
            // var res3 = res[8].split('/');
            $('#datauscita').val(res[8]);
            $("#tecnico select").val(res[9]);
            $('#noteintervento').val(res[10]);

            $('#prodrich').val(res[13]);
            $('#anommacc').val(res[12]);
            $('#notegar').val(res[14]);
            stato = res[15];
            if (stato == 'IN ATTESA') {
                $('#inviagaranzia').prop('hidden', true);
                $('#accrifgar').prop('hidden', false);
            } else if (stato == 'ACCETTATA' || stato == 'RIFIUTATA') {
                $('#inviagaranzia').prop('hidden', true);
                $('#accrifgar').prop('hidden', true);
            } else {
                $('#inviagaranzia').prop('hidden', false);
                $('#accrifgar').prop('hidden', true);
            }

            $.ajax({
                url: currentURL + "assets/inc/riparazione.php",
                method: "POST", //First change type to method here
                data: {
                    azione: 'daticliente2',
                    idcl: res[11],
                },
                success: function (data) {
                    var res = data.split('|-|');
                    $('#idcliente').val(res[0]); //ID
                    $('#clientecl').val(res[1]); //CLIENTE
                    $('#nickcl').val(res[10]); //CITTA
                    $('#cittacl').val(res[9]); //CITTA
                    $('#indcl').val(res[2]); //INDIRIZZO
                    $('#cellcl').val(res[3]); //Cellulare
                    $('#mailcl').val(res[4]); //Mail
                    $('#codfisc').val(res[5]); //Azienda
                    $('#pec').val(res[6]); //IndirizzoAz
                    $('#pivaz').val(res[7]); //PIVA
                    $('#sdizienda').val(res[8]); //SDI

                    dabilitamodcliente_rip()
                },
                error: function () {
                    Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                }

            });

            //INIZIO LISTA ACCONTI
            $.ajax({
                url: currentURL + "assets/inc/riparazione.php",
                method: "POST", //First change type to method here
                data: {
                    azione: 'cercaacconti',
                    nscheda: nscheda
                },
                success: function (data) {
                    if (data.length >= 15) {
                        var res = data.split('|-|')
                        $('#listaacconti').html(data);
                    }
                }
            });
            //FINE LISTA ACCONTI
            sbloccaui();
        },
        error: function (response) {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
            sbloccaui();
        }
    });

    //INIZIO INFO PRODOTTI
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'cercaprodotti',
            nscheda: nscheda
        },
        success: function (data) {
            var res = data.split('|-|')
            $('tbody#addriga').html(res[0]);
            $('#totprezz').html(res[2]);
            $('#totquant').html(res[1]);
            i = parseInt(res[3]);
        }
    });
    //FINE INFO PRODOTTI

    $("#nscheda").html('Scheda N°: ' + idscheda);
    $('#salvaschedaid').prop('hidden', true);
    $('#salvadettrip').prop('hidden', false);
    $('#salvadettingr').prop('hidden', false);
    neww = 1;

    $('#modificacliente').prop('hidden', false);
    $('#salvacliente').prop('hidden', true);

    dabilitamoddettrip();
    dabilitaingresso();
    abilitauscita();

    $('#listariparazioni').prop('hidden', true);
    $('#schedariparazione').prop('hidden', false);

});

function dabilitamoddettrip() {
    $('#marchio').prop('readonly', true);
    $('#prodotto').prop('readonly', true);
    $('#modello').prop('readonly', true);
    $('#seriale').prop('readonly', true);
    $('#accessorinote').prop('readonly', true);

    $('#bttsalvadettrip').prop('hidden', true)
    $('#bttdeldettrip').prop('hidden', true)
}

function abilitamoddettrip() {
    $('#marchio').prop('readonly', false);
    $('#prodotto').prop('readonly', false);
    $('#modello').prop('readonly', false);
    $('#seriale').prop('readonly', false);

    $('#bttsalvadettrip').prop('hidden', false)
    $('#bttdeldettrip').prop('hidden', false)
}

$(document).on('click', '.abdettrip', function () {
    abilitamoddettrip()
});

$(document).on('click', '.dabdettrip', function () {
    dabilitamoddettrip()
});

function abilitamoddettingr() {
    $('#motint').prop('readonly', false);
    $('#intervento').prop('disabled', false);

    $('#bttsalvadettingr').prop('hidden', false)
    $('#bttdeldettingr').prop('hidden', false)
}

function dabilitamoddettingr() {
    $('#motint').prop('readonly', true);
    $('#intervento').prop('disabled', true);

    $('#bttsalvadettingr').prop('hidden', true)
    $('#bttdeldettingr').prop('hidden', true)
}

$(document).on('click', '.abdettingr', function () {
    abilitamoddettingr()
});

$(document).on('click', '.dabdettingr', function () {
    dabilitamoddettingr()
});

$(document).on('click', '.sadettrip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'moddettrip',
            idrip: nscheda,
            marchio: $('#marchio').val(),
            prodotto: $('#prodotto').val(),
            modello: $('#modello').val(),
            seriale: $('#seriale').val(),
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Dettagli riparazione salvati con successo', 'success', 'done');
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
            dabilitamoddettrip()
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
});

$(document).on('click', '.sadettingr', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'moddettingr',
            idrip: nscheda,
            motint: $('#motint').val(),
            intervento: $('#intervento option:selected').val(),
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Note intervento salvate con successo', 'success', 'done');
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
            dabilitamoddettingr()
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
});

function dabilitaingresso() {
    $('#dataingresso').prop('disabled', true);
    $('#intervento').prop('disabled', true);
    $('#motint').prop('readonly', true);
}

function abilitaingresso() {
    $('#dataingresso').prop('disabled', false);
    $('#intervento').prop('disabled', false);
    $('#motint').prop('readonly', false);
}

function dabilitauscita() {
    $('#datauscita').prop('disabled', true);
    $('#tecnico').prop('disabled', true);
    $('#noteintervento').prop('readonly', true);
}

function abilitauscita() {
    $('#datauscita').prop('disabled', false);
    $('#tecnico').prop('disabled', false);
    $('#noteintervento').prop('readonly', false);
}
//FINE FUNZIONE APRI RIPARAZIONI

function salvadatauscita() {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'sadatauscita',
            nscheda: nscheda
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Data uscita salvata con successo', 'success', 'done');
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
}

function salvanoteinterv() {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'sanoteint',
            noteint: $('#noteintervento').val(),
            nscheda: nscheda,
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Note intervento salvate con successo', 'success', 'done');
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
}

$(document).on('click', '.inviagar_rip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'inviagar',
            prodrich: $('#prodrich').val(),
            anommacc: $('#anommacc').val(),
            nscheda: nscheda
        },
        success: function (data) {
            if (data == 'si') {

                var data = new FormData();

                data.append("mittente", 'noreply');
                data.append("indirizzodest", 'info@scifostore.com');
                data.append("oggetto", 'Nuova Garanzia! ID Scheda: ' + $('#nscheda').val());
                data.append("corpo", 'E\' stata richiesta una nuova garanzia per la scheda n°' + $('#nscheda').val());

                $.ajax({
                    url: currentURL + "assets/mail/invio_mail.php",
                    method: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    success: function (response) {
                        Swal.fire({ title: 'OK!', text: 'Garanzia aperta con successo!', icon: 'info', confirmButtonText: 'Chiudi' })
                    },
                    error: function () {
                        Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                    }
                });
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
});

$(document).on('click', '.accettagar_rip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'upgaranzia',
            stato: 'ACCETTATA',
            note: prompt('Inserisci note garanzia'),
            nscheda: nscheda
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Garanzia aggiornata con successo!', 'success', 'done');
                $('#accrifgar').prop('hidden', true);

            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
});

$(document).on('click', '.rifiutagar_rip', function () {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'upgaranzia',
            stato: 'RIFIUTATA',
            note: prompt('Inserisci note garanzia'),
            nscheda: nscheda
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Garanzia aggiornata con successo!', 'success', 'done');
                $('#accrifgar').prop('hidden', true);
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
});

$(document).on('click', '.completato', function () {
    changestato('Completato', $(this).attr('idr'))
});

$(document).on('click', '.lavorazione', function () {
    changestato('Lavorazione', $(this).attr('idr'))
});

$(document).on('click', '.annulla', function () {
    changestato('Annullato', $(this).attr('idr'))
});

$(document).on('click', '.sospeso', function () {
    changestato('Sospeso', $(this).attr('idr'))
});

$(document).on('click', '.pagato', function () {
    cambiopagina('officina', 'vendita-banco', '?scheda=' + $(this).attr('idr'));
});

$(document).on('click', '.usato', function () {
    changestato('Usato', $(this).attr('idr'))
    cambiopagina('officina', 'usato', '?scheda=' + $(this).attr('idr'));
});

function changestato(stato, idr) {
    bloccaui();
    $.ajax({
        url: currentURL + "assets/inc/riparazione.php",
        method: "POST", //First change type to method here
        data: {
            azione: 'upstato',
            stato: stato,
            nscheda: idr,
        },
        success: function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Stato aggiornato con successo!', 'success', 'done');
                aggiornarip()
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }

    });
}

$(document).on('click', '.addacconto_rip', function () {
    $('#aggiungiacconto').prop('hidden', false);
});

$(document).on('click', '.salvaacc_rip', function () {
    bloccaui();
    var comm = $('#commentoacconto').val();
    var acc = $('#accontocons').val();
    var dataacc = $('#dataacconto').val();

    if (comm == '' || comm == undefined) {
        showNotification('top', 'right', 'Errore, si prega di inserire il commento acconto.', 'danger', 'close');
    } else if (acc == '' || acc == undefined) {
        showNotification('top', 'right', 'Errore, si prega di inserire l\'importo dell\'acconto.', 'danger', 'close');
    } else if (dataacc == '' || dataacc == undefined) {
        showNotification('top', 'right', 'Errore, si prega di inserire la data dell\'acconto.', 'danger', 'close');
    } else {

        acc = acc.replace(',', '.');
        acc = parseFloat(acc).toFixed(2);

        $.ajax({
            url: currentURL + "assets/inc/riparazione.php",
            method: "POST", //First change type to method here
            data: {
                azione: 'addacconto',
                nscheda: nscheda,
                comm: comm,
                acc: acc,
                dataacc: dataacc
            },
            success: function (response) {
                if (response == 'no') {
                    showNotification('top', 'right', 'Errore nel caricamento, si prega di controllare i dati inseriti.', 'danger', 'close');
                } else {
                    showNotification('top', 'right', 'Acconto aggiunto con successo', 'success', 'done');
                    $('#commentoacconto').val('');
                    $('#accontocons').val('');
                    $('#dataacconto').val('');
                    $('#aggiungiacconto').prop('hidden', true);
                }
                sbloccaui();
            },
            error: function (response) {
                Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                sbloccaui();
            }
        });
    }
});

function modificanotegaranzia() {
    bloccaui();
    $.post(currentURL + "assets/inc/riparazione.php", { azione: 'upnotegar', notegar: $('#notegar').val(), nscheda: parseInt(nscheda) },
        function (data) {
            if (data == 'si') {
                showNotification('top', 'right', 'Note aggiornate con successo!', 'success', 'done');
                aggiornarip()
            } else {
                showNotification('top', 'right', 'Errore: ' + data, 'danger', 'close');
            }
            sbloccaui();
        })
}