<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$Anmeldename = $_POST['Anmeldename'];
$Vorname = $_POST['Vorname'];
$Nachname = $_POST['Nachname'];
$Passwort = $_POST['Passwort'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sqlUmziehen = "INSERT INTO `Admin` (`UID`, `Anmeldename`, `Passwort`, `Vorname`, `Nachname`) VALUES (NULL, '$Anmeldename', '$Passwort', '$Vorname', '$Nachname')";
//Ausführen des SQL-Befehls
if ($conn->query($sqlUmziehen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='neuAdmin.php'>Benutzter wurde gespeichert</a>";
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