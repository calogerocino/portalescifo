<div class="container-fluid">
    <div class="row g-0">
        <div class="col-12">
            <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
                <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1">ATTENZIONE! Sistema riparazioni in costruzione. Potrebbero presentarsi problemi all'utilizzo.</p>
            </div>
        </div>
    </div>
    <div id="listariparazioni">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom shadow mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h6 class="card-label text-primary font-weight-bold">Lista ordini</h6>
                        </div>
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-sm btn-light-primary nuovo">
                                <i class="fa-duotone fa-plus"></i> Inserisci nuova</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="dataTables_filter">
                                        <label class="bmd-label">Ricerca per:</label>
                                        <select class="form-control form-control-sm selectpicker" onchange="cambiadatirip()" data-style="btn btn-link" id="sceltadati">
                                            <option value="-" selected>-</option>
                                            <option value="r.ID">ID</option>
                                            <option value="c.Cliente">Cliente</option>
                                            <option value="r.Intervento">Intervento</option>
                                            <option value="r.DataIngresso">Data Ingresso</option>
                                            <option value="r.Stato">Stato</option>
                                            <option value="r.Seriale">Codice</option>
                                            <option value="r.code">Seriale</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div id="sceltadatidiv">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive tabellarip">
                            Caricamento ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--FINE LISTA RIPARAZIONI-->
    <!--INIZO SCHEDA RIPARAZIONE-->

    <div class="row" id="schedariparazione" hidden>
        <div class="col-md-12 my-auto">
            <a href="javascript:void(0)" class="float-left text-primary indietroscheda"><i class="fa-duotone fa-arrow-left"></i> lista riparazioni</a>
            <h3 class="text-center h3 mb-0 text-gray-800" id="nscheda">Scheda N° </h3>
            <div id="salvaschedaid"> <a href="javascript:void(0);" class="float-right d-none d-sm-inline-block btn btn-sm btn-light-primary salvascheda"><i class="fa-duotone fa-plus"></i> Crea Scheda</a></div>
        </div>
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3" data-tabs="tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#anagraficatab" data-toggle="tab">
                        <i class="fa-duotone fa-info-circle"></i> Anagrafica
                        <div class="ripple-container"></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#inouttab" data-toggle="tab">
                        <i class="fa-duotone fa-sign-in-alt"></i> Ingresso/Uscita
                        <div class="ripple-container"></div>
                    </a>
                </li>
                <li class="nav-item prodottitab">
                    <a class="nav-link" href="#prodottitab" data-toggle="tab">
                        <i class="fa-duotone fa-box"></i> Prodotti
                        <div class="ripple-container"></div>
                    </a>
                </li>
                <li class="nav-item" id="statogaranzia" hidden>
                    <a class="nav-link" href="#statogaranziatab" data-toggle="tab">
                        <i class="fa-duotone fa-book-reader"></i> Stato Garanzia
                        <div class="ripple-container"></div>
                    </a>
                </li>
                <li class="nav-item garanziatab">
                    <a class="nav-link" href="#richiedigaranziatab" data-toggle="tab">
                        <i class="fa-duotone fa-toolbox"></i> Richiedi Garanzia
                        <div class="ripple-container"></div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="anagraficatab">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-lg-12 ml-auto mr-auto">
                                <!-- Default Card Example -->
                                <div class="card shadow mt-4 mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Dettagli cliente</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-description">
                                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                            <h4 class="h4 mb-0 text-gray-800">Dati Cliente</h4>
                                        </div>
                                        <div class="row">
                                            <input id="idcliente" type="text" hidden>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-floating mb-3">
                                                            <input id="clientecl" type="search" class="form-control auto" placeholder=" " autocomplete="off" onchange="clienteclchange()">
                                                            <label for="clientecl">Cliente</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-floating mb-3">
                                                            <input id="nickcl" type="text" class="form-control" placeholder=" ">
                                                            <label for="nickcl">Nick</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="cittacl" type="text" class="form-control" placeholder=" ">
                                                            <label for="cittacl">Citta</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="indcl" type="text" class="form-control" placeholder=" ">
                                                            <label for="indcl">Indirizzo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="cellcl" type="text" class="form-control" placeholder=" ">
                                                            <label for="cellcl">Cellulare</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="mailcl" type="email" class="form-control" placeholder=" ">
                                                            <label for="mailcl">E-Mail</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                            <h4 class="h4 mb-0 text-gray-800">Dati Aziendali Cliente</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="pivaz" type="text" class="form-control" placeholder=" ">
                                                            <label for="pivaz">Partiva IVA</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-3">
                                                            <input id="codfisc" type="text" class="form-control" placeholder=" ">
                                                            <label for="codfisc">Codice Fiscale</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-floating mb-3">
                                                            <input id="sdizienda" type="text" class="form-control" placeholder=" ">
                                                            <label for="sdizienda">SDI</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-floating mb-3">
                                                            <input id="pec" type="text" class="form-control" placeholder=" ">
                                                            <label for="pec">PEC</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="modificacliente">
                                            <div class="col-md-6">
                                                <button class="btn btn-outline-primary abclienterip">
                                                    Modifica
                                                </button>
                                            </div>
                                            <div class="col-md-6 ml-auto mr-auto">
                                                <div class="gap-2 d-md-flex justify-content-md-end">
                                                    <button id="bttsalvacliente" class="btn btn-outline-success btn-just-icon saclienterip" hidden>
                                                        <i class="fa-duotone fa-save"></i>
                                                    </button>
                                                    <button id="bttdelcliente" class="btn btn-outline-danger btn-just-icon dabclienterip" hidden>
                                                        <i class="fa-duotone fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="salvacliente">
                                            <div class="col-md-6">
                                                <button class="btn btn-outline-info sanuoclienterip">
                                                    Crea nuovo
                                                </button>
                                            </div>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-lg-12 ml-auto mr-auto">
                                <!-- Default Card Example -->
                                <div class="card shadow mt-4 mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Dettagli Riparazione</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-description">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <input id="marchio" type="text" class="form-control auto1" placeholder=" ">
                                                            <label for="marchio">Marchio</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <input id="prodotto" type="text" class="form-control auto2" placeholder=" ">
                                                            <label for="prodotto">Prodotto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <input id="modello" type="text" class="form-control auto2" placeholder=" ">
                                                            <label for="modello">Modello</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <input id="seriale" type="text" class="form-control auto2" placeholder=" ">
                                                            <label for="seriale">Seriale</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <textarea id="accessorinote" type="text" class="form-control" rows="10" placeholder=" "></textarea>
                                                            <label for="accessorinote">Accessori/Note</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="salvadettrip" hidden>
                                            <div class="col-md-6">
                                                <button class="btn btn-outline-primary abdettrip">
                                                    Modifica
                                                </button>
                                            </div>
                                            <div class="col-md-6 ml-auto mr-auto">
                                                <div class="gap-2 d-md-flex justify-content-md-end">
                                                    <button id="bttsalvadettrip" class="btn btn-outline-success btn-just-icon sadettrip" hidden>
                                                        <i class="fa-duotone fa-save"></i>
                                                    </button>
                                                    <button id="bttdeldettrip" class="btn btn-outline-danger btn-just-icon dabdettrip" hidden>
                                                        <i class="fa-duotone fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="inouttab">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow mt-4 mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Ingresso</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-description">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <input id="dataingresso" type="date" class="form-control">
                                                        <label for="dataingresso">Data Ingresso</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control selectpicker" data-style="btn btn-link" id="intervento">
                                                            <option value="-">-</option>
                                                            <option value="MANUTENZIONE">MANUTENZIONE</option>
                                                            <option value="RIPARAZIONE">RIPARAZIONE</option>
                                                            <option value="TAGLIANDO">TAGLIANDO</option>
                                                            <option value="RICONDIZIONAMENTO">RICONDIZIONAMENTO</option>
                                                            <option value="ORDINE RICAMBI">ORDINE RICAMBI</option>
                                                            <option value="PREVENTIVO">PREVENTIVO</option>
                                                        </select>
                                                        <label for="intervento">Intervento</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <textarea id="motint" type="text" class="form-control" rows="7" placeholder=" "></textarea>
                                                        <label for="motint">Motivi Intervento e/o guasto</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="salvadettingr" hidden>
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-primary abdettingr">
                                                Modifica
                                            </button>
                                        </div>
                                        <div class="col-md-6 ml-auto mr-auto">
                                            <div class="gap-2 d-md-flex justify-content-md-end">
                                                <button id="bttsalvadettingr" class="btn btn-outline-success btn-just-icon sadettingr" hidden>
                                                    <i class="fa-duotone fa-save"></i>
                                                </button>
                                                <button id="bttdeldettingr" class="btn btn-outline-danger btn-just-icon dabdettingr" hidden>
                                                    <i class="fa-duotone fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow mt-4 mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-user"></i> Uscita</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-description">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <input id="datauscita" type="date" class="form-control" onchange="salvadatauscita()">
                                                        <label for="datauscita">Data Uscita</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control selectpicker" data-style="btn btn-link" id="tecnico">
                                                            <option value="Tecnico1">Tecnico1</option>
                                                            <option value="Tecnico2" selected>Tecnico2</option>
                                                        </select>
                                                        <label for="tecnico">Tecnico</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-3">
                                                        <textarea id="noteintervento" type="text" class="form-control" rows="8" onchange="salvanoteinterv()" placeholder=" "></textarea>
                                                        <label for="noteintervento">Note Intervento</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="prodottitab">
                    <div class="row">
                        <div class="col-md-12 ml-auto mr-auto">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button id="bttsalvacliente" class="btn btn-outline-info apricat mb-4">
                                    <i class="fa-duotone fa-book-reader"></i> Apri catalogo
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table responsive" id="tabellaprodotti">
                        <thead>
                            <tr>
                                <th width=13%>Codice</th>
                                <th width=38%>Decrizione</th>
                                <th width=7%>UM</th>
                                <th width=10%>Quantita</th>
                                <th width=10%>Prezzo</th>
                                <th width=10%>Sconto</th>
                                <th width=10%>Totale</th>
                                <th width=2%></th>
                                <th width=0% hidden>ID</th>
                            </tr>
                        </thead>
                        <tbody id="addriga">
                            <tr id="1">
                                <td><input id="cod1" type="text" class="form-control" value="" onchange="cercaprodotto_rip(1)"></td>
                                <td><input id="desc1" type="text" class="form-control" value="" readonly></td>
                                <td><select id="um1" type="text" class="form-control" value="" readonly>
                                        <option value="pz">PZ</option>
                                        <option value="mt">MT</option>
                                        <option value="lt">LT</option>
                                        <option value="kg">KG</option>
                                    </select></td>
                                <td><input id="quant1" type="number" class="form-control" onchange="calcolaprezzo_rip(1)" value=""></td>
                                <td><input id="prez1" type="text" class="form-control" onchange="calcolaprezzo_rip(1)" value=""></td>
                                <td><input id="sco1" type="text" class="form-control" onchange="calcolaprezzo_rip(1)" value="0"></td>
                                <td><input id="tot1" type="text" class="form-control" value="" readonly></td>
                                <td><a class="eliminaprod_rip" tipo="nuovo" id="1" href="javascript:void(0)" title="Elimina Voce"><i class="fa-duotone fa-minus-circle"></i></a></td>
                                <td><input id="idpr1" type="text" class="form-control" value="" hidden></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    <button class="btn btn-outline-info mb-4 nuovariga_rip">
                                        <i class="fa-duotone fa-plus fa-sm"></i> Nuova Riga
                                    </button>
                                </th>
                                <th></th>
                                <th></th>
                                <th id="totquant">0</th>
                                <th></th>
                                <th></th>
                                <th id="totprezz">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row mt-4 mb-4" id="accontotab">
                        <button class="btn btn-outline-primary mb-4 addacconto_rip">
                            <i class="fa-duotone fa-plus fa-sm"></i> Acconto
                        </button>
                        <div id="aggiungiacconto" hidden>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa-duotone fa-euro-sign"></i>
                                                </span>
                                            </div>
                                            <input id="accontocons" type="text" class="form-control" autocomplete="off"></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-comment"></i>
                                                </span>
                                            </div>
                                            <input id="commentoacconto" type="text" class="form-control" autocomplete="off"></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input id="dataacconto" type="date" class="form-control" autocomplete="off"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0);" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm salvaacc_rip"><i class="far fa-save fa-sm text-white-50"></i> Salva Acconto</a>
                        </div>
                    </div>
                    <div class="row" id="listaacconti">
                    </div>
                </div>
                <div class="tab-pane" id="statogaranziatab" id="statogaranzia" hidden>

                </div>
                <div class="tab-pane" id="richiedigaranziatab">
                    <div class="row">
                        <div class="col-md-12 ml-auto mr-auto">
                            <div class="form-group" id="inviagaranzia">
                                <button class="btn btn-outline-info mb-4 inviagar_rip">
                                    <i class="fa-duotone fa-plus fa-sm"></i> Richiedi garanzia
                                </button>
                            </div>
                            <div class="form-group" id="accrifgar" hidden>
                                <a href="javascript:void(0)" class="float-right d-none d-sm-inline-block btn btn-sm btn-success shadow-sm accettagar_rip"><i class="fa-duotone fa-plus fa-sm text-white-50"></i> Accetta</a>
                                <a href="javascript:void(0)" class="float-right d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm rifiutagar_rip"><i class="fa-duotone fa-plus fa-sm text-white-50"></i> Rifiuta</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea id="prodrich" type="text" class="form-control" rows="8" placeholder=" "></textarea>
                                <label for="prodrich">Prodotti richiesti</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea id="anommacc" type="text" class="form-control" rows="8" placeholder=" "></textarea>
                                <label for="anommacc">Anomalia macchina</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea id="notegar" type="text" class="form-control" rows="3" placeholder=" "></textarea>
                                <label for="notegar">Note Garanzia</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--FINE SCHEDA RIPARAZIONE-->

</div>


<script>
    $(document).ready(function(e) {
        aggiornarip()
    });


    var myArr = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'marchi'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr.push(value)
        });
        $(".auto1").autocomplete({
            source: myArr
        });
    });
    var myArr1 = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'prodotti'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr1.push(value)
        });
        $(".auto2").autocomplete({
            source: myArr1
        });
    });
    var myArr2 = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'clienti'
    }, function(data) {
        var res = data.split(',')
        $.each(res, function(index, value) {
            myArr2.push(value)
        });
        $(".auto").autocomplete({
            source: myArr2
        });
    });
</script>