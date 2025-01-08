<?php
session_start();
if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
    $session_idu = htmlspecialchars($_SESSION['session_idu']);
    if (isset($_GET['iddip'])) {
        $IDDipen_sd = $_GET['iddip'];
    }
    if (isset($_GET['nuovo'])) {
        $IDDipen_sd = 'nuovo';
    }
}
?>

<div class="card shadow">
    <div class="card-body" style=" height: 100vh;">
        <div class="row">
            <h3 style="text-align: center;">Scheda dipendente</h3>
            <div class="row mt-2">
                <div class="col-md-auto mr-1">
                    <h5 class="mt-2 mb-2">Dipendente: </h5>
                </div>
                <div class="col-md-auto w-25 mt-1">
                    <div contenteditable="" id="dpmod_NomeCognome"></div>
                </div>

                <div class="col-md-auto ms-auto">
                    <h5 class="mt-2 mb-2">Stato: </h5>
                </div>
                <div class="col-md-auto w-25 mt-1">
                    <select class="form-control form-control-sm" id="Stato" onchange="AggiornaDati_sd('Stato')">
                        <option value="-" selected>-</option>
                        <option value="0">Assunto</option>
                        <option value="1">Licenziato</option>
                        <option value="2">Malattia</option>
                        <option value="3">Deceduto</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2 col-sm-12 mt-5" style="float:left;min-height: 400px;color:white;">
                <ul id="artSezList" class="nav nav-pills mb-3" role="tablist" style="list-style: none;margin: 0px;padding: 0px;width: 100%;">
                    <li><a class="nav-link active" id="datianag-tab" data-bs-toggle="tab" href="#datianag-det" role="tab" aria-controls="datianag-det" aria-selected="false"><i class="fa-regular fa-id-card-clip"></i> Dati anagrafici</a></li>
                    <li><a class="nav-link" id="datifisc-tab" data-bs-toggle="tab" href="#datifisc-det" role="tab" aria-controls="datifisc-det" aria-selected="false"><i class="fa-regular fa-binary-circle-check"></i> Dati fiscali</a></li>
                    <li><a class="nav-link" id="bustepag-tab" data-bs-toggle="tab" href="#bustepag-det" role="tab" aria-controls="bustepag-det" aria-selected="false"><i class="fa-regular fa-money-check-dollar-pen"></i> Buste paghe</a></li>
                    <li><a class="nav-link" id="econto-tab" data-bs-toggle="tab" href="#econto-det" role="tab" aria-controls="econto-det" aria-selected="false"><i class="fa-regular fa-file-invoice"></i> Estratto conto</a></li>
                    <li><a class="nav-link" id="documenti-tab" data-bs-toggle="tab" href="#documenti-det" role="tab" aria-controls="documenti-det" aria-selected="false"><i class="fa-regular fa-file-arrow-up"></i> Documenti</a></li>
                    <li><a id="nonce" class="nav-link btn btn-falcon-default btn-sm mt-3 NuovoDipendente" href="javascript:void(0)" hidden><i class="fa-regular fa-plus"></i> Crea nuovo</a></li>
                </ul>
            </div>
            <div class="col-md-10 col-sm-12 mt-5 scrollbar" style="max-height:60vh;">
                <div class="tab-content border p-3 mt-3" id="pill-myTabContent">
                    <div class="tab-pane fade show active" id="datianag-det" role="tabpanel" aria-labelledby="datianag-det">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mt-2 mb-2">Indirizzo</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label-block">Indirizzo completo</label>
                                            <input id="Indirizzo" type="text" class="form-control" onchange="AggiornaDati_sd('Indirizzo')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label-block">Citta</label>
                                            <input id="Citta" type="text" class="form-control auto" autocomplete="off" onchange="AggiornaDati_sd('Citta')">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="mt-2 mb-2">Contatti</h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="label-block">Cellulare</label>
                                            <input id="Cellulare" type="text" class="form-control" onchange="AggiornaDati_sd('Cellulare')">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="label-block">E-Mail</label>
                                            <input id="Mail" type="email" class="form-control" onchange="AggiornaDati_sd('Mail')">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="label-block">PEC</label>
                                            <input id="pec" type="email" class="form-control" onchange="AggiornaDati_sd('pec')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="datifisc-det" role="tabpanel" aria-labelledby="datifisc-det">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mt-2 mb-2">Codice Fiscale</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input id="CodFisc" type="text" class="form-control" onchange="AggiornaDati_cf_sd()">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="mt-2 mb-2">Banca</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label-block">Banca</label>
                                            <input id="Banca" type="text" class="form-control" onchange="AggiornaDati_sd('Banca')">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="label-block">IBAN</label>
                                            <input id="Iban" type="text" class="form-control" onchange="AggiornaDati_sd('Iban')">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-block">BIC/SWIFT</label>
                                            <input id="Bic" type="text" class="form-control" onchange="AggiornaDati_sd('Bic')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bustepag-det" role="tabpanel" aria-labelledby="bustepag-det">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ListaBustePaghe_sd">
                                    Caricamento buste paghe...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="econto-det" role="tabpanel" aria-labelledby="econto-det">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive over4 EstrattoConto_sd">
                                    Caricamento estratto conto...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="documenti-det" role="tabpanel" aria-labelledby="documenti-det">
                        <div class="row">
                            <div class="col-2 col-md-2 col-sm-12">
                                <div id="dropzone">
                                    <form class="dropzone needsclick" id="Upload_Allegati" action="dipendente/<?= $IDDipen_sd; ?>">
                                        <div class="dz-message needsclick">
                                            Inserisci i file qui.
                                        </div>
                                    </form>
                                </div>
                                <div id="preview-template" style="display: none;">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="dz-image"><IMG data-dz-thumbnail=""></div>
                                        <div class="dz-details">
                                            <div class="dz-size"><span data-dz-size=""></span></div>
                                            <div class="dz-filename"><span data-dz-name=""></span></div>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                        <div class="dz-success-mark">
                                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <defs></defs>
                                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                                    <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="dz-error-mark">
                                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <defs></defs>
                                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                                    <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                                                        <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <button href="javascript:void(0);" class="nav-link btn btn-falcon-default btn-sm mt-3 StampaEstrattoConto_sd">
                                    <span class="icon text-gray-600">
                                        <i class="fa-regular fa-file-invoice"></i>
                                    </span>
                                    <span class="text">Estratto conto</span>
                                </button>
                            </div>
                            <div class="col-8 col-md-8 col-sm-12">
                                <div class="row" id="formvisass">
                                    <div class="px-card py-3">
                                        <div class="d-inline-flex">
                                            <?php

                                            function dir_list($directory = FALSE)
                                            {
                                                $files = array();
                                                if ($handle = opendir("./" . $directory)) {
                                                    while ($file = readdir($handle)) {
                                                        if ($file != "." & $file != "..") $files[] = $file;
                                                    }
                                                }

                                                closedir($handle);
                                                reset($files);
                                                sort($files);
                                                reset($files);

                                                $d = 0;
                                                $f = 0;

                                                while (list($key, $value, $size) = each($files)) {
                                                    $f++;
                                                    echo '
                                                    <div class="border px-2 rounded-3 d-flex flex-between-center bg-white dark__bg-1000 my-1 fs--1 ms-3" id="alsd' . $f . '">
                                                        <span class="fs-1 fa-solid fa-paperclip"></span>  
                                                        <span style="cursor:pointer;" class="ms-2 ApriAllegato_sd" all="' . $value . '">' . $value . '</span>
                                                        <a class="text-300 p-1 ms-3" href="javascript:void()" title="" onclick="EliminaAllegato_sd(\'' . $value . '\', \'' . $f . '\')">
                                                            <span class="fs-1 fa-solid fa-trash"></span>  
                                                        </a>
                                                    </div>';
                                                }
                                                if (!$f) $f = "0";
                                            }
                                            dir_list('../upload/dipendente/' . $IDDipen_sd . '/');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var IDDipen = '<?= $IDDipen_sd; ?>';

    $(document).ready(function(e) {
        docReady(dropzoneInit);
        if (IDDipen != 'nuovo') {
            $('#nonce').prop('hidden', true);
            CaricaDati_sd();
        } else if (IDDipen == 'nuovo') {
            $('#bustepag-tab').remove();
            $('#econto-tab').remove();
            $('#documenti-tab').remove();
            $('#nonce').prop('hidden', false);
        }
    });
</script>