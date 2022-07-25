<form action="" method="post">

    <p>Zu suchender Termin</p>
    <input type="text" name="suchbegriff" value="<?php echo @$_SESSION["suchbegriff"] ?>" />

    <br />
    <br />
    <input type="submit" name="suche_absenden" value="Suchen" />

</form>
<?php
/*
- Formular elemente abfangen (suchbegriff).
- Fehler check. Formulare Elemente checken.      
    - "suchbegriff" leer sein.
- Sql basteln mit entsprechende "suchbegriff".
- Kalendar darstellen mit dem selectierten Ergebnisse.
- mit leeren Ergebnisse soll aktuellste Kalendar dargestellt werden.
    - Muss gesagt werden "gesuchter Termin nicht gibt!".
- Eventuell mit weiteren Feldern angepasst/erweitert werden.

*/
if (isset($_SESSION["suchbegriff"])) {
    //echo "session: ".$_SESSION["suchbegriff"]." formular: " .$_POST["suchbegriff"];
    $suchbegriff = $_SESSION["suchbegriff"];
}
if (isset($_POST["suche_absenden"]) && isset($_POST["suchbegriff"])) {
    //echo "absenden session: ".$_SESSION["suchbegriff"]." formular: " .$_POST["suchbegriff"];
    $suchbegriff = $_POST["suchbegriff"];
    $_SESSION["suchbegriff"] = $suchbegriff;
    unset($_SESSION["suchbegriff"] );
}
if (isset($suchbegriff)) {
    $connect = mysqli_connect("localhost", "root", "", "terminplaner");
    $suchbegriff = mysqli_real_escape_string($connect, $suchbegriff);
    $benutzernr = $_SESSION["pi"];
    echo "<h1>Such Ergebnisse für '" . $suchbegriff . "' </h1>";
    
    $sql = "select * 
        from termine 
        where beschreibung like '%$suchbegriff%' and benutzer_nr = $benutzernr order by datum asc";

    // echo "<h1>$sql</h1>";
    

    $abholinfo = mysqli_query($connect, $sql);
    $termine = array();
    $wochentlicheTermine = array();
    $taeglicheTermine = array();
    $kleinsteJahr = 0;
    $groessteJahr = 0;

    while ($datensatz = mysqli_fetch_assoc($abholinfo)) {
/*   echo "<pre>";
        echo "<p>";
        echo $datensatz["datum"];
        echo $datensatz["beschreibung"];
        echo $datensatz["interval_end_datum"];
        echo $datensatz["interval_nr"];
        echo "</p>";
        echo "</pre>"; */
        $tag = (int) substr($datensatz["datum"], 8, 2);
        $jahr = (int) substr($datensatz["datum"], 0, 4);

        if ($kleinsteJahr == 0) {
            $kleinsteJahr = $jahr;
        }
        if ($jahr > $groessteJahr) {
            $groessteJahr = $jahr;
        }

        if ($datensatz["interval_end_datum"] != "") {
            $groessteJahr = (int) substr($datensatz["interval_end_datum"], 0, 4);
        }

        if ($datensatz["interval_nr"] == 3) {
            $datensatz["wochentag"] = date("N", strtotime($datensatz["datum"]));
            $wochentlicheTermine[] = $datensatz;
        }else if ($datensatz["interval_nr"] == 2) {
            $taeglicheTermine[] = $datensatz;
        }else {
            $termine[$tag][] = $datensatz;
        }
	}
	$pdo=null;

/*     echo "<h1>";
    print_r($WochentlicheTermine);
    echo "</h1><br/>"; */ 
    //echo "kleinste: $kleinsteJahr, grösste: $groessteJahr";

    for ($jahr = $kleinsteJahr; $jahr <= $groessteJahr; $jahr++) {
        if(count($termine)>0 ||
            count($wochentlicheTermine) ||
            count($taeglicheTermine) ){
                jahresAnsicht($jahr, $termine, $wochentlicheTermine, $taeglicheTermine);
            }else{
                echo "Kein Termin Gefunden!!!";
            }
        echo "<hr/>";
    }
} else {
    echo "<h1>Formular nicht geschickt! </h1>";
}

?>