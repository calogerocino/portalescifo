<?php

require_once("../assets/inc/database.php");
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;

$columns = array(
	0 => 'o.id',
	1 => 'c.Nome',
	2 => 'o.NOrdine',
	3 => 'o.NOrdine',
	4 => 'o.piattaforma',
	5 => 'o.DataOrdine',
	6 => 'o.Stato',
	8 => 'o.corriere',
	9 => 'o.Tipo',
	10 => 'o.DataEvasione',
	12 => 'o.IDMarketplace',
	13 => 'o.IDPS',
	14 => 'c.Cognome',
);

$where_condition = $sqlTot = $sqlRec = "";

if (!empty($params['search']['value'])) {
	$where_condition .=	" WHERE ";
	$where_condition .= " ( o.id LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.Cliente LIKE '%" . $params['search']['value'] . "%' ";
	//$where_condition .= " OR c.Cognome LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.CAP LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.Cellulare LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.Cellulare2 LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.Telefono LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.EMail LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR c.Citta LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.NOrdine LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.NFattura LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.tracking LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.piattaforma LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.DataOrdine LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.Stato LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.corriere LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.Tipo LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.DataEvasione LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.IDMarketplace LIKE '%" . $params['search']['value'] . "%' ";
	$where_condition .= " OR o.IDPS LIKE '%" . $params['search']['value'] . "%')";
}

$sql_query = "SELECT o.id, c.Cliente, o.NOrdine, o.tracking, o.piattaforma, o.DataOrdine, o.corriere, o.Tipo, o.DataEvasione, o.Stato, o.IDMarketplace, o.IDPS, c.EMail FROM donl_ordini o INNER JOIN donl_clienti c ON (o.idcl=c.id) ";
$sqlTot .= $sql_query;
$sqlRec .= $sql_query;

if (isset($where_condition) && $where_condition != '') {

	$sqlTot .= $where_condition;
	$sqlRec .= $where_condition;
}

$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

$queryTot = mysqli_query($conn, $sqlTot) or die("Errore Database:" . mysqli_error($conn));

$totalRecords = mysqli_num_rows($queryTot);

$queryRecords = mysqli_query($conn, $sqlRec) or die("Errore nella ricerca.");

while ($row = mysqli_fetch_row($queryRecords)) {
	$data[] = $row;
}

$json_data = array(
	"draw"            => intval($params['draw']),
	"recordsTotal"    => intval($totalRecords),
	"recordsFiltered" => intval($totalRecords),
	"data"            => $data
);

echo json_encode($json_data);
