<?php
# Befehl abschicken
$befehl = "select termine.nr as terminnr, 
termine.benutzer_nr, 
termine.interval_nr, 
termine.beschreibung, termine.datum, termine.interval_end_datum, intervalle.nr as intervallenr, 
intervalle.name
 from termine left join intervalle on termine.interval_nr = intervalle.nr 
 where termine.nr = ".$_GET["terminnr"];
$info = mysqli_query($connect, $befehl);
# Daten rausholen
$datensatz = mysqli_fetch_array($info);

