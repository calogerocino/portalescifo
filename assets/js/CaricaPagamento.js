$(document).ready(function () {
    CheckNO_cp();
    $('div#formvisualizza').hide();
});

function CheckOK_cp() {
    $("button#carica").prop("disabled", false);
    $("button#controllo").prop("disabled", true);
    $("input#importo").prop("readonly", true);
    $("input#date").prop("readonly", true);
}

function CheckNO_cp() {
    $("button#carica").prop("disabled", true);
    $("button#controllo").prop("disabled", false);
    $("input#importo").prop("readonly", false);
    $("input#date").prop("readonly", false);
}

function CaricaPagamento() {
    bloccaui();
    var idpag = $('#idpag').val();

    $.ajax({
        url: currentURL + "assets/inc/carica-pagamento.php",
        method: "POST",
        data: {
            azione: 'cerca',
            idpag: idpag

        },
        success: function (data) {
            var res = data.split('|-|');
            $('#date').val(res[0]);
            $('#importo').val(res[1]);
            $('#banca option[value=' + res[2] + ']').prop('selected', true);
            $('#pagamento option[value=' + res[3] + ']').prop('selected', true);
            $('#nassegno').val(res[4]);
            $('#nfattura').val(res[5]);
            $('#intestatario option[value=' + res[6] + ']').prop('selected', true);
            $('#notepag').val(res[7]);
            $('#pag_nomefile').val(res[8]);
            $('#tabellapagamenti').html(res[9]);

            $("#banca").prop("disabled", true);
            $("#date").prop("disabled", false);
            $("#importo").prop("disabled", false);
            $("#intestatario").prop("disabled", true);
            $("#nassegno").prop("disabled", false);
            $("#pagamento").prop("disabled", true);
            $("#nfattura").prop("disabled", false);
            $("#notepag").prop("disabled", false);

            var allegato = $('#pag_nomefile').val();

            if (allegato != '') {
                $('div#formupload').hide();
                $('div#formvisualizza').show();
            } else {
                $('div#formvisualizza').hide();
                $('div#formupload').show();
            }
            sbloccaui();
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
            sbloccaui();
        }
    });
}

$(document).on('click', '.ControllaPagamento', function () {
    bloccaui();
    var date = $('#date').val();
    var datscad = date.split('-');
    var importo = $('input#importo').val();
    importo = importo.replace(',', '.')
    $('input#importo').val(importo);
    $.ajax({
        url: currentURL + "assets/inc/carica-pagamento.php",
        method: "POST",
        data: {
            azione: 'controllo',
            datascad: datscad[1],
            importo: importo
        },
        success: function (data) {
            if (data == 'ok') {
                CheckOK_cp();
                Toast.fire({ icon: 'success', title: 'Puoi proseguire con l\'inserimento del pagamento!' })
            } else if (data == 'no') {
                if ($('#pagamento').val() == '25') {
                    ajobunucchiu(datscad[1]);
                    CheckNO_cp();
                    Toast.fire({ icon: 'error', title: 'Non puoi procedere con la richiesta, hai superato il massimale mensile selezionato!' })
                } else {
                    CheckOK_cp();
                    Toast.fire({ icon: 'success', title: 'Puoi proseguire con l\'inserimento del pagamento!' })
                }
            } else {
                CheckNO_cp();
                Toast.fire({ icon: 'error', title: 'Non puoi procedere con la richiesta, c\'è un errore nei dati! Errore: ' + data })
            }
            sbloccaui();
        },
        error: function (e) {
            Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta: ' + e })
            sbloccaui();
        }
    });
});

$(document).on('click', '.carica', function () {
    bloccaui();
    var cosa = $('#carica').attr('cosa')
    if (cosa == 'aggiorna') {
        var datscad = $('#date').val();
        var nassegno = $('#nassegno').val();
        var nfattura = $('#nfattura').val();
        var note = $('#notepag').val();
        var idpag = $('#idpag').val();
        var allegato = $('#pag_nomefile').val();
        var importo = $('#importo').val();
        importo = importo.replace(",", ".");

        $.ajax({
            url: currentURL + "assets/inc/carica-pagamento.php",
            method: "POST",
            data: {
                azione: 'aggiorna',
                idpag: idpag,
                datascadenza: datscad,
                importo: importo,
                nassegno: nassegno,
                nfattura: nfattura,
                note: note,
                allegato: allegato,
            },
            success: function (data) {
                if (data == 'si') {
                    alert('Pagamento aggiornato con successo!');
                    window.close()
                } else {
                    Toast.fire({ icon: 'error', title: 'Il pagamento non è stato aggiornato! Errore: ' + data })
                    CheckNO_cp()
                }
                sbloccaui();
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                sbloccaui();
            }
        });
    } else if (cosa == 'carica') {
        var datscad = $('#date').val();

        var importo = $('#importo').val();
        importo = importo.replace(",", ".");
        var banca = $('#banca').val();
        var pagamento = $('#pagamento').val();
        var nassegno = $('#nassegno').val();
        var nfattura = $('#nfattura').val();
        var intestatario = $('#intestatario').val();
        var note = $('#notepag').val();
        var allegato = $('#pag_nomefile').val();

        $.ajax({
            url: currentURL + "assets/inc/carica-pagamento.php",
            method: "POST",
            data: {
                azione: 'carica',
                datascadenza: datscad,
                importo: importo,
                banca: banca,
                pagamento: pagamento,
                nassegno: nassegno,
                nfattura: nfattura,
                intestatario: intestatario,
                note: note,
                allegato: allegato
            },
            success: function (data) {
                if (data == 'si') {
                    Toast.fire({ icon: 'success', title: 'Pagamento caricato con successo!' })
                } else {
                    Toast.fire({ icon: 'error', title: 'Il pagamento non è stato caricato! Errore: ' + data })
                }
                sbloccaui();
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Errore nel gestire la richiesta' })
                sbloccaui();
            }
        });

        $("#btnSubmit").prop("disabled", true);
        $("#userfile").prop("disabled", true);
        $('#date').val('');
        $('#importo').val('');
        $('#nassegno').val('');
        $('#nfattura').val('');
        $('#notepag').val('');
        $('#pag_nomefile').val('');
        CheckNO_cp();
    }
});

$(document).on('click', '.VisualizzaAllegato_cp', function () {
    var filename = $('#pag_nomefile').val();
    window.open(currentURL + "upload/assegni/" + filename, "_blank", 'Visualizza', 'width=800, height=800, resizable, status, scrollbars=1, location');
});

$(document).on('click', '.CaricaAllegato_cp', async function () {
    //CARICA FILE
    const { value: file } = await Swal.fire({
        title: 'Seleziona file',
        input: 'file',
        enctype: 'multipart/form-data',
        inputAttributes: {
            'accept': 'image/*, application/pdf',
            'aria-label': 'Carica il file'
        }
    })

    if (file) {
        var data = new FormData();
        data.append("userfile", file);
        data.append('cartella', 'assegni');
        NotificaCaricamento();
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: currentURL + "upload/carica-allegato.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', progress, false);
                }
                return myXhr;
            },
            success: function (data) {
                if (data == 'fileno') {
                    Swal.fire({ icon: 'error', title: 'File non esistente o sconosciuto' })
                } else if (data.includes('carno')) {
                    var r = data.split(';')
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore: ' + r[1]
                    })
                } else {
                    $('div#formupload').hide();
                    $('div#formvisualizza').show();
                    $("#pag_nomefile").val(data);
                    if ($('#idpag').val() != '') {
                        $.post(currentURL + 'assets/inc/carica-pagamento.php', { azione: 'modall', allegato: $("#pag_nomefile").val(), idpag: $('#idpag').val() }, function (r) {
                            (r == 'ok' ? Swal.fire({ icon: 'success', title: 'File caricato correttamente!' }) : Swal.fire({ icon: 'error', title: 'Errore: ' + r }))
                        })
                    } else {
                        Swal.fire({ icon: 'success', title: 'File caricato correttamente!' })
                    }
                }
            }
        });
        e.preventDefault();
    }
});

function ajobunucchiu(mese) {
    var dati = new FormData();
    // data.append("iduser", iduser);
    dati.append("mittente", 'noreply');
    dati.append("indirizzodest", 'amministrazione@scifostore.com');
    dati.append("oggetto", '[PXS] Massimale mensile superato');
    dati.append("corpo", 'Ajo, è stato raggiunto il massimale mensile del mese di ' + mese);

    $.post(currentURL + "assets/mail/invio_mail.php", { mittente: 'noreply', indirizzodest: 'amministrazione@scifostore.com', oggetto: 'Massimale mensile superato', corpo: 'Ajo, &#233; stato raggiunto il massimale mensile. <br />Mese: ' + mese + '<br />Importo: ' + $('#importo').val() + '<br />Intestatrio: ' + $("#intestatario").children("option").filter(":selected").text() }, function (response) {

    })
}

function progress(e) {
    if (e.lengthComputable) {
        var max = e.total;
        var current = e.loaded;
        var Percentage = (current * 100) / max;
        $("#pr_perccar").css("width", Percentage + '%');
        $("#pr_perccar").attr("aria-valuenow", Percentage);
        $("#pr_perccar").html(parseInt(Percentage) + ' %');
    }
}

function NotificaCaricamento() {
    Swal.fire({
        title: 'Caricamento file',
        html: '<div class="progress mb-3"><div id="pr_perccar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>',
        showConfirmButton: false,
        allowOutsideClick: false
    });
}

$(document).on('click', '.eliminapaga', function () {
    idpagfg = $(this).attr('id')
    $.post(currentURL + "/assets/inc/carica-pagamento.php", { azione: 'eliminarigapagamento', id: idpagfg }, function (r) {
        (r == 'si' ? Swal.fire({ icon: 'success', title: 'Eliminato correttamente!' }) : Swal.fire({ icon: 'error', title: 'Errore: ' + r }))
        CaricaPagamento();
    })
})

