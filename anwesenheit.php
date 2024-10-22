<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Anwesenheit</title>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>
</head>
<body>
<?php

echo "<h1>Zeiterfassungssystem</h1>";

        $servername = "localhost";
        $username = "Emin";
        $password = "Charlie144!";
        $dbname = "Zeiterfassung";

        $conn = new mysqli($servername, $username, $password,$dbname);
        $sqlMitarbeiterDaten = "SELECT * FROM Mitarbeiter WHERE MitarbeiterID NOT LIKE '1' AND `Status` = 'Arbeit' OR `Status` = 'Dienstgang' ORDER BY `Mitarbeiter`.`Status` DESC";
        $resultMitarbeiterDaten = $conn->query($sqlMitarbeiterDaten);

while ($row = $resultMitarbeiterDaten->fetch_assoc())
{
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];
    $MitarbeiterID = $row['MitarbeiterID'];
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

    echo "<div align=\"center\" style=\"width:30%;$FarbeStatus border:none;\">".$Vorname. " ".$Nachname."<div> Status: ".$Status."</div></div><br>";
} ?>
</body>
</html>