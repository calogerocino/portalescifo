var avvio = 0;

function aggiornafeedback() {
    bloccaui();

    if (avvio != 0) {
        var b = {
            pagina: $('.pagination .active').attr('id'),
            s_ricerca: $('#feed_searchcode').val(),
            s_stato: $('#feed_searchstato option:selected').val()
        };
        app_localStorage('feed', b, 'save')
    }

    var obj = app_localStorage('feed', '', 'load');
    var clausolafeed = '';
    var clausolafeed2 = '';

    $('#feed_searchcode').val(obj.s_ricerca);
    $('#feed_searchstato option:selected').val(obj.s_stato);


    clausolafeed = $('#feed_searchstato option:selected').val();
    clausolafeed2 = $('#feed_searchcode').val();

    if (clausolafeed2 != '') {
        clausolafeed2 = ' AND c.Cliente LIKE \'%' + clausolafeed2 + '%\'';
    } else {
        clausolafeed2 = '';
    }

    clausolafeed = " WHERE o.Stfeed = " + clausolafeed + " AND DataEvasione != 'Non Evaso'" + clausolafeed2;

    $.ajax({
        url: currentURL + "assets/inc/feedback.php",
        method: "POST",
        data: {
            azione: 'aggiornalista',
            clausolafeed: clausolafeed
        },
        success: function (data) {
            $("#tabellafeedback").html(data);
            docReady(listInit)
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
    avvio = 1;
}

$(document).on('click', '.page-link', function () {
    var b = {
        pagina: $('.pager .active').attr('id'),
        s_ricerca: $('#feed_searchcode').val(),
        s_stato: $('#feed_searchstato option:selected').val()
    };
    app_localStorage('feed', b, 'save')
});


function statofeed(idstato, idordine) {
    bloccaui();
    $.post(currentURL + "assets/inc/feedback.php", { azione: 'cambiastato', Stfeed: idstato, idordine: idordine },
        function (response) {
            if (response == 'ok') {
                showNotification('top', 'right', 'Aggiornato con successo', 'success', 'done');
                aggiornafeedback();
                sbloccaui();
            } else {
                Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                sbloccaui();
            }
        });
}



