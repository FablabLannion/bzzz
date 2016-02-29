/*
$( document ).delegate("#stats", "pageinit", function() {
  drawCharts();
  
  $('.button-reset').click(function() { plot1.resetZoom() });
});
*/
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

function drawCharts() {
  // create all the charts
  <?php for ($i = 1; $i <= $nb_ruches; $i++) {
      $array_masse = getData($sql,$rucheids[$i-1]['rucheid'],_ALERT_MASSE);
      $display_masse = formatForDisplay($array_masse);
      $array_temp = getData($sql,$rucheids[$i-1]['rucheid'],_ALERT_TEMPERATURE);
      $display_temp = formatForDisplay($array_temp);
      
      $array_humidity = getData($sql,$rucheids[$i-1]['rucheid'],_ALERT_HUMIDITY);
      $display_humidity = formatForDisplay($array_humidity);
      
      $array_sound = getData($sql,$rucheids[$i-1]['rucheid'],_ALERT_LUM);
      $display_sound = formatForDisplay($array_sound);
      
    ?>
  // draw mass graph for "<?php echo $rucheids[$i-1]['rucheid'];?>"
  drawChart('<?php echo "chartmass_".$i;?>',<?php echo $display_masse;?>);
  // draw temp graph
  drawChart('<?php echo "charttemp_".$i;?>',<?php echo $display_temp;?>);
  
  // draw humidity
  drawChart('<?php echo "charthumidity_".$i;?>',<?php echo $display_humidity;?>);
  
  // draw sound
  drawChart('<?php echo "chartsound_".$i;?>',<?php echo $display_sound;?>);
  
  
  <?php
    // fin de la boucle sur les ruches
    } ?>
}

function drawChart(divid, value){
  if (value.length > 2) {
    var plot1 = $.jqplot(divid, [value], {
    title:'',
    axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
    
    cursor:{show: true,zoom:true,showTooltip:false}, 
    series:[{lineWidth:4, markerOptions:{style:'square'}}]
    });
  } else {
    $("#"+divid).append("<h4 class='message'>Pas de Données disponible</h4>");
    //$("#"+divid).css('style','height:200px;width:300px');
  }
}
