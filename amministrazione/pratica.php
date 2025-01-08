<?php
if (empty($_GET['nuova']) !== TRUE) {
    $idpratica = '0';
} else if (empty($_GET['idpratica']) !== TRUE) {
    $idpratica = $_GET['idpratica'];
} else {
    $idpratica = '-1';
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <a href="javascript:void(0)" onclick="cambiopagina('amministrazione', 'listapratiche','')"><i class="fa-regular fa-arrow-left"></i> Torna a lista pratiche</a>
    <?= ($idpratica == '0' ? '<a href="javascript:void(0)" class="btn btn-falcon-default btn-sm ms-auto mt-2 nuovapratica">Aggiungi nuova pratica</a>' : ''); ?>
</div>
<div class="row">
    <div class="col-lg-9 ml-auto mr-auto">
        <div class="card mb-auto">
            <div class="card-header">
                <div class="row flex-between-center">
                    <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-clipboard"></i> Dati Pratica</h5>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <input id="idpratica" type="text" hidden>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descprat">Descrizione</label>
                            <input id="descprat" type="text" class="form-control" onchange="AggiornaPratica('descprat')">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="noteprat">Note pratica</label>
                            <textarea id="noteprat" type="text" class="form-control" rows="8" onchange="AggiornaPratica('noteprat')"></textarea>
                        </div>
                    </div>
                </div>
                <?= ($idpratica != '0' && $idpratica != '-1' ?
                    '<div class="row mt-3">
                            <div class="col-md-6 e-auto">
                                <a href="javascript:void(0);" class="btn btn-falcon-default btn-sm abpratica">Abilita modifica</a>
                            </div>
                            <div class="col-md-6 ms-auto">
                            <div class="row mb-3">
                                <label class="col-3 col-form-label-sm" for="changestato">Imposta stato:</label>
                                    <div class="col-9">
                                        <select class="form-control form-control-sm" id="changestato" onchange="CambiaStatoPratica()">
                                            <option value="-" selected>-</option>
                                            <option value="0">NUOVA</option>
                                            <option value="6">ATTESA RISPOSTA</option>
                                            <option value="8">ATTESA SCIFO</option>
                                            <option value="2">ATTESA SENTENZA</option>
                                            <option value="4">SOSPESA</option>
                                            <option value="5">CHIUSA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>' : ''); ?>
            </div>
        </div>
    </div>

    <div class="col-lg-3 ml-auto mr-auto">
        <div class="card mb-auto">
            <div class="card-header">
                <div class="row flex-between-center">
                    <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-book-section"></i> Avvocato</h5>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <input id="idavv" type="text" hidden>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nomeavv">Avvocato</label>
                            <input id="nomeavv" type="text" class="form-control autoavv" onchange="AggiornaAvvocato('nomeavv')" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="studioavv">Studio</label>
                            <input id="studioavv" type="text" class="form-control" onchange="AggiornaAvvocato('studioavv')">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="indavv">Indirizzo</label>
                            <input id="indavv" type="text" class="form-control" onchange="AggiornaAvvocato('indavv')">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cellulare1avv">Cellulare</label>
                            <input id="cellulare1avv" type="text" class="form-control" onchange="AggiornaAvvocato('cellulare1avv')">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cellulare2alt">Cellulare Alt.</label>
                            <input id="cellulare2alt" type="text" class="form-control" onchange="AggiornaAvvocato('cellulare2alt')">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="emailavv">EMail</label>
                            <input id="emailavv" type="text" class="form-control" onchange="AggiornaAvvocato('emailavv')">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pecavv">PEC</label>
                            <input id="pecavv" type="text" class="form-control" onchange="AggiornaAvvocato('pecavv')">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <?= ($idpratica != '0' && $idpratica != '-1' ?
                        '<div class="col-md-6 mr-auto">
                        <a href="javascript:void(0);" class="btn btn-falcon-default btn-sm abavv">Abilita modifica</a>
                        </div>' : ''); ?>
                    <div class="col-md-6 ms-auto">
                        <button title="Invia una mail" class="btn btn-falcon-default btn-sm" onclick="$('#mms-mail_dest').val($('#emailavv').val());$('#mms-mail_ogg').val('[#'+$('#idpratica').val()+'#] ' + $('#descprat').val())" data-bs-toggle="modal" data-bs-target="#mms-mailmodal"><i class="fa-duotone fa-envelope-open-text"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-lg-5">
        <div class="card mb-auto">
            <div class="card-header">
                <div class="row flex-between-center">
                    <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-hammer-war"></i> Sentenze</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Sede</th>
                                    <th>Note</th>
                                    <th>Stato</th>
                                </tr>
                            </thead>
                            <tbody id="tabellasentenze">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12" id="creanuovasentenza" hidden>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datasent">Data</label>
                                <input id="datasent" type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sedesent">Sede</label>
                                <input id="sedesent" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notesent">Note</label>
                                <textarea id="notesent" type="text" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?= ($idpratica != '0' && $idpratica != '-1' ?
                    '<div class="row mt-2">
                            <div class="col-md-6">
                                <a href="javascript:void(0);" class="btn btn-falcon-default btn-sm addsent">Aggiungi sentenza</a>
                            </div>
                        </div>' : '') ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card mb-auto">
            <div class="card-header">
                <div class="row flex-between-center">
                    <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><i class="fa-regular fa-link"></i> Allegati</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 col-md-4 col-sm-12">
                        <div id="dropzone">
                            <form class="dropzone needsclick" id="Upload_Allegati" action="pratiche/<?= $idpratica; ?>">
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
                    </div>
                    <div class="col-8 col-md-8 col-sm-12">
                        <div id="formvisass">
                            <div class="px-card py-3">
                                <div class="d-inline-flex">
                                    <?php
                                    function dir_list($directory = FALSE, $idpratica)
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
                                        while (list($key, $value) = each($files)) {
                                            $f++;
                                            if (strpos($value, '[' . $idpratica . '] ') !== FALSE) {
                                                echo '
                                        <div class="border px-2 rounded-3 d-flex flex-between-center bg-white dark__bg-1000 my-1 fs--1 ms-3" id="pr_' . $f . '">
                                            <span class="fs-1 fa-solid fa-paperclip"></span>  
                                            <span style="cursor:pointer;" class="ms-2 pr_apriall" all="' . $value . '" id="pr_nomefile">' . $value . '</span>
                                            <a class="text-300 p-1 ms-3" href="javascript:void()" title="" onclick="EliminaAllegato_pr(\'' . $value . '\', \'' . $f . '\')">
                                                <span class="fs-1 fa-solid fa-trash"></span>  
                                            </a>
                                        </div>';
                                            }
                                        }
                                        if (!$f) $f = "0";
                                    }
                                    dir_list('../upload/pratiche/', $idpratica);
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

<script type="text/javascript">
    var myArr1 = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'avvocati'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr1.push(value)
        });
    });

    $(document).ready(function(e) {
        docReady(dropzoneInit);
        var idpratica = '<?php echo $idpratica ?>';
        $('#idpratica').val(idpratica);
        if (idpratica != '0' && idpratica != '-1') {
            CaricaPratica(idpratica);
            bspratica('blocca');
            bsavv('blocca');
        } else {
            $(".autoavv").autocomplete({
                source: myArr1
            });
            $('#dropzone').remove()
        }
    });
</script>