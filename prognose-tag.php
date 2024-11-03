<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$Tag = $_POST['Day'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sqlUmziehen = "UPDATE `AdminEinstellungen` SET `Wert` = '$Tag' WHERE `AdminEinstellungen`.`UID` = 2";
//Ausführen des SQL-Befehls
if ($conn->query($sqlUmziehen) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='adminverwaltung.html'>Einstellung gespeichert</a>";
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