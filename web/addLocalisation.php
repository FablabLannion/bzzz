<!DOCTYPE html>
<?php
require_once ("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");

if (isset($_POST['place']) && isset($_POST['longitude']) && isset($_POST['latitude'])) {
	addLocation($sql,$_POST['place'],$_POST['longitude'],$_POST['latitude']);
}

?>
<html>
        <title>Ajout de localisation de Rucher</title>
        <head>
				<meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="CSS/jquery.mobile-1.1.0.min.css" />
                <link rel="stylesheet" href="CSS/leaflet.css" />
				<script src="javascript/leaflet.js"></script>
                <script src="javascript/jquery.js"></script>
                <script src="javascript/jquery.mobile-1.1.0.min.js"></script>   
<script>
	       L_PREFER_CANVAS = true;
        $(document).ready(function() {
            var map;
			var lannion = new L.LatLng(48.700, -3.450);            
			var myLayerGroup = new L.LayerGroup();

            initmap();
        
            function initmap() {

                // set up the map
                map = new L.Map('map');

                // create the tile layer with correct attribution
                var osmUrl = 'http://a.tile.openstreetmap.org/{z}/{x}/{y}.png';
                var osmAttrib = 'Map data © OpenStreetMap contributors';
                var osm = new L.TileLayer(osmUrl, { minZoom: 1, maxZoom: 17, attribution: osmAttrib, detectRetina: true });

				map.setView(lannion,11);
                map.addLayer(osm);
                                      
            };
             
			function onMapClick(e) {
				$('#latitudeid').val( e.latlng.lat );	
				$('#longitudeid').val( e.latlng.lng );	
			}

			map.on('click', onMapClick);
        }); 
        

</script> 		              
</head>
       
<body>
       
        <!-- Debut de la page de declaration de décès -->
        <div data-role="page" id="localisation" data-theme="b">

					

			<div data-role="header">
					<p align="center">Bzzz : Localisation d'un nouveau rucher</p>
			</div>

			<div data-role="content">
				
				<center><div id="map"></div></center>					
				
				<form action="addLocalisation.php" method="post"
							data-transition="pop" data-direction="reverse"
							onsubmit="return submitForm()">
							

							
							<fieldset>
									<div data-role="fieldcontain">
													<label for="placeid">Localisation:</label>
													<input type="text" name="place" id="placeid" />
									</div>

									<div data-role="fieldcontain">
													<label for="latitudeid">Latitude:</label>
													<input type="text" name="latitude" id="latitudeid"/>
									</div>

									<div data-role="fieldcontain">
													<label for="longitudeid">Longitude:</label>
													<input type="text" name="longitude" id="longitudeid"/>
									</div>
																		   
									<input type="submit" name="mysubmit" value="Valider" />
							</fieldset>
					</form>

			</div>
				   
			<div data-role="footer">
					<div data-id="navig" data-role="navbar">
							<ul>
									<li><a href="rucher.php" data-icon="home">Accueil</a></li>
								  <li><a href="rucher.php#declaration">Back</a></li>                       
							</ul>
				   
					</div>
			</div>
                       
        </div>
        <!-- Fin de la page add localisation -->
       
</body>
</html>
