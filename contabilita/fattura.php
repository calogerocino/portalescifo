<div class="invoice-box" style="position: relative;">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td style="width:70%">
                            <div class="d-flex flex-column flex-root">
                                <div class="title">FATTURA</div>
                            </div>
                        </td>
                        <td style="width:30%">
                            <div class="form-group d-flex flex-column flex-root">
                                <label class="label-block">N° Fattura</label>
                                <input id="ndoc_fatt" type="text" class="form-control" placeholder="Numero fattura" onchange="AggiornaFattura('ndoc_fatt')">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:70%">
                            <input id="idfatt" type="text" class="form-control" value="0" hidden>
                            <input id="idforn" type="text" class="form-control" value="" hidden>
                            <div class="form-group d-flex flex-column flex-root">
                                <label class="label-block">Fornitore</label>
                                <input id="fa_fornitore" type="text" class="form-control auto" value="" placeholder="Ragione Sociale">
                            </div>
                        </td>
                        <td style="width:30%">
                            <div class="d-flex flex-column flex-root">
                                <label class="label-block">Data</label>
                                <input class="form-control" type="date" aria-label="Data documento" aria-describedby="Data documento" id="fa_datadoc" onchange="AggiornaFattura('fa_datadoc')" autocomplete="off">
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="heading">
            <td>Imponibile</td>
            <td>Importo</td>
        </tr>
        <tr class="item">
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="imp22"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="imp22" placeholder="Imponibile 22%" type="text" class="form-control" onchange="$('#totale22').val(Math.round(parseFloat($(this).val())+(($(this).val() * 22) / 100)).toFixed(2)); CalcolaTotale_fa();">
                </div>
            </td>
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="totale22"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="totale22" placeholder="Totale 22%" type="text" class="form-control" onchange="$('#imp22').val(Math.round(($(this).val() *  100) / 122).toFixed(2)); CalcolaTotale_fa(); AggiornaFattura('totale22')">
                </div>
            </td>
        </tr>
        <tr class="item">
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="imp10"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="imp10" placeholder="Imponibile 10%" type="text" class="form-control" onchange="$('#totale10').val(Math.round(parseFloat($(this).val())+(($(this).val() * 10) / 100)).toFixed(2)); CalcolaTotale_fa()">
                </div>
            </td>
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="totale10"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="totale10" placeholder="Totale 10%" type="text" class="form-control" onchange="$('#imp10').val(Math.round(($(this).val() *  100) / 110).toFixed(2)); CalcolaTotale_fa(); AggiornaFattura('totale10')">
                </div>
            </td>
        </tr>
        <tr class="item">
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="imp4"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="imp4" placeholder="Imponibile 4%" type="text" class="form-control" onchange="$('#totale4').val(Math.round(parseFloat($(this).val())+(($(this).val() * 4) / 100)).toFixed(2)); CalcolaTotale_fa()">
                </div>
            </td>
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="totale4"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="totale4" placeholder="Totale 4%" type="text" class="form-control" onchange="$('#imp4').val(Math.round(($(this).val() *  100) / 104).toFixed(2)); CalcolaTotale_fa(); AggiornaFattura('totale4')">
                </div>
            </td>
        </tr>
        <tr class="item last">
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="impese"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="impese" placeholder="Esente IVA" type="text" class="form-control" onchange="$('#totaleese').val($(this).val()); CalcolaTotale_fa()">
                </div>
            </td>
            <td>
                <div class="input-group mb-3">
                    <span class="input-group-text" for="impese"><i class="fa-solid fa-euro-sign"></i></span>
                    <input id="totaleese" placeholder="Esente IVA" type="text" class="form-control" onchange="$('#impese').val($(this).val()); CalcolaTotale_fa(); AggiornaFattura('totaleese')">
                </div>
            </td>
        </tr>
        <tr class="total">
            <td></td>
            <td>Totale: € <span class="font-size-h2 font-weight-boldest text-danger mb-1" id="totalefattura"></span></td>
        </tr>
        <tr class="heading">
            <td>Metodo di pagamento</td>
            <td>Allegato</td>
        </tr>
        <tr class="details">
            <td>
                <select style="width:80%" class="form-select selectpicker" data-style="btn btn-link" id="fa_tippag" data-live-search="true" data-size="7" onchange="NuovoPagamento_fa()">
                    <option value="null" selected>Nessuno</option>
                </select>
            </td>
            <td>
                <div class="form-group" id="formupload">
                    <button id="CaricaAllegato_fa" href="javascript:void(0);" class="btn btn-light btn-icon-split CaricaAllegato_fa">
                        <span class="icon text-gray-600">
                            <i class="far fa-image"></i>
                        </span>
                        <span class="text">Carica allegato</span>
                    </button>
                </div>
                <div class="form-group" id="formvisualizza" hidden>
                    <button id="visall_fa" href="javascript:void(0);" class="btn btn-light btn-icon-split VisualizzaAllegato_fa">
                        <span class="icon text-gray-600">
                            <i class="far fa-image"></i>
                        </span>
                        <input id="fa_nomefile" type="text" hidden>
                        <span class="text">Visualizza allegato</span>
                    </button>
                </div>
            </td>
        </tr>
        <tr class="heading">
            <td>Note</td>
            <td></td>
        </tr>
        <tr class="details">
            <td>
                <textarea id="note" type="text" class="form-control" rows="3" onchange="AggiornaFattura('note')"></textarea>
            </td>
            <td>
                <div class="form-group ml-2">
                    <a class="nav-link btn btn-falcon-default btn-sm mt-1 NuovoPagamento_fa" href="javascript:void(0)">Aggiungi scadenza</a>
                </div>
                <div class="form-group ml-2">
                    <a class="nav-link btn btn-falcon-default btn-sm mt-1 NuovoDocumento_fa" href="javascript:void(0)">Salva documento</a>
                </div>
            </td>
        </tr>
    </table>
</div>

<script>
    $(document).ready(function() {
        NuovaModalitaPagamento_fa()
    });

    var myArr = new Array();
    $.post(currentURL + 'assets/inc/autocomplete.php', {
        azione: 'fornitori'
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