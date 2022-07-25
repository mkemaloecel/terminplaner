<?php
function monatsAnsicht($aktuellesJahr, $aktuellerMonat, $termine = array()){
    $monatsNamen = ["Januar", "Februar", "März", "April", "Mai", "Juni", 
    "Juli", "August", "September", "Oktober", "November", "Dezember"];
    $tagesNamen = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"];

    $datum = $aktuellesJahr."-".$aktuellerMonat."-01";

    //echo $datum;
    $time = strtotime($datum);
    $prev = date("Y-m", mktime(0,0,0,date('m', $time)-1, 1, date('Y', $time)));
    $next = date("Y-m", mktime(0,0,0,date('m', $time)+1, 1, date('Y', $time)));
    # aktualeste = 2022-01-01 -
    
    $prevMonthNumOfDays = date("t", mktime(0, 0, 0, date('m', $time) - 1, 1, date('Y', $time)));
    $letzteTag = date("t", $time); //28 29 30 31
    $startTag = date("N", $time)-1;
    $prevMonthLastDayWeekday = date("N", $time)-1;

    # Ein Tag Darstellung. Grid...
    echo "<h1>" . $monatsNamen[$aktuellerMonat-1]." ".$aktuellesJahr."</h1>";
    
    echo "<a href='?seite=alle_termine&datum=$prev'> <<< </a>" 
             . $monatsNamen[$aktuellerMonat-1].
             " <a href='?seite=alle_termine&datum=$next'> >>> </a> ";  
    
    echo "<div class ='grid-container'>";
    
    # überschriften
    for($i = 1; $i<=7; $i++){
        echo "<div class ='grid-tag-ueberschrift'>";
         if($tagesNamen[$i-1] == "Samstag" || $tagesNamen[$i-1] == "Sonntag" ){
             echo "<h3 class='wochenende'>".$tagesNamen[$i-1]."</h3>";
         }else{
            echo "<h3>".$tagesNamen[$i-1]."</h3>";
         }

        echo "</div>";
    }
    
    $tagesZaehler = 0;
    # vorher tage
    //echo "prevMonthNumOfDays: $prevMonthNumOfDays, prevMonthLastDayWeekday: $prevMonthLastDayWeekday, startTag: $startTag";
    for($i = 1; $i<=$startTag; $i++){
        //x = 31 - 6 + (1.2.3....)
        $prevMonthDaysInMonthChangeWeek = $prevMonthNumOfDays - $prevMonthLastDayWeekday + $i;

        echo "<div class ='grid-tag-vorher'>";
        $heute = $aktuellesJahr."-".($aktuellerMonat-1)."-".$prevMonthDaysInMonthChangeWeek; //2022-05-12
        $time = strtotime($heute);
        $wochentag = date("N", $time);
        if($wochentag == 6 || $wochentag == 7){
            echo "<h3 class='wochenende'>$prevMonthDaysInMonthChangeWeek</h3>";
        }else{
            echo "<h3>" . $prevMonthDaysInMonthChangeWeek . "</h3>";
        }
        
        echo "</div>";
        $tagesZaehler++;
    }
    
    # tage
    for($i = 1; $i<=$letzteTag; $i++){
        # alle Termine sollen hier dargestellt werden.
        echo "<div class ='grid-tag'>";
        
        $tag = $i;
        if($i<10){
            $tag = "0$i";
        }
        $heute = $aktuellesJahr."-".$aktuellerMonat."-".$tag; //2022-05-12
        $time = strtotime($heute);
        $wochentag = date("N", $time);
        if($wochentag == 6 || $wochentag == 7){
            echo "<h1 class='wochenende'>$i</h1>";
        }else{
            echo "<h1 style='text-align: center;'>$i</h1>";
        }

        if(isset($termine[$i])){
            foreach($termine[$i] as $nummer => $termin){
                $uhr = substr($termin["datum"], 11, 5);
                $title = $termin["beschreibung"];
                echo "$uhr <a href='?seite=details_ansicht&ansicht=monatAnsicht&terminnr=".$termin["nr"]."'>$title</a>
                      <br/>
                      <a href='?seite=termin_bearbeiten&terminnr=".$termin["nr"]."'>Bearbeiten</a>
                      &nbsp;&nbsp;    
                      <a href='?seite=termin_loeschen&terminnr=".$termin["nr"]."'>Löschen</a>
                    <br/><br/>
                ";
            }
        }
        echo "</div>";
        $tagesZaehler++;
    }
    $restTage = (7 - ($tagesZaehler % 7)) % 7;
    
    # nacher tage
    for($i = 1; $i<=$restTage; $i++){
        //echo "<div class ='grid-tag-nachher'><h4>".$i."</h4></div>";
        $tag = $i;
        if($i<10){
            $tag = "0$i";
        }
        if($aktuellerMonat+1 == 13){
            $heute = ($aktuellesJahr+1)."-01-".$tag; //2022-05-12
        }else{
            $heute = $aktuellesJahr."-".($aktuellerMonat+1)."-".$tag; //2022-05-12
        }
        //$heute = $aktuellesJahr."-".($aktuellerMonat+1)."-".$tag; //2022-05-12
        $time = strtotime($heute);
        $wochentag = date("N", $time);
        echo "<div class ='grid-tag-nachher'>";
        if($wochentag == 6 || $wochentag == 7){
            echo "<h3 class='wochenende'>$i</h3>";
        }else{
            echo "<h3 style='text-align: center;'>$i</h3>";
        }
        echo "</div>";
    }
    
    echo "</div>";

}
