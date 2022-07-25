<?php
    
    if(isset($_GET["ansicht"]) && $_GET["ansicht"] != "jahresAnsicht"){
        echo "<br/><br/><a href='?seite=alle_termine'>Zurück zu Termine Ansicht</a> ";
    }else{
        echo "<br/><br/><a href='?seite=termin_suchen'>Zurück zu Termine Suchen</a> ";
    }

    $terminnr = $_GET["terminnr"];
    $connect = mysqli_connect("localhost","root","","terminplaner");
    $select = "select termine.nr as terminnr, 
termine.benutzer_nr, 
termine.interval_nr, 
termine.beschreibung, termine.datum, 
termine.interval_end_datum, 
intervalle.nr as intervallenr, 
intervalle.name
 from termine left join intervalle on termine.interval_nr = intervalle.nr 
 where termine.nr = ".$_GET["terminnr"];
    //echo $select;
    $variable1 = mysqli_query($connect, $select);

    $datensatz = mysqli_fetch_array($variable1);

    // echo "<pre>";
    // print_r($datensatz);
    // echo "</pre>";

    echo "<h1>".$datensatz['beschreibung']."</h1>
         <p>Datum: ".$datensatz['datum']."</p>";
    if(isset($datensatz['interval_end_datum'])){
        echo "<p>Interval Ende: ".$datensatz['interval_end_datum']."</p>
        <p>Intervalle: ".$datensatz['name']."</p>
        ";
    }
    mysqli_close($connect);

?>