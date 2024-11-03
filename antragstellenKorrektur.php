<?php
session_start();

require "dbconn.php";

if ($_POST['MitarbeiterID'] == null)
{
    $Mitarbeiter = $_SESSION['Mitarbeiter'];
}
else
{
    $Mitarbeiter = $_POST['MitarbeiterID'];
}

$UID = $_POST['UID'];
$Datum = $_POST['Datum'];
$BeginnZeit = substr($_POST['BeginnZeit'],0,5);
$EndeZeit = substr($_POST['EndeZeit'],0,5);
$DauerPausen = substr($_POST['DauerPausen'],0,5);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrag stellen</title>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>
</head>
<body>
    <h1>Zeiterfassungssystem</h1>
    <a href='antragstellenFront.php'><button>zurück</button></a>
    <form method="post" action="antragstellen.php">
        <p> Datum: <input type="date" name="Datum" id="Datum" value="<?php echo $Datum; ?>" required/></p>
        <p> Arbeitsbeginn: <input type="time" name="BeginnZeit" id="BeginnZeit" value="<?php echo $BeginnZeit; ?>" required/></p>
        <p> Arbeitsende: <input type="time" name="EndeZeit" id="EndeZeit" value="<?php echo $EndeZeit; ?>" required/></p>
        <p> Dauer der Pausen: <input type="time" name="DauerPausen" id="DauerPausen" value="<?php echo $DauerPausen; ?>" required/></p>
        <p> Beschreibung: <br><br><textarea cols="40" rows="5" name="Beschreibung" id="Beschreibung" required></textarea></p>
        <input type="hidden" name="Art" id="Art" value="Korrektur"/>
        <input type="hidden" name="UID" id="UID" value="<?php echo $UID; ?>"/>
        <button type="submit">Antrag stellen</button>
    </form>
    <!-- <footer style="position: fixed;border-top: solid;left: 0;bottom: 0;width: 100%;text-align: center;">
        Programmiert und verwaltet von Emin Sivac
    </footer> -->
</body>
</html>

