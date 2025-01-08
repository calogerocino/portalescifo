var clausola = '';

function AggiornaCatalogo() {
    bloccaui();


    if (avvio != 0) {
        var c = {
            pagina: $('.pagination .active').attr('data-i'),
            c_descr: $('#ctn_s_descr').val(),
            c_ref: $('#ctn_s_ref').val(),
            c_quantpr: $('#ctn_s_quantpr').val(),
            c_stato: $('#ctn_s_stato').val(),
            c_selmag: ControllaMagazzinoSelezionato()
        };
        app_localStorage('cat', c, 'save')
    }

    var obj_c = app_localStorage('cat', '', 'load');
    if (obj_c.c_descr != '' || obj_c.c_ref != '' || obj_c.c_quantpr != '') {
        $('#ctn_s_descr').val(obj_c.c_descr);
        $('#ctn_s_ref').val(obj_c.c_ref);
        $('#ctn_s_quantpr').val(obj_c.c_quantpr);
        $('#ctn_s_stato').val(obj_c.c_stato);
        $("input[name=toggle_option][value=" + obj_c.c_selmag + "]").attr('checked', 'checked');
    }



    clausola = ''
    var ctn_s_descr = $('#ctn_s_descr').val();
    var ctn_s_ref = $('#ctn_s_ref').val();
    var ctn_s_quantpr = $('#ctn_s_quantpr').val();
    var ctn_s_stato = $('#ctn_s_stato').val();
    var ctn_s_mag = ControllaMagazzinoSelezionato();
    var sql;

    if (ctn_s_ref != '') {
        clausola = clausola + "(sku LIKE '%" + ctn_s_ref + "%' OR ean='" + ctn_s_ref + "' OR fornitori LIKE '%" + ctn_s_ref + "%') AND ";
    }
    if (ctn_s_quantpr != '') {
        clausola = clausola + "PrestashopDisponibilita=" + ctn_s_quantpr + " AND ";
    }
    if (ctn_s_stato != '-' && ctn_s_stato != null) {
        clausola = clausola + "stato=" + ctn_s_stato + " AND ";
    }

    if (ctn_s_mag != '0') {
        clausola = clausola + "tipo=" + ctn_s_mag + " AND ";
    }

    var mclausola = '';
    clausola = clausola + "1";
    if (ctn_s_descr != '') {
        if (clausola.length <= 2) {
            clausola = ''
        } else {
            clausola = 'AND ' + clausola

        }

        var blabla = '';
        var res_descr = ctn_s_descr.split(' ')
        for (const val of res_descr) {
            if (!isNaN(parseFloat(val))) {// contiene numeri
                blabla += ' ' + val + ' '
            } else if (val.includes('"')) { //contiene il carattere "
                blabla += '+' + val.replace('"', '\"') + ' '
            } else if (val.length <= 3) {
                blabla = blabla
            } else {
                blabla += '+' + val + '* '
            }

            mclausola += " AND (nome LIKE '%" + val + "%' OR tag LIKE '%" + val + "%') "
        }


        sql = "SELECT *, MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE) as score FROM neg_magazzino WHERE MATCH(nome, tag) AGAINST('" + blabla + "' IN BOOLEAN MODE)>0.001 " + mclausola + clausola + " AND Mostra=1 ORDER BY score DESC LIMIT 100"
    } else {

        if (clausola.length <= 2) {
            clausola = ''
            if (ctn_s_mag == '1') {
                clausola += "(nm.sku LIKE 'RC%' OR nm.sku LIKE 'RO%') ";
            } else {
                clausola += "nm.sku LIKE 'N0%' ";
            }
            var ss = new Date();

            spdata = (ss.getMonth() + 2) == 13 ? (ss.getFullYear()) + "-01-" + ss.getDate() : (ss.getFullYear() - 1) + "-" + (ss.getMonth() + 2) + "-" + ss.getDate()
            databetw = "'" + (ss.getFullYear() - 1) + "-" + ss.getMonth() + "-" + ss.getDate() + "' AND '" + spdata + "'"
            sql = "SELECT nm.*, SUM(nr.quantita) AS Totquant FROM neg_magazzino nm INNER JOIN (neg_relpo nr INNER JOIN donl_ordini dor ON (nr.IDO=dor.ID)) ON (nm.ID=nr.IDP) WHERE " + clausola + " AND Mostra=1 AND dor.DataOrdine BETWEEN " + databetw + " GROUP BY nr.IDP ORDER BY dor.DataOrdine, Totquant ASC LIMIT 100"
        } else {
            clausola += "1";
            sql = "SELECT * FROM neg_magazzino WHERE " + clausola + " AND Mostra=1 ORDER BY NOME ASC LIMIT 100"
        }

    }

    $.post(currentURL + 'assets/inc/catalogo-neg.php', { azione: 'aggiorna', sql: sql }, function (response) {
        $('#cnm_tp').html(response);
        docReady(listInit);
        avvio = 1;
        eventkeyup_mag();
        sbloccaui();
    });
}

function ControllaMagazzinoSelezionato() {
    var radiostype = document.getElementsByName('toggle_option');
    for (var i = 0, length = radiostype.length; i < length; i++) {
        if (radiostype[i].checked) {
            return radiostype[i].value;
        }
    }
}

$(document).on('click', '.StampaZebraNegozio', function () {
    $.post(currentURL + 'assets/inc/catalogo-neg.php', { azione: 'infostampa2', idpr: $(this).attr('id') }, async function (response) {
        var res = response.split(';');
        const { value: formValues } = await Swal.fire({
            title: 'Stampa etichetta',
            html:
                '<h5>' + res[0] + '</h5>' +
                '<input type="text" class="form-control form-control-sm mb-2" placeholder="Testo 1" id="test1_zbpn">' +
                '<input type="text" class="form-control form-control-sm mb-2" value="' + number_format(res[3], 2, ',', '.') + '" placeholder="Prezzo" id="prezz_zbpn">' +
                '<input type="text" class="form-control form-control-sm mb-2" value="' + res[1] + '" readonly>' +
                '<input type="text" class="form-control form-control-sm mb-2" value="' + res[2] + '" readonly>' +
                '<input type="number" class="form-control form-control-sm" id="quant_zbpn" placeholder="Quantita da stampare" value="1">',
            focusConfirm: false,
            preConfirm: () => {
                return [
                    document.getElementById('test1_zbpn').value,
                    document.getElementById('prezz_zbpn').value,
                    document.getElementById('quant_zbpn').value
                ]
            }
        })
        if (formValues) {
            stampazebra(res[2], formValues[1], formValues[0], res[1], 1, formValues[2])
        }
    });
});

$(document).on('click', '.StampaZebraOfficina', function () {
    $.post(currentURL + 'assets/inc/catalogo-neg.php', { azione: 'infostampa2', idpr: $(this).attr('id') }, async function (response) {
        var res = response.split(';');
        const { value: formValues } = await Swal.fire({
            title: 'Stampa etichetta',
            html:
                '<h5>' + res[0] + '</h5>' +
                '<input type="text" class="form-control form-control-sm mb-2" placeholder="Testo 1" id="test1_zbp">' +
                '<input type="text" class="form-control form-control-sm mb-2" placeholder="Testo 2" id="test2_zbp">' +
                '<input type="text" class="form-control form-control-sm mb-2" value="' + res[1] + '" readonly>' +
                '<input type="text" class="form-control form-control-sm mb-2" value="' + res[2] + '" readonly>' +
                '<input type="number" class="form-control form-control-sm" id="quant_zbpn" placeholder="Quantita da stampare" value="1">',
            focusConfirm: false,
            preConfirm: () => {
                return [
                    document.getElementById('test1_zbp').value,
                    document.getElementById('test2_zbp').value,
                    document.getElementById('quant_zbpn').value
                ]
            }
        })
        if (formValues) {
            stampazebra(res[2], res[1], formValues[0], formValues[1], 0, formValues[2])
        }
    });
});

function ApriProdotto_ca(id) {
    CaricaFrame(currentURL + 'assets/page/schedaprodotton.php?idpr=' + id, 'Dettagli prodotto', 'Visualizza e modifica e dettagli dei prodotti caricati in magazzino', '65%')
};

function modst(st, id) {
    $.post(currentURL + "assets/inc/prestashop-control.php", { azione: 'attdisatt', st: st, id: id }, function (response) {
        Toast.fire({ icon: 'success', title: 'Stato aggiornato correttamente!' })
    })
}

function eventkeyup_mag() {
    $('[id^=cquant_]').keyup(function () {
        if (typeof cartTimeout != 'undefined')
            window.clearTimeout(cartTimeout);
        element = $(this);
        cartTimeout = window.setTimeout(function () {
            cartCont_mag(element)
        }, 500);
    })
}

function cartCont_mag(element) {
    id_riga = element.attr('id');
    id_riga = $(this)[0].id_riga.split('_');

    if (id_riga[2] == 'disponibilita') {
        quant = $('#' + id_riga[0] + '_' + id_riga[1] + '_' + id_riga[2]).text()
    } else if (id_riga[2] == 'PrestashopDisponibilita') {
        quant = $('#' + id_riga[0] + '_' + id_riga[1] + '_' + id_riga[2] + '_' + id_riga[3]).text()
    }


    $.post(currentURL + "assets/inc/catalogo-neg.php", { azione: 'aggquant', quant: quant, campo: id_riga[2], idpr: id_riga[1] }, function (response) {
        if (response == 'si') {
            Toast.fire({ icon: 'success', title: 'Quantita aggiornata correttamente!' })
            if (id_riga[2] == 'PrestashopDisponibilita' && id_riga[3] != '0') {
                $.post(currentURL + "assets/inc/prestashop-control.php", { azione: 'modificastock', quant: quant, idpr: id_riga[3] })
                // if (quant == '-2') {
                //     modst(0, id_riga[1])
                // } else if (quant >= 0 || quant == '-1') {
                //     modst(1, id_riga[1])
                // }
            }

        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    })
}

function DuplicaProdotto(id) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi duplicare questo prodotto?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, procedi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(currentURL + "assets/inc/catalogo-neg.php", { azione: 'duplicaprodotto', id: id }, function (res) {
                if (res == 'si') {
                    Toast.fire({ icon: 'success', title: 'Duplicato con successo' })
                } else {
                    Toast.fire({ icon: 'error', title: 'Errore: ' + res })
                }
            })
        }
    })
}

async function AggiungiAlCarrello(id, sku) {
    const { value: carrello } = await Swal.fire({
        title: 'Aggiungi a ordine fornitore',
        html:
            '<input id="swal-input1" placeholder="Inserisci la quantita desiderata" value="1" type="number" class="swal2-input">' +
            '<input id="swal-input2" placeholder="Inserisci le note" type="text" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
            return [
                document.getElementById('swal-input1').value,
                document.getElementById('swal-input2').value
            ]
        }
    })

    if (carrello) {
        $.post(currentURL + "assets/inc/catalogo-neg.php", { azione: 'aggiungicarrello', id: id, sku: sku, quantita: carrello[0], note: carrello[1] }, function (r) {
            if (r == 'si') {
                Toast.fire({ icon: 'success', title: 'Aggiunto con successo' })
            } else {
                Toast.fire({ icon: 'error', title: 'Errore: ' + r })
            }
        })
    }
}

function StampaCatalogo_ca() {
    window.open(currentURL + 'assets/pdf/catalogo.php?create_pdf=1')
}