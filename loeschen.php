<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$UID = $_POST['UID'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sqlUmziehen = "INSERT INTO `ArbeitszeitenArchive` SELECT * FROM `Arbeitszeiten` WHERE `UID` = $UID";
$sqlLoeschen = "DELETE FROM `Arbeitszeiten` WHERE `Arbeitszeiten`.`UID` = $UID";
//Ausführen des SQL-Befehls
if ($conn->query($sqlUmziehen) === TRUE) 
{
    // Antwort für das Frontend
    // echo "<a href='./adminPortal.php'>Die Arbeitszeit wurde gelöscht</a>";
} 
else 
{
    // Antwort bei einem Fehler
    echo "Error: " . $sqlUmziehen . "<br>" . $conn->error;
}
if ($conn->query($sqlLoeschen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='./adminPortal.php'>Die Arbeitszeit wurde gelöscht</a>";
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