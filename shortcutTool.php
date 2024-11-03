<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortcut-Tool</title>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>
</head>
<body>
<h1>Zeiterfassungssystem</h1>
<a href="adminPortal.php"><button>zurück</button></a>
<br>

<h2>An- / Abmeldung</h2>
<form method="post" action="bueroBestaetigen.php">
        <p> 
            <div>
                <input type="radio" id="Kommt" name="Art" onclick="getFocus()" value="BeginnZeit">
                <label for="Kommt">Kommt</label>
            </div><br>
            <div>
                <input type="radio" id="PauseBeginn" name="Art" onclick="getFocus()" value="BeginnPause">
                <label for="PauseBeginn">Pause Beginn</label>
            </div><br>
            <div>
                <input type="radio" id="PauseEnde" name="Art" onclick="getFocus()" value="EndePause">
                <label for="PauseEnde">Pause Ende</label>
            </div><br>
            <div>
                <input type="radio" id="Geht" name="Art" onclick="getFocus()" value="EndeZeit">
                <label for="Geht">Geht</label>
            </div><br>
            <div>
                <input type="radio" id="DienstgangBeginn" name="Art" onclick="getFocus()" value="DienstgangBeginn">
                <label for="DienstgangBeginn">Dienstgang Beginn</label>
            </div><br>
            <div>
                <input type="radio" id="DienstgangEnde" name="Art" onclick="getFocus()" value="DienstgangEnde">
                <label for="DienstgangEnde">Dienstgang Ende</label>
            </div><br>
            <div>
                <input type="radio" id="Status" name="Art" onclick="getFocus()" value="Status">
                <label for="Status">Status abfragen</label>
            </div><br>
            <input type="hidden" name="case" id="case" value="1"/>
        </p>       
<?php
session_start();
require "dbconn.php";

$Admin = $_SESSION['Admin'];
$Password = $_SESSION['Password'];

$sqlCheckAdmin = "SELECT * FROM `Admin` WHERE `Anmeldename` = '$Admin' AND `Passwort` = '$Password'";
$resultsqlCheckAdmin = $conn->query($sqlCheckAdmin);

$row = mysqli_fetch_array($resultsqlCheckAdmin);
$Vorname = $row['Vorname'];
$Nachname = $row['Nachname'];

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

function getMitarbeiterName($MitarbeiterID, $conn)
{
    $sqlGetMitarbeiterName = "SELECT * FROM Mitarbeiter WHERE MitarbeiterID = $MitarbeiterID";
    $resultGetMitarbeiterName = $conn->query($sqlGetMitarbeiterName);

    $row = mysqli_fetch_array($resultGetMitarbeiterName);
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];

    return $Vorname." ".$Nachname;
}

$anzahlMitarbeiter = anzahlMitarbeiter($conn);
echo "<select name=\"MitarbeiterID\" id=\"MitarbeiterID\">";

for ($i = 2;$i <= $anzahlMitarbeiter; $i++)
{
  $MitarbeiterID = getMitarbeiterID($i, $conn);
  $MitarbeiterName = getMitarbeiterName($MitarbeiterID, $conn);

  if ($MitarbeiterID != null)
  {
    echo "<option value=".$MitarbeiterID.">".$MitarbeiterName."</option>";
  }

}

echo "</select>";?>
    <button type="submit">Bestätigen</button>
    </form>

    <h2>Anträge</h2>

    <form method="post" action="antragstellen.php">
        <p> Datum: <input type="date" name="Datum" id="Datum" required/></p>
        <p> Arbeitsbeginn: <input type="time" name="BeginnZeit" id="BeginnZeit" value="00:00" required/></p>
        <p> Arbeitsende: <input type="time" name="EndeZeit" id="EndeZeit" value="00:00" required/></p>
        <p> Dauer der Pausen: <input type="time" name="DauerPausen" id="DauerPausen" value="00:00" required/></p>

        <?php echo "<p> Beschreibung: <br><br><textarea cols=\"40\" rows=\"5\" name=\"Beschreibung\" id=\"Beschreibung\" required/>gestellt von ". $Vorname ." ". $Nachname . ":" ."</textarea></p>";?>
        <p> Art: <select name="Art" id="Art" required>
                <option value="">Wählen</option>
                <option value="Korrektur">Korrektur einer Arbeitszeit [z.B. Vergessen aus Pause auszuloggen, Pause konnte nicht beendet werden]</option>
                <option value="Nachtragen">Arbeitszeit nachtragen [z.B. Home Office, vergessen sich einzuloggen, Messe]</option>
                </select></p>
                <input type="hidden" name="case" id="case" value="1"/>

    <?php
    $anzahlMitarbeiter = anzahlMitarbeiter($conn);
    echo "<select name=\"MitarbeiterID\" id=\"MitarbeiterID\">";
    
    for ($i = 2;$i <= $anzahlMitarbeiter; $i++)
    {
      $MitarbeiterID = getMitarbeiterID($i, $conn);
      $MitarbeiterName = getMitarbeiterName($MitarbeiterID, $conn);
    
      if ($MitarbeiterID != null)
      {
        echo "<option value=".$MitarbeiterID.">".$MitarbeiterName."</option>";
      }
    
    }
    
    echo "</select>";
    ?>

    
    <button type="submit">Antrag stellen</button>
</form>

</body>
</html>