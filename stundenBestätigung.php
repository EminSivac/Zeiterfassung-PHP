<?php
    $Monat = $_POST['Monat'];
    $Jahr = $_POST['Jahr'];
    
    $servername = "localhost";
    $username = "Emin";
    $password = "Charlie144!";
    $dbname = "Zeiterfassung";

    $conn = new mysqli($servername, $username, $password,$dbname);
    
    //$sqlMitarbeiterName = "SELECT Vorname, Nachname FROM Mitarbeiter WHERE MitarbeiterID = $Mitarbeiter";

    //$resultMitarbeiterName = $conn->query($sqlMitarbeiterName);

    //$row = mysqli_fetch_array($resultMitarbeiterName);
    //$Vorname = $row['Vorname'];
    //$Nachname = $row['Nachname'];

    ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title"><?php echo "Arbeitszeiten ".$Monat." ".$Jahr; ?></title>
</head>
<script>
function printData()
{
   var divToPrint = document.getElementById("printTable");
   var title = document.getElementById("title");
   newWin = window.open("");
   newWin.document.write(title.outerHTML);
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
//    newWin.close();
}
</script>

    <?php
    //date_default_timezone_set('Europe/Berlin');

    
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
// echo "<style>
// table, th, td {
//     border: 1px solid black;
//     border-collapse: collapse;} 
// </style>";

function anzahlMitarbeiter($conn)
{
    $sqlMitarbeiterAnzahl = "SELECT UID FROM `Mitarbeiter` ORDER BY `Mitarbeiter`.`UID` DESC LIMIT 0, 1";
    $resultMitarbeiterAnzahl = $conn->query($sqlMitarbeiterAnzahl);

    $row = mysqli_fetch_array($resultMitarbeiterAnzahl);
    $anzahlMitarbeiter = $row['UID'];

    return $anzahlMitarbeiter;
}

function getMitarbeiterID($UID, $conn)
{
    $sqlGetMitarbeiterID = "SELECT MitarbeiterID FROM Mitarbeiter WHERE UID = $UID";
    $resultGetMitarbeiterID = $conn->query($sqlGetMitarbeiterID);

    $row = mysqli_fetch_array($resultGetMitarbeiterID);
    $MitarbeiterID = $row['MitarbeiterID'];

    return $MitarbeiterID;
}

function getMitarbeiterName($MitarbeiterID, $Monat, $Jahr, $TotalZeit, $Überzeit, $LetzerTagImMonat, $conn)
{
    $sqlGetMitarbeiterName = "SELECT * FROM Mitarbeiter WHERE MitarbeiterID = $MitarbeiterID";
    $resultGetMitarbeiterName = $conn->query($sqlGetMitarbeiterName);

    $sqlBestätigt = "SELECT * FROM `Zeitendifferenz` WHERE `MitarbeiterID` = $MitarbeiterID AND `Datum` = '$Jahr-$Monat-01' ORDER BY `UID` DESC";

    $resultBestätigt = $conn->query($sqlBestätigt);

    $row = mysqli_fetch_array($resultGetMitarbeiterName);
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];
   
    echo "<tr>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">$Vorname</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">$Nachname</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">01.$Monat.$Jahr</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">$LetzerTagImMonat.$Monat.$Jahr</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">$Überzeit</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">$TotalZeit</td>
    <td align=\"center\" style=\"border: 1px solid black;border-collapse: collapse;\">";

    if ($resultBestätigt->num_rows == 0)
    {
        echo "<form method='post' action='zeitendifferenzInsert.php'>
        <input type='hidden' name='Monat' value='$Monat'>
        <input type='hidden' name='Jahr' value='$Jahr'>
        <input type='hidden' name='Ueberzeit' value='$Überzeit'>
        <input type='hidden' name='Total' value='$TotalZeit'>
        <input type='hidden' name='MitarbeiterID' value='$MitarbeiterID'>
        <button type='submit'>Bestätigen</button>
    </form>";
    }

    echo "</td></tr>";
}

$anzahlMitarbeiter = anzahlMitarbeiter($conn);

echo "<table width=\"75%\" id=\"printTable\" style=\"border: 1px solid black;border-collapse: collapse;\">
<tr style=\"border: 1px solid black;border-collapse: collapse;\">
<th style=\"border: 1px solid black;border-collapse: collapse;\">Vorname</th>
<th style=\"border: 1px solid black;border-collapse: collapse;\">Nachname</th>
<th style=\"border: 1px solid black;border-collapse: collapse;\">Anfangszeit</th>
<th style=\"border: 1px solid black;border-collapse: collapse;\">Endzeit</th>
<th style=\"border: 1px solid black;border-collapse: collapse;\">Überzeit</th>
<th style=\"border: 1px solid black;border-collapse: collapse;\">Total</th>
<th>Bestätigen</th>
</tr>";



for ($i = 2; $i <= $anzahlMitarbeiter; $i++)
{
    $MitarbeiterID = getMitarbeiterID($i, $conn);

    if ($MitarbeiterID != null && $MitarbeiterID != 4986 && $MitarbeiterID != 2850 && $MitarbeiterID != 3477)
    {
        MeineUebersichtMonat($Monat, $Jahr, 19, $MitarbeiterID, $conn);
    }

}


function MeineUebersichtMonat($monat, $jahr, $startTag, $mitarbeiterID, $conn)
{
    $KummulierteSollArbeitszeit = 0;
    $DatumAusDemMonat = strtotime("$jahr-$monat-01");
    $LetzerTagImMonat = date("t", $DatumAusDemMonat);

    /*echo "<table id=\"data\">
            <tr>
            <th>Datum</th>
            <th>Arbeitsbeginn</th>
            <th>Arbeitsende</th>
            <th>Gesamte Pausendauer</th>
            <th>Gesamt Arbeitszeit</th>
            </tr>";*/
    /*Schleife vom 1-25*/
    for($tag=1;$tag<$startTag;$tag++){
        $WochenTag = date("l", mktime(0,0,0,$monat,$tag,$jahr));
        $WochenTag_DE = EngToDeu($WochenTag);
        //echo $WochenTag_DE."---".$echtesTagesdatum."<br>";  
        /*SELECT TAG FÜR TAG*/

        $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $mitarbeiterID AND Datum = '$jahr-$monat-$tag'";

        $resultDaten = $conn->query($sqlDaten);


        if ($resultDaten->num_rows >0)
        {
            $row = mysqli_fetch_array($resultDaten);
            $BeginnZeit = $row["BeginnZeit"];
            $EndeZeit = $row["EndeZeit"];
            $DauerPausen = $row["DauerPausen"];
            $DauerArbeitszeit = $row["DauerArbeitszeit"];
            $DauerArbeitszeit_sec = strtotime($DauerArbeitszeit) - strtotime('TODAY');
            $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $DauerArbeitszeit_sec;
            $DatumOF = date_create($row["Datum"]);
            $Datum = date_format($DatumOF, "d.m.Y");
            /*echo "<tr>
                <td>".$Datum."</td>
                <td>".$BeginnZeit."</td>
                <td>".$EndeZeit."</td>
                <td>".$DauerPausen."</td>
                <td>".$DauerArbeitszeit."</td>
                </tr>";*/
        }
    }


    /*Schleife vom 25 bis zum letzten Tag*/


    for($tag = $startTag;$tag<=$LetzerTagImMonat;$tag++){
        //echo $tag."<br>";
        $WochenTag = date("l", mktime(0,0,0,$monat,$tag,$jahr));
        $WochenTag_DE = EngToDeu($WochenTag);
        //echo $WochenTag_DE."---".$echtesTagesdatum."<br>";

        if ($WochenTag_DE == "Samstag" or $WochenTag_DE == "Sonntag")
        {
            
        }
        else
        {
            $sqlSollzeit = "SELECT * FROM Sollzeiten WHERE Wochentag = '$WochenTag_DE' AND `MitarbeiterID` = $mitarbeiterID";

            $resultSollzeit = $conn->query($sqlSollzeit);
            
            if ($resultSollzeit->num_rows >0)
            {
                $row = mysqli_fetch_array($resultSollzeit);
                //echo $resultSollzeit;
                $BeginnZeit = $row["BeginnZeit"];
                $EndeZeit = $row["EndeZeit"];
                $DauerPausen = $row["DauerPausen"];
                $DauerArbeitszeit = $row["DauerArbeitszeit"];
                $DauerArbeitszeit_sec = strtotime($DauerArbeitszeit) - strtotime('TODAY');
                $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $DauerArbeitszeit_sec;


                $Datum = "$tag.$monat.$jahr";

                if ($BeginnZeit != '00:00:00')
                {
                    /*echo "<tr>
                        <td>".$Datum."</td>
                        <td>".$BeginnZeit."</td>
                        <td>".$EndeZeit."</td>
                        <td>".$DauerPausen."</td>
                        <td>".$DauerArbeitszeit."</td>
                        </tr>";*/
                }
            }
        }
    }

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$KummulierteSollArbeitszeit') AS Ergebnis";

    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = mysqli_fetch_array($resultSECTOTIME);
    $KummulierteSollArbeitszeitTIME = $row['Ergebnis'];


    $newdate = mktime(0, 0, 0, $monat, 1, $jahr);
    $AktuellerMonat = date("Y-m-d", $newdate);
    /*$sqlVormonatZeitPrognoseInsert = "INSERT INTO `Zeitendifferenz` (`UID`, `MitarbeiterID`, `Datum`, `DifferenzZeit`, `Auszahlstunden`) VALUES (NULL, '$mitarbeiterID', '$AktuellerMonat', TIME_TO_SEC('$KummulierteSollArbeitszeitTIME'), '0')";
    $sqlPruefeZeitPrognose = "SELECT * FROM Zeitendifferenz WHERE MitarbeiterID = $mitarbeiterID AND Datum = '$AktuellerMonat'";

    $resultPruefeZeitPrognose = $conn->query($sqlPruefeZeitPrognose);
    if ($resultPruefeZeitPrognose->num_rows == 0)
    {
        if ($conn->query($sqlVormonatZeitPrognoseInsert) === TRUE) 
        {
            //echo "Checked <a href='./buero.html'>Nächser Mitarbeiter</a>";
            //echo "Checked";
        }
    }*/
    
    //echo $VormonatZeitPrognoseInsert;
    
    //$VorMonat = date("Y-m", $newdate);
    $VorMonat = date("Y-m", strtotime("-1 months", $newdate));
    
    $sqlEchteZeitBerechnen = "SELECT SUM(TIME_TO_SEC(DauerArbeitszeit)) AS Ergebnis FROM Arbeitszeiten 
    WHERE MitarbeiterID = $mitarbeiterID AND Datum LIKE '%$VorMonat%'";
    //echo $sqlEchteZeitBerechnen;

    $resultEchteZeitBerechnen = $conn->query($sqlEchteZeitBerechnen);

    $row = mysqli_fetch_array($resultEchteZeitBerechnen);
    $StundenAusDemVormonat = $row['Ergebnis'];

    $sqlPrognoseZeitAusVormonat = "SELECT DifferenzZeit AS Ergebnis FROM `Zeitendifferenz` WHERE `MitarbeiterID` = $mitarbeiterID AND `Datum` LIKE '%$VorMonat%'";
    //echo $sqlPrognoseZeitAusVormonat." ";
    $resultPrognoseZeitAusVormonat = $conn->query($sqlPrognoseZeitAusVormonat);

    $row = mysqli_fetch_array($resultPrognoseZeitAusVormonat);
    $PrognoseDerZeit = $row['Ergebnis'];

    $Überzeit = $StundenAusDemVormonat - $PrognoseDerZeit;
    $KummulierteSollArbeitszeit = $KummulierteSollArbeitszeit + $Überzeit;
    //echo $Überzeit." ";

    $sqlÜberzeitBerechnen = "SELECT SEC_TO_TIME('$Überzeit') AS Ergebnis";
    $resultÜberzeitBerechnen = $conn->query($sqlÜberzeitBerechnen);

    $row = mysqli_fetch_array($resultÜberzeitBerechnen);
    $Überzeit = $row['Ergebnis'];

    $sqlSECTOTIME = "SELECT SEC_TO_TIME('$KummulierteSollArbeitszeit') AS Ergebnis";

    $resultSECTOTIME = $conn->query($sqlSECTOTIME);

    $row = mysqli_fetch_array($resultSECTOTIME);
    $KummulierteSollArbeitszeitTIME = $row['Ergebnis'];

    getMitarbeiterName($mitarbeiterID, $monat, $jahr, $KummulierteSollArbeitszeitTIME, $Überzeit, $LetzerTagImMonat, $conn);

    //echo $PrognoseDerZeit." ";
    //echo $StundenAusDemVormonat." ";

    /*echo "<tr>
        <th></th>
        <th></th>
        <th></th>
        <th>Überzeit:</th>
        <td>".$Überzeit."</td>
        </tr>
        ";*/

    /*echo "Stunden aus Arbeitszeit: $ArbeitszeitAusStunden<br>";
    echo "Stunden aus Soll: $ArbeitszeitAusSOLLZEITEN<br>";
    echo "Stunden aus Gesamtzeit: $GesamtArbeitsZeit<br>";*/

        /*echo "<tr>
        <th></th>
        <th></th>
        <th></th>
        <th>Total:</th>
        <td>".$KummulierteSollArbeitszeitTIME."</td>
        </tr></table>";*/
        
        
}
?>
<p>
<a href="adminPortal.php"><button>zurück</button></a></p>

<?php
$conn->close();

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
/*header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Arbeitszeiten ".$Monat." ".$Jahr.".xls");*/
?>