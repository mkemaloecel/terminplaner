

<p>Beschreibung</p>
<?php echo $datensatz["beschreibung"]; ?>

<p>Datum</p>
<?= $datensatz["datum"]; ?>

<form method="post" action="?seite=termin_endgueltig_loeschen">
<h1>Wollen Sie wirklich löschen?</h1>
<!-- Terminnr -->
<input type="hidden" name="terminnr" value="<?= $datensatz["terminnr"]; ?>" />
<input type="submit" value="JA" name="termin_loeschen_ja" />
<input type="submit" value="NEIN" name="termin_loeschen_nein" />
</form>