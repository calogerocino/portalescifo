function aggiorna_cli() {
    bloccaui();
    var ricerca = $('#search').val();
    ricerca = ricerca.replace(/\s*\(.*?\)\s*/g, '');

    var idcl = parseInt(ricerca.replace(/[^0-9\.]/g, ''), 10);

    var clausola = '';

    if (ricerca != '') {
        clausola = " WHERE ID=" + idcl;
    } else {
        clausola = "";
    }

    $.ajax({
        url: currentURL + "assets/inc/listaclienti.php",
        method: "POST",
        data: {
            azione: 'aggiorna',
            clausola: clausola
        },
        success: function (data) {
            (ModalitaDebug?console.log(data):'')
            $("#tablistaclienti_cli").html(data);
            docReady(listInit);
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

$(document).on('click', '.cl_apricliente', function () {
    CaricaFrame(currentURL + 'assets/page/schedacliente.php?idcl=' + $(this).attr('idcl'), '<i class="fa-regular fa-user-gear"></i> Visualizza cliente', 'Visualizza attraverso questa scheda cliente tutte le informazioni relativi ai tuoi clienti!.', '80%')
});

$(document).on('click', '.cl_nuovocliente', function () {
    CaricaFrame(currentURL + 'assets/page/schedacliente.php?nuovo', '<i class="fa-regular fa-user-gear"></i> Crea nuovo cliente', 'Crea un nuovo cliente attraverso questa scheda inserendo tutte le informazioni necessarie!.', '80%')
});

$(document).on('click', '.cl_esporta', function () {
    window.open(currentURL + 'assets/pdf/clientinegozio.php?create_pdf=1');
});
