<?php
function monatsMiniAnsicht($aktuellesJahr, $aktuellerMonat, $termine = array())
{
    $monatsNamen = [
        "Januar", "Februar", "März", "April", "Mai", "Juni",
        "Juli", "August", "September", "Oktober", "November", "Dezember"
    ];
    $tagesNamen = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"];

    $datum = $aktuellesJahr . "-" . $aktuellerMonat . "-01";

    $time = strtotime($datum);

    # aktualeste = 2022-01-01 -

    $prevMonthNumOfDays = date("t", mktime(0, 0, 0, date('m', $time) - 1, 1, date('Y', $time)));
    $letzteTag = date("t", $time); //28 29 30 31
    $startTag = date("N", $time) - 1;
    $prevMonthLastDayWeekday = date("N", $time) - 1;

    # Ein Tag Darstellung. Grid...
    echo "<h1>" . $monatsNamen[$aktuellerMonat - 1] . " " . $aktuellesJahr . "</h1>";

    echo "<div class ='grid-container'>";

    # überschriften
    for ($i = 1; $i <= 7; $i++) {
        //echo "<div class ='grid-tag-ueberschrift'>" . $tagesNamen[$i - 1] . "</div>";
        echo "<div class ='grid-tag-ueberschrift'>";
        if($tagesNamen[$i-1] == "Samstag" || $tagesNamen[$i-1] == "Sonntag" ){
            echo "<div class='wochenende'>".$tagesNamen[$i-1]."</div>";
        }else{
           echo "<div>".$tagesNamen[$i-1]."</div>";
        }

       echo "</div>";
    }

    $tagesZaehler = 0;
    # vorher tage
    for ($i = 1; $i <= $startTag; $i++) {
       // $prevMonthDaysInMonthChangeWeek = $prevMonthNumOfDays - $prevMonthLastDayWeekday + $i;
        //echo "<div class ='grid-tag-vorher'>" . $prevMonthDaysInMonthChangeWeek . "</div>";
        $prevMonthDaysInMonthChangeWeek = $prevMonthNumOfDays - $prevMonthLastDayWeekday + $i;

        echo "<div class ='grid-tag-vorher'>";
 
 
        $heute = $aktuellesJahr."-".($aktuellerMonat-1)."-".$prevMonthDaysInMonthChangeWeek; //2022-05-12
        $time = strtotime($heute);
        $wochentag = date("N", $time);
        if($wochentag == 6 || $wochentag == 7){
            echo "<div class='wochenende'>$prevMonthDaysInMonthChangeWeek</div>";
        }else{
            echo "<div>" . $prevMonthDaysInMonthChangeWeek . "</div>";
        }
        
        echo "</div>";
        $tagesZaehler++;
    }

    /*
Array ( [nr] => 1 [benutzer_nr] => 1 [interval_nr] => 5 
[beschreibung] => Jährlicher Termin Torsten B-Day 
[datum] => 2022-05-04 13:26:17 
[interval_end_datum] => 
[start_uhr_zeit] => 10:00:00 
[end_uhr_zeit] => 23:26:17 
[status] => 6 ) 
Array ( [nr] => 11 [benutzer_nr] => 1 
[interval_nr] => 5 
[beschreibung] => Jährlicher termin Abdullah B-Day 
[datum] => 2022-05-04 14:52:07 
[interval_end_datum] => [start_uhr_zeit] => [end_uhr_zeit] => [status] => ) 
                */
    # tage
    for ($i = 1; $i <= $letzteTag; $i++) {
        # alle Termine sollen hier dargestellt werden.
        $zaehlerEinmalig = 0;
        $zaehlerMonatlich = 0;
        $zaehlerJaehrlich = 0;
        $zaehlerWochentlich = 0;
        $zaehlerTaeglich = 0;
        if (isset($termine[$i])) {
            //echo "$i";
            $tag = $i;
            if ($i < 10) {
                $tag = "0$i";
            }
            // print_r($termine[$i]);
            // die();
            foreach ($termine[$i] as $number => $termin) {
                //echo $aktuellerMonat."-$tag"; //2022-05-09 08:00:00
                $startDatum = $termin["datum"];
                //$endDatum = "2023-06-01"; //$datensatz["interval_end_datum"];
                $time = strtotime($startDatum);
                $endDatum = date("Y-m-d", mktime(
                    0,
                    0,
                    0,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time) + 1
                ));
                //echo "enddatum: $endDatum";
                if (isset($datensatz["interval_end_datum"])) {
                    $endDatum = $datensatz["interval_end_datum"];
                }
                if (
                    $termin["interval_nr"] == 5 &&
                    substr($startDatum, 5, 5) ==  $aktuellerMonat . "-$tag" &&
                    substr($startDatum, 0, 4) <= $aktuellesJahr &&
                    (substr($endDatum, 0, 10) >= $aktuellesJahr . "-" . $aktuellerMonat . "-$tag"
                        || $endDatum == ""
                    )
                ) {
                    //print_r($termin);
                    $zaehlerJaehrlich++;
                } else if (
                    $termin["interval_nr"] == 4 &&
                    //monatliche
                    substr($startDatum, 8, 2) ==  $tag
                ) {
                    $zaehlerMonatlich++;
                } else if ($termin["interval_nr"] == 3) {
                    $wochentag = date("N", strtotime($aktuellesJahr . "-" . $aktuellerMonat . "-$tag"));
                    if ($termin["wochentag"] == $wochentag) {
                        $zaehlerWochentlich++;
                    }
                } else if (
                    $termin["interval_nr"] == 2 &&
                    substr($endDatum, 0, 10) >= $aktuellesJahr . "-" . $aktuellerMonat . "-$tag" &&
                    substr($startDatum, 0, 7) <= $aktuellesJahr . "-" . $aktuellerMonat
                ) {
                    $zaehlerTaeglich++;
                } else if (
                    $termin["interval_nr"] == "" &&
                    substr($startDatum, 0, 10) == $aktuellesJahr . "-" . $aktuellerMonat . "-$tag"
                ) {
                    $zaehlerEinmalig++;
                }
            }
        }

        if ($zaehlerEinmalig + $zaehlerJaehrlich + $zaehlerMonatlich + $zaehlerWochentlich + $zaehlerTaeglich > 0) {
            echo "<div class ='grid-tag-mit-termin' 
            title='Anzahl der Termine: 
            Jährlich ($zaehlerJaehrlich), 
            Monatlich ($zaehlerMonatlich),
            Wochentlich ($zaehlerWochentlich),
            Täglich ($zaehlerTaeglich),
            Einmalig ($zaehlerEinmalig)
                '>";
            echo "<a href='?seite=alle_termine&ansicht=jahresAnsicht&datum=$aktuellesJahr-$aktuellerMonat'>";
            $heute = $aktuellesJahr."-".$aktuellerMonat."-".$tag; //2022-05-12
            $time = strtotime($heute);
            $wochentag = date("N", $time);
            if($wochentag == 6 || $wochentag == 7){
                echo "<div class='wochenende'>$i</div>";
            }else{
                echo "<div style='text-align: center;'>$i</div>";
            }
 
            echo "</a></div>";
        } else {
            $tag = $i;
            if ($i < 10) {
                $tag = "0$i";
            }
            echo "<div class ='grid-tag'>";
            $heute = $aktuellesJahr."-".$aktuellerMonat."-".$tag; //2022-05-12
            $time = strtotime($heute);
            $wochentag = date("N", $time);
            if($wochentag == 6 || $wochentag == 7){
                echo "<div class='wochenende'>$i</div>";
            }else{
                echo "<div style='text-align: center;'>$i</div>";
            }
            echo "</div>";
        }

        $tagesZaehler++;
    }
    $restTage = (7 - ($tagesZaehler % 7)) % 7;

    # nacher tage
    for ($i = 1; $i <= $restTage; $i++) {
        //echo "<div class ='grid-tag-nachher'>" . $i . "</div>";
        $tag = $i;
        if($i<10){
            $tag = "0$i";
        }
        if($aktuellerMonat+1 == 13){
            $heute = ($aktuellesJahr+1)."-01-".$tag; //2022-05-12
        }else{
            $heute = $aktuellesJahr."-".($aktuellerMonat+1)."-".$tag; //2022-05-12
        }

        $time = strtotime($heute);
        $wochentag = date("N", $time);
        echo "<div class ='grid-tag-nachher'>";
        if($wochentag == 6 || $wochentag == 7){
            echo "<div class='wochenende'>$i</div>";
        }else{
            echo "<div style='text-align: center;'>$i</div>";
        }
        echo "</div>";
    }

    echo "</div>";
}
