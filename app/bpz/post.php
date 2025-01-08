<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<head>
	<script type="text/javascript" src="BrowserPrint-3.0.216.min.js"></script>
	<script type="text/javascript">
		var selected_device;
		var devices = [];

		function setup() {
			//Get the default device from the application as a first step. Discovery takes longer to complete.
			BrowserPrint.getDefaultDevice("printer", function(device) {

				//Add device to list of devices and to html select element
				selected_device = device;
				devices.push(device);
				// var html_select = document.getElementById("selected_device");
				// var option = document.createElement("option");
				// option.text = device.name;
				// html_select.add(option);

				//Discover any other devices available to the application
				BrowserPrint.getLocalDevices(function(device_list) {
					for (var i = 0; i < device_list.length; i++) {
						//Add device to list of devices and to html select element
						var device = device_list[i];
						if (!selected_device || device.uid != selected_device.uid) {
							devices.push(device);
							// var option = document.createElement("option");
							// option.text = device.name;
							// option.value = device.uid;
							// html_select.add(option);
						}
					}

					selected_device = devices[2];
				}, function() {
					alert("Error getting local devices")
				}, "printer");

			}, function(error) {
				alert(error);
			})
		}

		function getConfig() {
			BrowserPrint.getApplicationConfiguration(function(config) {
				alert(JSON.stringify(config))
			}, function(error) {
				alert(JSON.stringify(new BrowserPrint.ApplicationConfiguration()));
			})
		}

		function writeToSelectedPrinter(dataToWrite) {
			selected_device.send(dataToWrite, undefined, errorCallback);
		}
		var readCallback = function(readData) {
			if (readData === undefined || readData === null || readData === "") {
				alert("No Response from Device");
			} else {
				alert(readData);
			}

		}
		var errorCallback = function(errorMessage) {
			alert("Error: " + errorMessage);
		}

		function readFromSelectedPrinter() {

			selected_device.read(readCallback, errorCallback);

		}

		function getDeviceCallback(deviceList) {
			alert("Devices: \n" + JSON.stringify(deviceList, null, 4))
		}

		function sendImage(imageUrl) {
			url = window.location.href.substring(0, window.location.href.lastIndexOf("/"));
			url = url + "/" + imageUrl;
			selected_device.convertAndSendFile(url, undefined, errorCallback)
		}

		function sendFile(fileUrl) {
			url = window.location.href.substring(0, window.location.href.lastIndexOf("/"));
			url = url + "/" + fileUrl;
			selected_device.sendFile(url, undefined, errorCallback)
		}

		function onDeviceSelected(selected) {
			for (var i = 0; i < devices.length; ++i) {
				if (selected.value == devices[i].uid) {
					selected_device = devices[i];
					return;
				}
			}
		}
		window.onload = setup;
	</script>

</head>

<body>

	<?php


	echo "<script> var cmds = 'CT~~CD,~CC^~CT~';
		cmds += '^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ';
		cmds += '^XA';
		cmds += '^MMT';
		cmds += '^PW256';
		cmds += '^LL0200';
		cmds += '^LS0';
		cmds += '^FO192,0^GFA,01536,01536,00008,:Z64:';
		cmds += 'eJy9VD2L1FAUfcl7yWvysQE7d1grrcXCSmfAsJ1gMaUu29la2Ch+ZFhwmkyGLRX8AGGFNzBYyghxi90fsH9AphCE2UXZTpY1MbnnZjGjYGeay3n33pNzz82LEP/hkWVCUSvg6wb4OEJ6AazjmHBgUO9Hr4GnaPPWEYPJd4phdA19Bv3WF/SLh8zfAX9VAGyBNyxLAf6I3zejcy/ut3CTD8sCPN1NxMUb0B3weAY4iHusbw7+tV3gD6xfz5kf9Tpq91t7PdYPLEr0y0PI1w0/1/vcH5j3FNONW1Qe7BB2fb1C51lO9TpVF+qoMvSrEfcztjzw22FJOvWwPKI4hl8qjc9xP/kjK3/qqBmL8Okd4ts6hF/DwSn43xrStXrJIf5XuYG+M/+xHzfu1wJsbU5ocLn9nKK1OE9p8WSV+gfwQ8pt+KF5ngz63Yz98Ds0v5NNkU9dOraDHfgxgj9W8BX5IfZc9X+kwtHSfNX8CfTOqL7xJRkfQcfpbYH6iWj7g/k8pXrgJ+yMf3bw3mya0HyPbu7SfOW3v81X+cP6m/1jPsH71uk67d/Ocux/FHEe9XqI/UtpyFY1OCZZaqv4gffc2AP/O9Jvv1yLgHPWH9P9lazf1tGDWqAlzYua0JH7HTpPis/w5eoc8xWfoF85v/tTfb+NPvoehNfn+5fnyFN5ff8xr8Z3Wf0vEuAI/rjL9y/n/XeZ/xmwd3GlnT+7P8zP/ZJ/F8394/tQzd+uX75/XnwP9QXywhdcwPgx+K1mjj/222vx+VdY7wR57+7S+y9zfsbz32e+E+iXXfHP5xfCuttr:8D34';
		cmds += '^BY2,2,63^FT201,131^BEI,,Y,N';
		cmds += '^FD" .  $_GET['ean'] . "^FS';
		cmds += '^FT213,81^A0I,25,24^FH\^FD" .  $_GET['ref'] . "^FS';
		cmds += '^FT213,50^ACI,18,10^FH\^FD" .  $_GET['t1'] . "^FS';
		cmds += '^FT213,22^ACI,18,10^FH\^FD" .  $_GET['t2'] . "^FS';
		cmds += '^PQ1,0,1,Y^XZ';

		writeToSelectedPrinter(cmds) </script>";

	echo 'Stampa in corso';


	?>


</body>

</html>