<?php
error_reporting(0);
$data = date("d/m/Y");
?>

<script src="app/js/excellentexport.min.js"></script>

<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor" id="card-with-background">Esporta file ManoMano</h5>
            </div>
            <div class="col-auto ms-auto">
                <a download="manomano13/10/2021.csv" href="javascript:void(0)" onclick="return ExcellentExport.csv(this, 'datatable');" class="btn btn-falcon-default btn-sm">
                    <i class="fa-regular fa-cloud-arrow-down"></i> Esporta</a>
            </div>
        </div>
    </div>
    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive scrollbar">
                    <table class="table table-bordered fs--1 mb-0" id="datatable">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>order</th>
                                <th>carrier</th>
                                <th>tracking_number</th>
                                <th>tracking_url</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php
                            $totale = 0;
                            $corriere = '';
                            $linktracking = '';
                            include("../assets/inc/database.php");
                            $sql = mysqli_query($conn, "SELECT c.Cliente, o.IDMarketplace, o.tracking, o.corriere, o.DataEvasione FROM donl_ordini o INNER JOIN donl_clienti c ON (o.idcl=c.id) WHERE o.DataEvasione = '" . $data . "' AND Piattaforma='Manomano' ORDER BY o.IDMarketplace ASC");

                            if ($sql != false) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    if ($row['corriere'] == 'BRT') {
                                        $corriere = 'Bartolini (parcel ID)';
                                        $linktracking = 'https://vas.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm%26ChiSono='  . $row['tracking'];
                                    } else if ($row['corriere'] == 'DHL') {
                                        $corriere = 'DHL (IT)';
                                        $linktracking = 'https://www.dhl.com/it-it/home/tracking/tracking-express.html?submit=1%26tracking-id=' . $row['tracking'];
                                    } else if ($row['corriere'] == 'Poste Italiane') {
                                        $corriere = 'Poste Italiane';
                                        $linktracking = 'https://www.poste.it/cerca/index.html%23/risultati-spedizioni/' . $row['tracking'];
                                    } else if ($row['corriere'] == 'SDA') {
                                        $corriere = 'SDA';
                                        $linktracking = 'https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it%26tracing.letteraVettura=' . $row['tracking'];
                                    } else if ($row['corriere'] == 'GLS') {
                                        $corriere = 'GLS';
                                        $linktracking = 'https://www.gls-italy.com/?option=com_gls%26view=track_e_trace%26mode=search%26numero_spedizione=' . $row['tracking'] . '%26tipo_codice=nazionale';
                                    } else if ($row['corriere'] == 'SAVISE') {
                                        $corriere = 'Savise';
                                        $linktracking = 'https://www.oneexpress.it/it/cerca-spedizione/';
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $row['IDMarketplace'] . "</td>";
                                    echo "<td>" . $corriere . "</td>";
                                    echo "<td>" . $row['tracking'] . "</td>";
                                    echo "<td>" . $linktracking . "</td>";
                                }
                            } else {
                                echo $conn->error;
                                echo "<tr><td colspan='4'>NESSUN RISULTATO</td></tr> ";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>