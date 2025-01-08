function CaricaIva() {
    $.post(currentURL + 'assets/inc/schedaprodotton.php', {
        azione: 'caricaiva'
    }, function (data) {
        $('#impiva').html(data);
    });
}

function CaricaArrayOfficina() {
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'tiporicambi2'
    }, function (data) {
        var res = data.split(',')
        $.each(res, function (index, value) {
            myArr1.push(value)
        });
    });

    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'prodotti'
    }, function (data) {
        var res = data.split(',')
        $.each(res, function (index, value) {
            myArr2.push(value)
        });
    });

    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'fornitoriprod'
    }, function (data) {
        var res = data.split(',')
        $.each(res, function (index, value) {
            myArr3.push(value)
        });
    });
}

var countf = 0;

function CaricaDatiProdotto() {
    //PRODOTTO ESISTENTE
    $.post((currentURL + 'assets/inc/schedaprodotton.php'), {
        azione: 'caricaprodotto',
        idpr: idpr
    }, function (data) {
        (ModalitaDebug ? console.log(data) : '');
        var res = data.split('|-|')
        $('#idprod').val(res[0]);
        $('#nomeprod').val(res[1]);
        $('#riferimento_off').val(res[2]);
        $('#ean').val(res[3]);
        $('#prestashopid').val(res[14]);
        (res[14] == '0') ? '' : $('#MarketZone').html('<div class="col-6 d-grid gap-2"><button class="btn btn-outline-primary me-1 mb-1 PrMod" type="button">Modifica su PrestaShop</button></div><div class="col-6 d-grid gap-2"><button class="btn btn-outline-primary me-1 mb-1 PrApri" type="button">Apri in Negozio</button></div>');
        (res[14] == '0') ? '' : $('#statuspropre').html('Online <span style="color: green;">●</span>');

        //INFORMAZIONI
        $('#pesoimb').val(res[11] + ' kg');
        RangeSpedizioni(res[11]);
        $('#tag').val(res[12]);
        setTimeout(function () {
            tinymce.get("tmce-DL").setContent(res[13]);
        }, 500);
        //altreinfo
        $('#prezzove1').val(res[4]);
        $('#prezzove2').val(res[4]);
        $('#prezzomarksi').val(res[15]);
        $('#impiva option[value=' + res[16] + ']').attr('selected', 'selected');
        $('#prezforn').val(res[7]);
        $('#ricarico').val(res[6]);
        $('#sco1').val('0');
        $('#sco2').val('0');
        $('#DataDisponibilita').val(res[17]);

        $('#unitmis').val(res[8]).prop('selected', true);
        $('#quantmag').val(res[9]);
        $('#quantprest').val(res[10]);

        //CALCOLO PREZZI
        var valiva = 0;
        valiva = res[16];
        $('#prezzomark').val((parseFloat(res[15]) + ((parseFloat(res[15]) * valiva) / 100)).toFixed(2)); //IVATO MARKETPLACE
        CalcolaCommissioni($('#prezzomark').val());
        if (valiva <= 9) {
            valiva = '10' + valiva
        } else {
            valiva = '1' + valiva
        };
        valiva = parseInt(valiva);
        $('#prezzoiva').val(((parseFloat(res[7]) * 100) / valiva).toFixed(2)); //SENZA IVA FORNITORE
        $('#impvend').val(((parseFloat(res[4]) * 100) / valiva).toFixed(2)); //SENZA IVA BANCO
        //FINE CALCOLO PREZZI

        //INIZO PRESTASHOP
        $('#prestashopid').val(res[14]);
        if (res[18] == '1') {
            $('#tipomagazzino').text('Trasforma in magazzino negozio');
            $('#tipomagazzino').val('2');
        } else {
            $('#tipomagazzino').text('Trasforma in magazzino ricambi');
            $('#tipomagazzino').val('1');
        }
        if (res[19] == '1') {
            $('#mostrata').text('Nascondi dal catalogo');
            $('#mostrata').val('0');
        } else {
            $('#mostrata').text('Mostra in catalogo');
            $('#mostrata').val('1');
        }
        //FINE PRESTASHOP
        $('#ImmagineProdotto').attr('hidden', false);
        $('#ImmagineProdotto').html('<img onerror="imgError();" height="100%" width="100%" src="https://portalescifo.it/upload/image/p/' + res[0] + '.jpg" />');
        $("#ImmagineProdotto").append('<a href="javascript:void(0)" onclick="CambioImmagine()">Cambia immagine</a>')
        $('#formupload').attr('hidden', true);

        //COMBINAZIONI
        // if (typeof res[19] === 'undefined') {
        //     $('#combinazioni-details-tab').attr('hidden', true);
        // } else {
        //     $('#prezzi-product-details-tab').attr('hidden', true);
        //     $('#supp-product-details-tab').attr('hidden', true);

        //     $('#combinazioni-details-tab').attr('hidden', false);
        //     $('#TabellaCombinazioni').html(res[19]);
        //     eventkeyup_off();
        // }

        //tipo res[14]
        var codicehtml = '';
        var res2 = res[5].split('/-/');
        countf = 0;
        res2.forEach(function (valore) {
            var res3 = valore.split(';')
            codicehtml += ' <div class="row"><div class="col-md-4 form-group"><label class="label-block">Codice</label><input id="codforn' + countf + '" type="text" value="' + res3[0] + '" class="form-control" onchange="InfoAggiornaFornitori()"></div><div class="col-md-4 form-group"><label class="label-block">Fornitore</label><input id="nomeforn' + countf + '" type="text" value="' + res3[1] + '" class="form-control auto_F" onchange="InfoAggiornaFornitori()" autocomplete="off"></div><div class="col-md-3 form-group"><label class="label-block">Prezzo</label><input id="prezfornmom' + countf + '" type="text" value="' + res3[2] + '" class="form-control" onchange="InfoAggiornaFornitori(); $(\'#prezfornmom' + countf + '\').val(parseFloat($(\'#prezfornmom' + countf + '\').val().replace(\',\',\'.\')).toFixed(2));"></div><div class="col-md-1"><button class="btn btn-falcon-default rounded-pill mt-4 me-1 mb-1" type="button" onclick="CreaPrezzo(' + countf + ')"><i class="fa-regular fa-calculator"></i></button></div></div>';
            countf += 1;
        });
        $('#fornitoreabc').html(codicehtml);

        docReady(choicesInit);
        $(".auto_F").autocomplete({
            source: myArr3
        });
    })
}

function eventkeyup_off() {
    $('[id^=ModComb_]').keyup(function () {
        if (typeof cartTimeout != 'undefined')
            window.clearTimeout(cartTimeout);
        element = $(this);
        cartTimeout = window.setTimeout(function () {
            ModificaCombinazione(element)
        }, 500);
    })
}

function ModificaCombinazione(element) {
    id_riga = element.attr('id');
    id_riga = $(this)[0].id_riga.split('_');
    var v = $('#ModComb_' + id_riga[1]).text(); // VALORE MODIFICA
    var c = $('#ModComb_' + id_riga[2]).text(); //COSA MODIFICARE
    $.post(currentURL + 'assets/inc/schedaprodotton.php', {
        azione: 'ModificaCombinazione',
        c: c,
        v: v,
        id: $('#prestashopid').val()
    }, function (res) {
        if (res == 'ok') {
            Toast.fire({
                icon: 'success',
                title: 'Modifica effettuata con successo!'
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + res
            })
        }

    });
}

$(document).on('click', '.CreaNuovoProdotto', function () {
    //crea nuovo prodotto
    if ($('#nomeprod').val() == '') {
        Toast.fire({
            icon: 'error',
            title: 'inserisci il nome del prodotto!'
        })
    } else if ($('#riferimento_off').val() == '') {
        Toast.fire({
            icon: 'error',
            title: 'inserisci il codice di riferimento!'
        })
    } else if ($('#ean').val() == '') {
        Toast.fire({
            icon: 'error',
            title: 'inserisci il codice ean!'
        })
    } else if (countf == 0) {
        Toast.fire({
            icon: 'error',
            title: 'inserisci almeno un fornitore!'
        })
    } else if ($('#prezzove2').val() == '0.00') {
        Toast.fire({
            icon: 'error',
            title: 'calcola il prezzo di vendita!'
        })
    } else {
        $(this).attr('disabled', true);
        // genera fornitori
        for (i = 0; i < countf; i++) {
            if (i == 0) {
                fornitori = $('#codforn' + i).val() + ';' + $('#nomeforn' + i).val() + ';' + $('#prezfornmom' + i).val();
            } else {
                fornitori = fornitori + '/-/' + $('#codforn' + i).val() + ';' + $('#nomeforn' + i).val() + ';' + $('#prezfornmom' + i).val();
            }
        }
        if ($('#riferimento_off').val().includes("ro1") || $('#riferimento_off').val().includes("rc1")) {
            tipo = '1'
        } else {
            tipo = '2'
        }

        $.post(currentURL + 'assets/inc/schedaprodotton.php', {
            azione: 'creaprodotto',
            np: $('#nomeprod').val(),
            cp: $('#riferimento_off').val(),
            ep: $('#ean').val(),
            pvp: $('#prezzove2').val(),
            f: fornitori,
            r: $('#ricarico').val(),
            pap: $('#prezzoiva').val(),
            m: $('#quantmag').val(),
            dp: tinymce.get("tmce-DL").getContent(),
            tp: $('#tag').val(),
            tipo: tipo
        }, function (response) {
            if (response == 'ok') {
                Toast.fire({
                    icon: 'success',
                    title: 'prodotto creato con successo!'
                })
                if ($('#riferimento_off').val().includes("ro1") || $('#riferimento_off').val().includes("rc1")) {
                    $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                        azione: 'add1',
                        ref: $('#riferimento_off').val(),
                    })
                }
                if ($('.propre_sincronizza').attr('valore') == 'si') {
                    $.post(currentURL + 'assets/inc/prestashop-control.php', {
                        azione: 'nuovoprodotto',
                        np: $('#nomeprod').val(),
                        cp: $('#riferimento_off').val(),
                        ep: $('#ean').val(),
                        pvp: $('#prezzove2').val(),
                        m: $('#quantmag').val(),
                        dp: tinymce.get("tmce-DL").getContent(),
                        tp: $('#tag').val()
                    }, function (response) {
                        tinymce.get("tmce-DL").setContent(response);
                        console.log(response);
                    })
                    $('#univ-offcanvas').offcanvas('hide')
                } else {
                    $('#univ-offcanvas').offcanvas('hide')
                }
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'errore: ' + response
                })
            }
        })
    }
});

function AggiornaFornitori() {
    var fornitori = '';
    for (i = 0; i < countf; i++) {
        if (i == 0) {
            fornitori = $('#codforn' + i).val() + ';' + $('#nomeforn' + i).val() + ';' + $('#prezfornmom' + i).val();
        } else {
            fornitori = fornitori + '/-/' + $('#codforn' + i).val() + ';' + $('#nomeforn' + i).val() + ';' + $('#prezfornmom' + i).val();
        }
    }

    $.post(currentURL + 'assets/inc/schedaprodotton.php', {
        azione: 'aggiornaforn',
        fornitori: fornitori,
        idpr: $('#idprod').val()
    }, function (data) {
        if (data == 'ok') {
            Toast.fire({
                icon: 'success',
                title: 'Fornitori aggiornati con successo!'
            })
            $("#aggiorna_forn").removeClass("btn-falcon-danger").addClass("btn-falcon-default");
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Errore: ' + data
            })
        }
    });
}

function InfoAggiornaFornitori() {
    $("#aggiorna_forn").removeClass("btn-falcon-default").addClass("btn-falcon-danger");
}

function AddRigaFornitori() {
    $('#fornitoreabc').append('<div class="row"><div class="col-md-4 form-group"><label class="label-block">Codice</label><input id="codforn' + countf + '" type="text" class="form-control"></div><div class="col-md-4 form-group"><label class="label-block">Fornitore</label><input id="nomeforn' + countf + '" type="text" class="form-control auto_F" autocomplete="off"></div><div class="col-md-3 form-group"><label class="label-block">Prezzo</label><input id="prezfornmom' + countf + '" onchange="$(\'#prezfornmom' + countf + '\').val(parseFloat($(\'#prezfornmom' + countf + '\').val().replace(\',\',\'.\')).toFixed(2))" type="text" class="form-control"></div><div class="col-md-1"><button class="btn btn-falcon-default rounded-pill mt-4 me-1 mb-1" type="button" onclick="CreaPrezzo(' + countf + ')"><i class="fa-regular fa-calculator"></i></button></div></div>');
    countf = (countf + 1);
    $(".auto_F").autocomplete({
        source: myArr3
    });
    $('.ui-autocomplete').css('z-index', 1046);
}

function CreaPrezzo(nforn) {
    if ($('#prezfornmom' + nforn).val() == '') {
        Toast.fire({
            icon: 'error',
            title: 'Inserisci il prezzo del fornitore per il calcolo!'
        })
    } else {
        $('#prezzoiva').val($('#prezfornmom' + nforn).val())
        if ($('#tipomagazzino').val() == 1) {
            $('#ricarico').val('35')
        } else {
            $('#ricarico').val('50')
        }
        $('[href="#prezzi-product-details"]').tab('show');

        CalcolaPrezzo_sp();
    }
}

function CalcolaPrezzo2_sp() {
    var ivaa = 0;
    var importoo = 0;
    importoo = $('#prezzomark').val()
    ivaa = (parseFloat(importoo) / (122 / 100)).toFixed(2)
    $('#prezzomark').val(parseFloat(importoo).toFixed(2));
    $('#prezzomarksi').val(ivaa);
    CalcolaCommissioni(importoo);
    AggiornaProdotto('prezzomarksi', false);
}

function CalcolaCommissioni(importoo) {
    $('#commissionimarket').val((parseFloat(importoo) * (parseFloat($('#percmarket').val())) / 100));
}

function CalcolaPrezzo3_sp() {
    var iva = 0;
    var valiva = 0;
    valiva = $('#impiva').val();
    var importoo = 0;
    var costo = 0;
    importoo = $('#prezzove2').val()
    costo = $('#prezforn').val()
    if (valiva <= 9) {
        valiva = '10' + valiva
    } else {
        valiva = '1' + valiva
    }
    valiva = parseInt(valiva);
    ivaa = (parseFloat(importoo) / (valiva / 100)).toFixed(2)
    $('#prezzove2').val(parseFloat(importoo).toFixed(2));
    $('#impvend').val(ivaa);
    $('#ricarico').val(Math.round(((parseFloat(importoo) - parseFloat(costo)) / parseFloat(costo)) * 100));

    AggiornaProdotto('prezzove2', false);
    AggiornaProdotto('ricarico', true);
}

function CalcolaPrezzo_sp() {
    var importo = parseFloat($('#prezzoiva').val());
    var iva = 0;
    var valiva = 0;
    valiva = $('#impiva').val();
    var ricarico = 0;
    var sconto = 0;
    if ($('#sco1').val() >= 1) {
        sconto = ((importo / 100) * parseFloat($('#sco1').val()));
        importo = (importo - sconto);
    }
    if ($('#sco2').val() >= 1) {
        sconto = ((importo / 100) * parseFloat($('#sco2').val()));
        importo = (importo - sconto);
    }
    iva = ((importo / 100) * valiva)
    importo = (importo + iva);

    $('#prezforn').val((importo).toFixed(2));

    if ($('#ricarico').val() >= 1) {
        ricarico = ((importo / 100) * parseFloat($('#ricarico').val()));
        importo = importo + ricarico
    }

    if (importo >= 5) {
        importo = Math.round(importo);
    }

    importo = parseFloat(importo).toFixed(2);

    $('#prezzove1').val(importo);
    $('#prezzove2').val(importo);

    if (valiva <= 9) {
        valiva = '10' + valiva
    } else {
        valiva = '1' + valiva
    };
    valiva = parseInt(valiva);
    iva = (importo / (valiva / 100)).toFixed(2)
    $('#impvend').val(iva);

    AggiornaProdotto('prezzove2', false);
    AggiornaProdotto('prezforn', false);
    AggiornaProdotto('sco1', false);
    AggiornaProdotto('sco2', false);
    AggiornaProdotto('ricarico', false);
    AggiornaProdotto('impiva', true);
}


function ModificaPrestashop(st, id, val) {
    console.log(st)
    console.log(id)
    console.log(val)
    $.post(currentURL + "assets/inc/prestashop-control.php", {
        azione: st,
        id: id,
        ref: val
    }, function (r) {
        console.log(r)
    })
}

var initMCE = 0;

function ModificaTinyMCE(content = null) {
    if (content != null) {
        initMCE = (initMCE + 1);
        if (idpr != 'nuovo' && initMCE >= 2) {
            $('#descrprod').append('<input id="_descrprod" />')
            $('[id^=_descrprod]').val(addslashes(content))
            $('[id^=_descrprod]').css('display', 'none')
            AggiornaProdotto('_descrprod', true)
        }
    }
}

function AggiornaProdotto(campo, not) {
    if (idpr != 'nuovo') {
        if (campo == 'nomeprod') {
            ncampo = 'nome';
        } else if (campo == 'riferimento_off') {
            ncampo = 'sku';
            ModificaPrestashop('changereference', $('#prestashopid').val(), $('#' + campo).val())
        } else if (campo == 'ean') {
            ncampo = 'ean';
            ModificaPrestashop('changeean', $('#prestashopid').val(), $('#' + campo).val())
        } else if (campo == 'quantmag') {
            ncampo = 'disponibilita';
        } else if (campo == 'prezzove2') {
            ncampo = 'prezzo';
        } else if (campo == 'prezforn') {
            ncampo = 'PrezzoAcquisto';
        } else if (campo == 'sco1') {
            ncampo = 'sco1';
        } else if (campo == 'sco2') {
            ncampo = 'sco2';
        } else if (campo == 'ricarico') {
            ncampo = 'ricarico';
        } else if (campo == 'prezzomarksi') {
            ncampo = 'PrestashopPrezzo';
            ModificaPrestashop('changeprice', $('#prestashopid').val(), $('#prezzomarksi').val())
        } else if (campo == 'prestashopid') {
            ncampo = 'id1';
        } else if (campo == 'impiva') {
            ncampo = 'iva';
        } else if (campo == 'DataDisponibilita') {
            ncampo = 'DataDisponibilita';
        } else if (campo == 'tipomagazzino') {
            ncampo = 'tipo';
        } else if (campo == 'tag') {
            ncampo = 'tag';
        } else if (campo == '_descrprod') {
            ncampo = 'descrizione';
        } else if (campo == 'mostrata') {
            ncampo = 'Mostra';
        }



        $.post((currentURL + 'assets/inc/schedaprodotton.php'), {
            azione: 'aggiornaprodotto',
            campo: ncampo,
            valore: $('#' + campo).val(),
            idpr: $('#idprod').val()
        }, function (response) {
            if (response == 'ok' && not == true) {
                Toast.fire({
                    icon: 'success',
                    title: 'Aggiornamento eseguito con successo!'
                })
                $('[id^=_descrprod]s').remove();
            } else if (response != 'ok' && not == true) {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + response
                })
            }
        })
    }
}

function kmpSearch(pattern, text) {
    if (pattern.length == 0)
        return 0;
    var lsp = [0];
    for (var i = 1; i < pattern.length; i++) {
        var j = lsp[i - 1];
        while (j > 0 && pattern.charAt(i) != pattern.charAt(j))
            j = lsp[j - 1];
        if (pattern.charAt(i) == pattern.charAt(j))
            j++;
        lsp.push(j);
    }
    var j = 0;
    for (var i = 0; i < text.length; i++) {
        while (j > 0 && text.charAt(i) != pattern.charAt(j))
            j = lsp[j - 1];
        if (text.charAt(i) == pattern.charAt(j)) {
            j++;
            if (j == pattern.length)
                return i - (j - 1);
        }
    }
    return -1;
}

function GeneraCodice(type) {
    if (type == 0) {
        $.post(currentURL + 'assets/inc/impostazioni.php', {
            azione: 'scodeprog'
        }, function (response) {
            var lung = response.length;
            var code = response;
            for (i = lung; i <= 4; i++) {
                code = '0' + code;
            }
            $('#CoDMa').text('N' + code);
        })
    } else if (type == 1) {
        var check = $('#nomeprod').val().toUpperCase();
        var T = ["...", "...", "...", "..."];

        if (kmpSearch('ORIGINALE', check) != -1) {
            T[0] = 'RO';
            $('#CoDOf').text(T[0] + T[1] + T[2] + T[3]);
        } else {
            T[0] = 'RC';
            $('#CoDOf').text(T[0] + T[1] + T[2] + T[3]);
        }


        $.each(myArr2, function (index, value) {
            if (kmpSearch(myArr2[index], check) != -1) {
                $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                    azione: 'codicericambio',
                    testo: myArr2[index],
                    tipo: 2
                }, function (response) {
                    T[1] = response;
                    $('#CoDOf').text(T[0] + T[1] + T[2] + T[3]);
                })
            }
        });

        $.each(myArr1, function (index, value) {
            if (kmpSearch(myArr1[index], check) != -1) {
                $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                    azione: 'codicericambio',
                    testo: myArr1[index],
                    tipo: 3
                }, function (response) {
                    T[2] = response;
                    $('#CoDOf').text(T[0] + T[1] + T[2] + T[3]);
                })
            }
        });

        $.post(currentURL + 'assets/inc/schedaprodotton.php', {
            azione: 'codicericambio',
            testo: 'CODICERIC',
            tipo: 4
        }, function (response) {
            T[3] = response;
            $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                azione: 'add1',
                ref: response,
                id: 232
            })
            $('#CoDOf').text(T[0] + T[1] + T[2] + T[3]);
        })
    } else if (type == 2) {
        $.post(currentURL + 'assets/inc/schedaprodotton.php', {
            azione: 'generaupc'
        }, function (response) {
            $('#CoDUpc').text(response);
        })
    } else if (type == 3) {
        var check = $('#nomeprod').val().toUpperCase();
        var A = ["...", "..."];

        $.each(myArr1, function (index, value) {
            if (kmpSearch(myArr1[index], check) != -1) {
                $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                    azione: 'codicericambio',
                    testo: myArr1[index],
                    tipo: 3
                }, function (response) {
                    A[0] = response;
                    $('#CoDMi').text('RM' + A[0] + A[1]);
                })
            }
        });

        $.post(currentURL + 'assets/inc/schedaprodotton.php', {
            azione: 'codicericambio',
            testo: 'CODICEMIN',
            tipo: 4
        }, function (response) {
            A[1] = response;
            $.post(currentURL + 'assets/inc/schedaprodotton.php', {
                azione: 'add1',
                ref: response,
                id: 241
            })
            $('#CoDMi').text('RM' + A[0] + A[1]);
        })
    }
}

$(document).on('click', '.PrApri', function () {
    window.open('https://scifostore.com/index.php?controller=product&id_product=' + $('#prestashopid').val(), '_blank');
});

$(document).on('click', '.PrMod', function () {
    window.open('https://scifostore.com/admin22SS/index.php/sell/catalog/products/' + $('#prestashopid').val() + '?_token=e35fx2ZCKy5FVXzTl0kgHKZyASPseFFo1nMWT3GB9wI#tab-step1', '_blank');
});

function RangeSpedizioni(p) {
    $.post(currentURL + 'assets/inc/schedaprodotton.php', {
        azione: 'rangeprod',
        peso: p
    }, function (data) {
        $('#prezzospedica').val(data.replace('.', ','));
        $('#PrezzoSpedizione').val('€ ' + data.replace('.', ','));
    });
}

function imgError() {
    $('#formupload').prop('hidden', false)
    $('#ImmagineProdotto').prop('hidden', true)
}

function ApriPrestashop() {
    window.open('https://scifostore.com/admin22SS/index.php/sell/catalog/products/' + $('#prestashopid').val() + '?_token=e35fx2ZCKy5FVXzTl0kgHKZyASPseFFo1nMWT3GB9wI#tab-step1', '_blank');
}

$(document).on('click', '.CaricaAllegato_off', async function () {
    //CARICA FILE
    const {
        value: file
    } = await Swal.fire({
        title: 'Seleziona file',
        input: 'file',
        enctype: 'multipart/form-data',
        inputAttributes: {
            'accept': 'image/jpeg',
            'aria-label': 'Carica il file'
        }
    })

    if (file) {
        var data = new FormData();
        data.append("userfile", file);
        data.append("cartella", 'image/p');
        data.append('idfile', $('#idprod').val())
        NotificaCaricamento()
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: currentURL.replace('v3.', '') + "upload/carica-allegato.php",
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
                    Swal.fire({
                        icon: 'error',
                        title: 'File non esistente o sconosciuto'
                    })
                } else if (data.includes('carno')) {
                    var r = data.split(';')
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore: ' + r[1]
                    })
                } else {
                    (ModalitaDebug ? console.log(data) : '')
                    $("#ImmagineProdotto").html('<img onerror="imgError();" height="100%" width="100%" src="https://portalescifo.it/upload/image/p/' + $("#idprod").val() + '.jpg" data-zoom-image="https://portalescifo.it/upload/image/p/' + $("#idprod").val() + '.jpg" onmouseover="$.removeData($(this), \'elevateZoom\'); $(\'.zoomContainer\').remove(); $(this).elevateZoom({zoomWindowFadeIn: 500,zoomWindowFadeOut: 500,lensFadeIn: 500,lensFadeOut: 500});" />');
                    $("#ImmagineProdotto").append('<a href="javascript:void(0)" onclick="CambioImmagine()">Cambia immagine</a>')
                    $('#formupload').prop('hidden', true)
                    $('#ImmagineProdotto').prop('hidden', false)
                    Swal.fire({
                        icon: 'success',
                        title: 'File caricato correttamente!'
                    })
                }
            }
        });
        e.preventDefault();
    }
});

function CambioImmagine() {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi eliminare questa immagine? L\'azione sarà irreversibile!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, procedi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("https://portalescifo.it/upload/elimina-file.php", {
                file: 'image/p/' + $('#idprod').val() + '.jpg',
            }, function (data) {
                (ModalitaDebug ? console.log(data) : '')
                if (data == 'ok') {
                    $('#formupload').prop('hidden', false)
                    $('#ImmagineProdotto').prop('hidden', true)
                    $('.zoomContainer').remove()
                    Toast.fire({
                        icon: 'success',
                        title: 'Eliminato con successo'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Errore: ' + data
                    })
                }
            });
        }
    })
};

//PROPRE

$(document).on('click', '.propre_sincronizza', function () {
    if ($('.propre_sincronizza').attr('valore') == 'si') {
        $('.propre_sincronizza').html('Sincronizza: NO');
        $('.propre_sincronizza').attr('valore', 'no')
        $('.propre_sincronizza').addClass('btn-danger').removeClass('btn-success');
    } else {
        $('.propre_sincronizza').html('Sincronizza: SI');
        $('.propre_sincronizza').attr('valore', 'si')
        $('.propre_sincronizza').addClass('btn-success').removeClass('btn-danger');
    }
});