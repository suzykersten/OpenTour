<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
  <title>Intro to graphics - 4.9</title>

  <link rel="stylesheet" href="https://js.arcgis.com/4.9/esri/css/main.css">
 <script src="https://js.arcgis.com/4.9/"></script>

  <style>
    html,
    body,
    #viewDiv {
      padding: 0;
      margin: 0;
      height: 100%;
      width: 100%;
    }

    html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
    #map{
      padding:0;
    }

ul{
	list-style:none;
	padding-left:0px;
}
li{
	padding-left:0px;
	display:block;
}
#audioLabel{
	text-align:left;
}

#lat, #long{
	display:none;
}
  </style> 
  
 <script>
    
var apiGeolocationSuccess = function(position) {
    //alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
};

var tryAPIGeolocation = function() {
    jQuery.post( "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyDCa1LUe1vOczX1hO_iGYgyo8p_jYuGOPU", function(success) {
        apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
  })
  .fail(function(err) {
    alert("API Geolocation error! \n\n"+err);
  });
};

var browserGeolocationFail = function(error) {
};

var tryGeolocation = function() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        browserGeolocationSuccess,
      browserGeolocationFail,
      {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
  }
};


var browserGeolocationSuccess = function(position) {
    //alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
	document.getElementById("lat").innerHTML = position.coords.latitude;
	document.getElementById("long").innerHTML = position.coords.longitude;

 require([
      "esri/Map",
      "esri/views/MapView",
      "esri/Graphic",
      "esri/geometry/ScreenPoint",
      "esri/WebMap"
    ], function(
Map, MapView, Graphic, ScreenPoint, WebMap
    ) {

      // var myPoint = null
      var myPointGraphic = null
      var myLocationGraphic=null;
	  
	  var webmap = new WebMap({
        //basemap: 'topo',
		//basemap: "streets"
		//basemap: "satellite",
		basemap: "hybrid",
		 });
      var view = new MapView({
        center: [document.getElementById("long").innerHTML-.003, document.getElementById("lat").innerHTML-.003],
        container: "viewDiv",
        map: webmap,
        zoom: 20
      });
      
      
      view.on("click", (event)=> {
        if (myPointGraphic != null){
          console.log('removing')
          view.graphics.remove(myPointGraphic)
        }
        // console.log('Harrison Welch')
        // console.log("mapPoint = " + JSON.stringify(event.mapPoint, null, 2))
        // console.log("map x map y = " + event.mapPoint.latitude + " y: " + event.mapPoint.longitude);
        // console.log("x = " + JSON.stringify(event.x, null, 2))
        // console.log("y = " + JSON.stringify(event.y, null, 2))
        // console.log("event = " + JSON.stringify(event, null, 2))
        
        var point = {
          type: "point", // autocasts as new Point()
          longitude: event.mapPoint.longitude,
          latitude: event.mapPoint.latitude
        };
        // Create a symbol for drawing the point
        var markerSymbol = {
          type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
          color: [226, 119, 40],
          outline: { // autocasts as new SimpleLineSymbol()
            color: [255, 255, 255],
            width: 2
          }
        };
        // Create a graphic and add the geometry and symbol to it
		delete myPointGraphic;
        myPointGraphic = new Graphic({
          geometry: point,
          symbol: markerSymbol
        });

        // add the point
        view.graphics.addMany([myPointGraphic])
 	
      });
	 var point = {
          type: "point", // autocasts as new Point()
          longitude: document.getElementById("long").innerHTML,
          latitude:  document.getElementById("lat").innerHTML
        };
        // Create a symbol for drawing the point
        var markerSymbol = {
          type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
          color: [0, 119, 150],
          outline: { // autocasts as new SimpleLineSymbol()
            color: [255, 255, 255],
            width: 3
          }
        };
      myLocationGraphic=new Graphic({
          geometry: point,
          symbol: markerSymbol
        });
		   
        view.graphics.addMany([myLocationGraphic])
    });

}

tryGeolocation();

//map.basemap="topo";
  </script>
</head>

<body>
<!--
<ul>
<?php
$dir    = 'audio/';
$files = scandir($dir, 1);

foreach($files as $i){

if($i!=".."&&$i!="."&&substr( $i, strlen( $i ) -  4) == ".mp3"){
	echo '<li><p id="audioLabel">'.$i."</p>";
?><audio  controls><source src="<?php echo "audio/".$i;?>" type="audio/mp3"></source></audio>
<?php
}
echo "</li><br>";
}
?>
</ul>
-->
<p id="lat"/>
<p id="long"/>
  <div id="viewDiv" height="50%"></div>

<style>

.esri-view-surface{
    width: 50% !important;
    height: 70% !important;

}

esri-popup__main-container.esri-widget {
      width: 310px !important;
      max-height: 300px !important;
    }
</style>
</body>

</html>

 