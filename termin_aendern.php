<?php
if(isset($_POST["termin_speichern"]))
{
	# Datenbankverbindung
	#########################################################################
	$link = mysqli_connect("localhost",	"root", "", "terminplaner");
	mysqli_query($link, "SET names utf8"); # Verbindung auf utf-8 umstellen
	#########################################################################
	#print_r($_POST);
	/* Array ( 
	[beschreibung] => Hausarzt 
	[datum] => 2022-05-02T00:00 
	[terminnr] => 109 
	[termin_speichern] => Daten absenden ) 
	*/
	$jahresAnsicht = $_GET["jahresAnsicht"];

	$_POST["beschreibung"] = mysqli_real_escape_string($link, $_POST["beschreibung"]);

	$beschreibung 	= $_POST["beschreibung"];
	$datum 			= $_POST["datum"];
	$intervalEnde	= $_POST["interval_end_datum"];
	$terminnr 		= $_POST["terminnr"];
	$intervalnr 	= $_POST["interval_nr"];
	
	$befehl = "update termine 
	SET beschreibung = '$beschreibung', 
	datum= '$datum', 
	interval_end_datum = '$intervalEnde',
	interval_nr = $intervalnr
	WHERE nr = $terminnr  ";
	#echo "<h1>$befehl</h1>";			
	# Datenbankverbindung öffnen
	mysqli_query($link, $befehl);	
	# Datenbank schließen
	mysqli_close($link);	
	
	# Weiterleitung zu allen Terminen
	
	header("Location: ?seite=details_ansicht&terminnr=$terminnr&jahresAnsicht=$jahresAnsicht");
	exit;
}
else
{
	echo "Es wurde kein Formular verschickt!";
}