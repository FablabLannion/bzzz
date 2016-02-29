<!DOCTYPE html>
<?php
require_once("passwords.php");
require_once("loader.php");

$text = "";
$info = "";
$rucherid = $_REQUEST['rucherid'];
$rucheid = -1;

$id_ruche = getNbRuches($sql,$rucherid )+1;

if (isset($_POST['mysubmit'])) {

	if (!isset($_POST['info']))  {
		$info = "";
	} else {
		$info=$_POST['info'];
	}

	$text = addRuche($sql,$id_ruche,$info,$rucherid);
}

?>
<html>
	<title>Bzzz - ajout de ruche</title>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="css/jquery.mobile-1.1.0.min.css" />
                <script src="js/jquery.js"></script>
                <script src="js/jquery.mobile-1.1.0.min.js"></script>

	</head>
	
<body>
	<div data-role="page" id="add_ruche" data-theme="b">

               <div data-role="header">
                        <p align="center">Bzzz : Ajout de la Ruche N°<?php echo $id_ruche;?> au rucher <?php echo $rucherid;?></p>
                </div>

               
                <div data-role="content">
                        <p align="center"><? echo $text ?></p>

                        <form action="add_ruche.php?rucherid=<?php echo $rucherid;?>" method="post"
                                data-transition="pop" data-direction="reverse"
                                onsubmit="return submitForm()" name="formname" id="formnameid">
                                <fieldset>
                                        <div data-role="fieldcontain">
                                                        <label for="infoid">Info:</label>
                                                        <input type="text" name="info" id="infoid" />
                                        </div>
                                               
                                        <input type="submit" name="mysubmit" value="Valider" />

                                       
                                </fieldset>
                        </form>

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
