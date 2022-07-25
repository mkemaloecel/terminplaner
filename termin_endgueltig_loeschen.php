<?php
if(isset($_POST["termin_loeschen_ja"]))
{
	#print_r($_POST);
	$terminnr 		= $_POST["terminnr"];
	
	$befehl = " delete from termine
				WHERE nr = '$terminnr' ";
	#echo "<h1>$befehl</h1>";			
	# Datenbankverbindung öffnen
	$connect = mysqli_connect("localhost", "root", "", "terminplaner");		

	mysqli_query($connect, $befehl);

	# Datenbank schließen
	mysqli_close($connect);		

}

# Weiterleitung zu allen Terminen
header("Location: ?seite=alle_termine");
exit;