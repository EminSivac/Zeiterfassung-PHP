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
        $sqlMitarbeiterDaten = "SELECT * FROM `Entwickler`";
        $resultMitarbeiterDaten = $conn->query($sqlMitarbeiterDaten);

while ($row = $resultMitarbeiterDaten->fetch_assoc())
{
    $Name = $row['Name'];
    $Email = $row['Email'];

    echo "<div>
    ".$Name."<br>".$Email."<br>
    <div>
        <form method='post' action='dev_loeschen.php'>
            <input type='hidden' name='UID' value=".$row['UID'].">
            <button type='submit'>Entfernen</button>
        </form>
    </div>
    </div><br>";
} ?>
<div>
    <form method='post' action='dev_eintragen.php'>
        Name:<br><input type='text' name='Name' required><br>
        E-Mail:<br><input type='text' name='Email' required><br>
        <button type='submit'>Speichern</button>
    </form>
</div>
</body>
</html>