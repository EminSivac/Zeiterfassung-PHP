<?php
// Session starten
session_start();
//UID aus der ausgewählten Admin aus dem POST holen
$UID = $_POST['UID'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Admin mit der UID
$sqlLoeschen = "DELETE FROM `Entwickler` WHERE `Entwickler`.`UID` = $UID";
//Ausführen des SQL-Befehls
if ($conn->query($sqlLoeschen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='dev.php'>Benutzter wurde gelöscht</a>";
} 
else 
{
    // Antwort bei einem Fehler
    echo "Error: " . $sqlLoeschen . "<br>" . $conn->error;
}
// Verbindung zur Datenbank schließen
$conn->close();

// Check Verbindung zur Datenbank, bei einem Fehler kommt eine Antwort
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}