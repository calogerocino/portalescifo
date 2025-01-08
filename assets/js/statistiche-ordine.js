function aggiornastatsord() {
    bloccaui();
    var clausola1 = '';
    var clausola2 = '';
    var ricerca = $('#searchcode').val();

    if (ricerca.length == 4 && !isNaN(ricerca)) {
        clausola1 = " AND (Ordine.DataOrdine LIKE '%" + ricerca + "')";
        clausola2 = " AND (Ordine.DataOrdine LIKE '%" + ricerca + "')";
    } else if (ricerca.length >= 1) {
        clausola1 = " AND (Prodottin.NomeProdotto LIKE '%" + ricerca + "%' OR Prodottin.CodiceProdotto LIKE '%" + ricerca + "%')";
        clausola2 = " AND (Prodotti.NomeProdotto LIKE '%" + ricerca + "%' OR Prodotti.CodiceProdotto LIKE '%" + ricerca + "%')";
    } else {
        clausola1 = "";
        clausola2 = "";
    }


    $.ajax({
        url: currentURL + "assets/inc/statistiche-ordine.php",
        method: "POST",
        data: {
            azione: 'aggiorna',
            clausola1: clausola1,
            clausola2: clausola2
        },
        success: function (data) {
            $("#tabellaprodotti").html(data);
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

$(document).on('click', '.stampa', function () {
    cambiopagina('assets/pdf', 'stats-ordine', '?create_pdf=1');
});

$(document).on('click', '.cercainevasi', function () {
    $.post(currentURL + "assets/inc/statistiche-ordine.php", { azione: 'cercainevasi', cosa: $(this).attr('id') }, function (response) {
        if (response == 'ok') {
            showNotification('top', 'right', 'Evaso con successo', 'success', 'done');
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    })
});