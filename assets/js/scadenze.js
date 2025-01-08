// stato sospeso 2 - non pagato 0 - pagato 1 - ritardo 4 - annullato 5

var clausola4 = '';

function aggiorna_sca() {
    bloccaui();
    var pagato = $('#searchtipo').val();
    var clausola2 = $('#searchpay').val();
    var clausola = '';
    var clausola3 = '';
    var datedalal = $("#DataPickerRangeScad").val();
    var ricerca = $("#search").val()

    if (datedalal == '') {
        clausola = controlladatapagamento_sca();
    } else {
        var split1 = datedalal.split(" -> ")
        var resdal = split1[0].split("/");
        var resal = split1[1].split("/");
        clausola = " AND p.Data BETWEEN '" + resdal[2] + "-" + resdal[1] + "-" + resdal[0] + "' AND '" + resal[2] + "-" + resal[1] + "-" + resal[0] + "'";
    }

    if (ricerca != '' && ricerca.length >= 3) {
        clausola3 = 'AND (p.N_Assegno Like \'%' + ricerca + '%\' OR p.Importo LIKE \'' + ricerca + '%\' OR f.Ragione_Sociale LIKE \'%' + ricerca + '%\' OR p.N_Fattura LIKE \'%' + ricerca + '%\') ';
    } else {
        clausola3 = '';
    }

    $.post(currentURL + 'assets/inc/scadenze.php', { azione: 'aggiorna', pagato: pagato, clausola: clausola, clausola2: clausola2, clausola3: clausola3, clausola4: clausola4 }, function (data) {
        var res = data.split('|-|');
        $("#BodyTabellaScadenze").html(res[0]);
        $("#FootTabellaScadenze").html(res[1]);
        docReady(listInit);
        $.post(currentURL + 'assets/inc/carica-pagamento.php', { azione: 'nassegni' }, function (data) {
            var res = data.split('|-|');
            $("#totassegni").prop("hidden", false);
            $("#bancabnl").html('<a href="javascript:void(0)" ba="19" class="cerbanca">BNL</a>: ' + res[0]);
            $("#bancaposta").html('<a href="javascript:void(0)" ba="20" class="cerbanca">Posta</a>: ' + res[1]);
            $("#bancauniaz").html('<a href="javascript:void(0)" ba="21" class="cerbanca">Unicredit Az.</a>: ' + res[2]);
            $("#bancaunipe").html('<a href="javascript:void(0)" ba="22" class="cerbanca">Unicredit Pers.</a>: ' + res[3]);
            $("#bancacred").html('<a href="javascript:void(0)" ba="49" class="cerbanca">Credem</a>: ' + res[4]);
            $("#bancaintsp").html('<a href="javascript:void(0)" ba="53" class="cerbanca">Intesa San Paolo</a>: ' + res[5]);
            sbloccaui();
        })
    })
}

$(document).on('click', '.cerbanca', function () {
    var banca = $(this).attr('ba');
    if (banca == 19) {
        if (clausola4 == ' AND (p.Banca=19) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    } else if (banca == 20) {
        if (clausola4 == ' AND (p.Banca=20) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    } else if (banca == 21) {
        if (clausola4 == ' AND (p.Banca=21) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    } else if (banca == 22) {
        if (clausola4 == ' AND (p.Banca=22) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    } else if (banca == 49) {
        if (clausola4 == ' AND (p.Banca=49) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    } else if (banca == 53) {
        if (clausola4 == ' AND (p.Banca=53) ') {
            clausola4 = '';
        } else {
            clausola4 = ' AND (p.Banca=' + banca + ') ';
        }
    }
    aggiorna_sca();
});

function controlladatapagamento_sca() {
    var fetchdata = new Date();
    var anno = fetchdata.getFullYear();
    var mese = fetchdata.getMonth() + 1;
    var mesepuno = fetchdata.getMonth() + 2;
    var mesemuno = fetchdata.getMonth();
    var radiosdate = $('#searchdate').val();


    if (radiosdate == 'tt') {
        rimdat_sca();
        return '';
    } else if (radiosdate == 'ac') {
        rimdat_sca();
        return ' AND  YEAR(p.Data) = \'' + anno + '\'';
    } else if (radiosdate == 'mc') {
        rimdat_sca();
        return ' AND YEAR(p.Data) = \'' + anno + '\' AND MONTH(p.Data) = \'' + mese + '\'';
    } else if (radiosdate == 'mp') {
        rimdat_sca();
        if (mese == 12) {
            mesepuno = '01';
            anno = fetchdata.getFullYear() + 1;
        }
        return ' AND YEAR(p.Data) = \'' + anno + '\' AND MONTH(p.Data) = \'' + mesepuno + '\'';
    } else if (radiosdate == 'mpr') {
        rimdat_sca();
        return ' AND YEAR(p.Data) = \'' + anno + '\' AND MONTH(p.Data) = \'' + mesemuno + '\'';
    } else if (radiosdate == 'da') (
        addform_sca()
    )

}

function addform_sca() {
    $('#dalalricerca').prop('hidden', false)
}

function rimdat_sca() {
    $('#dalalricerca').prop('hidden', true)
}


$(document).on('click', '.esporta_sca', function () {
    window.open(currentURL + 'assets/pdf/scadenziario.php?create_pdf=1')
});

$(document).on('click', '.apripag_sca', function () {
    window.open(currentURL + 'contabilita/carica.php?id=' + $(this).attr("id"), 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location')
    // CaricaFrame(currentURL + 'contabilita/carica.php?id=' + $(this).attr("id"), '<i class="fa-regular fa-money-bill-1-wave"></i> Visualizza scadenza', 'Visualizza e gestisci una scadenza/pagamento di un fornitore!', '60%')
});

$(document).on('click', '.nuovopag_sca', function () {
    window.open(currentURL + 'contabilita/carica.php?', 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location')
    // CaricaFrame(currentURL + 'contabilita/carica.php?', '<i class="fa-regular fa-money-bill-1-wave"></i> Carica scadenza', 'Crea una scadenza/pagamento di un fornitore!', '60%')
});

$(document).on('click', '.aprifornitore_sca', function () {
    CaricaFrame(currentURL + 'assets/page/schedafornitore.php?idfor=' + $(this).attr("idfor"), '<i class="fa-regular fa-user-gear"></i> Visualizza fornitore', 'Visualizza attraverso questa scheda tutte le informazioni relative ai tuoi fornitori', '80%')
});

$(document).on('click', '.massimali_sca', function () {
    bloccaui();
    $.post(currentURL + 'assets/inc/carica-pagamento.php', { azione: 'massimali' }, function (data) {
        $('#massimalitab').modal('show');
        $('#inserisciquidentro').html(data);
        sbloccaui();
    })
});

function cambiamass(mese) {
    var massimo = prompt("Inserisci il nuovo massimale", "0.00");
    if (massimo == null || massimo == "") {
        // NON FARE NULLA
    } else {
        massimo = massimo.replace(',', '.');
        $.post(currentURL + 'assets/inc/carica-pagamento.php', { azione: 'cambiamese', mese: mese, massimo: massimo }, function (data) {
            if (data == 'si') {
                Toast.fire({ icon: 'success', title: 'Eseguito con successo' })
            } else {
                Toast.fire({ icon: 'error', title: 'Errore: ' + data })
            }
        })
    }
}

$(document).on('click', '.pagato_sca', function () {
    bloccaui();
    var id = $(this).attr("id");
    $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'daticheservono', id: id }, function (data) {
        var res = data.split('|-|');
        $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'stato', id: id, stato: '1' }, function (data) {
            if (data == 'si') {
                $.post(currentURL + "assets/inc/fatture.php", { azione: 'addacconto', IDfo: res[3], comm: res[0], acc: res[1], dataacc: res[2], idpag: id }, function (response) {
                    if (response == 'si') {
                        Toast.fire({ icon: 'success', title: 'Pagamento saldato con successo' })
                        sbloccaui();
                        aggiorna_sca();
                    } else {
                        Toast.fire({ icon: 'error', title: 'Impossibile modificare lo stato. Errore:' + response })
                        sbloccaui();
                    }
                });
            } else {
                Toast.fire({ icon: 'error', title: 'Errore:' + data })
                sbloccaui();
            }
        });
    });
});

$(document).on('click', '.sospeso', function () {
    bloccaui();
    var id = $(this).attr("id");

    $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'stato', id: id, stato: '2' }, function (data) {
        if (data == 'si') {
            Toast.fire({ icon: 'success', title: 'Stato pagamento aggiornato in \'Sospeso\'' })
            aggiorna_sca()
        } else {
            Toast.fire({ icon: 'error', title: 'Impossibile modificare lo stato. Errore:' + data })
        }
        sbloccaui();
    });
});

$(document).on('click', '.nonpagato', function () {
    bloccaui();
    var id = $(this).attr("id");

    $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'stato', id: id, stato: '0' }, function (data) {
        if (data == 'si') {
            Toast.fire({ icon: 'success', title: 'Stato pagamento aggiornato in \'Non pagato\'' })
            aggiorna_sca()
        } else {
            Toast.fire({ icon: 'error', title: 'Impossibile modificare lo stato. Errore:' + data })
        }
        sbloccaui();
    });
});

$(document).on('click', '.annullapag', function () {
    bloccaui();
    var id = $(this).attr("id");

    $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'stato', id: id, stato: '5' }, function (data) {
        if (data == 'si') {
            Toast.fire({ icon: 'success', title: 'Stato pagamento aggiornato in \'Annullato\'' })
            aggiorna_sca()
        } else {
            Toast.fire({ icon: 'error', title: 'Impossibile modificare lo stato. Errore:' + data })
        }
        sbloccaui();
    });
});

$(document).on('click', '.eliminapag', function () {
    bloccaui();
    var id = $(this).attr("id");

    $.post(currentURL + "assets/inc/carica-pagamento.php", { azione: 'elimina', id: id }, function (data) {
        if (data == 'si') {
            Toast.fire({ icon: 'success', title: 'Pagamento eliminato con successo' })
            aggiorna_sca()
        } else {
            Toast.fire({ icon: 'error', title: 'Errore durante l\'eliminazione del pagamento. Errore:' + data })
        }
        sbloccaui();
    });
});
