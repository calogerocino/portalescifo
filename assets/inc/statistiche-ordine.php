<?php
error_reporting(0);
session_start();

include("database.php");
$azione = $_POST['azione'];

if ($azione == 'aggiorna') {
    $dati = '';
    $clausola1 = $_POST['clausola1'];
    $clausola2 = $_POST['clausola2'];
    $risultato = array();

    $sql = "SELECT Prodottin.NomeProdotto, SUM(RELPO.quantita) AS TotaleVendite, Prodottin.CodiceProdotto FROM Prodottin INNER JOIN RELPO ON Prodottin.=RELPO.IDP INNER JOIN Ordini ON RELPO.IDO=Ordini.ID WHERE RELPO.IDO >=12396 " . $clausola1 . " GROUP BY Prodottin.NomeProdotto ORDER BY TotaleVendite DESC";
    $sql1 = "SELECT Prodotti.NomeProdotto, SUM(RELPO.quantita) AS TotaleVendite, Prodotti.CodiceProdotto FROM Prodotti INNER JOIN RELPO ON Prodotti.ID=RELPO.IDP INNER JOIN Ordini ON RELPO.IDO=Ordini.ID WHERE RELPO.IDO <=12395 " . $clausola2 . " GROUP BY Prodotti.NomeProdotto ORDER BY TotaleVendite DESC";

    $_SESSION['SQLstatistiche'] = $sql;
    $_SESSION['SQL1statistiche'] = $sql1;

    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);

    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            array_push($risultato, array($row['TotaleVendite'], $row['NomeProdotto'], $row['CodiceProdotto']));
        }
    }
    if ($result1->num_rows >= 1) {
        while ($row1 = $result1->fetch_assoc()) {
            array_push($risultato, array($row1['TotaleVendite'], $row1['NomeProdotto'], $row1['CodiceProdotto']));
        }
    }

    arsort($risultato);

    foreach ($risultato as $dato1 => $dato2) {
        $dati .=  '<tr>
        $<td><div class="text-primary font-weight-bolder font-size-lg mb-0">' . $dato2[2] .  '</div></td>
        <td>' . $dato2[1] . '</td>
        <td><div class="font-weight-bolder text-success">' . $dato2[0] . '</div></td>
        </tr>';
    }

    echo $dati;
    exit;
} else if ($azione == 'cercainevasi') {
    $dati = '';

    $sql = "SELECT o.id, c.Cliente, o.NOrdine, o.piattaforma, o.DataOrdine, o.corriere, o.Tipo, o.Stato, o.IDMarketplace, o.IDPS, c.EMail FROM donl_ordini o INNER JOIN donl_clienti c ON (o.idcl=c.id) WHERE (o.Stato='Da Gestire' OR o.Stato='In Stock' OR o.Stato='Allerta') AND (o.DataOrdine LIKE '%/2020' OR o.DataOrdine LIKE '2020-%') AND o.Piattaforma='Sito'";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $dati .=  '';
        }
    }

    echo '<div class="table-responsive">
    <table class="table table-hover table-borderless table-striped" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Ordine</th>
                <th>Piattaforma</th>
                <th>Data Ordine</th>
                <th>Corriere</th>
                <th>Tipo</th>
                <th>Stato</th>
                <th>ID Marketplace</th>
                <th>ID Prestashop</th>
                <th>Mail</th>
            </tr>
        </thead>
        <tbody>'
        . $dati .
        ' </tbody>
    </table>
</div>';
    exit;
}
