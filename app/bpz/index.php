<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<head>
<script type="text/javascript" src="BrowserPrint-3.0.216.min.js"></script>
<script type="text/javascript">
var selected_device;
var devices = [];
function setup()
{
	//Get the default device from the application as a first step. Discovery takes longer to complete.
	BrowserPrint.getDefaultDevice("printer", function(device)
			{
		
				//Add device to list of devices and to html select element
				selected_device = device;
				devices.push(device);
				var html_select = document.getElementById("selected_device");
				var option = document.createElement("option");
				option.text = device.name;
				html_select.add(option);
				
				//Discover any other devices available to the application
				BrowserPrint.getLocalDevices(function(device_list){
					for(var i = 0; i < device_list.length; i++)
					{
						//Add device to list of devices and to html select element
						var device = device_list[i];
						if(!selected_device || device.uid != selected_device.uid)
						{
							devices.push(device);
							var option = document.createElement("option");
							option.text = device.name;
							option.value = device.uid;
							html_select.add(option);
						}
					}
					
				}, function(){alert("Error getting local devices")},"printer");
				
			}, function(error){
				alert(error);
			})
}
function getConfig(){
	BrowserPrint.getApplicationConfiguration(function(config){
		alert(JSON.stringify(config))
	}, function(error){
		alert(JSON.stringify(new BrowserPrint.ApplicationConfiguration()));
	})
}
function writeToSelectedPrinter(dataToWrite)
{
	selected_device.send(dataToWrite, undefined, errorCallback);
}
var readCallback = function(readData) {
	if(readData === undefined || readData === null || readData === "")
	{
		alert("No Response from Device");
	}
	else
	{
		alert(readData);
	}
	
}
var errorCallback = function(errorMessage){
	alert("Error: " + errorMessage);	
}
function readFromSelectedPrinter()
{

	selected_device.read(readCallback, errorCallback);
	
}
function getDeviceCallback(deviceList)
{
	alert("Devices: \n" + JSON.stringify(deviceList, null, 4))
}

function sendImage(imageUrl)
{
	url = window.location.href.substring(0, window.location.href.lastIndexOf("/"));
	url = url + "/" + imageUrl;
	selected_device.convertAndSendFile(url, undefined, errorCallback)
}
function sendFile(fileUrl){
    url = window.location.href.substring(0, window.location.href.lastIndexOf("/"));
    url = url + "/" + fileUrl;
    selected_device.sendFile(url, undefined, errorCallback)
}
function onDeviceSelected(selected)
{
	for(var i = 0; i < devices.length; ++i){
		if(selected.value == devices[i].uid)
		{
			selected_device = devices[i];
			return;
		}
	}
}
window.onload = setup;
</script>
</head>
<body>

<span style="padding-right:50px; font-size:200%">Zebra Browser Print Test Page</span><br/>
<span style="font-size:75%">This page must be loaded from a web server to function properly.</span><br><br>
Selected Device: <select id="selected_device" onchange=onDeviceSelected(this);></select> <!--  <input type="button" value="Change" onclick="changeDevice();">--> <br/><br/> 
<input type="button" value="Get Application Configuration" onclick="getConfig()"><br/><br/>
<input type="button" value="Send Config Label" onclick="writeToSelectedPrinter('~wc')"><br/><br/>
<input type="button" value="Send ZPL Label" onclick="writeToSelectedPrinter('^XA^FO200,200^A0N36,36^FDTest Label^FS^XZ')"><br/><br/>
<input type="button" value="Get Status" onclick="writeToSelectedPrinter('~hs'); readFromSelectedPrinter()"><br/><br/>
<input type="button" value="Get Local Devices" onclick="BrowserPrint.getLocalDevices(getDeviceCallback, errorCallback);"><br/><br/>
<input type="text" name="write_text" id="write_text"><input type="button" value="Write" onclick="writeToSelectedPrinter(document.getElementById('write_text').value)"><br/><br/>
<input type="button" value="Read" onclick="readFromSelectedPrinter()"><br/><br/>
<input type="button" value="Send BMP" onclick="sendImage('Zebra_logobox.bmp');"><br/><br/>
<input type="button" value="Send JPG" onclick="sendImage('ZebraGray.jpg');"><br/><br/>
<input type="button" value="Send File" onclick="sendFile('wc.zpl');"><br/><br/>
</body>
</html>