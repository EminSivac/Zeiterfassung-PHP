<?php
$MitarbeiterID = $_POST['MitarbeiterID'];
$Monat = $_POST['Monat'];
$Jahr = $_POST['Jahr'];
$Ueberzeit = $_POST['Ueberzeit'];
$Total = $_POST['Total'];


// echo $Auszahl;

require "dbconn.php";

$sqlAuszahlStundenSelect = "SELECT SEC_TO_TIME(`AuszahlStunden`) FROM `Auszahl` WHERE MitarbeiterID = $MitarbeiterID";
$resultAuszahlStundenSelect = $conn->query($sqlAuszahlStundenSelect);
$row = mysqli_fetch_array($resultAuszahlStundenSelect);
$Auszahl = $row['SEC_TO_TIME(`AuszahlStunden`)'];

if($Auszahl == null)
{
  $Auszahl = "00:00:00";
}

if($Auszahl == "00:00:00")
{
  $sqlTotalToSec = "SELECT TIME_TO_SEC('$Total')-TIME_TO_SEC('$Ueberzeit') AS Ergebnis";
}
else
{
  $sqlTotalToSec = "SELECT TIME_TO_SEC('$Auszahl')-TIME_TO_SEC('$Ueberzeit') AS Ergebnis";
}

$resultTotalToSec = $conn->query($sqlTotalToSec);

$row = mysqli_fetch_array($resultTotalToSec);
$Total = $row['Ergebnis'];

$sqlZeitendifferenzInsert = "INSERT INTO `Zeitendifferenz` (`UID`, `MitarbeiterID`, `Datum`, `DifferenzZeit`, `Auszahlstunden`) VALUES (NULL, $MitarbeiterID, '$Jahr-$Monat-01', $Total, TIME_TO_SEC('$Auszahl'))";
$sqlZeitendifferenzSelect = "SELECT * FROM `Zeitendifferenz` WHERE `MitarbeiterID` = $MitarbeiterID AND `Datum` LIKE '%$Jahr-$Monat%'";
$sqlZeitendifferenzUpdate = "UPDATE `Zeitendifferenz` SET `DifferenzZeit` = '$Total', `Auszahlstunden` = TIME_TO_SEC('$Auszahl') WHERE `MitarbeiterID` = $MitarbeiterID AND `Datum` LIKE '%$Jahr-$Monat%'";

$resultZeitendifferenzSelect = $conn->query($sqlZeitendifferenzSelect);

if ($resultZeitendifferenzSelect->num_rows > 0)
{
    if ($conn->query($sqlZeitendifferenzUpdate) === TRUE) 
    {
      // echo "Checked <a href='http://192.168.1.3/Projekt_Emin/buero.html'>Nächster Mitarbeiter</a><br>Ende Zeit<br>";
      // echo $sqlGehtUpdate;
    } 
    else 
    {
      echo "Error: " . $sqlZeitendifferenzUpdate . "<br>" . $conn->error;
    }
}
else
{
    if ($conn->query($sqlZeitendifferenzInsert) === TRUE) 
    {
      // echo "Checked
      // <form method='post' action='stundenBestätigung.php'>
      // <input type='hidden' name='Monat' value='$Monat'>
      // <input type='hidden' name='Jahr' value='$Jahr'>
      // <button type='submit'>zurück</button>
      // </form>";
    } 
    else 
    {
      echo "Error: " . $sqlZeitendifferenzInsert . "<br>" . $conn->error;
    }
}

echo "Checked
      <form method='post' action='stundenBestätigung.php'>
      <input type='hidden' name='Monat' value='$Monat'>
      <input type='hidden' name='Jahr' value='$Jahr'>
      <button type='submit'>zurück</button>
      </form>";

?>