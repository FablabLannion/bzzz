<!DOCTYPE html>
<?php
require_once("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");

?>
<html>
	<title>Bzzz - suivi des ruchers</title>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="css/jquery.mobile-1.1.0.min.css" />
                <script src="js/jquery.js"></script>
                <script src="js/jquery.mobile-1.1.0.min.js"></script>

	</head>
	
<body>
	<div data-role="page" id="accueil" data-theme="b">

		<div data-role="header">
			<p align="center">Bzzz - suivi des ruchers</p>
		</div>

		<div data-role="content">
			<p>Validation de la création d'un rucher</p>
			<?php
				$nom_rucher=$_POST["nom_rucher"];
				$localite_rucher=$_POST["localiteid"];
				$resp_rucher=$_POST["rucher_respid"];

				$result = addRucher($sql,$nom_rucher,$localite_rucher,$resp_rucher);
				
				if($result)
				{
					echo "Le rucher a été ajouté avec succes";
				}
				else
				{
					echo "Erreur. Veuillez reessayer plustard";
				}
			
			?>
			
			
			
		</div>

		<div data-role="footer">
			<div data-id="navig" data-role="navbar">
				<ul>
					<li><a href="rucher.php" data-icon="home">Accueil</a></li>
					<li><a href="rucher.php#declaration" class="ui-btn-active ui-state-persist">Rucher</a></li>
					<li><a href="stats.php">Statistiques</a></li>
					<li><a href="rucher.php#apropos">A propos</a></li>			
				</ul>
			
			</div>
		</div>
			
	</div>
		
		
	
</body>
</html>
