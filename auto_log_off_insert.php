
<?php
// Session starten
session_start();
//UID aus der ausgewählten Arbeitszeit aus dem POST holen
$UID = $_POST['UID'];
// Verbindung zur Datenbank
include "dbconn.php";
// SQL-Befehl für das Umziehen der Arbeitszeit mit der UID
$sql_select_mitarbeiterID = "SELECT * FROM Arbeitszeiten WHERE UID = $UID";
$result_select_mitarbeiterID = $conn->query($sql_select_mitarbeiterID);

$row = $result_select_mitarbeiterID->fetch_assoc();

$mitarbieterID = $row['MitarbeiterID'];

$sql_insert_auto_log_off = "INSERT INTO `AutoLogOff` (`UUID`, `UID`, `MitarbeiterID`, `Status`) VALUES (NULL, '$UID', '$mitarbieterID', '0')";
//Ausführen des SQL-Befehls
if ($conn->query($sql_insert_auto_log_off) === TRUE) 
{
    // Antwort für das Frontend
    echo "<a href='http://192.168.1.3/Projekt_Emin/adminPortal.php'>Die Arbeitszeit wurde zum Korrigieren markiert</a>";
} 
else 
{
    // Antwort bei einem Fehler
    echo "Error: " . $sql_insert_auto_log_off . "<br>" . $conn->error;
}