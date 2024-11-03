<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$UID = $_POST['UID'];
$Export = $_POST['Export'];

if($Export == "on")
{
    $Export = 1;
}
if($Export == "off")
{
    $Export = 0;
}

// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sqlUmziehen = "UPDATE `Mitarbeiter` SET `Angestellt` = '$Export' WHERE `Mitarbeiter`.`UID` = $UID";
//Ausführen des SQL-Befehls
if ($conn->query($sqlUmziehen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='./angestellt.php'>Benutzter wurde gespeichert</a>";
} 
else 
{
    // Antwort bei einem Fehler
    echo "Error: " . $sqlUmziehen . "<br>" . $conn->error;
}
// Verbindung zur Datenbank schließen
$conn->close();

// Check Verbindung zur Datenbank, bei einem Fehler kommt eine Antwort
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}