<?php
    session_start();

    $Mitarbeiter = $_SESSION['Mitarbeiter'];

    include "dbconn.php";

    $sqlSollzeitenSelect = "SELECT * FROM `Sollzeiten` WHERE `MitarbeiterID`= '$Mitarbeiter' ORDER BY `UID` ASC";

    $resultSollzeitenSelect = $conn->query($sqlSollzeitenSelect);

    ?>

    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Einstellungen</title>
        <style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>
    </head>
    <body>
        <h1>Zeiterfassungssystem</h1>
        <a href='webterminal.php'><button>zurück</button></a>
        <h2>Sollzeiten</h2>
        <form method="post" action="sollzeiten.php">

    <?php

    echo "<table>";

    while ($row = $resultSollzeitenSelect->fetch_assoc())
    {
        $BeginnZeit = $row['BeginnZeit'];
        $EndeZeit = $row['EndeZeit'];
        $DauerPausen = $row['DauerPausen'];
        $Wochentag = $row['Wochentag'];

        echo "
        <tr>
        <th>$Wochentag</th>
        <td>Arbeitsbeginn: <input type='time' value='$BeginnZeit' name='".$Wochentag."BeginnZeit' id='".$Wochentag."BeginnZeit'/></td>
        <td>Arbeitsende: <input type='time' value='$EndeZeit' name='".$Wochentag."EndeZeit' id='".$Wochentag."EndeZeit'/></td>
        <td>Dauer der Pause: <input type='time' value='$DauerPausen' name='".$Wochentag."DauerPausen' id='".$Wochentag."DauerPausen'/></td>
        </tr>";

    }

    echo "</table>";

    $sqlAuszahlStundenSelect = "SELECT SEC_TO_TIME(`AuszahlStunden`) FROM `Auszahl` WHERE MitarbeiterID = $Mitarbeiter";
    $resultAuszahlStundenSelect = $conn->query($sqlAuszahlStundenSelect);

    $row = mysqli_fetch_array($resultAuszahlStundenSelect);
    $Zeit = $row['SEC_TO_TIME(`AuszahlStunden`)'];

    $Jahr = date("Y") + 1;
    $sqlGetUrlaub = "SELECT * FROM `Urlaub` WHERE `MitarbeiterID` = $Mitarbeiter AND `Jahr` = $Jahr";
    $resultGetUrlaub = $conn->query($sqlGetUrlaub);
    
    $row = $resultGetUrlaub->fetch_assoc();
    $Urlaubstage = $row['RestUrlaub'];
?>
    <input type="submit" value="Speichern"/>
    </form>

    <!-- <form method="post" action="auszahlstunden.php">
    <h2>Auszahlstunden</h2> 
    <p> Anzahl der Stunden: <input type="text" name="AuszahlStunden" placeholder="00:00:00" value="<?php echo $Zeit; ?>"/></p>
    <h4>"Nichts" bedeutet alle Stunden.</h4>
    <input type="submit" value="Speichern"/>
    </form> -->

    <h2>Urlaubstage</h2>
    <form method="post" action="urlaub.php">
    <p> Anzahl der Urlaubstage im für <?php echo $Jahr; ?>: <input type="number" name="Anzahl" placeholder="30" value="<?php echo $Urlaubstage; ?>"/></p>
    <input type="submit" value="Speichern"/>
    </form>
</body>
</html>