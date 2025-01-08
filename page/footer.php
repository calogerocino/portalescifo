<script>
    appLoaded = false;

    var snd = new Audio("assets/snd/cassa.mp3");
    // PRINTER ZEBRA
    function printTest(cmds) {
        BrowserPrint.getLocalDevices(function(printer) {
            printer[0].send(cmds, printComplete, errorCallback);

        }, function() {
            Toast.fire({
                icon: 'warning',
                title: 'Nessuna stampante trovata'
            });
        }, "printer");
    }
    var errorCallback = function(errorMessage) {
        Toast.fire({
            icon: 'error',
            title: 'Errore: ' + errorMessage
        });
    }

    function printComplete() {
        Toast.fire({
            icon: 'success',
            title: 'Stampa completata'
        })
    }


    function stampazebra(a, b, c, d, tp, q) {
        var cmds = '^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ';
        cmds += '^XA';
        cmds += '^MMT';
        cmds += '^PW256';
        cmds += '^LL0200';
        cmds += '^LS0';
        if (tp == 0) {
            cmds += '^FO224,0^GFA,01792,01792,00008,:Z64:';
            cmds += 'eJztlDGOgzAQRcdiJagWmkipmGtssQo5wjZ7jz0AColyMaQUew2OQDoiRXEk/kwkG0LwrpQq07zGHj++xxA9ufK07sncgEXbc+Wz6sDyDs8gdx5P2J+2YNxkPU3tepgLGNkt1lksSK142dblHv0LI+fTNHUd73S/eBn0fyfwjdY0Vmy7IN6r4D7dH0mexwdgLDg7Z8nrUc63fJW/bh/tr9Rz1YdKVzeJ4cdcC8VvMJ/isZmez8KfT53Lo87nD3Jp1MDPcZrh8zGXUg/eRzp4H/PubXB/h3n3ph60odEKzcO0LiNhLP8NPnrf5zGv4Les4JfxdtRr8f0V5JV//pOSbzJq8yqvrhpY++8=:C34C';
            cmds += '^BY2,2,63^FT201,131^BEI,,Y,N';
            cmds += '^FD' + a + '^FS';
            cmds += '^FT213,81^AKI,25,24^FH\^FD' + b + '^FS';
            cmds += '^FT213,50^AXI,19,15^FH\^FD' + c + '^FS';
            cmds += '^FT213,22^AXI,19,15^FH\^FD' + d + '^FS';
        } else if (tp == 1) {
            cmds += '^FO0,0^GFA,02048,02048,00032,:Z64:';
            cmds += 'eJztkj1u3DAQhYdrGwLWhdykZu9DhHuEFFHto5DGFk6nKxipAh8iq8UWalOkjww1i6QwA6Tg2jJfZoayfYdkBwNhwKePw/kh+uftUl1sSbS4nY9ezXjx5qz5SH5FNr7TwDQfTNOIXmNgR4t8gkTIGw1O5IvAukVk9xVyhckAToMKMEV3GBwSauRamOzmAOuie3QeSXgLFJ0D5rdFx8MA5ltR0OavToMbYJx5WsEMvorkOt/HUz7wnb9JdjtpffwTaEDNenB94gPywa2THYvOtWb+Mu/J9pNc6Mn10d7PfL5mHW36hOAesOcLOdgxj1zeL4l89fxdeEzK257zA6T1j8LXXENwzAhPbnzcjEW3+K08JsaEKbzWX/r/pPkxIdhhI9n5ItZ3RaerwxvfnSG88Ka8n+pDpiD5uexBs0v9PIKiX1QHmNXcvy25uX/IM/++Tfi15/rJ/cR6r9k5eMrrfKf1/4he5h9J5vcs75f5JZl/p/2JOv/IlUB2hnlOnt7mX/YnUlkb5iWI/rrwkP3T/OeYau0f11Ilt3vZP/6/E94gGvxh3uBbpf0X/vyRFpkul7z47lacl9+FxZfl5zt9/9GOdrT/1f4C7OWONQ==:B98D';
            cmds += '^BY2,2,51^FT43,188^BEN,,Y,N';
            cmds += '^FD' + a + '^FS';
            cmds += '^FT63,98^AKN,39,38^FB125,1,0,C^FH\^FD' + b + '^FS';
            cmds += '^FT10,57^AXN,23,24^FB234,1,0,C^FH\^FD' + c + '^FS';
            cmds += '^FT60,129^AXN,25,24^FB141,1,0,C^FH\^FD' + d + '^FS';
        }
        cmds += '^PQ' + q + ',0,1,Y^XZ';
        printTest(cmds)
    }

    var currentURL = $(location).attr('protocol') + '//' + $(location).attr('hostname') + '/';

    var loaderChecker = function() {
        var loader = document.getElementById('loader-screen')
        if (appLoaded) {
            loader.classList.add('loader-screen--leaving')
            window.setTimeout(function() {
                loader.remove()
            }, 600)
        } else {
            window.setTimeout(loaderChecker, 200)
        }
    }
    window.setTimeout(loaderChecker, 400)

    function bloccaui() {
        $.blockUI({
            message: '<img src="assets/img/generic/loading.svg">',
            css: {
                //     top: ($(window).height() - 563) / 2 + 'px',
                //     left: ($(window).width() - 1000) / 2 + 'px',
                //     border: 'none',
                //     padding: '15px',
                backgroundColor: 'rgb(0 0 0 / 0%)',
                //     '-webkit-border-radius': '10px',
                //     '-moz-border-radius': '10px'
            }
        });
        $('#top').css('-webkit-filter', 'blur(3px)');
        x = window.setTimeout("sbloccaui(1)", 15000);
    }


    function sbloccaui(i = 0) {
        window.clearTimeout(x)
        $.unblockUI({});
        $('#top').css('-webkit-filter', '');
        if (i == 1) {
            Toast.fire({
                icon: 'warning',
                title: 'E\' stato rilevato un errore. Potrebbe essere necessario ricaricare la pagina!'
            })
        }
    }

    function showTime() {
        var data = new Date();
        var giornosettimana = data.getDay();

        var date = new Date();
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        var gs = date.getDay(); // 0 - 6 (Dom->Sab)
        var session = "AM";
        if (h == 0) {
            h = 12;
        }
        if (h > 12) {
            h = h - 12;
            session = "PM";
        }
        if (gs == 6) {
            if (h >= 12 && m >= 30 && m <= 49) {
                $('.manutenzione').html('<div class="my-2 my-md-0"><div class="alert2 alert-warning" role="alert">MANUTENZIONE TERMINATA, premere CTRL+F5 per aggiornare tutti i file e avere i nuovi aggiornamenti</div></div>')
            } else if (h == 12 && m >= 50) {
                $('.manutenzione').html('')
            } else {
                $('.manutenzione').html('<div class="my-2 my-md-0"><div class="alert2 alert-danger" role="alert">MANUTENZIONE IN CORSO, alcune funzionalità potrebbero non funzionare</div></div>')
            }
        }

        if (h == 10 && m == 30 && s >= 01) {
            $.blockUI({
                message: '<img src="assets/img/generic/backup.gif">',
                css: {
                    backgroundColor: 'rgb(0 0 0 / 0%)',
                    top: ($(window).height() - 512) / 2 + 'px',
                    left: ($(window).width() - 512) / 2 + 'px',
                    border: 'none',
                    padding: '15px',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px'
                }
            });
            $('#top').css('-webkit-filter', 'blur(3px)');
        }
        if (h == 10 && m == 31 && s >= 45) {
            $.unblockUI({});
            $('#top').css('-webkit-filter', '');
        }

        h = (h < 10) ? "0" + h : h;
        m = (m < 10) ? "0" + m : m;
        s = (s < 10) ? "0" + s : s;
        var time = h + ":" + m + ":" + s + " " + session;
        //document.getElementById("MyClockDisplay").innerText = time;
        //document.getElementById("MyClockDisplay").textContent = time;
        setTimeout(showTime, 1000);
    }
    showTime();

    function messaggioalto() {
        console.log('PXS: Controllo comunicazioni.')

        $.post(currentURL + 'assets/inc/impostazioni.php', {
            azione: 'chemessaggio',
            tipo: 2
        }, function(response) {
            if (response.length >= 6) {
                var res = response.split('|-|');
                $('#messgiorno').html('<div class="alert alert-' + res[1] + ' border-2 d-flex align-items-center" role="alert"><div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-3"></span></div><p class="mb-0 flex-1">' + res[0] + '</p></div>');
            } else {
                $('#messgiorno').html('');
            }
        });

        $.post(currentURL + 'assets/inc/impostazioni.php', {
            azione: 'chemessaggio',
            tipo: 7
        }, function(r) {
            if (r.length >= 1) {
                $('#MessaggioBarraLaterale').html(r);
            } else {
                $('#MessaggioBarraLaterale').html('Nessun aggiornamento!');
            }

        });
        setTimeout(messaggioalto, 600000);
    }
    messaggioalto()

    async function showMail() {
        console.log('PXS: Controllo nuove mail.')
        const response = await $.post(currentURL + "assets/mail/check-mail.php", {
            azione: 'controlla'
        });
        (ModalitaDebug ? console.log('check-mail.php => ' + response) : '');
        var res = response.split('|-|');
        if (response.length >= 5) {
            $('#navbarDropdownNotification').addClass('notification-indicator');
            $(document).prop('title', 'Hai ' + res[0] + ' mail da leggere - PixelSmart');
            for (let i = 1; i <= res[0]; i++) {
                var r = res[i].split('|--|');
                $('#CentroMailSupport').after('<div class="list-group-item"><a class="notification notification-flush notification-unread mmsr-aprimail" href="javascript:void(0)" idmail="' + r[0] + '" data-bs-toggle="modal" data-bs-target="#mmsr-mailmodal"><div class="notification-avatar"><div class="avatar avatar-2xl me-3"><img class="rounded-circle" src="assets/img/icons/svgexport-19.svg" alt="" /></div></div><div class="notification-body"><p class="mb-1"><strong>' + r[2] + '</strong><br/>' + r[1] + '</p><span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">💬</span>' + r[3] + '</span></div></a></div>')
            }
        } else {
            $('#navbarDropdownNotification').removeClass('notification-indicator');
        }
        setTimeout(showMail, 100000);
    }

    async function UtentiOnline() {
        const response = await $.post(currentURL + "assets/inc/index-control.php", {
            azione: 'utentilog'
        })
        var rest = response.split('|-|');
        $('.membri-online-chat').html('');
        $('#UtentiOnline').html('');
        for (let i = 0; i < (rest.length - 1); i++) {
            var restm = rest[i].split('//')
            $('.membri-online-chat').append('<div class="d-flex col-md-6 col-sm-12 p-3"><div class="avatar avatar-xl status-online me-2""><img class="rounded-circle" src="assets/img/team/' + restm[0] + '.svg" alt=""></div><div class="flex-1 chat-contact-body ms-2 d-md-none d-lg-block"><div class="d-flex justify-content-between"><h6 class="mb-0 chat-contact-title">' + restm[0][0].toUpperCase() + restm[0].slice(1) + '</h6></div><div class="min-w-0"><a class="chat-contact-content pe-3" href="javascript:void(0)" onclick="ApriChatUtente(' + restm[1] + ')">Apri chat</a></div></div></div>');
            $('#UtentiOnline').append('<span class="dropdown-item px-card py-2"><div class="d-flex align-items-center"><div class="avatar avatar-l status-online me-2"><img class="rounded-circle" src="assets/img/team/' + restm[0] + '.png" alt="" /></div><div class="flex-1"><h6 class="mb-0 title">' + restm[0][0].toUpperCase() + restm[0].slice(1) + '</h6><a class="chat-contact-content pe-3 fs--2 mb-0 d-flex" href="javascript:void(0)" onclick="ApriChatUtente(' + restm[1] + ')">Apri chat</a></div></div></a>')
        }
        setTimeout(UtentiOnline, 15000);
    }
    UtentiOnline();


    function PoP_MoMoC() {
        $("[id^=PoP-]").mouseover(function() {
            $(this).attr('data-bs-placement', 'bottom')
            $(this).attr('data-bs-trigger', 'focus')
            $(this).attr('data-bs-toggle', 'popover')
            $(this).attr('data-bs-content', 'Copia')
            $(this).popover("show").on('shown.bs.popover');
        }).mouseout(function() {
            $(this).popover("dispose");
        }).click(function() {
            $(this).popover("dispose");
            var copyhelper = document.createElement("input");
            copyhelper.className = 'copyhelper'
            document.body.appendChild(copyhelper);
            copyhelper.value = $(this).attr('cp');
            copyhelper.select();
            document.execCommand("copy");
            document.body.removeChild(copyhelper);
            $(this).attr('data-bs-placement', 'bottom')
            $(this).attr('data-bs-trigger', 'focus')
            $(this).attr('data-bs-toggle', 'popover')
            $(this).attr('data-bs-content', 'Copiato!')
            $(this).popover("show").on('shown.bs.popover');
        })
        setTimeout(PoP_MoMoC, 500);
    }
    PoP_MoMoC();

    function PoP_Manuale() {
        $("[id^=PoPM-]").mouseover(function() {
            $(this).attr('data-bs-placement', 'bottom')
            $(this).attr('data-bs-trigger', 'focus')
            $(this).attr('data-bs-toggle', 'popover')
            $(this).attr('data-bs-content', $(this).attr('tt'))
            $(this).popover("show").on('shown.bs.popover');
        }).mouseout(function() {
            $(this).popover("dispose");
        })
        setTimeout(PoP_Manuale, 500);
    }
    PoP_Manuale();

    var newpag;
    var nscheda = 1;
    var totschede = 1;

    $(document).ready(function() {
        $.post(currentURL + 'assets/inc/impostazioni.php', {
            azione: 'licensekey'
        }, function(res) {
            var r = res.split('|-|')
            var d1 = new Date(r[1]);
            var d2 = new Date();
            if (d2 <= d1) {
                console.log('PXS: Chiave di licenza valida!')
                // CARICA TEMA E FUNZIONI
                docReady(themeControl);
                docReady(searchInit);
                docReady(scrollbarInit);
                docReady(tinymceInit('tmce-MMS'));
                // ========================
                $.post(currentURL + 'assets/inc/impostazioni.php', {
                    azione: 'homepage',
                    idu: '<?php echo $session_idu; ?>'
                }, function(ress) {
                    var rr = ress.split(';');
                    chech_app_localStorage();
                    if (rr[0] == '0') {
                        nscheda = '1';
                        if (getCookie('cat') != "" && getCookie('pag') != "") {
                            cambiopagina(getCookie('cat'), getCookie('pag'), getCookie('gett'));
                            showMail();
                        } else {
                            $("#contenutopagina" + nscheda).load("home.php", function(response, status, xhr) {
                                if (status == "error") {
                                    $("#contenutopagina" + nscheda).html("Errore: " + xhr.status + " " + xhr.statusText);
                                    showMail();
                                } else {
                                    showMail();
                                    $(document).prop('title', getCookie('pag')[0].toUpperCase() + getCookie('pag').slice(1) + ' - PixelSmart');
                                    // Swal.fire({
                                    //     title: 'Versione 3.x!',
                                    //     html: 'Versione in via di sviluppo, proseguendo e utilizzando questa versione, potresti trovare ancora alcuni problemi.<br><br> Puoi sempre utilizzare la vecchia versione.',
                                    //     confirmButtonText: 'Prosegui comunque'
                                    // })
                                }
                            });
                        }
                        appLoaded = true;
                    } else {
                        nscheda = '1';
                        cambiopagina(rr[0], rr[1], '')
                        appLoaded = true;
                    }

                    if (ModalitaDebug) {
                        $('#CheckDebug').attr('debug', 1)
                        $('#CheckDebug').html('<span class="fa-regular fa-ban-bug me-1"></span><span>Disattiva Debug')
                    } else {
                        $('#CheckDebug').attr('debug', 0)
                        $('#CheckDebug').html('<span class="fa-regular fa-bug me-1"></span><span>Attiva Debug')
                    }
                    $.post(currentURL + 'assets/inc/impostazioni.php', {
                        azione: 'cercalink'
                    }, function(r) {
                        var res = r.split(';')
                        $('#imp-cl').attr('onclick', "CaricaFrameExt('" + res[1] + "', 'Changelog 3.x PixelSmart', 'Visualizza le modifiche apportate, le nuove funzionalità e i problemi risolti.', '80%')");
                        $('#imp-c').attr('onclick', "CaricaFrameExt('" + res[0] + "', 'Costi di spedizione', 'Tutti i corrieri attualmente attivi per la consegna dei prodotti. Visualizza i prezzi in base ai pesi', '72%')");
                    });
                });
            } else {
                console.log('Chiave di licenza scaduta o non valida!');
                $('.main').remove();
                appLoaded = true;
                $.blockUI({
                    message: '<div class="card shadow mb-4"> <div class="card-header pb-0"> <h6 class="m-0 font-weight-bold text-primary"><i class="fa-duotone fa-satellite-dish"></i> Inserisci codice licenza</h6> </div><div class="card-body"> <div class="row"> <div class="col-md-12"> <div class="form-floating mb-3"> <input id="imp_licenza" type="text" class="form-control" placeholder=" " onchange="VerificaLicenza()" autocomplete="off"> <label for="imp_licenza"><i class="fa-solid fa-key"></i> Codice Licenza</label> </div></div></div></div></div>'
                });
            }
        });
    });

    function cambiopagina(cat, pag, gett) {
        bloccaui();
        cancellaCookie();
        setCookie('cat', cat, Math.pow(2, 31) - 1);
        setCookie('pag', pag, Math.pow(2, 31) - 1);
        setCookie('gett', gett, Math.pow(2, 31) - 1);

        if (pag != '') {
            newpag = pag + '.php';
        } else {
            newpag = pag;
        }
        $("#contenutopagina" + nscheda).load(cat + (cat == '' ? '' : '/') + newpag + gett, function(response, status, xhr) {
            if (status == "error") {
                $("#contenutopagina" + nscheda).html("Errore: " + xhr.status + " " + xhr.statusText);
                sbloccaui();
            } else {
                $("#contenutopagina" + nscheda).attr("pag", pag);
                (cat == '' ? '' : VisualizzatiDiRecente(cat, pag, gett))
                console.log('Pagina attuale: ' + cat + '/' + newpag + gett)
                $(document).prop('title', pag[0].toUpperCase() + pag.slice(1) + ' - PixelSmart');
                $('#tab-' + nscheda).text(pag)
                sbloccaui();
            }
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function cancellaCookie() {
        setCookie('cat', '', -1);
        setCookie('pag', '', -1);
        setCookie('gett', '', -1);
    }

    function app_localStorage(page, data, cosa) {
        if (cosa == 'save') {
            localStorage.setItem('app_' + page + location.pathname, JSON.stringify(data));
        } else if (cosa == 'load') {
            try {
                return JSON.parse(localStorage.getItem('app_' + page + location.pathname))
            } catch (b) {}
        }
    }

    function chech_app_localStorage() {
        if (app_localStorage('feed', '', 'load') == undefined) {
            var b = {
                pagina: 'pg0',
                s_ricerca: '',
                s_stato: '0'
            };
            app_localStorage('feed', b, 'save')
        }

        if (app_localStorage('cat', '', 'load') == undefined) {
            var b = {
                c_descr: '',
                c_ref: '',
                c_quantpr: '',
                c_stato: '',
                c_selmag: '',
            };
            app_localStorage('cat', b, 'save')
        }
    }

    function VisualizzatiDiRecente(p1, p2, p3) {
        var Costruzione = '<a class="dropdown-item fs--1 px-card py-1 hover-primary" href="javscript:void(0)" onclick="cambiopagina(\'' + p1 + '\',\'' + p2 + '\',\'' + p3 + '\')"><div class="d-flex align-items-center"><span class="fas fa-circle me-2 text-300 fs--2"></span><div class="fw-normal title">' + p1[0].toUpperCase() + p1.slice(1) + '<span class="fas fa-chevron-right mx-1 text-500 fs--2" data-fa-transform="shrink-2"></span> ' + p2[0].toUpperCase() + p2.slice(1) + '</div></div></a>';
        $('#VisRecenti').append(Costruzione)
        if ($("#VisRecenti > a").children().length >= 5) {
            $('#VisRecenti>a:nth-child(1)').remove()
        }
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
    //==== FINE SISTEMA DI CARICAMENTO

    function Debug() {
        $.post($(location).attr('protocol') + '//' + $(location).attr('hostname') + '/assets/inc/impostazioni.php', {
            azione: 'setimp',
            tipo: 6,
            valore: ($("#CheckDebug").attr('debug') == 0 ? 1 : 0)
        }, function(r) {
            console.log(r)
            if (r == 'ok') {
                location.reload();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Errore: ' + r
                })
            }
        });
    }

    $(document).on('mouseout', '.zoomLens', function() {
        $('.zoomContainer').remove();
    });

    function replaceErrorImg(image) {
        var imgSrc = image.src;
        var relativePath = imgSrc.substring(imgSrc.lastIndexOf("/") + 1);
        image.onerror = "";
        image.src = imgSrc.replace(relativePath, "noimg.png");
        return true;
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    function delay(n) {
        return new Promise(function(resolve) {
            setTimeout(resolve, n * 1000);
        });
    }

    function addslashes(string) {
        return string.replace(/\\/g, '\\\\').
        replace(/\u0008/g, '\\b').
        replace(/\t/g, '\\t').
        replace(/\n/g, '\\n').
        replace(/\f/g, '\\f').
        replace(/\r/g, '\\r').
        replace(/'/g, '\\\'').
        replace(/"/g, '\\"');
    }
</script>