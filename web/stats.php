<!DOCTYPE html>
<?php
require_once("passwords.php");
require_once("loader.php");

// Check that the user is identified
require_once("checkAuth.php");
?>

<?php
// id du rucher
$rucherid = -1;
$text = "";

if (!isset($_REQUEST['rucherid']))  {
  // si pas de rucherid dans la requête, pas possibilité de trouver des ruches
  // --> affichage d'un message d'erreur
  $text = "Erreur: Rucher Id inconnu!";
} else {
  // si on retrouver un rucherid dans la requête, on le note...
  $rucherid = $_REQUEST['rucherid'];

  $name=getRucherName($sql, $rucherid);

  // pour un rucher donné, on récupère les différentes données relatives au rucher
  // le contact
  $contact = getContact($sql, $rucherid);
  // la localisation du rucher
  $localisation = getLocation($sql, $rucherid);
  // le nombre de ruches dans le rucher
  $nb_ruches = getNbRuches($sql, $rucherid);
  // un tableau des ids des ruches du rucher
  $rucheids = getRucheIds($sql,$rucherid);
}
?>
<html>
  <title>BZZZ : Suivi des ruchers</title>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/jquery.mobile-1.1.0.min.css" />
    <link rel="stylesheet" type="text/css" href="CSS/jquery.jqplot.css" />

    <script type="text/javascript" src="javascript/jquery.js"></script>
    <script type="text/javascript" src="javascript/jquery.mobile-1.1.0.min.js"></script>

    <script type="text/javascript" src="javascript/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="javascript/jqplot.dateAxisRenderer.min.js"></script>
	<script type="text/javascript" src="javascript/jqplot.cursor.min.js"></script>
    
    <!--script type="text/javascript" src="javascript/BzzzStats.php"></script-->
    <script type="text/javascript">
<?php
require_once("javascript/BzzzStats.php");
?>
      $(document).ready(function(){
        drawCharts();
        /*
        function drawCharts() {
          // create all the charts
          <?php for ($i = 1; $i <= $nb_ruches; $i++) {
              $array_masse = getData($sql,$rucheids[$i-1]['rucheid'],"MASSE");
              $display_masse = formatForDisplay($array_masse);
              $array_temp = getData($sql,$rucheids[$i-1]['rucheid'],"TEMP");
              $display_temp = formatForDisplay($array_temp);
            ?>
          // draw mass graph for <?php echo $rucheids[$i-1];?>
          drawChart('<?php echo "chartmass_".$i;?>',<?php echo $display_masse;?>);
          // draw temp graph
          drawChart('<?php echo "charttemp_".$i;?>',<?php echo $display_temp;?>);
          <?php
            // fin de la boucle sur les ruches
            } ?>
        }

        function drawChart(divid,value){
          if (value.length > 2) {
            var plot1 = $.jqplot(divid, [value], {
            title:'',
            axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
            series:[{lineWidth:4, markerOptions:{style:'square'}}]
            });
          } else {
            $("#"+divid).append("<h4 class='message'>Pas de Données disponible</h4>");
            //$("#"+divid).css('style','height:200px;width:300px');
          }
        }
        */
      });
    </script>
  </head>
  
<body>
		
  <div data-role="page" id="stats" data-theme="b" data-dom-cache="false">
	  
  
	  

    <div data-role="header">
      <p align="center">BZZZ&nbsp;: Suivi des ruchers</p>
    </div>
    
    <div data-role="content">
        <?php
          if (strlen($text) > 0 ) { 
            echo  "<h1>".$text."</h1>";
          // sinon on peut commencer à travailler
          } else {
            echo "<h2>Rucher&nbsp;:&nbsp;".$name."</h1>";	
            echo "<h3>Contact&nbsp;:&nbsp;".$contact."</h2>";
            echo "<h3>Localisation&nbsp;:&nbsp;".$localisation."</h2>";
            echo "<h3>Nombre de Ruches&nbsp;:&nbsp;".$nb_ruches."</h2>";
          }
        ?>
      
        <p align="center"><h3>Données des ruches</h3>
        <!-- TODO : Regrouper les options dans un « gadget » escamotable pour
                    n'afficher que ce qui intéresse directement l'happyculteur  -->
        <div data-role="collapsible">
          <h4>Options</h4>
          <!-- Champ alerte rucher: si l'apiculteur travaille sur la ruche il peut desactiver l'alarme pour éviter d'être spammé -->
          <fieldset data-role="controlgroup">
            <legend>Alerte:</legend>
            <input type="radio" name="alerte" id="alerteon" value="ON" <?php if (getAlarmSystem($sql,$rucherid) == "ON") {echo 'checked="checked"';}?> /><label for="alerteon">ON</label>
            <input type="radio" name="alerte" id="alerteoff" value="OFF" <?php if (getAlarmSystem($sql,$rucherid) == "OFF") {echo 'checked="checked"';}?> /><label for="alerteoff">OFF</label>
          </fieldset>

          <fieldset data-role="controlgroup">
            <legend>Alarme Type:</legend>
            <input type="radio" name="causes" id="causes-1" value="SMS" <?php if (getAlarmType($sql,$rucherid) == "SMS") {echo 'checked="checked"';}?> /> <label for="causes-1">SMS</label>
            <input type="radio" name="causes" id="causes-2" value="MAIL" <?php if (getAlarmType($sql,$rucherid) == "MAIL") {echo 'checked="checked"';}?>/><label for="causes-2">Mail</label>
            <input type="radio" name="causes" id="causes-3" value="PHONE" <?php if (getAlarmType($sql,$rucherid) == "PHONE") {echo 'checked="checked"';}?> /> <label for="causes-3">Téléphone</label>
          </fieldset>
        </div>
        
        <!-- Affichage des infos des ruches -->
        <fieldset data-role="fieldcontain">
        <?php
          if ( $nb_ruches == 0 ) {
            echo "<center>Aucune ruche référencée dans ce rucher</center>";
          } else {
        ?> 
        <ul data-role="listview" data-inset="true" data-filter="true">
        <?php } ?>
        <?php 
          // pour chaque ruche on récupère les informations
          // TODO pour l'instant on récupère toutes les données
          // quand il va y en avoir beaucoup, il faut prévoir de considérer uniquement les N dernières valeurs
          for ($i = 1; $i <= $nb_ruches; $i++) {
        
			echo "<li>";
			$rucheid = $rucheids[$i-1]['rucheid'];
			$drucheid = displayRucheID($rucheid);
			echo "<h2>Ruche ".$drucheid.":</h2>";
			
		$graph_height="";
		$graph_width="";
		 if ($detect->isMobile()){
			$graph_height="200px";
			$graph_width = "250px"; 
		 } else {
			$graph_height="300px";
			$graph_width = "900px"; 			 
		 }
			
         
         if ( hasData($sql,$rucheid,_ALERT_MASSE)) {
          echo '<h3>Suivi de la masse</h3>';
          echo '<div id="chartmass_'.$i.'" style="height:'.$graph_height.';width:'.$graph_width.'; "></div>';
         }

         if ( hasData($sql,$rucheid,_ALERT_TEMPERATURE)) {
          echo '<h3>Suivi de la température</h3>';
          echo '<div id="charttemp_'.$i.'" style="height:'.$graph_height.';width:'.$graph_width.'; "></div>';
         }
         
         if ( hasData($sql,$rucheid,_ALERT_HUMIDITY)) {
          echo '<h3>Suivi de l\'humidité</h3>';
		  echo '<div id="charthumidity_'.$i.'" style="height:'.$graph_height.';width:'.$graph_width.'; "></div>';
		}

        // TODO LUM pour les tests, capteurs à repréciser pour l'affichage
         if ( hasData($sql,$rucheid,_ALERT_LUM)) {         
          echo '<h3>Suivi du son</h3>';
          echo '<div id="chartsound_'.$i.'" style="height:'.$graph_height.';width:'.$graph_width.'; "></div>';
		}
		
        echo "</li>";
        } ?> 
      </ul>
      </fieldset>
    </div>

    <!-- 3 Menus depuis la page stats: 
          - retour à l'accueil
          - modifier le rucher
          - ajouter d'une ruche au rucher
    -->
    <div data-role="footer">
      <div data-id="navig" data-role="navbar">
        <ul>
          <li><a data-ajax="false" href="rucher.php" data-icon="home">Accueil</a></li>
          <li><a data-ajax="false" href="modify_rucher.php?rucherid=<?php echo $rucherid;?>" data-icon="home">Modifier le rucher</a></li>
          <li><a href="add_ruche.php?rucherid=<?php echo $rucherid;?>" data-icon="home">Ajouter une Ruche</a></li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>

 
<script>

$(":radio").bind ("change", function (event)
{
  // detect a change in radio button
  var TheAlerte = $('input[type=radio][name=alerte]:checked').attr('value'); //Retourne la valeur du radio sélectionné dans le groupe opt1. Retourne vide si aucun radio sélectionné
  var TheCause = $('input[type=radio][name=causes]:checked').attr('value'); //Retourne la valeur du radio sélectionné dans le groupe opt1. Retourne vide si aucun radio sélectionné
  
 // mettre à jour les valeurs dans la base
 $.ajax({
  type: "POST",
  url: "http://projects.emerginov.org/Bzzz/callajax.php?rucherid=<?php echo $rucherid;?>",
  data: {alertetype: TheAlerte,cause:TheCause},
  cache: false,
  dataType: "text",
});  
});



</script>
