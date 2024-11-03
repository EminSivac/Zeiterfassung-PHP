<?php
    $Mitarbeiter = $_POST['MitarbeiterID'];
    $Monat = $_POST['Monat'];
    $Jahr = $_POST['Jahr'];
    
    require "dbconn.php";
    
    $sqlMitarbeiterName = "SELECT Vorname, Nachname FROM Mitarbeiter WHERE MitarbeiterID = $Mitarbeiter";
    $resultMitarbeiterName = $conn->query($sqlMitarbeiterName);

    $row = $resultMitarbeiterName -> fetch_assoc();
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];

    $sqlGetAbgabeTag = "SELECT * FROM `AdminEinstellungen` WHERE `Einstellung` = 'Abgabe'";
    $resultGetAbgabeTag = $conn->query($sqlGetAbgabeTag);

    $row = $resultGetAbgabeTag -> fetch_assoc();
    $Abgabe = $row['Wert'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title"><?php echo "Arbeitszeiten ".$Monat." ".$Jahr." ".$Vorname." ".$Nachname; ?></title>
</head>

<?php
function EngToDeu($Wochentag)
{
    if ($Wochentag == "Monday")
    {
        $Wochentag = "Montag";
    }
    if ($Wochentag == "Tuesday")
    {
        $Wochentag = "Dienstag";
    }
    if ($Wochentag == "Wednesday")
    {
        $Wochentag = "Mittwoch";
    }
    if ($Wochentag == "Thursday")
    {
        $Wochentag = "Donnerstag";
    }
    if ($Wochentag == "Friday")
    {
        $Wochentag = "Freitag";
    }
    if ($Wochentag == "Saturday")
    {
        $Wochentag = "Samstag";
    }
    if ($Wochentag == "Sunday")
    {
        $Wochentag = "Sonntag";
    }
    return $Wochentag;
}
echo "<style>
table, th, td
{
    text-align: center;
    font-family: 	Arial, Verdana, sans-serif;
    border-collapse: collapse;
}
th, td
{
    padding: 5px;
}
</style>";

MeineUebersichtMonat($Monat, $Jahr, $Abgabe, $Mitarbeiter, $conn);


function MeineUebersichtMonat($monat, $jahr, $startTag, $mitarbeiterID, $conn)
{
    $KummulierteSollArbeitszeit = 0;
    $DatumAusDemMonat = strtotime("$jahr-$monat-01");
    $LetzerTagImMonat = date("t", $DatumAusDemMonat);

    echo "<table id=\"printTable\">
    <tr>
    <th>Datum</th>
    <th>Arbeitsbeginn</th>
    <th>Arbeitsende</th>
    <th>Gesamte Pausendauer</th>
    <th>Gesamt Arbeitszeit</th>
    <th>Anmerkung</th>
    </tr>";

    /*Schleife vom 1-25*/
    for($tag=1;$tag<$startTag;$tag++){
        //SELECT TAG FÜR TAG
        $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $mitarbeiterID AND Datum = '$jahr-$monat-$tag'";
        $resultDaten = $conn->query($sqlDaten);

        if ($resultDaten->num_rows >0)
        {
            while($row = $resultDaten -> fetch_assoc())
            {
                $BeginnZeit = $row["BeginnZeit"];
                $EndeZeit = $row["EndeZeit"];
                $DauerPausen = $row["DauerPausen"];
                $DauerArbeitszeit = $row["DauerArbeitszeit"];
                $DauerArbeitszeit_sec = strtotime($DauerArbeitszeit) - strtotime('TODAY');
                $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $DauerArbeitszeit_sec;
                $DatumOF = date_create($row["Datum"]);
                $Datum = date_format($DatumOF, "d.m.Y");
                $Anmerkung = $row['Anmerkung'];
                echo "<tr>
                    <td>".$Datum."</td>
                    <td>".$BeginnZeit."</td>
                    <td>".$EndeZeit."</td>
                    <td>".$DauerPausen."</td>
                    <td>".$DauerArbeitszeit."</td>
                    <td>".$Anmerkung."</td>
                    </tr>";
            }
        }
    }


    /*Schleife vom 25 bis zum letzten Tag*/
    for($tag = $startTag;$tag<=$LetzerTagImMonat;$tag++)
    {
        $WochenTag = date("l", mktime(0,0,0,$monat,$tag,$jahr));
        $WochenTag_DE = EngToDeu($WochenTag);

        $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $mitarbeiterID AND Datum = '$jahr-$monat-$tag'";
        $resultDaten = $conn->query($sqlDaten);

        if ($resultDaten->num_rows < 1)
        {
            if ($WochenTag_DE != "Samstag" || $WochenTag_DE != "Sonntag")
            {
                $sqlSollzeit = "SELECT * FROM Sollzeiten WHERE Wochentag = '$WochenTag_DE' AND `MitarbeiterID` = $mitarbeiterID";
                $resultSollzeit = $conn->query($sqlSollzeit);
                
                if ($resultSollzeit->num_rows >0)
                {
                    while($row = $resultSollzeit -> fetch_assoc())
                    {
                        $BeginnZeit = $row["BeginnZeit"];
                        $EndeZeit = $row["EndeZeit"];
                        $DauerPausen = $row["DauerPausen"];
                        $DauerArbeitszeit = $row["DauerArbeitszeit"];
                        $DauerArbeitszeit_sec = strtotime($DauerArbeitszeit) - strtotime('TODAY');
                        $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $DauerArbeitszeit_sec;
                        $Datum = "$tag.$monat.$jahr";

                        if ($BeginnZeit != '00:00:00')
                        {
                            echo "<tr>
                                <td>".$Datum."</td>
                                <td>".$BeginnZeit."</td>
                                <td>".$EndeZeit."</td>
                                <td>".$DauerPausen."</td>
                                <td>".$DauerArbeitszeit."</td>
                                <td>Prognose</td>
                                </tr>";
                        }
                    }
                }
            }
        }
        else
        {
            while($row = $resultDaten -> fetch_assoc())
            {
                $BeginnZeit = $row["BeginnZeit"];
                $EndeZeit = $row["EndeZeit"];
                $DauerPausen = $row["DauerPausen"];
                $DauerArbeitszeit = $row["DauerArbeitszeit"];
                $DauerArbeitszeit_sec = strtotime($DauerArbeitszeit) - strtotime('TODAY');
                $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $DauerArbeitszeit_sec;
                $DatumOF = date_create($row["Datum"]);
                $Datum = date_format($DatumOF, "d.m.Y");
                $Anmerkung = $row['Anmerkung'];
                echo "<tr>
                <td>".$Datum."</td>
                <td>".$BeginnZeit."</td>
                <td>".$EndeZeit."</td>
                <td>".$DauerPausen."</td>
                <td>".$DauerArbeitszeit."</td>
                <td>".$Anmerkung."</td>
                </tr>";
            }
        }
    }

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$KummulierteSollArbeitszeit') AS Ergebnis";
    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = $resultSECTOTIME -> fetch_assoc();
    $KummulierteSollArbeitszeitTIME = $row['Ergebnis'];


    $newdate = mktime(0, 0, 0, $monat, 1, $jahr);
    $AktuellerMonat = date("Y-m-d", $newdate);

    $VorMonat = date("Y-m", strtotime("-1 months", $newdate));
    
    $sqlEchteZeitBerechnen = "SELECT SUM(TIME_TO_SEC(DauerArbeitszeit)) AS Ergebnis FROM Arbeitszeiten WHERE MitarbeiterID = $mitarbeiterID AND Datum LIKE '%$VorMonat%'";

    $resultEchteZeitBerechnen = $conn->query($sqlEchteZeitBerechnen);

    $row = mysqli_fetch_array($resultEchteZeitBerechnen);
    $StundenAusDemVormonat = $row['Ergebnis'];

    $sqlPrognoseZeitAusVormonat = "SELECT DifferenzZeit AS Ergebnis FROM `Zeitendifferenz` WHERE `MitarbeiterID` = $mitarbeiterID AND `Datum` LIKE '%$VorMonat%'";
    $resultPrognoseZeitAusVormonat = $conn->query($sqlPrognoseZeitAusVormonat);

    $row = mysqli_fetch_array($resultPrognoseZeitAusVormonat);
    $PrognoseDerZeit = $row['Ergebnis'];

    // Die Überzeit für den jetztigen Monat wird folgend berechnet: die echten Stunden minus die Prognosezeit die abgegeben wurde am 23.XX.202X
    $Überzeit = $StundenAusDemVormonat - $PrognoseDerZeit;
    $OHNEÜberzeit = $KummulierteSollArbeitszeit;
    $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $Überzeit;

    $sqlÜberzeitBerechnen = "SELECT SEC_TO_TIME('$Überzeit') AS Ergebnis";
    $resultÜberzeitBerechnen = $conn->query($sqlÜberzeitBerechnen);

    $row = mysqli_fetch_array($resultÜberzeitBerechnen);
    $Überzeit = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$KummulierteSollArbeitszeit') AS Ergebnis";

    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = mysqli_fetch_array($resultSECTOTIME);
    $KummulierteSollArbeitszeitTIME = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$OHNEÜberzeit') AS Ergebnis";

    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = mysqli_fetch_array($resultSECTOTIME);
    $OHNEÜberzeit = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$PrognoseDerZeit') AS Ergebnis";
    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = $resultSECTOTIME -> fetch_assoc();
    $PrognoseDerZeit = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$StundenAusDemVormonat') AS Ergebnis";
    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = $resultSECTOTIME -> fetch_assoc();
    $StundenAusDemVormonat = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME(AuszahlStunden)  FROM `Auszahl` WHERE `MitarbeiterID` = $mitarbeiterID";
    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = $resultSECTOTIME -> fetch_assoc();
    $Auszahlen = $row['Ergebnis'];

    if($Auszahlen == NULL || $Auszahlen == "00:00:00")
    {
        $Auszahlen = $KummulierteSollArbeitszeitTIME;
    }

    echo "<tr>
    <th></th>
    <th></th>
    <th></th>
    <th>Total:</th>
    <th>".$OHNEÜberzeit."</td>
    <th></th>
    </tr>";

    /*echo "<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>erfasste Stunden: <br> abgebebende Stunden:</td>
    <td>".$StundenAusDemVormonat."<br>-".$PrognoseDerZeit." =</td>
    <td></td>
    </tr>";

    echo "<tr>
    <th></th>
    <th></th>
    <th></th>
    <th>Überzeit: </th>
    <th>".$Überzeit."</td>
    <td></td>
    </tr>";

    echo "<tr>
    <th></th>
    <th></th>
    <th></th>
    <th>Total:</th>
    <th>".$KummulierteSollArbeitszeitTIME."</td>
    <th></th>
    </tr>";

    echo "<tr>
    <th></th>
    <th></th>
    <th></th>
    <th>Auszahlen:</th>
    <th>".$Auszahlen."</td>
    <th></th>
    </tr>*/
    echo "</table>";
}
?>
<p>
<button onclick="printData()">PDF EXPORT</button></p>
<a href='webterminal.php'><button>zurück</button></a>
<script>
function printData() {
   var divToPrint = document.getElementById("printTable");
   var title = document.getElementById("title");
   var style = document.getElementsByTagName("style")[0].outerHTML; // Hier wird der erste <style>-Tag auf der Seite geholt
   var combinedHTML = "<!DOCTYPE html><html><head>" + style + "</head><body>" + title.outerHTML + divToPrint.outerHTML + "</body></html>";
   var newWin = window.open("");
   newWin.document.write(combinedHTML);
   newWin.document.close();
   newWin.print();
   newWin.close();
}

</script>
<?php
$conn->close();

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
/*header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Arbeitszeiten ".$Monat." ".$Jahr.".xls");*/
?>
