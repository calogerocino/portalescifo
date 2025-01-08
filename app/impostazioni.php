<div class="card mb-3">
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
            <li class="nav-item shadow mb-3 mr-2">
                <a class="nav-link active" id="imp-gen-tab" data-bs-toggle="tab" href="#imp-gen" role="tab" aria-controls="imp-gen" aria-selected="false"><i class="fa-solid fa-wrench-simple"></i> Generali</a>
            </li>
            <li class="nav-item shadow mb-3 mr-2">
                <a class="nav-link" id="imp-sped-tab" data-bs-toggle="tab" href="#imp-sped" role="tab" aria-controls="imp-sped" aria-selected="false"><i class="fa-solid fa-truck"></i> Spedizione</a>
            </li>
            <li class="nav-item shadow mb-3 mr-2">
                <a class="nav-link" id="imp-utenti-tab" data-bs-toggle="tab" href="#imp-utenti" role="tab" aria-controls="imp-utenti" aria-selected="false"><i class="fa-solid fa-lock-keyhole-open"></i> Permessi</a>
            </li>
            <?php
            session_start();
            if ($_SESSION['session_perm'][3][3] == 1) {
                echo "<li class=\"nav-item shadow mb-3 mr-2\"><a class=\"nav-link\" id=\"licenza-tab\" data-bs-toggle=\"tab\" href=\"#licenza\" role=\"tab\" aria-controls=\"licenza\" aria-selected=\"false\"><i class=\"fa-solid fa-key\"></i> Licenza</a></li>";
            } ?>
            <li class="nav-item shadow mb-3 mr-2">
                <a class="nav-link" id="impdati-tab" data-bs-toggle="tab" href="#impdati" role="tab" aria-controls="impdati" aria-selected="false"><i class="fa-solid fa-coins"></i> Dati</a>
            </li>
            <li class="nav-item shadow mb-3 mr-2">
                <a class="nav-link" id="imp-cronmanuali-tab" data-bs-toggle="tab" href="#imp-cronmanuali" role="tab" aria-controls="imp-cronmanuali" aria-selected="false"><i class="fa-light fa-gears"></i> CRON</a>
            </li>
        </ul>
        <div class="tab-content mt-3 mx-0">
            <div class="tab-pane active" id="imp-gen" role="tabpanel" aria-labelledby="imp-gen-tab">
                <h2>Avvisi applicazione</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input id="imp_messgiorno" type="text" class="form-control" placeholder=" " />
                            <label for="imp_messgiorno"><i class="far fa-sticky-note"></i> Testo di avviso</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <select class="form-control selectpicker" data-style="btn btn-link" id="imp_messgiornost" placeholder=" ">
                                <option value="-">-</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                            </select>
                            <label for="imp_messgiornost">Tipo di avviso</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-info" onclick="messaggiodg()"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                </div>
                <h2>Avvisi aggiornamento</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input id="imp_messbarralat" type="text" class="form-control" placeholder=" " onchange="messaggiobl()" />
                            <label for="imp_messbarralat"><i class="far fa-sticky-note"></i> Testo di avviso</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-3">
                        <h2>Tabella tariffaria</h2>
                        <input id="imp-linkcorriere" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Link tabella tariffaria!" onchange="ModificaValore(4);">

                        <h2>Changelog PXS</h2>
                        <input id="imp-changelog" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Link changelog!" onchange="ModificaValore(4);">
                    </div>
                    <div class="col-3">
                        <h2>Prestashop</h2>
                        <div class="form-group"><label class="label-block">Link prestashop API</label><input id="imp-prestalink" type="text" class="form-control" onchange="ModificaValore(5);"></div>
                        <div class="form-group"><label class="label-block">Chiave API</label><input id="imp-prestakey" type="text" class="form-control" onchange="ModificaValore(5);"></div>
                        <div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" id="imp-debuf" checked="false" onchange="ModificaValore(5);"><label class="form-check-label" for="imp-debuf">Debug</label></div>
                    </div>
                    <div class="col-3">
                        <h2>ManoMano</h2>
                        <h6>Attenzione modificare con cura</h6>
                        <div class="form-group"><label class="label-block">Chiave API</label><input id="imp-apikeymano" type="text" class="form-control" readonly></div>
                        <div class="form-group"><label class="label-block">WebService User</label><input id="imp-wbusermano" type="text" class="form-control" onchange="ModificaValore(8);" readonly></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="imp-sped" role="tabpanel" aria-labelledby="imp-sped-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <textarea id="imp_bollcons" type="text" class="form-control" rows="5" onchange="messaggiobollacons();" placeholder=" "></textarea>
                            <label for="imp_bollcons"><i class="far fa-sticky-note"></i> Messaggio bolla di consegna</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="imp-utenti" role="tabpanel" aria-labelledby="imp-utenti-tab">
                <input type="text" id="idu_permimp" value="" hidden>
                <div class="row">
                    <div class="col-md-8 table-responsive scrollbar" style="border-right: 2px solid #b1b5c0;">
                        <table class="table table-bordered fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th>Immagine</th>
                                    <th>Nome</th>
                                    <th>Username</th>
                                    <th>Ruolo</th>
                                    <th>Homepage</th>
                                </tr>
                            </thead>
                            <tbody class="table-hover" id="bd_luser"></tbody>
                        </table>
                    </div>
                    <div class="col-md-4" id="ms_perm_user"></div>
                </div>
            </div>
            <div class="tab-pane" id="licenza" role="tabpanel" aria-labelledby="licenza-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input id="imp_licenza" type="text" class="form-control" placeholder=" " onchange="VerificaLicenza()" />
                            <label for="imp_licenza"><i class="fa-solid fa-key"></i> Codice Licenza</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input id="imp_licenza_codice" type="text" class="form-control" placeholder=" " readonly />
                            <label for="imp_licenza_codice"><i class="fa-solid fa-bullseye-pointer"></i> Codice Licenza</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input id="imp_licenza_scadenza" type="datetime" class="form-control" placeholder=" " readonly />
                            <label for="imp_licenza_scadenza"><i class="fa-solid fa-calendar-star"></i> Scadenza Licenza</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input id="imp_licenza_univoco" type="password" class="form-control" placeholder=" " readonly />
                            <label for="imp_licenza_univoco"><i class="fa-solid fa-fingerprint"></i> Univoco Licenza</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="impdati" role="tabpanel" aria-labelledby="impdati-tab">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Stati ordine</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovostatiordine" type="text" class="form-control form-control-sm mb-2" placeholder="Se vuoi inserire un nuovo stato ordine, inserisci da qui!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-statiordine" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <h2>Progressivo prodotti</h2>
                        <div class="form-floating mb-3">
                            <input id="imp-progprod" type="text" class="form-control" placeholder=" " />
                            <label for="imp-progprod">Progressivo</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <h2>Fornitori prodotti</h2>
                        <div class="row">
                            <div class="col-8">
                                <input id="imp-nuovofornitoreprod" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire un nuovo fornitore prodotti!">
                            </div>
                            <div class="col-2 mt-2">
                                <button class="btn btn-falcon-default btn-sm" type="button" onclick="CreaDato('imp-nuovofornitoreprod')"><svg class="svg-inline--fa fa-plus fa-w-14" data-fa-transform="shrink-3 down-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.625em;">
                                        <g transform="translate(224 256)">
                                            <g transform="translate(0, 64)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                                <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <select id="imp-fornitoreprod" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h2>Tipi di prodotto</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovotipiprodotto" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire un nuovo tipo di prodotto!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-tipiprodotto" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h2>Tipi di ricambi</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovotipiricambi" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire un nuovo tipo di ricambi!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-tipiricambi" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h2>Banche</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovobanche" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire una nuova banca!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-banche" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h2>Tipo pagamento (scadenze)</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovopagscad" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire un nuovo tipo pagamento (scadenza)!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-pagscad" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                    <div class="col-md-6">
                        <h2>Tipo pagamento (fatture)</h2>
                        <?php echo (false ? '<div class="row">
                            <div class="col-8">
                                <input id="imp-nuovopagfatt" type="text" class="form-control form-control-sm mb-2 mt-2" placeholder="Se vuoi inserire un nuovo tipo pagamento (fattura)!">
                            </div>
                        </div>' : ''); ?>
                        <select id="imp-pagfatt" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h2>IVA</h2>
                        <select id="imp-ivaset" class="form-select" style="height: 9rem;" multiple="off">
                        </select>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="imp-cronmanuali" role="tabpanel" aria-labelledby="imp-cronmanuali-tab">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead>
                                <tr class="text-center fw-bold">
                                    <td>Descrizione</td>
                                    <td>Quando avviato</td>
                                    <td>Nome file</td>
                                    <td>Link posizione</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="listacron">
                                <tr>
                                    <td>Cron gestione scadenze in ritardo</td>
                                    <td>1 volta al giorno / alle ore 8:00</td>
                                    <td>cron1g8.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/cron1g8.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="cron1g8" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Importazione ordini da prestashop</td>
                                    <td>ogni giorno / una volta ogni ora</td>
                                    <td>cron1hd.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/cron1hd.php</td>
                                    <td><button class="btn btn-success EseguiCron" EseguiCron="cron1hd"><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Importazione prodotti da prestashop</td>
                                    <td>1 volta al giorno / alle ore 6:00</td>
                                    <td>cronprodotti.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/cronprodotti.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="cronprodotti" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>

                                <tr>
                                    <td>Importazione combinazioni da prestashop</td>
                                    <td>Mai</td>
                                    <td>croncombinazioni.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/croncombinazioni.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="croncombinazioni" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <!-- ANCORA DA CREARE -->
                                <tr>
                                    <td>Aggiornamento prezzi PS=>ITM</td>
                                    <td>Mai</td>
                                    <td>psitm.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/psitm.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="psitm?prezzi=pi" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Aggiornamento tag PS=>ITM</td>
                                    <td>Mai</td>
                                    <td>psitm.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/psitm.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="psitm?tag=pi" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Aggiornamento descrizioni PS=>ITM</td>
                                    <td>Mai</td>
                                    <td>psitm.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/psitm.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="psitm?descrizioni=pi" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Aggiornamento quantita PS=>ITM</td>
                                    <td>Mai</td>
                                    <td>psitm.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/psitm.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="psitm?quantita=pi" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                                <tr>
                                    <td>Aggiornamento quantita ITM=>PS</td>
                                    <td>Mai</td>
                                    <td>psitm.php</td>
                                    <td><? echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/tools/psitm.php</td>
                                    <td><button class="btn btn-danger EseguiCron" EseguiCron="psitm?quantita=ip" disabled><i class="fa-solid fa-play"></i> Esegui</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 rispostacron" hidden>
                    <h3>Risposta sorgente</h3>
                    <div class="row">
                    <div id="rispostacron"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(e) {
        recuperaimpostazioi();
    });
</script>