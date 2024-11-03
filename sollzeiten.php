<?php
    session_start();

    include "dbconn.php";

    $MitarbeiterID = $_SESSION['Mitarbeiter'];

    $BeginnZeitMontag = $_POST['MontagBeginnZeit'];
    $EndeZeitMontag = $_POST['MontagEndeZeit'];
    $DauerPausenMontag = $_POST['MontagDauerPausen'];

    $BeginnZeitDienstag = $_POST['DienstagBeginnZeit'];
    $EndeZeitDienstag = $_POST['DienstagEndeZeit'];
    $DauerPausenDienstag = $_POST['DienstagDauerPausen'];

    $BeginnZeitMittwoch = $_POST['MittwochBeginnZeit'];
    $EndeZeitMittwoch = $_POST['MittwochEndeZeit'];
    $DauerPausenMittwoch = $_POST['MittwochDauerPausen'];

    $BeginnZeitDonnerstag = $_POST['DonnerstagBeginnZeit'];
    $EndeZeitDonnerstag = $_POST['DonnerstagEndeZeit'];
    $DauerPausenDonnerstag = $_POST['DonnerstagDauerPausen'];

    $BeginnZeitFreitag = $_POST['FreitagBeginnZeit'];
    $EndeZeitFreitag = $_POST['FreitagEndeZeit'];
    $DauerPausenFreitag = $_POST['FreitagDauerPausen'];

    if ($BeginnZeitMontag == null)
    {
        $BeginnZeitMontag = "00:00:00";
    }
    if ($EndeZeitMontag == null)
    {
        $EndeZeitMontag = "00:00:00";
    }
    if ($DauerPausenMontag == null)
    {
        $DauerPausenMontag = "00:00:00";
    }

    if ($BeginnZeitDienstag == null)
    {
        $BeginnZeitDienstag = "00:00:00";
    }
    if ($EndeZeitDienstag == null)
    {
        $EndeZeitDienstag = "00:00:00";
    }
    if ($DauerPausenDienstag == null)
    {
        $DauerPausenDienstag = "00:00:00";
    }
    
    if ($BeginnZeitMittwoch == null)
    {
        $BeginnZeitMittwoch = "00:00:00";
    }
    if ($EndeZeitMittwoch == null)
    {
        $EndeZeitMittwoch = "00:00:00";
    }
    if ($DauerPausenMittwoch == null)
    {
        $DauerPausenMittwoch = "00:00:00";
    }
    
    if ($BeginnZeitDonnerstag == null)
    {
        $BeginnZeitDonnerstag = "00:00:00";
    }
    if ($EndeZeitDonnerstag == null)
    {
        $EndeZeitDonnerstag = "00:00:00";
    }
    if ($DauerPausenDonnerstag == null)
    {
        $DauerPausenDonnerstag = "00:00:00";
    }
    
    if ($BeginnZeitFreitag == null)
    {
        $BeginnZeitFreitag = "00:00:00";
    }
    if ($EndeZeitFreitag == null)
    {
        $EndeZeitFreitag = "00:00:00";
    }
    if ($DauerPausenFreitag == null)
    {
        $DauerPausenFreitag = "00:00:00";
    }


    $sqlSollzeitUpdate1 = "UPDATE `Sollzeiten` SET `BeginnZeit` = '$BeginnZeitMontag', `EndeZeit` = '$EndeZeitMontag', `DauerPausen` = '$DauerPausenMontag', `DauerArbeitszeit` = SUBTIME( TIMEDIFF(`EndeZeit`, `BeginnZeit`) , `DauerPausen`) WHERE `Sollzeiten`.`MitarbeiterID` = '$MitarbeiterID' AND `Sollzeiten`.`Wochentag` = 'Montag'";
    $sqlSollzeitUpdate2 = "UPDATE `Sollzeiten` SET `BeginnZeit` = '$BeginnZeitDienstag', `EndeZeit` = '$EndeZeitDienstag', `DauerPausen` = '$DauerPausenDienstag', `DauerArbeitszeit` = SUBTIME( TIMEDIFF(`EndeZeit`, `BeginnZeit`) , `DauerPausen`)  WHERE `Sollzeiten`.`MitarbeiterID` = '$MitarbeiterID' AND `Sollzeiten`.`Wochentag` = 'Dienstag'";
    $sqlSollzeitUpdate3 = "UPDATE `Sollzeiten` SET `BeginnZeit` = '$BeginnZeitMittwoch', `EndeZeit` = '$EndeZeitMittwoch', `DauerPausen` = '$DauerPausenMittwoch', `DauerArbeitszeit` = SUBTIME( TIMEDIFF(`EndeZeit`, `BeginnZeit`) , `DauerPausen`)  WHERE `Sollzeiten`.`MitarbeiterID` = '$MitarbeiterID' AND `Sollzeiten`.`Wochentag` = 'Mittwoch'";
    $sqlSollzeitUpdate4 = "UPDATE `Sollzeiten` SET `BeginnZeit` = '$BeginnZeitDonnerstag', `EndeZeit` = '$EndeZeitDonnerstag', `DauerPausen` = '$DauerPausenDonnerstag', `DauerArbeitszeit` = SUBTIME( TIMEDIFF(`EndeZeit`, `BeginnZeit`) , `DauerPausen`)  WHERE `Sollzeiten`.`MitarbeiterID` = '$MitarbeiterID' AND `Sollzeiten`.`Wochentag` = 'Donnerstag'";
    $sqlSollzeitUpdate5 = "UPDATE `Sollzeiten` SET `BeginnZeit` = '$BeginnZeitFreitag', `EndeZeit` = '$EndeZeitFreitag', `DauerPausen` = '$DauerPausenFreitag', `DauerArbeitszeit` = SUBTIME( TIMEDIFF(`EndeZeit`, `BeginnZeit`) , `DauerPausen`)  WHERE `Sollzeiten`.`MitarbeiterID` = '$MitarbeiterID' AND `Sollzeiten`.`Wochentag` = 'Freitag'";

    if ($conn->query($sqlSollzeitUpdate1) === TRUE) 
    {
      //echo "Checked <a href='./webterminal.php'>zurück</a>";
      
    } 
    else 
    {
      echo "Error: " . $sqlSollzeitUpdate1 . "<br>" . $conn->error;
    }
    if ($conn->query($sqlSollzeitUpdate2) === TRUE) 
    {
      //echo "Checked <a href='./webterminal.php'>zurück</a>";
      
    } 
    else 
    {
      echo "Error: " . $sqlSollzeitUpdate2 . "<br>" . $conn->error;
    }
    if ($conn->query($sqlSollzeitUpdate3) === TRUE) 
    {
      //echo "Checked <a href='./webterminal.php'>zurück</a>";
      
    } 
    else 
    {
      echo "Error: " . $sqlSollzeitUpdate3 . "<br>" . $conn->error;
    }
    if ($conn->query($sqlSollzeitUpdate4) === TRUE) 
    {
      //echo "Checked <a href='./webterminal.php'>zurück</a>";
      
    } 
    else 
    {
      echo "Error: " . $sqlSollzeitUpdate4 . "<br>" . $conn->error;
    }
    if ($conn->query($sqlSollzeitUpdate5) === TRUE) 
    {
      echo "Checked <a href='./webterminal.php'>zurück</a>";
      
    } 
    else 
    {
      echo "Error: " . $sqlSollzeitUpdate5 . "<br>" . $conn->error;
    }
?>