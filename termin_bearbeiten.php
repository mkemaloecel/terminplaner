<?php
if(isset($_GET["terminnr"]) && $_GET["terminnr"] != "")
{
	#echo "<h1>TEST: Datenbank öffnen</h1>";
	# Datenbankverbindung öffnen
	$connect = mysqli_connect("localhost", "root", "", "terminplaner");	
	#echo "<h1>TEST: DATEN AUSLESEN</h1>";
	# Daten auslesen
	include("termin_bearbeiten/daten_auslesen.php");
	#echo "<h1>TEST: BEARBEITUNGSFORMULAR</h1>";
	# Formular
	include("termin_bearbeiten/termin_bearbeitungsformular.php");
	#echo "<h1>TEST: DATENBANK SCHLIESSEN</h1>";	
	# Datenbank schließen
	mysqli_close($connect);	
}
else
{
	echo "Kein Datensatz ausgewählt";
}