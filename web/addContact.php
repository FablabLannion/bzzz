<!DOCTYPE html>
<?php
require_once ("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");

$text = "";

if (isset($_POST['mysubmit'])) {
	if (!isset($_POST['mail']))  {
		$text = "Entrer une adresse mail!";
	}

	if (!isset($_POST['phone']))  {
		$text = "Entrer un numéro de téléhone!";
	}

	if (!isset($_POST['adresse']))  {
		$text = "Entrer une addresse";
	}

	if (!isset($_POST['nom']))  {
		$text = "Entrer un nom!";
	}
}

if (isset($_POST['nom']) && isset($_POST['adresse']) && isset($_POST['phone']) && isset($_POST['mail'])) {
	$text = addContact($sql,$_POST['nom'],$_POST['adresse'],$_POST['phone'],$_POST['mail'],$_POST['alerte']);
}

?>
<html>
        <title>Ajout d'un contact responsable de Rucher</title>
        <head>
				<meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="CSS/jquery.mobile-1.1.0.min.css" />
                <script src="javascript/jquery.js"></script>
                <script src="javascript/jquery.mobile-1.1.0.min.js"></script>       </head>
       
<body>
       
        <div data-role="page" id="contact" data-theme="b">

                <div data-role="header">
                        <p align="center">Bzzz : Contact d'un nouveau rucher</p>
                </div>

               
                <div data-role="content">
                        <p align="center"><? echo $text ?></p>

                        <form action="addContact.php" method="post"
                                data-transition="pop" data-direction="reverse"
                                onsubmit="return submitForm()" name="formname" id="formnameid">
                                <fieldset>
                                        <div data-role="fieldcontain">
                                                        <label for="nomid">Nom:</label>
                                                        <input type="text" name="nom" id="nomid" />
                                        </div>

                                        <div data-role="fieldcontain">
                                                        <label for="adresseid">Adresse:</label>
                                                        <input type="text" name="adresse" id="adresseid" />
                                        </div>

                                        <div data-role="fieldcontain">
                                                        <label for="phoneid">Téléphone:</label>
                                                        <input type="text" name="phone" id="phoneid" />
                                        </div>

                                        <div data-role="fieldcontain">
                                                        <label for="mailid">Mail:</label>
                                                        <input type="text" name="mail" id="mailid" />
                                        </div>
                                        
										<div data-role="controlgroup">
												<legend>Mode d'alerte préféré</legend>
												<input type="radio" name="alerte" id="alerte-1" value="SMS" checked="checked" /> <label for="alerte-1">SMS</label>
												<input type="radio" name="alerte" id="alerte-2" value="MAIL" /><label for="alerte-2">Mail</label>
												<input type="radio" name="alerte" id="alerte-3" value="PHONE" /> <label for="alerte-3">Appel téléphonique</label>
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
        <!-- Fin de la page add contact -->               
       
</body>
</html>
