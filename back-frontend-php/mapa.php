<!DOCTYPE html>
<html>
<head>
<title>H2HC - Mapa de resultados</title>
<link href="css/gmap/default.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBbwcTf-a5E4rZ-a4C_C-uTf1VrI3zM1L0"></script>

<?php  

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set("memory_limit", "-1");

   // include_once 'include/config.php';
	//include_once 'include/database.php';
	



	$rows = array();
	
	if( isset($_FILES["file"]['tmp_name'])){
	
		$fp = fopen($_FILES["file"]['tmp_name'], 'rb');
		while ( ($line = fgets($fp)) !== false) {
			$rows[] = $line;
		}
		
	} else {
	
		$text = trim($_POST['data']); // remove the last \n or whitespace character
		$rows = explode("\n", str_replace("\r", "", $text));
	
	}
	
	/*
	
	$count = 0;
	while ($count < count($rows) ){

		$amostra = explode("|", $rows[$count]);
		
		if( count($amostra) > 1 ){
			$ssid = $amostra[0];
			$enc = $amostra[1];
			$rssi = $amostra[2];
			$lat =  $amostra[3];
			$lng =  $amostra[4];
			$bat =  $amostra[5];

			$dados = array();
			$dados['ssid'] = $ssid ;
			$dados['rssi'] = $rssi;
			$dados['lat'] = $lat;
			$dados['lng'] = $lng;
			$dados['auth'] = $enc;
			$dados['bat'] = $bat;
	
			executeInsert($conn, "log_badge", $dados);
			
		}		
		$count++;
	}
	
	//die();
	*/
	
	$amostra = explode("|", $rows[0]);
	

	
?>

<script src="js/markerwithlabel_packed.js"></script>

<style>


.mark_labels {
	color: red;
   background-color: white;
   font-family: "Lucida Grande", "Arial", sans-serif;
   font-size: 10px;
   font-weight: bold;
   text-align: center;
   width: auto;     
   border: 2px solid black;
   white-space: nowrap;
}


</style>

<script>

var map;

var centro = new google.maps.LatLng( <?=$amostra[3]?>,<?=$amostra[4]?>  ); 

var NOITE_MAPTYPE_ID = 'noite_style';

var DIA_MAPTYPE_ID = 'dia_style';



function HomeControl(controlDiv, map) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map.
  controlDiv.style.padding = '0px';

  // Set CSS for the control border.
  var controlUI = document.createElement('div');
  //controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '0px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Clique para abrir o menu da aplicação';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior.
  var controlText = document.createElement('div');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.backgroundImage = "url('imagens/Abinha.png')";
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.style.width = '99px';
  controlText.style.height = '120px';
  controlText.innerHTML = '';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago.
  google.maps.event.addDomListener(controlUI, 'click', function() {

	  
	  window.location.href = "estatisticas.php";
	    
  });
}


var periodo_dia;
var dia_noite_control_image;


function setPeriodoDia( periodo_dia ){

	if( periodo_dia )
		dia_noite_control_image.style.backgroundImage = "url('imagens/dia_icone.png')";
	else
		dia_noite_control_image.style.backgroundImage = "url('imagens/noite_icone.png')";
}

function diaNoiteControl(div,  map ) {

	  periodo_dia = true;
	  // Set CSS styles for the DIV containing the control
	  // Setting padding to 5 px will offset the control
	  // from the edge of the map.
	  var controlDiv = div;
	  controlDiv.style.padding = '0px';

	  // Set CSS for the control border.
	  var controlUI = document.createElement('div');
	  //controlUI.style.backgroundColor = 'white';
	  controlUI.style.borderStyle = 'solid';
	  controlUI.style.borderWidth = '0px';
	  controlUI.style.cursor = 'pointer';
	  controlUI.style.textAlign = 'center';
	  controlUI.title = 'troque a visualização diurna ou noturna do mapa ';
	  controlDiv.appendChild(controlUI);

	  // Set CSS for the control interior.
	  dia_noite_control_image = document.createElement('div');
	  dia_noite_control_image.style.fontFamily = 'Arial,sans-serif';
	  dia_noite_control_image.style.fontSize = '12px';
	  dia_noite_control_image.style.backgroundImage = "url('imagens/dia_icone.png')";
	  dia_noite_control_image.style.paddingLeft = '0px';
	  dia_noite_control_image.style.marginTop = '24px';
	  dia_noite_control_image.style.marginRight = '24px';
	  dia_noite_control_image.style.width = '75px';
	  dia_noite_control_image.style.height = '75px';
	  dia_noite_control_image.innerHTML = '';
	  controlUI.appendChild(dia_noite_control_image);

	  // Setup the click event listeners: simply set the map to Chicago.
	  google.maps.event.addDomListener(controlUI, 'click', function() {

			periodo_dia = !periodo_dia;
 		    setPeriodoDia( periodo_dia );

 		    if( periodo_dia ) {
 		    	
 		    	map.setMapTypeId( DIA_MAPTYPE_ID );
 		    
 		    } else {

 		    	map.setMapTypeId( NOITE_MAPTYPE_ID );
 		    	
 		    }
		  
		  //alert('Obrigado por clicar e nos ajudar a coletar informações sobre você... Vetü Agradece! \n Não esqueça de conferir o mapa noturno, clicando lá em cima.... ');
	   	  //alert('Ahhh, mais uma coisa... Nossa reunião no Sábado foi bem legal ! Obrigado aos dois pelo caloroso acolhimento.');
		    
	  });
	}




function initialize() {

	var featureOpts_noite = [
	{
		stylers: [
		  		/*
			{ hue: '#000089' },
			{ visibility: 'simplified' },
			//{ visibility: 'on' },
			{ saturation: 50 },
			{ gamma: 0.5 },
			{ weight: 0.5 }
				*/

			{ "invert_lightness": true }, { "visibility": "on" }, { "hue": "#0008ff" }, { "gamma": 1.29 }
		]
	},
	/*
	{
		elementType: 'labels',
		stylers: [
		{ visibility: 'on' },
		{ color: '#FFFFFF' }
		]
	},
	*/
	{
		featureType: 'water',
		stylers: [
		{ color: '#000089' }
		]
	}
	];

	var featureOpts_dia = [
			{ "elementType": "geometry", "stylers": [ { "hue": "#002bff" }, { "visibility": "on" }, { "gamma": 0.55 } ] },{ "elementType": "labels.text.fill", "stylers": [ { "color": "#000079" } ] },{ "elementType": "geometry.fill", "stylers": [ { "weight": 7 } ] }
	 ];
	

	
	google.maps.visualRefresh = true;
	
	var mapOptions = {
		zoom: 16,
		center: centro,
		//disableDefaultUI: true,
		panControl: false,
    	zoomControl: false,
    	scaleControl: false,
    	streetViewControl: false,
    	mapTypeControl: false,
		//mapTypeControlOptions: {
		//	mapTypeIds: [ DIA_MAPTYPE_ID, NOITE_MAPTYPE_ID]
		//},
		mapTypeId: DIA_MAPTYPE_ID
	};

	map = new google.maps.Map(document.getElementById('map-canvas'),
	mapOptions);

	var styledMapOptions_noite = {
		name: 'Noite... hora de ir para a balada fazer warwalk!'
	};

	var styledMapOptions_dia = {
			name: 'Dia azul, dia de warwalk.'
	};

	
	var customMapType_noite = new google.maps.StyledMapType( featureOpts_noite , styledMapOptions_noite);
	var customMapType_dia = new google.maps.StyledMapType( featureOpts_dia , styledMapOptions_dia);
	map.mapTypes.set(NOITE_MAPTYPE_ID, customMapType_noite);
	map.mapTypes.set(DIA_MAPTYPE_ID, customMapType_dia);


	  // Create the DIV to hold the control and call the HomeControl() constructor
	  // passing in this DIV.
	  var homeControlDiv = document.createElement('div');
	  var homeControl = new HomeControl(homeControlDiv, map);

	  homeControlDiv.index = 1;
	  map.controls[google.maps.ControlPosition.LEFT_CENTER].push(homeControlDiv);

	  var diaNoiteControlDiv = document.createElement('div');
	  diaNoiteControl(diaNoiteControlDiv, map );
	  diaNoiteControlDiv.index = 1;
	  map.controls[google.maps.ControlPosition.RIGHT_TOP].push(diaNoiteControlDiv);
	  

	  var image = 'imagens/wi-fi-sinal-simbolo-pequeno.png';
	  var image_wep = 'imagens/wi-fi-sinal-simbolo-pequeno_wep.png';

	  contentString = "Wi-Fi";
	  var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });


<?php 
		

	/*
	ssid[32];  encryptionType;  RSSI;  lat;  lng; bat; 
	*/
	

	$count = 0;
	$lat_ant = 0;
	$lng_ant = 0;
	$ssid_ant = "";
	$sc = 0;
	$ssids = "";
	$bat = 0;
	
	while ($count < count($rows) ){
			
		if( strlen( $rows[$count] ) > 0 ){
			
			$amostra = explode("|", $rows[$count]);
			
			//var_dump(  $amostra );
			
			$ssid = $amostra[0];
			$enc = $amostra[1]; 
			$rssi = $amostra[2]; 
			$lat =  $amostra[3];
			$lng =  $amostra[4];
			$bat =  $amostra[5];

			$ssids = "SSID:".$ssid." - Encryption:".$enc." - RSSI:".$rssi."db - BatLevel:".$bat."<br>";
				
			$img_mark = "";
			$mostra_wep = true;
			$mostra_outros = false;
			
			if( ($enc == "WEP" && $mostra_wep) or ($enc != "WEP" && $mostra_outros)) {
			
				if( $enc == "WEP" )
					$img_mark = "image_wep";
				else 
					$img_mark= "image";
				
		?>

     					var lat<?=$count?> = new google.maps.LatLng(<?=$lat?>,<?=$lng?>);
			  	
						var m<?=$count?> = new MarkerWithLabel({
						    position: lat<?=$count?>,
						    map: map,
						    icon: <?=$img_mark?>,
						    title: "",
			   	            labelContent: "<?=$ssids?>",
			   	            labelAnchor: new google.maps.Point(32, 0),
			   	            labelClass: 'mark_labels',
			   	            labelStyle: {opacity: 1}
							    
						});
						

	<?php
			}	
	
		$count++;
	}

}

?>			

  
	  //google.maps.event.addListener(map, 'click', function(e) {
	//	   alert( e.latLng );
	 // });


	  
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
<body>


<div id="map-canvas"></div>

<style>

</style>


</body>
</html>

