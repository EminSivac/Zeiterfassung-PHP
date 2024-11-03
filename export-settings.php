<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
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
        $sqlMitarbeiterDaten = "SELECT * FROM `Mitarbeiter`";
        $resultMitarbeiterDaten = $conn->query($sqlMitarbeiterDaten);

while ($row = $resultMitarbeiterDaten->fetch_assoc())
{
    $Vorname = $row['Vorname'];
    $Nachname = $row['Nachname'];
    $Export = $row['Export'];

    $checked = ($Export == 1) ? 'checked' : '';

    echo "<div>
    <b>".$Vorname. " ".$Nachname."</b><br>
    <div>
        <form method='post' action='export-settings_edit.php'>
        Export:<input type='checkbox' name='Export' $checked><br>
            <input type='hidden' name='UID' value=".$row['UID'].">
            <button type='submit'>Speichern</button>
        </form>
    </div>
    </div><br>";
} ?>
<!-- <div> -->
    <!-- <form method='post' action='admin_eintragen.php'> -->
        <!-- Vorname:<br><input type='text' name='Vorname' required><br> -->
        <!-- Nachname:<br><input type='text' name='Nachname' required><br> -->
        <!-- Anmeldename:<br><input type='text' name='Anmeldename' required><br> -->
        <!-- Passwort:<br><input type='text' name='Passwort' required><br> -->
        <!-- <button type='submit'>Speichern</button> -->
    <!-- </form> -->
<!-- </div> -->
</body>
</html>