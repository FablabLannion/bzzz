<?php
require_once ("passwords.php");
require_once("loader.php");

// ce fichier vérifie que l'utilisateur est connu du system
// si c'est le cas, ça redirige vers la page rucher (menu principal)
// sinon ça renvoie vers la page d'accueil avec un message d'erreur
	
if (isset($_POST['username']) && isset($_POST['password'])) {
	if (!checkPassword($sql,$_POST['username'], $_POST['password'])) {
		header("location:index.php?msg=Login+ou+password+incorrect");//redirection
	} else {
		// Add this user to the session
		$_SESSION['user'] = $_POST['username'];
		header("location:rucher.php");//redirection
	}
}

?>
