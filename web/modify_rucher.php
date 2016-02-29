<!DOCTYPE html>

<?php
require_once("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");

$text = "";
$info = "";
$rucherid = $_REQUEST['rucherid'];
$rucheid = -1;

$rucher_name = getRucherName($sql,$rucherid);
$rucher_resp = getContact($sql,$rucherid);
$rucher_localisation = getLocation($sql,$rucherid);
$ruche_coordinates=getCoordinates($sql,$rucherid);
$rucher_alert_type = "";

?>
<html>
	<title>Bzzz - Modification du rucher</title>
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
	<div data-role="page" id="modify_rucher" data-theme="b">

               <div data-role="header">
                        <p align="center">Bzzz : Modification du rucher <?php echo $rucherid;?></p>
                </div>

               
                <div data-role="content">
					<center><div id="map"></div></center>

                        <form action="modify_rucher_form.php?rucherid=<?php echo $rucherid;?>" method="post"
                                data-transition="pop" data-direction="reverse"
                                onsubmit="return submitForm()" name="formname" id="formnameid">                
                                
                              <fieldset>
                                        <div data-role="fieldcontain">
                                            <label for="nomid">Nom du Rucher:</label>
                                            <input type="text" name="rucher" id="nom_rucherid" value="<?php echo $rucher_name;?>"/>
                                        </div>

                                        <div data-role="fieldcontain">                                              
                                            <label for="rucher_respid">Responsable du Rucher:</label> 
											<select name="rucher_respid" id="rucher_respid">
											<?php
											$res = getResponsableRucher($sql);
											foreach ($res as $element) {
											?>
											  <option value="<?php echo($element['id']); ?>"><?php echo($element['name']); ?></option>
											<?php
											}
											?> 
											</select>
										</div>
                                       										
                                        <div data-role="fieldcontain">
											<label for="localiteid">Localisation du Rucher:</label> 
											<select name="localiteid" id="localiteid">
											  <?php
											  $res = getLocalite($sql);
											  foreach ($res as $element) {
											  ?>
												<option value="<?php echo($element['id']); ?>"><?php echo($element['place']); ?></option>
											  <?php
											  }
											  ?>   
											</select>
                                        </div>
                                        
                                        <div data-role="fieldcontain">
                                                        <label for="latitudeid">Latitude:</label>
                                                        <input type="text" name="latitude" value="<?php echo $ruche_coordinates['latitude'];?>" id="latitudeid"/>
                                        </div>

                                        <div data-role="fieldcontain">
                                                        <label for="longitudeid">Longitude:</label>
                                                        <input type="text" name="longitude" value="<?php echo $ruche_coordinates['longitude'];?>" id="longitudeid"/>
                                        </div>
                                                    
										<!-- TODO amodify/delete Ruche --> 
                                        <input type="submit" name="mysubmit" value="Modifier" />        
                                </fieldset>
                        </form>
                </div>


		<div data-role="footer">
			<div data-id="navig" data-role="navbar">
				<ul>
					<li><a data-ajax="false" href="rucher.php" data-icon="home">Accueil</a></li>
					<li><a data-ajax="false" href="stats.php?rucherid=<?php echo $rucherid; ?>" data-icon="home">Back</a></li>
				</ul>
			
			</div>
		</div>
			
	</div>
		
</body>
</html>
