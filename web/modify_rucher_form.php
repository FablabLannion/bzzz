<!DOCTYPE html>

<?php
require_once("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");

$text = "";
$rucherid = $_REQUEST['rucherid'];

$rucher_alert_type = "";

if (isset($_POST['mysubmit'])) {

	//TODO update rucher
	$nom_rucher=$_POST['rucher'];
	$localite = $_POST['localiteid'];
	$lat = $_POST['latitude'];
	$long = $_POST['longitude'];
	$responsable = $_POST['rucher_respid'];
	
	$text = updateRucher($sql,$rucherid,$nom_rucher,$localite,$lat,$long,$responsable);
}

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

	</head>
	
<body>
	<div data-role="page" id="modify_rucher_form" data-theme="b">
	
	   <div data-role="header">
				<p align="center">Bzzz : Modification du rucher <?php echo $rucherid;?></p>
		</div>
	
	    <div data-role="content">
		<?php 
		if (strlen($text) > 0 ) { // soit chaîne vide (si KO) sinon message OK
            echo  "<h1>".$text."</h1>";
          // sinon on peut commencer à travailler
          } else {
            echo "<h3>Erreur</h2>";
          }		?>
	</div>


	<div data-role="footer">
		<div data-id="navig" data-role="navbar">
			<ul>
				<li><a href="rucher.php" data-icon="home">Accueil</a></li>
				<li><a href="stats.php?rucherid=<?php echo $rucherid; ?>" data-icon="home">Back</a></li>
			</ul>
		
		</div>
	</div>

</div>	
				
</body>
</html>
