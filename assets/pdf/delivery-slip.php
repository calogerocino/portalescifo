<html>

<head>
	<link href="delivery-slip.css" rel="stylesheet" type="text/css">
</head>

<body id="content">

	<table width="100%" id="body" border="0" cellpadding="0" cellspacing="0" style="margin:0;">
		<!-- Addresses -->
		<tr>
			<td colspan="12">

				<table id="addresses-tab" cellspacing="0" cellpadding="0">
					<tr>
						<td width="33%"><span class="bold"> </span><br /><br />
							<?php //// echo $_POST['nfattura']; 
							?>
						</td>
						<td width="33%"><span class="bold">Indirizzo di spedizione</span><br /><br />
							<?php //// echo $_POST['indirizzospe']; 
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="12" height="30">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="12">
				<table id="summary-tab" width="100%">
					<tr>
						<th class="header small" valign="middle">Riferimento</th>
						<th class="header small" valign="middle">Data ordine</th>
						<th class="header small" valign="middle">Corriere</th>
					</tr>
					<tr>
						<td class="center small white"><?php // echo $_POST['reford']; 
														?></td>
						<td class="center small white"><?php // echo $_POST['dataord']; 
														?></td>
						<td class="center small white"><?php // echo $_POST['corriere']; 
														?></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td colspan="12" height="20">&nbsp;</td>
		</tr>

		<!-- Products -->
		<tr>
			<td colspan="12">

				<table class="product" width="100%" cellpadding="4" cellspacing="0">

					<thead>
						<tr>
							<th class="product header small" width="25%">Riferimento</th>
							<th class="product header small" width="65%">Prodotto</th>
							<th class="product header small" width="10%">Quantita</th>
						</tr>
					</thead>

					<tbody>
						<!-- PRODUCTS -->
						<tr class="product {$bgcolor_class}">

							<td class="product center">
								<?php // echo $_POST['riferimentoprod']; 
								?>
							</td>
							<td class="product left">
								<table width="100%">
									<tr>
										<td width="15%">
											<?php // echo $_POST['immagineprod']; 
											?>
										</td>
										<td width="5%">&nbsp;</td>
										<td width="80%">
											<?php // echo $_POST['nomeprod']; 
											?>
										</td>
									</tr>
								</table>
							</td>
							<td class="product center">
								<?php // echo $_POST['quantitaprod']; 
								?>
							</td>
						</tr>
						<!-- END PRODUCTS -->
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="12" height="20">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="7" class="left">
				<table id="payment-tab" width="100%" cellpadding="4" cellspacing="0">
					<tr>
						<td class="payment center small grey bold" width="44%">Metodo di pagamento</td>
						<td class="payment left white" width="56%">
							<table width="100%" border="0">
								<tr>
									<td class="right small"><?php // echo $_POST['pagamento']; 
															?></td>
									<td class="right small"><?php // echo $_POST['importo']; 
															?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td colspan="5">&nbsp;</td>
		</tr>
		<!-- Hook -->
		<tr>
			<td colspan="12" height="30">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="10">
				Footer
			</td>
		</tr>
	</table>
</body>
<script src="https://192.168.1.35/v2/app/jq/jquery.min.js"></script>

<script src='jspdf.debug.js'></script>
<script src='html2pdf.js'></script>
<script>
	var doc = new jsPDF();
	var source = window.document.getElementsByTagName("body")[0];
	doc.fromHTML(
		source,
		15,
		15, {
			'width': 180,
		});

	doc.save("dataurlnewwindow");
	// var pdf = new jsPDF('p', 'pt', 'letter');
	// // var canvas = pdf.canvas;
	// // canvas.height = 72 * 11;
	// // canvas.width = 72 * 8.5;;
	// // var width = 400;
	// html2pdf(document.body, pdf, function(pdf) {

	// 	//var iframe = document.createElement('iframe');
	// 	//iframe.setAttribute('style', 'position:absolute;right:0; top:0; bottom:0; height:100%; width:500px');
	// 	//document.body.appendChild(iframe);
	// 	//iframe.src = pdf.output('datauristring');


	// 	pdf.save('Test.pdf')
	// });
</script>

</html>