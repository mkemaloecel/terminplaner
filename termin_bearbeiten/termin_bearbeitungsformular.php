<form method="post" action="?seite=termin_aendern">

<p>Beschreibung</p>
<input type="text" name="beschreibung" value="<?php echo $datensatz["beschreibung"]; ?>" />

<p>Datum</p>
<input type="datetime-local" name="datum" value="<?= $datensatz["datum"]; ?>" />
<p>Interval Ende</p>
<input type="datetime-local" name="interval_end_datum" value="<?= $datensatz["interval_end_datum"]; ?>" />
<p>Intervalle</p>
<?php
	# Datenbankverbindung
	#########################################################################
	$connect = mysqli_connect("localhost",	"root", "", "terminplaner");
	mysqli_query($connect, "SET names utf8"); # Verbindung auf utf-8 umstellen
	#########################################################################
	$info = mysqli_query($connect, "select * from intervalle");
?>
<select name="interval_nr">
<?php
while($option = mysqli_fetch_array($info))
{
	if($datensatz["interval_nr"] == $option["nr"])
	{
		echo "<option value='".$option["nr"]."' selected>".$option["name"]." (Ausgewählt)</option>";
	}
	else
	{
		echo "<option value='".$option["nr"]."'>".$option["name"]."</option>";
	}
}

?>
</select>

<!-- Terminnr -->
<input type="hidden" name="terminnr" value="<?= $datensatz["terminnr"]; ?>" />
<br /><br />
<input type="submit" name="termin_speichern" />

</form>