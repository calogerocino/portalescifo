// STATI DOCUMENTI: 1 PAGATO - 2 SCADENZE - 3 VUOTO - 4 FATTURATO
var fatturasino = 'no';
var totcheckfattu = 0;
var caricato = false; //funzione per salvataggio nfatt ag

function AggiornaDocumenti_do() {
    bloccaui();
    var pagato = $('#searchtipo').val();
    var doc = $('#searchdoc').val();
    var clausola = '';
    var clausola2 = '';
    var fatturazione = '';
    var datedalal = $("#DataPickerRange").val();

    var matches = $("input#search").val().match(/\[(.*?)\]/)

    if (datedalal == '') {
        clausola = DataDocumentoCheck_dc();
    } else {
        var split1 = datedalal.split(" -> ")
        var resdal = split1[0].split("/");
        var resal = split1[1].split("/");
        clausola = " AND Data BETWEEN '" + resdal[2] + "-" + resdal[1] + "-" + resdal[0] + "' AND '" + resal[2] + "-" + resal[1] + "-" + resal[0] + "'";
    }

    if (matches != null) {
        if (matches[1] != '' && matches[1].length >= 1) {
            clausola2 = ' AND (IDcl=' + matches[1] + ') ';
        } else {
            clausola2 = '';
        }
    }
    if (doc == 1) {
        doc = ' AND N_Doc LIKE \'BC/%\'';
    } else if (doc == 2) {
        doc = ' AND N_Doc LIKE \'FA/%\'';
    } else if (doc == 3) {
        doc = ' AND N_Doc LIKE \'DDT/%\'';
    } else if (doc == 4) {
        doc = ' AND N_Doc LIKE \'BC/%\' OR N_Doc LIKE \'FA/%\' OR N_Doc LIKE \'DDT/%\'';
    } else {
        doc = '';
    }

    $.ajax({
        url: currentURL + "assets/inc/documenti.php",
        method: "POST",
        data: {
            azione: 'aggiorna',
            pagato: pagato,
            clausola: clausola,
            clausola2: clausola2,
            fatturazione: fatturasino,
            doc: doc
        },
        success: function (data) {
            var res = data.split('|-|')
            $("#tabelladoc").html(res[0]);
            docReady(listInit)
            totcheckfattu = res[1];
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

function DataDocumentoCheck_dc() {
    var fetchdata = new Date();
    var anno = fetchdata.getFullYear();
    var mese = fetchdata.getMonth() + 1;
    var mesemuno = fetchdata.getMonth();
    var mesepuno = fetchdata.getMonth() + 2;
    var radiosdate = $('#searchdate').val();

    if (radiosdate == 'tt') {
        RimuoviForm_dc();
        return '';
    } else if (radiosdate == 'ac') {
        RimuoviForm_dc();
        return ' AND  YEAR(Data) = \'' + anno + '\'';
    } else if (radiosdate == 'mc') {
        RimuoviForm_dc();
        return ' AND YEAR(Data) = \'' + anno + '\' AND MONTH(Data) = \'' + mese + '\'';
    } else if (radiosdate == 'ms') {
        RimuoviForm_dc();
        return ' AND YEAR(Data) = \'' + anno + '\' AND MONTH(Data) = \'' + mesepuno + '\'';
    } else if (radiosdate == 'mp') {
        RimuoviForm_dc();
        return ' AND YEAR(Data) = \'' + anno + '\' AND MONTH(Data) = \'' + mesemuno + '\'';
    } else if (radiosdate == 'da') (
        AggiungiForm_dc()
    )
}

function AggiungiForm_dc() {
    $('#dalalricerca').prop('hidden', false)
}

function RimuoviForm_dc() {
    $('#dalalricerca').prop('hidden', true)
}

$(document).on('click', '.ApriDocumento_dc', function () {
    CaricaFrame(currentURL + 'negozio/documento.php?iddoc=' + $(this).attr('id'), '<i class="fa-regular fa-cash-register"></i> Visualizza documento', 'Visualizza o modifica un documento cliente, creato precedentemente!', '80%')
});

$(document).on('click', '.NuovoDocumento_dc', function () {
    CaricaFrame(currentURL + 'negozio/documento.php?nuovo', '<i class="fa-regular fa-cash-register"></i> Crea documento', 'Crea un novo documento cliente, inserisci correttamente tutti i campi richiesti.', '80%')
});

$(document).on('click', '.ApriCliente_dc', function () {
    CaricaFrame(currentURL + 'assets/page/schedacliente.php?idcl=' + $(this).attr('id'), '<i class="fa-regular fa-user-gear"></i> Visualizza cliente', 'Visualizza attraverso questa scheda cliente tutte le informazioni relative ai tuoi clienti!.', '80%')
});


//FATTURAZIONE BUONI
$(document).on('click', '.FatturaBuoni_dc', function () {
    var buonisel = '';
    var oldidcl = [];
    if (fatturasino == 'si') {
        for (let i = 0; i <= totcheckfattu; i++) {
            if ($('#fattcheck' + i).is(':checked')) {
                oldidcl.push($('#fattcheck' + i).attr('idcl'));
                buonisel = buonisel + '|-|' + $('#fattcheck' + i).val();
            }
        }
        if (buonisel.length <= 4) {
            fatturasino = 'no';
            AggiornaDocumenti_do();
        } else {
            oldidcl.forEach(function (entry) {
                if (entry == oldidcl[0]) {
                    checkcl = 0;
                }
                else {
                    checkcl = 1;
                }
            });

            if (checkcl == 0) {
                $.post(currentURL + 'assets/inc/documenti.php', { azione: 'genfattura', buonisel: buonisel }, function (response) {
                    if (response.lenght <= 10) {
                        Toast.fire({ icon: 'success', title: 'Fattura creata correttamente. N° Fattura: ' + response })
                    } else {
                        Toast.fire({ icon: 'error', title: 'Errore: ' + response })
                    }
                })

            } else {
                Toast.fire({ icon: 'warning', title: 'Devi selezionare buoni con lo stesso cliente' })
            }
        }
    } else if (fatturasino == 'no') {
        fatturasino = 'si';
        AggiornaDocumenti_do();
    }
});

function CambiaNFattura_dc() {
    if (caricato == true) {
        $.post(currentURL + 'assets/inc/documenti.php', { azione: 'modificafatt', ndoc: $('#ndoc').val(), nfatt: $('#nfattag').val() }, function (response) {
            if (response == 'si') {
                Toast.fire({ icon: 'success', title: 'N° Fattura modificato con successo' })
            } else {
                Toast.fire({ icon: 'error', title: 'Errore:' + response })
            }
        })
    }
}
// DOCUMENTO
var doc_prog_rg = 1;
var doc_progpr = 0;

function daysInMonth(iMonth, iYear) {
    return new Date(iYear, iMonth, 0).getDate();
}

$(document).on('click', '.AggiungiRigaScadenze_doc', function () {
    var today = new Date();
    var yyyy = today.getFullYear();

    doc_prog_rg = (doc_prog_rg + 1);
    var mm2 = String(today.getMonth() + doc_prog_rg).padStart(2, '0'); //January è 0!
    $('#scadrate-list').append('<tr><th class="ps-0">' + doc_prog_rg + '^ Scadenza</th><th class="pe-0 text-end"><input id="imp_' + doc_prog_rg + '" type="text" class="form-control form-control-sm mb-2" style="width: 100%" placeholder="Importo scadenza"><input id="scad_' + doc_prog_rg + '" type="date" class="form-control form-control-sm " style="width: 100%" placeholder="Data Documento" value="' + yyyy + '-' + mm2 + '-' + daysInMonth(mm2, yyyy) + '"></th></tr>')
});

$(document).on('click', '.AggiungiProdottoManuale_doc', function () {
    doc_progpr = doc_progpr + 1;

    var tr = '<div class="row gx-card mx-0 align-items-center border-bottom border-200" id="idr' + doc_progpr + '"> <div class="col-5 py-3"> <div class="d-flex align-items-center"><a href="javascript:void(0)"><img class="img-fluid rounded-1 me-3 d-none d-md-block" src="https://portalescifo.it/upload/image/p/noimg.png" alt="" width="128px" height="auto"></a> <div class="flex-1"> <h5 class="fs-0"><a class="text-900" href="javascript:void(0)" id="cart_ds_' + doc_progpr + '" idprn="0" contenteditable>Inserisci descrizione</a></h5> <div class="fs--2 fs-md--1"><a class="text-danger" href="javascript:void(0)" onclick="EliminaRiga_bn(' + doc_progpr + ')">Rimuovi</a></div></div></div></div><div class="col-7 py-3"> <div class="row align-items-center"> <div class="col-2 ps-0 mb-2 mb-md-0 text-600" contenteditable="" id="cartcont_pr_' + doc_progpr + '">0,00</div><div class="col-3 d-flex justify-content-end justify-content-md-center"> <div> <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="minus" id="cartcont_minus_' + doc_progpr + '" onclick="cartCont_docRitardato(this)">-</button><input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="1" aria-label="Quantita" style="width: 50px" id="cartcont_qt_' + doc_progpr + '" onchange="cartCont_doc(this);"><button class="btn btn-sm btn-outline-secondary border-300 px-2" data-type="plus" id="cartcont_plus_' + doc_progpr + '" onclick="cartCont_docRitardato(this)(this);">+</button></div></div></div><div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" id="cartcont_iva_' + doc_progpr + '">22</div><div class="col-2 ps-0 mb-2 mb-md-0 text-center text-600" contenteditable="" id="cartcont_rs_' + doc_progpr + '" title="0">0</div><div class="col-3 text-end ps-0 mb-2 mb-md-0 text-600" id="doc_tot' + doc_progpr + '">0,00</div><div id="doc_idpr' + doc_progpr + '" hidden></div></div></div></div>'
    if ($('#doc_contcart').attr("att") == 1) {
        $('#doc_contcart2').append(tr);
        FDoc_CalcolaTotale();
        EventKeyUp_doc();
        docReady(quantityInit);
    } else if ($('#doc_contcart').attr("att") == 0) {
        $('#doc_contcart').html('<table class="table" style=""><tbody id="doc_contcart2">' + tr + '</tbody></table>');
        $('#doc_contcart').attr("att", 1)
        FDoc_CalcolaTotale();
        EventKeyUp_doc();
        docReady(quantityInit);
    }
});