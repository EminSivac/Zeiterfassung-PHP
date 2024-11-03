<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$Email = $_POST['Email'];
$Name = $_POST['Name'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sqlUmziehen = "INSERT INTO `Entwickler` (`UID`, `Name`, `Email`) VALUES (NULL, '$Name', '$Email')";
//Ausführen des SQL-Befehls
if ($conn->query($sqlUmziehen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='dev.php'>Benutzter wurde gespeichert</a>";
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