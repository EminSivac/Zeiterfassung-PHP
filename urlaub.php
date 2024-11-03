<?php
session_start();
include "dbconn.php";

$MitarbeiterID = $_SESSION['Mitarbeiter'];
$Auszahl = $_POST['Anzahl'];
$Jahr = date("Y") + 1;
if($Auszahl == null)
{
  $Auszahl = "00:00:00";
}

$sqlAuszahlInsert = "INSERT INTO `Urlaub` (`UID`, `MitarbeiterID`, `RestUrlaub`, `Jahr`) VALUES (NULL, '$MitarbeiterID', '$Auszahl', '$Jahr')";
$sqlAuszahlSelect = "SELECT * FROM `Urlaub` WHERE `MitarbeiterID` = $MitarbeiterID";
$sqlAuszahlUpdate = "UPDATE `Urlaub` SET `RestUrlaub`= $Auszahl WHERE `MitarbeiterID` = $MitarbeiterID AND `Jahr` = $Jahr";

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