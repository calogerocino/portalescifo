<?php
//error_reporting(0);
session_start();

include("database.php");
$azione = $_POST['azione'];
$dati = '';

if ($azione == 'caricahome') {
    // ORDINI E VENDITE DI OGGI
    $result = $conn->query("SELECT SUM(Importo) as TotaleImp, COUNT(ID) as TotaliOrd FROM donl_ordini WHERE DataOrdine LIKE '" . date('Y-m-d') . "' AND (Stato !='Annullato' OR Stato !='Rimborsato' OR Stato !='Attesa di pagamento')");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaliOrd'] . '|-|' . (is_null($row['TotaleImp']) ? '0,00' : $row['TotaleImp']) . '|-|';

    // PRODOTTI FUORI STOCK
    $result = $conn->query("SELECT COUNT(ID) as TotaleProd FROM neg_magazzino WHERE ID1!=0 AND PrestaShopDisponibilita=0");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaleProd'] . '|-|';

    // ORDINI IMPORTATI
    $result = $conn->query("SELECT COUNT(ID) as TotaleOrd FROM donl_ordini WHERE Stato='Importato'");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaleOrd'] . '|-|';

    // TOTALI ORDINI DA SPEDIRE
    $result = $conn->query("SELECT COUNT(ID) as TotaleOrd FROM donl_ordini WHERE Stato='In Stock' OR Stato='Da Gestire'");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaleOrd'] . '|-|';

    // VENDITE ULTIMI 7 GIORNI
    $u7gg = '';
    $tu7gg = 0;
    for ($i = 0; $i <= 6; $i++) {
        $sql = "SELECT SUM(Importo) as TotaleImp FROM donl_ordini WHERE DataOrdine = '" . date('Y-m-d', strtotime("-" . $i . " days")) . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tu7gg += (float)$row["TotaleImp"];
            if (is_null($row["TotaleImp"])) {
                $u7gg = $u7gg . "0.00 , ";
            } else {
                $u7gg = $u7gg . number_format($row["TotaleImp"], 2) . ", ";
            }
        }
    }
    $u7gg = substr($u7gg, 0, -2);
    $dati .= number_format($tu7gg, 2, ',', '.') . '|-|' . $u7gg . '|-|';

    // TOTALE PRODOTTI PUBLICATI
    $result = $conn->query("SELECT COUNT(ID) as TotaleProd FROM neg_magazzino WHERE ID1!=0");
    $result1 = $conn->query("SELECT COUNT(ID) as TotaleProd FROM neg_magazzino WHERE 1");
    $row = $result->fetch_assoc();
    $row1 = $result1->fetch_assoc();
    $dati .= number_format((($row['TotaleProd'] * 100) / $row1['TotaleProd']), 0) . '|-|';

    // TOTALE ORDINI MARKETPLACE
    $result = $conn->query("SELECT COUNT(ID) as TotaleOrd FROM donl_ordini WHERE Piattaforma='Sito' AND (Stato!='Rimborsato' OR Stato!='Annullato' OR Stato !='Attesa di pagamento')");
    $result1 = $conn->query("SELECT COUNT(ID) as TotaleOrd FROM donl_ordini WHERE Piattaforma='ManoMano' AND (Stato!='Rimborsato' OR Stato!='Annullato' OR Stato !='Attesa di pagamento')");
    $result2 = $conn->query("SELECT COUNT(ID) as TotaleOrd FROM donl_ordini WHERE Piattaforma='eBay' AND (Stato!='Rimborsato' OR Stato!='Annullato' OR Stato !='Attesa di pagamento')");
    $row = $result->fetch_assoc();
    $row1 = $result1->fetch_assoc();
    $row2 = $result2->fetch_assoc();
    $a1 = (int)$row['TotaleOrd'] + (int)$row1['TotaleOrd'] + (int)$row2['TotaleOrd'];
    $m1 = number_format((($row['TotaleOrd'] * 100) / $a1), 0);
    $m2 = number_format((($row1['TotaleOrd'] * 100) / $a1), 0);
    $m3 = number_format((($row2['TotaleOrd'] * 100) / $a1), 0);

    $dati .=  $m1 . '-' . $m2 . '-' . $m3 . '|-|' . $a1 . '|-|';

    // VENDITE ULTIMI 4 MESI
    $u4ms = '';
    $tu4ms = 0;
    for ($i = 0; $i <= 3; $i++) {
        $sql = "SELECT SUM(Importo) as TotaleImp FROM donl_ordini WHERE DataOrdine LIKE '" . date('Y-m', strtotime("-" . $i . " month")) . "-%'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tu4ms += (float)$row["TotaleImp"];
            if (is_null($row["TotaleImp"])) {
                $u4ms = $u4ms . "0.00-";
            } else {
                $u4ms = $u4ms . number_format($row["TotaleImp"], 2, '.', '') . "-";
            }
        }
    }
    $u4ms = substr($u4ms, 0, -1);
    $dati .= number_format($tu4ms, 2, ',', '.') . '|-|' . $u4ms . '|-|';

    // PRODOTTI VENDUTI (MESE)
    $result = $conn->query("SELECT SUM(r.Quantita) as TotaleProd FROM neg_relpo r INNER JOIN donl_ordini o ON (r.IDO=o.ID) WHERE o.DataOrdine LIKE '" . date('Y-m') . "-%' AND (o.Stato!='Rimborsato' OR o.Stato!='Annullato' OR o.Stato!='Attesa di pagamento')");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaleProd'] . '|-|';

    // PRODOTTI VENDUTI (MESE-1)
    $result = $conn->query("SELECT SUM(r.Quantita) as TotaleProd FROM neg_relpo r INNER JOIN donl_ordini o ON (r.IDO=o.ID) WHERE o.DataOrdine LIKE '" . date('Y-m', strtotime("-1 month")) . "-%' AND (o.Stato!='Rimborsato' OR o.Stato!='Annullato' OR o.Stato!='Attesa di pagamento')");
    $row = $result->fetch_assoc();
    $dati .= $row['TotaleProd'] . '|-|';

    //ORDINI IN RITARDO
    $tr = 0;
    $result = $conn->query("SELECT DataOrdine FROM donl_ordini WHERE Stato='In Stock' OR Stato='Da Gestire'");
    while ($row = $result->fetch_assoc()) {
        $tr += ControllaRitardo($row['DataOrdine']);
    }
    $dati .= $tr . '|-|';

    // RIMBORSI FATTI QUESTO MESE
    $result = $conn->query("SELECT SUM(o.Importo) as TotaleRimb FROM donl_ordini o INNER JOIN donl_stato_ordine so ON (o.ID=so.IDO) WHERE so.IDS='10' AND Data_stato LIKE '" . date('Y-m') . "-%'");
    $row = $result->fetch_assoc();
    $dati .= number_format($row['TotaleRimb'], 2, ',', '.') . '|-|';
    // RIMBORSI FATTI SCORSO MESE
    $result = $conn->query("SELECT SUM(o.Importo) as TotaleRimb FROM donl_ordini o INNER JOIN donl_stato_ordine so ON (o.ID=so.IDO) WHERE so.IDS='10' AND Data_stato LIKE '" . date('Y-m', strtotime("-1 month")) . "-%'");
    $row = $result->fetch_assoc();
    $dati .= number_format($row['TotaleRimb'], 2, ',', '.') . '|-|';

    // CONTROLLO SPESE DI SPEDIZIONE
    $result = $conn->query("SELECT COUNT(ol.ID) AS TotPack, SUM(cp.PrezzoInserito) AS TotSped FROM donl_ordini ol LEFT JOIN donl_corriere cp ON (ol.ID=cp.ID) WHERE ol.Corriere='Poste Italiane' AND ol.DataEvasione LIKE '%/" . date("m") . "/" . date("Y") . "'");
    $row = $result->fetch_assoc();
    $sped['pi'] = $row['TotSped'];
    $pack['tp'] = $row['TotPack'];

    $result = $conn->query("SELECT cp.PesoReale AS TotSped FROM donl_ordini ol LEFT JOIN donl_corriere cp ON (ol.ID=cp.ID) WHERE ol.Corriere='Poste Italiane' AND ol.DataEvasione LIKE '%/" . date("m/Y") . "'");
    while ($row = $result->fetch_assoc()) {
        $result1 = $conn->query("SELECT * FROM donl_costi_spedizione");
        while ($row1 = $result1->fetch_assoc()) {
            if ((floatval($row['PesoReale']) <= floatval($row1['range2'])) && (floatval($row1['PesoReale']) >= floatval($row1['range1']))) {
                $sped['checkp'] = $sped['checkp'] + $row1['prezzo'];
            }
        }
    }

    $dati .=  number_format($sped['pi'], 2, ',', '.') . '|-|' . $pack['tp'] . '|-|' . number_format($sped['checkp'], 2, ',', '.') . '|-|';

    // VENDITE ANNO 0
    $vaq = '';
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $i = "0" . $i;
        }
        //TOTALE ORDINI
        $sql = "SELECT SUM(Importo) as TotaleImp FROM donl_ordini WHERE DataOrdine LIKE '" . date("Y") . "-" . $i . "-%'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (is_null($row["TotaleImp"])) {
                $vaq = $vaq . "0.00, ";
            } else {
                $vaq = $vaq . number_format($row["TotaleImp"], 2, '.', '') . ", ";
            }
        }
    }
    $vaq = substr($vaq, 0, -2);

    // VENDITE ANNO - 1
    $vam = '';
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $i = "0" . $i;
        }
        //TOTALE ORDINI
        $sql = "SELECT SUM(Importo) as TotaleImp FROM donl_ordini WHERE DataOrdine LIKE '" . date("Y", strtotime("-1 year")) . "-" . $i . "-%'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (is_null($row["TotaleImp"])) {
                $vam = $vam . "0.00, ";
            } else {
                $vam = $vam . number_format($row["TotaleImp"], 2, '.', '') . ", ";
            }
        }
    }
    $vam = substr($vam, 0, -2);

    $dati .= $vaq . '|-|' . $vam . '|-|';

    echo $dati;
    exit;
}


function ControllaRitardo($DataOrdine)
{
    // ========== CONTROLLA RITARDO ==========  
    $a = time();
    $b = strtotime($DataOrdine);
    $dd = $a - $b;

    $dt = round($dd / (60 * 60 * 24));
    if ($dt >= 15) {
        return 1;
    } else {
        return 0;
    }
}
