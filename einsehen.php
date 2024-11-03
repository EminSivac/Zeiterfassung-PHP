<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einsehen</title>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>
</head>
<body>
<p>
    <a href="adminverwaltung.html"><button>zurück</button></a>
</p>
<?php

echo "<h1>Zeiterfassungssystem</h1>";

require "dbconn.php";
        $sqlMitarbeiterDaten = "SELECT * FROM Mitarbeiter ORDER BY `Mitarbeiter`.`Vorname` ASC";
        $resultMitarbeiterDaten = $conn->query($sqlMitarbeiterDaten);

while ($row = $resultMitarbeiterDaten->fetch_assoc())
{
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];
    $MitarbeiterID = $row['MitarbeiterID'];
    $PIN = $row['PIN'];
    $Status = $row['Status'];

    if ($Status == "Abwesend")
    {
        $FarbeStatus = "background-color: grey;color: white;";
    }
    if ($Status == "Arbeit")
    {
        $FarbeStatus = "background-color: green;color: white;";
    }
    if ($Status == "Pause")
    {
        $FarbeStatus = "background-color: orange;color: white;";
    }
    if ($Status == "Dienstgang")
    {
        $FarbeStatus = "background-color: chartreuse; color: black;";
    }

    echo "<div align=\"center\" style=\"width:30%;$FarbeStatus border:none;\">

    ".$Vorname. " ".$Nachname."<br>
    MitarbeiterID: ".$MitarbeiterID."
    PIN: ".$PIN."<br>
    <div> Status: ".$Status."</div>
    
    </div><br>";
} ?>
</body>
</html>