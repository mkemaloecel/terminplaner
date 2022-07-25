<h1>Terminliste</h1>
<?php
/*
Vorgabe:
- Einmalige Termine
- Regelmässige Termine
	- Jährlich
	- Monatlich
	- ...

Ziel:
Alle Termine auf dem Kalendar sichbar sind.

wie kommt man hin?
	- Unterschiede finden zwischen einmallige und regelmässige Termine
		- Einmalig hat keine interval_nr und kein interval_end_datum

	1.SQL für Einmalige monat:
		select 
                    termine.nr as terminnr,
                    termine.beschreibung,
                    termine.datum,
                    termine.interval_nr,
                    termine.interval_end_datum,
                    termine.start_uhr_zeit,
                    termine.end_uhr_zeit,
                    termine.status,
                    statusmoeglichkeiten.nr as statusnr,
                    statusmoeglichkeiten.bezeichnung

                    from termine, statusmoeglichkeiten

                    where termine.status = statusmoeglichkeiten.nr and 
						  termine.interval_nr is null and
						  termine.datum like '2022-05%';
	2.SQL für regelmässige monat:
		select 
                    termine.nr as terminnr,
                    termine.beschreibung,
                    termine.datum,
                    termine.interval_nr,
                    termine.interval_end_datum,
                    termine.start_uhr_zeit,
                    termine.end_uhr_zeit,
                    termine.status,
                    statusmoeglichkeiten.nr as statusnr,
                    statusmoeglichkeiten.bezeichnung

                    from termine, statusmoeglichkeiten

                    where termine.status = statusmoeglichkeiten.nr and 
						  termine.interval_nr = 5 and
						  termine.datum like '____-05-01%';
		
	- Alle termine Ansicht, erste Aufruf bekommt man aktualestes Datum (2022 und 05)
	- Dann man kann Datum -/+ Ändern! (Entweder Monat oder Jahr)
		- Man braucht eine Db-Abfrage aktuelstes Datum Selectiert!
			- Der soll beinhalte einmalige und regelmässige Termine!
		- SQl:
			select *
                    from termine
                    where datum like '2022-05%' or 
					(datum like '____-05%')
 */
if (isset($_GET["datum"])) {
	$ausgewaeltesdatum = $_GET["datum"];
} else {
	$ausgewaeltesdatum = date("Y-m");
}
$monat = substr($ausgewaeltesdatum, 5, 2);
$jahr = substr($ausgewaeltesdatum, 0, 4);

//echo "<h1>Angezeigt wird $jahr $monat</h1>";
$connect = mysqli_connect("localhost", "root", "", "terminplaner");

# einmalig
$sql_einmalig = "select *
	from termine
	where benutzer_nr = ".$_SESSION["pi"]." and datum like '$jahr-$monat%' and interval_nr is null ";
//echo "<h2>$sql_einmalig</h2>";

$abholinfo = mysqli_query($connect, $sql_einmalig);

// ein Array erstellen für alle termine zusammen fügen 01 ...31
$termine = array();
while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
	// echo "<pre>";
	// echo "<p>";
	// echo $datensatz["datum"]; 
	// echo $datensatz["beschreibung"]; 
	// echo $datensatz["interval_end_datum"]; 
	// echo "</p>";
	// echo "</pre>";
	$tag = (int) substr($datensatz["datum"], 8, 2);
	$termine[$tag][] = $datensatz;
}

$sql_jaehrlich = "select *
	from termine
	where benutzer_nr = ".$_SESSION["pi"]." and (datum like '____-$monat%' and 
	interval_nr = 5 and 
	datum<='$jahr-$monat-31 23:59:59' and
	(interval_end_datum >= '$jahr-$monat-01 00:00:00' or interval_end_datum is null))";
//echo "<h2>$sql_jaehrlich</h2>";

$abholinfo = mysqli_query($connect, $sql_jaehrlich);

while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
	// echo "<pre>";
	// echo "<p>";
	// echo $datensatz["datum"]; 
	// echo $datensatz["beschreibung"]; 
	// echo $datensatz["interval_end_datum"]; 
	// echo "</p>";
	// echo "</pre>";
	$tag = (int) substr($datensatz["datum"], 8, 2);
	$termine[$tag][] = $datensatz;
}

# nur monatlichen regelmäsiger Termine  
$sql_monatlich = "select *
	from termine
	where benutzer_nr = ".$_SESSION["pi"]." and interval_nr = 4 and 
	datum <= '$jahr-$monat-31 23:59:59' and
	(interval_end_datum>='$jahr-$monat-01' or interval_end_datum is null)";
//echo "<h2>$sql_monatlich</h2>";
$abholinfo = mysqli_query($connect, $sql_monatlich);
while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
	// echo "<pre>";
	// echo "<p>";
	// echo $datensatz["datum"]; 
	// echo $datensatz["beschreibung"]; 
	// echo $datensatz["interval_end_datum"]; 
	// echo "</p>";
	// echo "</pre>";
	$tag = (int) substr($datensatz["datum"], 8, 2);
	$termine[$tag][] = $datensatz;
}


# nur wochentlich regelmäsiger Termine  
$sql_wochentlich = "select *
	from termine
	where benutzer_nr = ".$_SESSION["pi"]." and interval_nr = 3 and 
	datum <= '$jahr-$monat-31 23:59:59' and
	(interval_end_datum>='$jahr-$monat-01' or interval_end_datum is null)";
// echo "<h2>$sql_wochentlich</h2>";
$abholinfo = mysqli_query($connect, $sql_wochentlich);
while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
	// echo "<pre>";
	// echo "<p>";
	// echo $datensatz["datum"]; 
	// echo $datensatz["beschreibung"]; 
	// echo $datensatz["interval_end_datum"]; 
	// echo "</p>";
	// echo "</pre>";
	$startDatum = $datensatz["datum"];
	$wochentag = date("N", strtotime($startDatum));

	$erstenTag = date("N", strtotime("$jahr-$monat-01"));
	//echo "wochenTag: $wochentag, erstenTag: $erstenTag"; 

	if (substr($startDatum, 0, 7) != "$jahr-$monat") {
		//echo "wochenTag: $wochentag, erstenTag: $erstenTag"; 
		$tag = $wochentag - $erstenTag + 1;
	} else {
		$tag = (int) substr($startDatum, 8, 2);
	}

	$termine[$tag][] = $datensatz;

	for ($i = 1; $i <= 5; $i++) {
		$tag = $tag + 7;
		$termine[$tag][] = $datensatz;
		/* 		echo "<h1>$tag ---> ";
		print_r($termine[$tag]);
		echo "</h1><br/>"; */
	}
}

# nur täglich regelmäsiger Termine 
$sql_taeglich = "select *
	from termine
	where benutzer_nr = ".$_SESSION["pi"]." and interval_nr = 2 and 
	datum <= '$jahr-$monat-31 23:59:59' and
	(interval_end_datum>='$jahr-$monat-01 00:00:00' or interval_end_datum is null)";
//echo "<h1>$sql_taeglich</h1>";
$abholinfo = mysqli_query($connect, $sql_taeglich);
while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
	// echo "<pre>";
	// echo "<p>";
	// echo $datensatz["datum"]; 
	// echo $datensatz["beschreibung"]; 
	// echo $datensatz["interval_end_datum"]; 
	// echo $datensatz["interval_nr"]; 
	// echo "</p>";
	// echo "</pre>";
	$startDatum = $datensatz["datum"];
	//$endDatum = "2023-06-01"; //$datensatz["interval_end_datum"];
	$time = strtotime($startDatum);
    $endDatum = date("Y-m-d", mktime(0,0,0,date('m', $time), 
		date('d', $time), 
		date('Y', $time)+1));
	//echo "enddatum: $endDatum";
	if(isset($datensatz["interval_end_datum"])){
		$endDatum = $datensatz["interval_end_datum"];
	}
	
	if (substr($startDatum, 0, 7) == "$jahr-$monat" && 
	substr($endDatum, 0, 7) > "$jahr-$monat") {
		for ($i = (int)substr($startDatum, 8, 2); $i <= 31; $i++) {
			$termine[$i][] = $datensatz;
		}
	} else if (substr($startDatum, 0, 7) < "$jahr-$monat" && 
	substr($endDatum, 0, 7) > "$jahr-$monat") {
		for ($i = 1; $i <= 31; $i++) {
			$termine[$i][] = $datensatz;
		}
	} else if (substr($startDatum, 0, 7) < "$jahr-$monat" && 
	substr($endDatum, 0, 7) == "$jahr-$monat") {
		for ($i = 1; $i <= substr($endDatum, 8, 2); $i++) {
			$termine[$i][] = $datensatz;
		}
	} else if (substr($startDatum, 0, 7) == "$jahr-$monat" && 
	substr($endDatum, 0, 7) == "$jahr-$monat") {
		for ($i = (int)substr($startDatum, 8, 2); $i <= substr($endDatum, 8, 2); $i++) {
			$termine[$i][] = $datensatz;
		}
	}
}

mysqli_close($connect);

monatsAnsicht($jahr, $monat, $termine);
?>