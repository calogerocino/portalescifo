<div class="container-fluid">
    <div class="row" id="listaemenudoc">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header pb-0">
                    <h6 class="m-0 font-weight-bold text-primary">Menù</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input name="search" id="search" type="search" class="form-control auto" value="" placeholder=" " onchange="AggiornaDocumenti_do()">
                                <label for="search">Ricerca</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label fw-bold">Periodo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdatett" value="tt" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdate">
                                        Tutto
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdateac" value="ac" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdateac">
                                        Anno Corrente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdatemp" value="mp" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdatemp">
                                        Mese pecedente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdatemc" value="mc" onchange="AggiornaDocumenti_do()" checked>
                                    <label class="form-check-label" for="searchdatemc">
                                        Mese Corrente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdatems" value="ms" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdatems">
                                        Mese Prossimo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdate" id="searchdatedl" value="da" onchange="AggiungiForm_dc()">
                                    <label class="form-check-label" for="searchdatedl">
                                        Dal .. Al ..
                                    </label>
                                </div>
                                <div id="dalalricerca" hidden>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="bmd-label" id="datedal1">Dal:</label>
                                            <input class="form-control" name="datedal" id="datedal" type="date" value="" placeholder="dd/MM/yy" title="format : dd/MM/yy" onchange="AggiornaDocumenti_do()" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="bmd-label" id="dateal1">Al:</label>
                                            <input class="form-control" name="dateal" id="dateal" type="date" value="" placeholder="dd/MM/yy" title="format : dd/MM/yy" onchange="AggiornaDocumenti_do()" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label fw-bold">Visualizza</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchtipo" id="searchtiposa" value="1" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchtiposa">
                                        Saldati
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchtipo" id="searchtipons" value="2 OR Pagato=3" onchange="AggiornaDocumenti_do()" checked>
                                    <label class="form-check-label" for="searchtipons">
                                        Non Saldati
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchtipo" id="searchtipoti" value="0 OR Pagato=1 OR Pagato=2 OR Pagato=3" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchtipoti">
                                        Tutto
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label fw-bold">Tipo Documento</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdoc" id="searchdocbuo" value="1" onchange="AggiornaDocumenti_do()" checked>
                                    <label class="form-check-label" for="searchdocbuo">
                                        Buono
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdoc" id="searchdocfat" value="2" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdocfat">
                                        Fattura
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdoc" id="searchdocddt" value="3" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdocddt">
                                        DDT
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="searchdoc" id="searchdoctu" value="4" onchange="AggiornaDocumenti_do()">
                                    <label class="form-check-label" for="searchdoctu">
                                        Tutto
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-success NuovoDocumento_dc">
                                <i class="fa-duotone fa-plus fa-sm"></i> Nuovo
                            </button>
                            <button class="btn btn-outline-warning FatturaBuoni_dc">
                                <i class="fa-duotone fa-file-invoice-dollar fa-sm"></i> Fattura buoni
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-body table-responsive tabelladoc">
                    Caricamento ...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        AggiornaDocumenti_do();
    });

    var myArr = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'clienti'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr.push(value)
        });
        $(".auto").autocomplete({
            source: myArr
        });
    });
</script>