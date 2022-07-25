<?php
function jahresAnsicht($aktuellesJahr, $termine = array(), $wochentlicheTermine = array(), $taeglicheTermine = array())
{
    echo "<div class ='grid-container-jahr'>";
    for ($monat = 1; $monat <= 12; $monat++) {
        echo "<div class ='grid-month' >";

        if ($monat < 10) {
            $monat = "0$monat";
        }
        if (count($wochentlicheTermine) > 0) {
            foreach ($wochentlicheTermine as $number => $termin) {
                if ((substr($termin["datum"], 0, 7) <= $aktuellesJahr . "-" . $monat)) {
                    $wochentag = date("N", strtotime($termin["datum"]));
                    $erstenTag = date("N", strtotime("$aktuellesJahr-$monat-01"));

                    if (substr($termin["datum"], 0, 7) != "$aktuellesJahr-$monat") {
                        $tag = $wochentag - $erstenTag + 1;
                    } else {
                        $tag = (int) substr($termin["datum"], 8, 2);
                    }

                    $termine[$tag][] = $termin;

                    for ($i = 1; $i <= 5; $i++) {
                        $tag = $tag + 7;
                        $termine[$tag][] = $termin;
                    }
                }
            }
        }

        if (count($taeglicheTermine) > 0) {
            foreach ($taeglicheTermine as $number => $termin) {
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
                    substr($startDatum, 0, 7) == "$aktuellesJahr-$monat" &&
                    substr($endDatum, 0, 7) > "$aktuellesJahr-$monat") {
                    for ($i = (int)substr($startDatum, 8, 2); $i <= 31; $i++) {
                        $termine[$i][] = $termin;
                    }
                } else if (
                    substr($startDatum, 0, 7) < "$aktuellesJahr-$monat" &&
                    substr($endDatum, 0, 7) > "$aktuellesJahr-$monat" ) {
                    for ($i = 1; $i <= 31; $i++) {
                        $termine[$i][] = $termin;
                    }
                } else if (
                    substr($startDatum, 0, 7) < "$aktuellesJahr-$monat" &&
                    substr($endDatum, 0, 7) == "$aktuellesJahr-$monat" ) {
                    for ($i = 1; $i <= substr($endDatum, 8, 2); $i++) {
                        $termine[$i][] = $termin;
                    }
                } else if (
                    substr($startDatum, 0, 7) == "$aktuellesJahr-$monat" &&
                    substr($endDatum, 0, 7) == "$aktuellesJahr-$monat" ) {
                    for ($i = (int)substr($startDatum, 8, 2); $i <= substr($endDatum, 8, 2); $i++) {
                        $termine[$i][] = $termin;
                    }
                }
            }
        }

        monatsMiniAnsicht($aktuellesJahr, $monat, $termine);
        echo "</div>";
    }

    echo "</div>";
}
