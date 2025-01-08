function messaggiobollacons() {
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'setimp', valore: $('#imp_bollcons').val(), tipo: 1 }, function (response) {
        if (response == 'ok') {
            Toast.fire({ icon: 'success', title: 'Messaggio impostato con successo' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    });
}

function messaggiodg() {
    var valore = $('#imp_messgiorno').val() + '|-|' + $("#imp_messgiornost option:selected").val();
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'setimp', valore: valore, tipo: 2 }, function (response) {
        if (response == 'ok') {
            Toast.fire({ icon: 'success', title: 'Messaggio impostato con successo, aggiorna la pagina!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    });
}

function messaggiobl() {
    var valore = $('#imp_messbarralat').val();
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'setimp', valore: valore, tipo: 7 }, function (response) {
        if (response == 'ok') {
            Toast.fire({ icon: 'success', title: 'Messaggio impostato con successo, aggiorna la pagina!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + response })
        }
    });
}

function recuperaimpostazioi() {
    //MESSAGGIO BOLLA DI CONSEGNA
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'chemessaggio', tipo: 1 }, function (response) {
        $('#imp_bollcons').val(response);
    });
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'chemessaggio', tipo: 2 }, function (response) {
        var res = response.split('|-|');
        $('#imp_messgiorno').val(res[0]);
        $('#imp_messgiornost option[value=' + res[1] + ']').attr('selected', 'selected');
    });
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'chemessaggio', tipo:7 }, function (response) {
        $('#imp_messbarralat').val(response);
    });
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'listautenti' }, function (data) {
        $('#bd_luser').html(data);
    });
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'licensekey' }, function (data) {
        var res = data.split('|-|');
        $('#imp_licenza_codice').val(res[0]);
        $('#imp_licenza_scadenza').val(res[1]);
        $('#imp_licenza_univoco').val(res[2]);
    });

    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'DatiDeiDati' }, function (data) {
        var r = data.split(';')

        $('#imp-statiordine').html(r[0]);
        $('#imp-progprod').val(r[1]);
        $('#imp-fornitoreprod').html(r[2]);
        $('#imp-tipiprodotto').html(r[3]);
        $('#imp-tipiricambi').html(r[4]);
        $('#imp-banche').html(r[5]);
        $('#imp-pagscad').html(r[6]);
        $('#imp-pagfatt').html(r[7]);
        $('#imp-ivaset').html(r[8]);
    });

    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'cercalink' }, function (r) {
        var res = r.split(';')
        $('#imp-linkcorriere').val(res[0]);
        $('#imp-changelog').val(res[1]);
    });

    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'cercapresta' }, function (r) {
        var res = r.split(';')
        $('#imp-prestalink').val(res[0]);
        $('#imp-prestakey').val(res[1]);
        $('#imp-debuf').prop('checked', res[2]);
    });

    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'cercamano' }, function (r) {
        var res = r.split(';')
        $('#imp-apikeymano').val(res[0]);
        $('#imp-wbusermano').val(res[1]);
    });
}

function ModificaValore(t) {
    if (t == 4) {
        valore = $('#imp-linkcorriere').val() + ';' + $('#imp-changelog').val()
    } else if (t == 5) {
        valore = $('#imp-prestalink').val() + ';' + $('#imp-prestakey').val() + ';' + $('#imp-debuf').prop('checked');
    } else if (t == 8) {
        valore = $('#imp-apikeymano').val() + ';' + $('#imp-wbusermano').val();
    } else {
        return;
    }
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'modificalink', tipo: t, valore: valore }, function (r) {
        if (r == 'si') {
            Toast.fire({ icon: 'success', title: 'Aggiornato con successo!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + r })
        }
    });
}

function imp_mostrapermessi(idu) {
    $('#ms_perm_user').html('');
    $('#idu_permimp').val(idu);
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'mostrapermessi', idu: idu }, function (data) {
        //STEP 1
        var section = data.split('|-|');
        $.each(section, function (index, value) {
            var category = value.split(';');
            if (index == 0) {
                $('#ms_perm_user').append('<h4 class="mb-1">Sezione: Online</h4>');
                $.each(category, function (index1, value1) {
                    if (index1 == 0) { // - +
                        if (value1 == '-') {
                            add_switch('', 'Attiva sezione', 'sect_on', 'on1')
                        } else {
                            add_switch('checked', 'Disattiva sezione', 'sect_on', 'on1')
                        }
                    } else if (index1 == 1) { // ordini, mm, sinistri
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Spedizioni</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //lista ordini
                            add_switch('', 'Attiva lista ordini', 'sect_on', 'on2')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva lista ordini', 'sect_on', 'on2')
                        }
                        if (mini_category[1] == '0') { //manomano
                            add_switch('', 'Attiva ManoMano', 'sect_on', 'on3')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva ManoMano', 'sect_on', 'on3')
                        }
                        if (mini_category[2] == '0') { //sinistri
                            add_switch('', 'Attiva sinistri', 'sect_on', 'on4')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva sinistri', 'sect_on', 'on4')
                        }
                    } else if (index1 == 2) { // feedback
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Feedback</h6>');
                        if (value1 == '0') {
                            add_switch('', 'Attiva feedback', 'sect_on', 'on5')
                        } else {
                            add_switch('checked', 'Disattiva feedback', 'sect_on', 'on5')
                        }
                    } else if (index1 == 3) { // segnalazioni
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Segnalazioni</h6>');
                        if (value1 == '0') {
                            add_switch('', 'Attiva segnalazioni', 'sect_on', 'on6')
                        } else {
                            add_switch('checked', 'Disattiva segnalazioni', 'sect_on', 'on6')
                        }
                    }
                });
            } else if (index == 1) {
                $('#ms_perm_user').append('<h4 class="mb-1">Sezione: Punti Vendita</h4>');
                $.each(category, function (index1, value1) {
                    if (index1 == 0) { // - +
                        if (value1 == '-') {
                            add_switch('', 'Attiva sezione', 'sect_pv', 'pv1')
                        } else {
                            add_switch('checked', 'Disattiva sezione', 'sect_pv', 'pv1')
                        }
                    } else if (index1 == 1) { // preventivi,spaccati,usato,listaschede
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Officina</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //preventivi
                            add_switch('', 'Attiva preventivi', 'sect_pv', 'pv2')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva preventivi', 'sect_pv', 'pv2')
                        }
                        if (mini_category[1] == '0') { //spaccati
                            add_switch('', 'Attiva spaccati', 'sect_pv', 'pv3')
                        } else if (mini_category[1] == '1') {
                            add_switch('checked', 'Disattiva spaccati', 'sect_pv', 'pv3')
                        }
                        if (mini_category[2] == '0') { //usato
                            add_switch('', 'Attiva usato', 'sect_pv', 'pv4')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva usato', 'sect_pv', 'pv4')
                        }
                        if (mini_category[3] == '0') { //lista schede
                            add_switch('', 'Attiva lista schede', 'sect_pv', 'pv5')
                        } else if (mini_category[3] == '1') {
                            add_switch('checked', 'Disattiva lista schede', 'sect_pv', 'pv5')
                        }
                    } else if (index1 == 2) { // vendita,catalogo,ordine prodotti,lista vendite
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Negozio</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //vendita
                            add_switch('', 'Attiva vendita', 'sect_pv', 'pv6')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva vendita', 'sect_pv', 'pv6')
                        }
                        if (mini_category[1] == '0') { //catalogo
                            add_switch('', 'Attiva catalogo', 'sect_pv', 'pv7')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva catalogo', 'sect_pv', 'pv7')
                        }
                        if (mini_category[2] == '0') { //ordine prodotti
                            add_switch('', 'Attiva ordine prodotti', 'sect_pv', 'pv8')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva ordine prodotti', 'sect_pv', 'pv8')
                        }
                        if (mini_category[3] == '0') { //lista vendite
                            add_switch('', 'Attiva lista vendite', 'sect_pv', 'pv9')
                        } else if (mini_category[3] == '1') {
                            add_switch('checked', 'Disattiva lista vendite', 'sect_pv', 'pv9')
                        }
                    } else if (index1 == 3) { // documenti,clienti
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Clienti</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //documenti
                            add_switch('', 'Attiva documenti', 'sect_pv', 'pv10')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva documenti', 'sect_pv', 'pv10')
                        }
                        if (mini_category[1] == '0') { //clienti
                            add_switch('', 'Attiva clienti', 'sect_pv', 'pv11')
                        } else if (mini_category[1] == '1') {
                            add_switch('checked', 'Disattiva clienti', 'sect_pv', 'pv11')
                        }
                    }
                });
            } else if (index == 2) {
                $('#ms_perm_user').append('<h4 class="mb-1">Sezione: Amministrazione</h4>');
                $.each(category, function (index1, value1) {
                    if (index1 == 0) { // - +
                        if (value1 == '-') {
                            add_switch('', 'Attiva sezione', 'sect_amm', 'amm1')
                        } else {
                            add_switch('checked', 'Disattiva sezione', 'sect_amm', 'amm1')
                        }
                    } else if (index1 == 1) { // scadenze,fatture,fornitori
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Contabilità</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //scadenze
                            add_switch('', 'Attiva scadenze', 'sect_amm', 'amm2')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva scadenze', 'sect_amm', 'amm2')
                        }
                        if (mini_category[1] == '0') { //fatture
                            add_switch('', 'Attiva fatture', 'sect_amm', 'amm3')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva lista fatture', 'sect_amm', 'amm3')
                        }
                        if (mini_category[2] == '0') { //fornitori
                            add_switch('', 'Attiva fornitori', 'sect_amm', 'amm4')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva fornitori', 'sect_amm', 'amm4')
                        }
                    } else if (index1 == 2) { // pratiche legali
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Pratiche Legali</h6>');
                        if (value1 == '0') {
                            add_switch('', 'Attiva pratiche legali', 'sect_amm', 'amm5')
                        } else {
                            add_switch('checked', 'Disattiva pratiche legali', 'sect_amm', 'amm5')
                        }
                    } else if (index1 == 3) { // dipendenti
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Dipententi</h6>');
                        if (value1 == '0') {
                            add_switch('', 'Attiva dipendenti', 'sect_amm', 'amm6')
                        } else {
                            add_switch('checked', 'Disattiva dipendenti', 'sect_amm', 'amm6')
                        }
                    }
                });
            } else if (index == 3) {
                $('#ms_perm_user').append('<h4 class="mb-1">Sezione: Altro</h4>');
                $.each(category, function (index1, value1) {
                    if (index1 == 0) { // - +
                        if (value1 == '-') {
                            add_switch('', 'Attiva sezione', 'sect_altr', 'altr1')
                        } else {
                            add_switch('checked', 'Disattiva sezione', 'sect_altr', 'altr1')
                        }
                    } else if (index1 == 1) { // lista utenti, task
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Funzionalità</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //lista utenti
                            add_switch('', 'Attiva lista utenti', 'sect_altr', 'altr2')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva lista utenti', 'sect_altr', 'altr2')
                        }
                        if (mini_category[1] == '0') { //task
                            add_switch('', 'Attiva task', 'sect_altr', 'altr3')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva task', 'sect_altr', 'altr3')
                        }
                    } else if (index1 == 2) { // database,backupdb
                        $('#ms_perm_user').append('<h6 class="mb-1">Categoria: Navogazione</h6>');
                        mini_category = value1.split(',');
                        if (mini_category[0] == '0') { //database
                            add_switch('', 'Attiva database', 'sect_altr', 'altr4')
                        } else if (mini_category[0] == '1') {
                            add_switch('checked', 'Disattiva database', 'sect_altr', 'altr4')
                        }
                        if (mini_category[1] == '0') { //backupdb
                            add_switch('', 'Attiva backup database', 'sect_altr', 'altr5')
                        } else if (mini_category[2] == '1') {
                            add_switch('checked', 'Disattiva backup database', 'sect_altr', 'altr5')
                        }
                    }
                });
            }
        });
    });
}

function switchperm(name) {
    var res = name.split('_');
    val = '';
    var len = $('[id^=' + res[1] + '][name=' + name + ']').length
    for (var i = 1; i <= len; i++) {

        if (i == 1) {
            if ($('#' + res[1] + i).is(':checked')) {
                val = '+;';
            } else {
                val = '-;';
            }
        } else {
            if ($('#' + res[1] + i).is(':checked')) {
                val = val + '1';
            } else {
                val = val + '0';
            }
            if (res[1] == 'on') {
                if (i == 4 || i == 5) {
                    val = val + ';';
                } else {
                    val = val + ',';
                }
            } else if (res[1] == 'pv') {
                if (i == 5 || i == 9) {
                    val = val + ';';
                } else {
                    val = val + ',';
                }
            } else if (res[1] == 'amm') {
                if (i == 4) {
                    val = val + ';';
                } else {
                    val = val + ',';
                }
            } else if (res[1] == 'altr') {
                if (i == 3) {
                    val = val + ';';
                } else {
                    val = val + ',';
                }
            }
        }
    }

    val = val.substring(0, val.length - 1);
    if (res[1] == 'on') {
        imp_salvapermessi('s_Online', val, $('#idu_permimp').val())
    } else if (res[1] == 'pv') {
        imp_salvapermessi('s_PVend', val, $('#idu_permimp').val())
    } else if (res[1] == 'amm') {
        imp_salvapermessi('s_Amm', val, $('#idu_permimp').val())
    } else if (res[1] == 'altr') {
        imp_salvapermessi('s_Altro', val, $('#idu_permimp').val())
    }
}

function imp_salvapermessi(campo, valore, idu) {
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'salvaperm', campo: campo, valore: valore, idu: idu }, function (resp) {
        if (resp == 'ok') {
            Toast.fire({ icon: 'success', title: 'Permessi aggiornati!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + resp })
        }

    })
}

function add_switch(state, txt, name, id) {
    $('#ms_perm_user').append('<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="' + name + '" id="' + id + '" ' + state + ' onchange="switchperm(\'' + name + '\')"><label class="form-check-label" for="' + id + '">' + txt + '</label></div>');

}

function VerificaLicenza() {
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'setimp', valore: $('#imp_licenza').val(), tipo: 3 }, function (resp) {
        if (resp.includes("Codice valido")) {
            Toast.fire({ icon: 'success', title: 'Licenza aggiornata!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + resp })
        }
    });
}

function CambiaPermessiHome(idu) {
    var q = $('#homepermut_' + idu).val();
    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'changehome', home: q, idu: idu }, function (resp) {
        if (resp == 'si') {
            Toast.fire({ icon: 'success', title: 'Home aggiornata con successo!' })
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + resp })
        }
    });
}

function CreaDato(d) {
    if (d == 'imp-nuovofornitoreprod') {
        tabella = 'doff_dati';
        tipo = 1;
        valore = 0;
    } else {
        Toast.fire({ icon: 'warning', title: 'Non valido!' })
        return;
    }

    $.post(currentURL + 'assets/inc/impostazioni.php', { azione: 'creadato', tabella: tabella, dato: $('#' + d).val(), tipo: tipo, valore: valore }, function (r) {
        if (r == 'si') {
            Toast.fire({ icon: 'success', title: 'Nuovo dato aggiunto con successo!' })
            recuperaimpostazioi();
            $('#' + d).val('');
        } else {
            Toast.fire({ icon: 'error', title: 'Errore: ' + r })
        }
    })
}

$(document).on('click', '.EseguiCron', function () {
    btt = $(this);
    btt.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Caricamento');
    btt.attr('disabled', true);
    $.post(currentURL + 'assets/tools/' + btt.attr('EseguiCron') + '.php', { azione: '1' }, function (r) {
        $('.rispostacron').attr('hidden', false);
        $('#rispostacron').html(r);
        $(btt).attr('disabled', false)
        $(btt).html('<i class="fa-solid fa-play"></i> Esegui')
    });
});