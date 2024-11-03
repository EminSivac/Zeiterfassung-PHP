<?php
    session_start();
    $UIDUP = $_POST['UID'];
    $StatusUP = $_POST['Status'];
    $Mitarbeiter = $_POST['MitarbeiterID'];
    $DatumOF = date_create($_POST['Datum']);
    $Datum = date_format($DatumOF, "Y-m-d");
    $Anmerkung = $_POST['Anmerkung'];
    // echo $Datum;
    // echo $UIDUP;

    $BeginnZeit = $_POST["BeginnZeit"];
    $EndeZeit = $_POST["EndeZeit"];
    $DauerPausen = $_POST["DauerPausen"];
    echo $Art = $_POST['Art'];
    $ArbeitszeitUID = $_POST['ArbeitszeitUID'];

    // $_SESSION['Admin'] = "root";
    // $_SESSION['Password'] = "t568jswi";
    $_SESSION['MitarbeiterID'] = $Mitarbeiter;
    //$Mitarbeiter = $_POST['MitarbeiterID'];

    require "dbconn.php";

    $sqlAntragUpdate = "UPDATE `Anträge` SET `Status` = '$StatusUP' WHERE `Anträge`.`UID` = $UIDUP";

    if ($StatusUP == "Akzeptiert")
    {
      if ($Art == "Korrektur")
      {
          $sqlArbeitszeitenUpdate = "UPDATE `Arbeitszeiten` SET `Anmerkung` = '$Anmerkung',`BeginnZeit` = '$BeginnZeit', `EndeZeit` = '$EndeZeit', `DauerPausen` = '$DauerPausen', `DauerArbeitszeit` = SUBTIME( TIMEDIFF('$EndeZeit', '$BeginnZeit') , '$DauerPausen') WHERE `Arbeitszeiten`.`UID` = $ArbeitszeitUID";
          //echo $sqlArbeitszeitenUpdate;
          if ($conn->query($sqlArbeitszeitenUpdate) === TRUE) 
          {
            echo "Checked korektur";
            //header ("Location: ./adminPortal.php");
            //exit;
          }
          else 
          {
            echo "Error: " . $sqlArbeitszeitenUpdate . "<br>" . $conn->error;
          }
      }
      if ($Art == "Nachtragen")
      {
        $Jahr = date('Y');
        echo $sqlUrlaubUpdate = "UPDATE `Urlaub` SET `RestUrlaub` = `RestUrlaub` - 1 WHERE `Urlaub`.`MitarbeiterID` = $Mitarbeiter AND `Urlaub`.`Jahr` = '$Jahr'";
        if ($conn->query($sqlUrlaubUpdate) === TRUE) 
        {
          echo "Checked nachtragen here";
          //header ("Location: ./adminPortal.php");
          //exit;
        }
        else 
        {
          echo "Error: " . $sqlUrlaubUpdate . "<br>" . $conn->error;
        }

        $sqlAntragPush = "INSERT INTO `Arbeitszeiten` (`UID`, `MitarbeiterID`, `Datum`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`,`Anmerkung`) VALUES (NULL, '$Mitarbeiter', '$Datum', '$BeginnZeit', '$EndeZeit', '$DauerPausen', SUBTIME( TIMEDIFF( '$EndeZeit', '$BeginnZeit'), '$DauerPausen'),'$Anmerkung')";
        //echo $sqlAntragPush;
        if ($conn->query($sqlAntragPush) === TRUE) 
        {
          echo "Checked nachtragen here";
          //header ("Location: ./adminPortal.php");
          //exit;
        }
        else 
        {
          echo "Error: " . $sqlAntragPush . "<br>" . $conn->error;
        }
      
      }
    }
    if ($conn->query($sqlAntragUpdate) === TRUE) 
    {
      // echo "Checked status";
      header ("Location: //192.168.178.40/zeiterfassung/adminPortal.php");
      exit;
    }
    else 
    {
      echo "Error: " . $sqlAntragUpdate . "<br>" . $conn->error;
    }
