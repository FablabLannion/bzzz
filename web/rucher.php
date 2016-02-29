<!DOCTYPE html>
<?php
require_once ("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");
?>
<html>
  <title>Surveillance des Ruchers</title>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/jquery.mobile-1.1.0.min.css" />
    <script src="javascript/jquery.js"></script>
    <script src="javascript/jquery.mobile-1.1.0.min.js"></script>
  </head>
<body>
<!-- Pages préchargées  -->
<div data-role="content">
  <!--
  <a href="stats.php.html" data-prefetch="true">&nbsp;</a>
  <a href="add_rucher.php" data-prefetch="true">&nbsp;</a>
  -->
</div>


<!-- Debut de la page d'accueil -->
  <div data-role="page" id="accueil" data-theme="b">
    <div data-role="header">
      <p align="center">Bzzz: Surveillance des Ruchers</p>
    </div>

    <div data-role="content">
      <p>Choisissez un rucher</p>
      <?php
      $res=getRuchers($sql);
      //print_r($res);

      if(sizeof($res)) {
        ?>
       
        <ul data-role="listview" data-inset="true" data-filter="true">
        <?php
        foreach ($res as $element) {
        ?>
          <li><a data-ajax="false" href="stats.php?rucherid=<?php echo($element['rucher_id']); ?>"><?php echo($element['name']); ?></a></li>
        <?php 
        } ?>
        </ul>
        <?php
      }
      else {
      ?><p>Aucun rucher enregistré</p><?php
      }
      ?>                     
    </div>
    <div data-role="footer">
      <div data-id="navig" data-role="navbar">
        <ul>
          <li><a href="rucher.php#accueil" class="ui-btn-active ui-state-persist" data-icon="home">Accueil</a></li>
          <li><a href="rucher.php#declaration" class="ui-btn-active ui-state-persist">Nouveau Rucher</a></li>
          <li><a href="#apropos" class="ui-btn-active ui-state-persist">A propos</a></li>
        </ul>
     
      </div>
    </div>
  </div>
  <!-- Fin de la page d'accueil -->
       
  <!-- Debut de la page de création de rucher -->
  <div data-role="page" id="declaration" data-theme="b">
    <div data-role="header">
            <p align="center">Bzzz : Declaration d'un nouveau rucher</p>
    </div>
    <div data-role="content">
      <form action="add_rucher.php" method="post"
            data-transition="pop" data-direction="reverse"
            onsubmit="return submitForm()" name="formname" id="formnameid">
        <fieldset>
          <div data-role="fieldcontain">
            <label for="nomid">Nom du Rucher:</label>
            <input type="text" name="nom_rucher" id="nom_rucherid" />
          </div>
                 
          <div data-role="fieldcontain">
            <label for="localiteid">Localisation de la ruche:</label>
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
            <label for="contactid">Nom du responsable du Rucher:</label>
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
          <input type="submit" name="mysubmit" value="Valider" />
        </fieldset>
      </form>
    </div>
    <div data-role="footer">
      <div data-id="navig" data-role="navbar">
        <ul>
          <li><a href="#accueil" data-icon="home">Accueil</a></li>
          <li><a data-ajax="false" href="addLocalisation.php">Localisation</a></li>
          <li><a href="addContact.php">Contact</a></li>
          <li><a href="#apropos">A propos</a></li>
        </ul>
      </div>
    </div>

  </div>
  <!-- Fin de la page de declaration des ruchers -->
       
  <!-- Debut de la page A propos -->
  <div data-role="page" id="apropos" data-theme="b">

    <div data-role="header">
      <p align="center">Bzzz&nbsp;: Surveillance des Ruchers</p>
    </div>

    <div data-role="content">
      <p>A propos de Bzzz</p>
      <div align="justify">
        <br/>Bzzz<br />
        <u>Back end solution pour la surveillance apicole</u>&nbsp;:<br />
        Envoyer par SMS&nbsp;: Bz ID_ruche capteur1 capteur2 capteur3<br />
        L'ID de la ruche est unique et se construit comme suit: 10*Id_rucher + Id_Ruche<br />
        ex: 504 = ruche 4 du rucher 5<br />
        avec capteur 1&nbsp;: masse<br />
        avec capteur 2&nbsp;: température<br />
        avec capteur 3&nbsp;: ...<br /><br />
        <br />
        <br /><br />
        <u>Version Vocale</u>&nbsp;:<br />
        <br /><br />
        <u>Version Mobile</u>&nbsp;:<br />
      </div>
    </div>
    <div data-role="footer">
      <div data-id="navig" data-role="navbar">
        <ul>
          <li><a href="#accueil" class="ui-btn-active ui-state-persist" data-icon="home">Accueil</a></li>
          <li><a href="#apropos">A propos</a></li>                       
        </ul>
      </div>
    </div>
  </div> 
  <!-- Fin de la page A propos -->
</body>
</html>
