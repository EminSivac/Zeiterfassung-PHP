<?php
require "dbconn.php";

$Arbeitsende = $_POST['EndeZeit'];
$UID = $_POST['UID'];
$UUID = $_POST['UUID'];

$sqlUpdateAutoLogOff = "UPDATE `AutoLogOff` SET `Status` = '1' WHERE `AutoLogOff`.`UUID` = $UUID";

    if ($conn->query($sqlUpdateAutoLogOff) === TRUE) 
    {
      
    } 
    else 
    {
      echo "Error: " . $sqlUpdateAutoLogOff . "<br>" . $conn->error;
    }

    $sqlBeginnZeit = "SELECT * FROM `Arbeitszeiten` WHERE `UID` = $UID";

    $resultBeginnZeit = $conn->query($sqlBeginnZeit);

    $row = mysqli_fetch_array($resultBeginnZeit);
    $datum = $row['Datum'];
    $BeginnZeit = $row['BeginnZeit'];
    
    $sqlPausenZusammenrechnen = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`DauerPause`))) AS `Ergebnis` FROM `Pausen` WHERE `BeginnPause` > '$BeginnZeit'  AND `EndePause` < '$Arbeitsende' AND `MitarbeiterID` = $Mitarbeiter AND `Datum` = '$datum'";

    $resultPausenZusammenrechnen = $conn->query($sqlPausenZusammenrechnen);

    $row = mysqli_fetch_array($resultPausenZusammenrechnen);
    $DauerPausen = $row['Ergebnis'];
    if ($DauerPausen == null)
    {
      $DauerPausen = "00:00:00";
    }

    $sqlStundenAnzahlAbfrage = "SELECT TIMEDIFF('$Arbeitsende', '$BeginnZeit') as Ergebnis";

    $resultStundenAnzahlAbfrage = $conn->query($sqlStundenAnzahlAbfrage);

    $row = mysqli_fetch_array($resultStundenAnzahlAbfrage);
    $StundenAnzahl  = $row['Ergebnis'];
    //echo $StundenAnzahl;

    if($StundenAnzahl > "06:00:00")
    {
      if ($DauerPausen < "00:30:00")
      {
        $DauerPausen = "00:30:00";
      }
    }
    if ($StundenAnzahl > "09:00:00")
    {
      if ($DauerPausen < "00:45:00")
      {
        $DauerPausen = "00:45:00";
      }
    }

    $sqlGehtUpdate = "UPDATE `Arbeitszeiten` SET `EndeZeit` = '$Arbeitsende', `DauerPausen` = '$DauerPausen', `DauerArbeitszeit` = SUBTIME( TIMEDIFF('$Arbeitsende', '$BeginnZeit') , '$DauerPausen') WHERE `Arbeitszeiten`.`UID` = $UID";
    //echo $sqlGehtUpdate;

    if ($conn->query($sqlGehtUpdate) === TRUE) 
    {
      if($case == 1)
            {
                echo "Checked <a href='./shortcutTool.php'>zurück</a><br>Ende Zeit";
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Zeit wurde korrigiert";

            }
    } 
    else 
    {
      echo "Error: " . $sqlGehtUpdate . "<br>" . $conn->error;
    }
