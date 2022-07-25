<form method="post" action="">

<p>Beschreibung</p>
<input type="text" name="beschreibung" placeholder="Bitte Termin eintragen" value="" />
<br />

<p>Datum</p>
<input type="datetime-local" name="datum" value="" />
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
mysqli_close($connect);
?>
</select>
<br />
<br />
<br />
<input type="submit" />

</form>