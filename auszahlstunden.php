<?php
session_start();
include "dbconn.php";

$MitarbeiterID = $_SESSION['Mitarbeiter'];
$Auszahl = $_POST['AuszahlStunden'];
if($Auszahl == null)
{
  $Auszahl = "00:00:00";
}

$sqlAuszahlInsert = "INSERT INTO `Auszahl` (`UID`, `MitarbeiterID`, `AuszahlStunden`) VALUES ('', '$MitarbeiterID', TIME_TO_SEC('$Auszahl'))";
$sqlAuszahlSelect = "SELECT * FROM `Auszahl` WHERE `MitarbeiterID` = $MitarbeiterID";
$sqlAuszahlUpdate = "UPDATE `Auszahl` SET `Auszahlstunden` = TIME_TO_SEC('$Auszahl') WHERE `MitarbeiterID` = $MitarbeiterID";

$resultAuszahlSelect = $conn->query($sqlAuszahlSelect);

if ($resultAuszahlSelect->num_rows > 0)
{
    if ($conn->query($sqlAuszahlUpdate) === TRUE) 
    {
      echo "Checked <a href='./einstellungen.php'>zurück</a>";
    } 
    else 
    {
      echo "Error: " . $sqlAuszahlUpdate . "<br>" . $conn->error;
    }
}
else
{
    if ($conn->query($sqlAuszahlInsert) === TRUE) 
    {
      echo "Checked <a href='./einstellungen.php'>zurück</a>";
    } 
    else 
    {
      echo "Error: " . $sqlAuszahlInsert . "<br>" . $conn->error;
    }
}

$conn->close();

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}