<?php


?>


<!DOCTYPE html>
<html>
<head>
<title>Testes com os mapas do google</title>
<link href="../css/gmap/default.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
var map;
var brooklyn = new google.maps.LatLng(-23.585975,-46.608946);

var lat1 = new google.maps.LatLng(-23.584381682468734, -46.60430431365967);
var lat2 = new google.maps.LatLng(-23.588648994465935, -46.61072015762329);
var lat3 = new google.maps.LatLng(-23.58900295881357, -46.6015362739563);
var lat4 = new google.maps.LatLng(-23.585286285541848, -46.616835594177246);
var lat5 = new google.maps.LatLng(-23.5917756465004, -46.610398292541504);
var lat6 = new google.maps.LatLng(-23.582552791992782, -46.599884033203125);

var latPessoa = new google.maps.LatLng(-23.585581263456707, -46.60754442214966);




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
  controlText.style.backgroundImage = "url('../imagens/Abinha.png')";
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.style.width = '99px';
  controlText.style.height = '120px';
  controlText.innerHTML = '';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago.
  google.maps.event.addDomListener(controlUI, 'click', function() {

	  
	  //alert('Obrigado por clicar e nos ajudar a coletar informações sobre você... Vetü Agradece! \n Não esqueça de conferir o mapa noturno, clicando lá em cima.... ');
   	  //alert('Ahhh, mais uma coisa... Nossa reunião no Sábado foi bem legal ! Obrigado aos dois pelo caloroso acolhimento.');
	    
  });
}


var periodo_dia;
var dia_noite_control_image;


function setPeriodoDia( periodo_dia ){

	if( periodo_dia )
		dia_noite_control_image.style.backgroundImage = "url('../imagens/dia_icone.png')";
	else
		dia_noite_control_image.style.backgroundImage = "url('../imagens/noite_icone.png')";
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
	  dia_noite_control_image.style.backgroundImage = "url('../imagens/dia_icone.png')";
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
		center: brooklyn,
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
		name: 'Noite... hora de ir para a balada!'
	};

	var styledMapOptions_dia = {
			name: 'Dia azul, dia de Vetü.'
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
	  

	  var image = '../imagens/buraco.png';

	  contentString = "Buraco";
	  
	  var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });

	  

	  var pessoa = new google.maps.Marker({
	      position: latPessoa,
	      map: map,
	      title: "Sua posição"
	  });
	  
	  
	  var m1 = new google.maps.Marker({
	      position: lat1,
	      map: map,
	      icon: image,
	      title: "Buraco profundo. Cuidado!"
	  });
			
	  var m2 = new google.maps.Marker({
	      position: lat2,
	      map: map,
	      icon: image,
	      title: "Buraco."
	  });

	  var m3 = new google.maps.Marker({
	      position: lat3,
	      map: map,
	      icon: image,
	      title: "Lombada deteriorada"
	  });

	  var m4 = new google.maps.Marker({
	      position: lat4,
	      map: map,
	      icon: image,
	      title: "Buraco profundo. Cuidado!"
	  });

	  var m5 = new google.maps.Marker({
	      position: lat5,
	      map: map,
	      icon: image,
	      title: "Entulho/dejeto na via"
	  });

	  var m6 = new google.maps.Marker({
	      position: lat6,
	      map: map,
	      icon: image,
	      title: "Outro maldito buraco"
	  });

	  google.maps.event.addListener(m1, 'mouseover', function() {
		    infowindow.setContent( m1.title );
		    infowindow.open(map, m1);
		  });
	  
	  google.maps.event.addListener(m2, 'mouseover', function() {
		    infowindow.setContent( m2.title );
		    infowindow.open(map, m2);
		  });	  

	  google.maps.event.addListener(m3, 'mouseover', function() {
		    infowindow.setContent( m3.title );
		    infowindow.open(map, m3);
		  });	  

	  google.maps.event.addListener(m4, 'mouseover', function() {
		    infowindow.setContent( m4.title );
		    infowindow.open(map, m4);
		  });	  

	  google.maps.event.addListener(m5, 'mouseover', function() {
		    infowindow.setContent( m5.title );
		    infowindow.open(map, m5);
		  });	  

	  google.maps.event.addListener(m6, 'mouseover', function() {
		    infowindow.setContent( m6.title );
		    infowindow.open(map, m6);
		  });	  	  	  	  

	  
	  google.maps.event.addListener(map, 'click', function(e) {
		   // alert( e.latLng );
	  });


	    var pessoa_opt = {
	    	      strokeColor: '#0000fF',
	    	      strokeOpacity: 0.5,
	    	      strokeWeight: 2,
	    	      fillColor: '#00008f',
	    	      fillOpacity: 0.25,
	    	      map: map,
	    	      center: latPessoa,
	    	      radius: 200
	    	    };
	    		  

	  var pessoaCircle = new google.maps.Circle( pessoa_opt );
	  

	  
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

