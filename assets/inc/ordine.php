<?php
session_start();

if (empty($_GET['idordine']) !== FALSE) {
	echo "non ce nulla";
} else {
	$idordine = $_GET['idordine'];

	include("../assets/inc/database.php");
	$prodotti = "";


	$sql1 = "SELECT c.id, c.cliente, c.via, c.citta, c.cap, c.telefono, c.cellulare, c.cellulare2, c.EMail FROM donl_ordini o LEFT JOIN donl_clienti c ON (o.idcl=c.id) WHERE o.id=" . $idordine;
	$result1 = $conn->query($sql1);
	if ($result1->num_rows > 0) {
		while ($row1 = $result1->fetch_assoc()) {
			$cliente['id'] = $row1['id'];
			$cliente['cliente'] = stripslashes($row1['cliente']);
			$cliente['via'] = stripslashes($row1['via']);
			$cliente['citta'] = stripslashes($row1['citta']);
			$cliente['cap'] = $row1['cap'];
			$cliente['telefono'] = $row1['telefono'];
			$cliente['cellulare'] = $row1['cellulare'];
			$cliente['cellulare2'] = $row1['cellulare2'];
			$cliente['email'] = $row1['EMail'];
		}
	}

	$sql3 = "SELECT p.ID, p.nome, p.sku, r.quantita, p.disponibilita FROM neg_magazzino p INNER JOIN neg_relpo r ON p.ID = r.IDP WHERE r.IDO=" . $idordine;
	$result3 = $conn->query($sql3);
	if (empty($result3) !== TRUE) {
		if ($result3->num_rows > 0) {
			while ($row3 = $result3->fetch_assoc()) {

				$disp = '';
				if ($row3['disponibilita'] != '0000-00-00') {

					$oggi = strtotime("now");
					$dataDisp = strtotime($row3['disponibilita']);
					if ($dataDisp > $oggi) {
						$res = explode("-", $row3['disponibilita']);
						$disp = '<br/><span class="font-italic text-bg-yellow">Disponibilità prodotto: <span class="text-danger font-weight-bold">' . $res[2] . '/' . $res[1] . '/' . $res[0] . '</span></span>';
					}
				}

				$prodotti = '<tr><td onclick="ApriProdotto_ca(' . $row3['ID'] . ')" style="cursor:pointer;"><img onerror="replaceErrorImg(this);" height="auto" width="128" src="https://portalescifo.it/upload/image/p/' . $row3['ID'] . '.jpg" data-zoom-image="https://portalescifo.it/upload/image/p/' . $row3['ID'] . '.jpg" onmouseover="$.removeData($(this), \'elevateZoom\'); $(this).elevateZoom({zoomWindowFadeIn: 500,zoomWindowFadeOut: 500,lensFadeIn: 500,lensFadeOut: 500});" /></td>
        <td onclick="ApriProdotto_ca(' . $row3['ID'] . ')" style="cursor:pointer;">' . $row3['nome'] . $disp . '</td>
        <td onclick="ApriProdotto_ca(' . $row3['ID'] . ')" style="cursor:pointer;">' . $row3['sku'] . '</td>
		<td onclick="ApriProdotto_ca(' . $row3['ID'] . ')" style="cursor:pointer;">' . $row3['quantita'] . '</td>
		<td><a class="text-primary" href="javascript:void(0);" onclick="InfoOrdineRicambi_ov(' . $row3['ID'] . ', ' . $idordine . ')"><i class="fa-solid fa-info-circle "></i></a>  <a href="javascript:void(0);" onclick="RimuoviProdottoOrdine('.$idordine.', ' . $row3['ID'] . ')"><i class="fa-solid fa-trash-xmark"></i></a></td>
        <tr>' . $prodotti;
			}
		}
	}

	$sql4 = "SELECT `ID`, `NOrdine`, `NFattura`, `IDMarketplace`, `Tracking`, `Piattaforma`, `DataOrdine`, `Stato`, `Corriere`, `Tipo`, `Noteo`, `DataEvasione`, `Pagamento`, `Importo`, `IDPS` FROM `donl_ordini` WHERE `ID`=" . $idordine;
	$result4 = $conn->query($sql4);
	if ($result4->num_rows > 0) {
		while ($row4 = $result4->fetch_assoc()) {
			$ordine['id'] = $row4['ID'];
			$ordine['riferimento'] = $row4['NOrdine'];
			$ordine['nfattura'] = $row4['NFattura'];
			$ordine['idmarketplace'] = $row4['IDMarketplace'];
			$ordine['tracking'] = $row4['Tracking'];
			$ordine['piattaforma'] = $row4['Piattaforma'];
			$ordine['dataordine'] = $row4['DataOrdine'];
			$ordine['stato'] = $row4['Stato'];
			$ordine['corriere'] = $row4['Corriere'];
			$ordine['tipo'] = $row4['Tipo'];
			$ordine['noteo'] = $row4['Noteo'];
			$ordine['dataevasione'] = $row4['DataEvasione'];
			$ordine['pagamento'] = $row4['Pagamento'];
			$ordine['importo'] = $row4['Importo'];
			$ordine['idpresta'] = $row4['IDPS'];
		}
	}

	$sql11 = "SELECT so.Data_stato, dt.valore FROM donl_stato_ordine so INNER JOIN donl_dati dt ON so.IDS=dt.ID WHERE so.IDO=" . $idordine . " ORDER BY so.Data_stato DESC";
	$result11 = $conn->query($sql11);
	if ($result11->num_rows > 0) {
		while ($row11 = $result11->fetch_assoc()) {
			$res = explode(" ", $row11['Data_stato']);
			$resdata = explode("-", $res[0]);
			$resora = explode(":", $res[1]);
			$ordine['cronstato'] .= '<option>' . $resdata[2] . '/' . $resdata[1] .  '/' . $resdata[0] . ' alle ' . $resora[0] . '.' .  $resora[1] . ': ' . $row11['valore'] . '</option>';
		}
	}

	$sql5 = "SELECT `PesoReale`, `PesoVolume`, `Altezza`, `Larghezza`, `Profondita`, `PrezzoInserito`, `PrezzoReale`, `Codici` FROM `donl_corriere` WHERE ID=" . $idordine;
	$result5 = $conn->query($sql5);
	if ($result5->num_rows > 0) {
		while ($row5 = $result5->fetch_assoc()) {
			$spedcorr['pesoreale'] = $row5['PesoReale'];
			$spedcorr['pesovolume'] = $row5['PesoVolume'];
			$spedcorr['altezza'] = $row5['Altezza'];
			$spedcorr['larghezza'] = $row5['Larghezza'];
			$spedcorr['profondita'] = $row5['Profondita'];
			$spedcorr['prezzoinserito'] = $row5['PrezzoInserito'];
			$spedcorr['prezzoreale'] = $row5['PrezzoReale'];
			$spedcorr['codici'] = $row5['Codici'];
		}
	}

	$sql9 = "SELECT `NTicket`, `Stato`, `Tipologia`, `Creatoda`, `Operatore`, `UltimoContatto`, `Problema`, `Aperto`, `Riserva`, `DataCreazione` FROM `donl_ticket` WHERE `ido`=" . $idordine;
	$result9 = $conn->query($sql9);
	if ($result9->num_rows > 0) {
		while ($row9 = $result9->fetch_assoc()) {
			$ticket['nticket'] = $row9['NTicket'];
			$ticket['stato'] = $row9['Stato'];
			$ticket['tipologia'] = $row9['Tipologia'];
			$ticket['creatoda'] = $row9['Creatoda'];
			$ticket['operatore'] = $row9['Operatore'];
			$ticket['ultimocontatto'] = $row9['UltimoContatto'];
			$ticket['problema'] = $row9['Problema'];
			$ticket['aperto'] = $row9['Aperto'];
			$ticket['riserva'] = $row9['Riserva'];
			$ticket['datacreazione'] = $row9['DataCreazione'];
		}
	}

	$sql10 = "SELECT `Immagine` FROM `donl_allegati` WHERE `idtk`='" . $ticket['nticket'] . "'";
	$result10 = $conn->query($sql10);
	if ($result10->num_rows > 0) {
		while ($row10 = $result10->fetch_assoc()) {
			$ticket['immagine'] = base64_encode($row10['Immagine']);
		}
	}
}
