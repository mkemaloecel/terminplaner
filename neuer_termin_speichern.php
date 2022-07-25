<?php
# print_r($_POST); # Array ( [beschreibung] => test123 [datum] => 2022-04-27 ) 

# Array ( [beschreibung] => [datum] => )    => leeres Formular

if(isset($_POST["beschreibung"]) && isset($_POST["datum"]))
{
	$geprueft 	= 0;
	$datum 		= $_POST["datum"];
	echo "<h1>Das Formular wurde versendet!</h1><br/>";
	if(strlen($_POST["beschreibung"]) >=0)
	{
		echo "Das Feld Beschreibung ist ausgefüllt!<br/>";
		$geprueft++;
	}
	else
	{
		echo "Das Formularfeld Beschreibung war leer.<br/>";
	}
	
	if(strlen($datum) > 10)
	{
		echo "Das Feld Datum ist ausgefüllt!<br/>";
		$geprueft++;
	}
	else
	{
		echo "Das Formularfeld Datum war leer.<br/>";
	}

	if($geprueft == 2)
	{
		echo "Speichern in der Datenbank";
		# Datenbankverbindung
		#########################################################################
		$link = mysqli_connect("localhost",	"root", "", "terminplaner");
		mysqli_query($link, "SET names utf8"); # Verbindung auf utf-8 umstellen
		#########################################################################		
		$_POST["beschreibung"] = mysqli_real_escape_string($link, $_POST["beschreibung"]);
		$beschreibung 	= $_POST["beschreibung"];

		$intervalEnde	= $_POST["interval_end_datum"];
		$intervalnr 	= $_POST["interval_nr"];
		$benutzernr = $_SESSION["pi"];

		if(isset($intervalEnde) && strlen($intervalEnde) >= 4){
			// echo "intervalEnde: $intervalEnde";
			// die();
			$insert_befehl = "insert into termine 
			(beschreibung, datum, interval_end_datum, interval_nr, benutzer_nr) 
			values('$beschreibung', '$datum', '$intervalEnde', $intervalnr, $benutzernr)";
		}else{
			$insert_befehl = "insert into termine 
			(beschreibung, datum, interval_nr, benutzer_nr) 
			values('$beschreibung', '$datum', $intervalnr, $benutzernr)";
		}
//echo "<h1>$insert_befehl</h1>";

		$variable = mysqli_query($link, $insert_befehl);
		
		#var_dump($variable);
		
		$id = $link->insert_id;
		mysqli_close($link);

		echo "<p>Der Primärschlüssel lautet: $id</p>";
		echo "<h1>weiterer Termin</h1>";
		include("formular_neuer_termin.php");
	}
	else
	{
		include("formular_neuer_termin.php");
	}
}
else
{
	include("formular_neuer_termin.php");
}