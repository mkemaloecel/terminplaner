<?php
function wochentlicheDatenHolen($datensatz, $jahr, $monat, $termine = array()){
    $startDatum = $datensatz["datum"];
	$wochentag = date("N", strtotime($startDatum));
	
	$erstenTag = date("N", strtotime("$jahr-$monat-01"));
	
	if(substr($datensatz["datum"], 0, 7) != "$jahr-$monat"){
		echo "wochenTag: $wochentag, erstenTag: $erstenTag"; 
		$tag = $wochentag - $erstenTag + 1;
	}else{
		$tag = (int) substr($startDatum, 8, 2);
	}
	
	$termine[$tag][] = $datensatz;
	
	for($i=1; $i<=5; $i++){
		$tag = $tag+7; 	 
		$termine[$tag][] = $datensatz;
		echo "<h1>$tag ---> ";
		print_r($termine[$tag]);
		echo "</h1><br/>";
		
	}
    //print_r($termine);
}

?>