<script>
		// Setze die vorgegebene Zeit im Format "00:00:00"
    var vorgegebeneZeit = document.getElementById("anfangPauseBeginn").value;
		console.log(vorgegebeneZeit);

		// Aktualisiere die Zeitdifferenz alle 1000ms (1 Sekunde)
		setInterval(function() {
			// Berechne die Differenz zwischen der vorgegebenen Zeit und der aktuellen Zeit
			const aktuellesDatum = new Date();
			const vorgegebeneZeitpunkt = new Date(aktuellesDatum.getFullYear(), aktuellesDatum.getMonth(), aktuellesDatum.getDate(), vorgegebeneZeit.split(":")[0], vorgegebeneZeit.split(":")[1], vorgegebeneZeit.split(":")[2]);
			const differenzInSekunden = Math.floor((aktuellesDatum - vorgegebeneZeitpunkt) / 1000);

			// Formatieren der Zeitdifferenz im Format "00:00:00"
			const stunden = Math.floor(differenzInSekunden / 3600);
			const minuten = Math.floor((differenzInSekunden - stunden * 3600) / 60);
			const sekunden = differenzInSekunden - stunden * 3600 - minuten * 60;
			const formatierteZeit = pad(stunden) + ":" + pad(minuten) + ":" + pad(sekunden);

			// Aktualisiere die HTML-Div mit der Zeitdifferenz
			document.getElementById("zeitdifferenz").innerHTML = "aktuelle Pausendauer: " + formatierteZeit;
		}, 1000);

		// Funktion zum Hinzufügen einer führenden Null bei Zahlen < 10
		function pad(zahl) {
			return zahl < 10 ? "0" + zahl : zahl;
		}
    function playSound() {
      var audio = document.getElementById('alertSound');
    audio.play().catch(function(error) {
        console.log("Audio konnte nicht abgespielt werden:", error);
    });
    }
	</script>
  <style>
    body, h1, button, input, table, td, th{
    font-family: 'Arial', sans-serif;
    }
  </style>
  <audio id="alertSound" src="sound.wav" preload="auto"></audio>
<?php 
session_start();

require "dbconn.php";

$Mitarbeiter = $_POST['MitarbeiterID'];
$Zeit = $_POST['Art'];
$case = $_POST['case'];
//echo $Mitarbeiter . " " . $Zeit . "<br>";

$timestamp = time();
$datum = date("Y-m-d", $timestamp);
$uhrzeit = date("H:i:s", $timestamp);
//echo $datum . "<br>" . $uhrzeit;

require "dbconn.php";
    
$sqlEmailReminder = "SELECT * FROM `AdminEinstellungen` WHERE `UID` = 1";
$resultEmailReminder = $conn->query($sqlEmailReminder);

$row = $resultEmailReminder -> fetch_assoc();
$Tag = $row['Wert'];

$sqlAbgabe = "SELECT * FROM `AdminEinstellungen` WHERE `UID` = 2";
$resultAbgabe = $conn->query($sqlAbgabe);

$row = $resultAbgabe -> fetch_assoc();
$Abgabe = $row['Wert'];

// Pfad zu einer Datei, die den Status speichert (ausgeführt oder nicht)
$ausgefuehrtDatei = 'ausgefuehrt.txt';
// Überprüfen, ob der aktuelle Tag der 20. Tag des Monats ist
if (date('j') >= $Tag && date('j') < $Abgabe) {
    // Überprüfen, ob das Skript in diesem Monat bereits ausgeführt wurde
    if (!file_exists($ausgefuehrtDatei) || strpos(file_get_contents($ausgefuehrtDatei), 'Ausgeführt am ' . date('Y-m')) === false) {
        // Der aktuelle Tag ist der 20. Tag des Monats, und das Skript wurde noch nicht ausgeführt
        // Führe hier dein Skript aus

        // $betreff = $Zeit;
        // $inhalt = $timestamp;
        include_once 'send.php';

        // Markiere die Datei als ausgeführt
        file_put_contents($ausgefuehrtDatei, 'Ausgeführt am ' . date('Y-m') . " von $Mitarbeiter"."\n", FILE_APPEND);
        // echo "Das Skript wurde erfolgreich ausgeführt.";
    } else {
        // Das Skript wurde bereits in diesem Monat ausgeführt
        // echo "Das Skript wurde bereits in diesem Monat ausgeführt.";
    }
} else {
    // Der aktuelle Tag ist nicht der 20. Tag des Monats
    // echo "Heute ist nicht der 20. Tag des Monats. Das Skript wird nicht ausgeführt.";
}

$sqlMitarbeiterTestNFC = "SELECT * FROM `NfcKarten` WHERE `NFC` = '$Mitarbeiter'";

$resultMitarbeiterTestNFC = $conn->query($sqlMitarbeiterTestNFC);
if ($resultMitarbeiterTestNFC->num_rows > 0) 
{
  $row = mysqli_fetch_array($resultMitarbeiterTestNFC);
  $Mitarbeiter = $row['MitarbeiterID'];
  // echo "here 31";
} 
else
{
  // echo "here 34 ";
}
//echo $Mitarbeiter;
$sqlMitarbeiterTest = "SELECT * FROM `Mitarbeiter` WHERE `MitarbeiterID` = $Mitarbeiter";
$resultMitarbeiterTest = $conn->query($sqlMitarbeiterTest);
//echo " here 40"; 

function DateFormat($date) {
  // Das Eingabedatum im Format YYYY-MM-DD in ein Array aufteilen
  $dateParts = explode('-', $date);
  
  // Die Teile in umgekehrter Reihenfolge zusammenfügen (Tag, Monat, Jahr)
  $convertedDate = $dateParts[2] . '.' . $dateParts[1] . '.' . $dateParts[0];
  
  return $convertedDate;
}

if ($resultMitarbeiterTest->num_rows > 0)
{
  $sqlMitarbeiterAutoLogOff = "SELECT * FROM `AutoLogOff` WHERE MitarbeiterID = $Mitarbeiter AND Status = 0";
  $resultMitarbeiterAutoLogOff = $conn->query($sqlMitarbeiterAutoLogOff);

  if ($resultMitarbeiterAutoLogOff->num_rows > 0)
  {
    $row = mysqli_fetch_array($resultMitarbeiterAutoLogOff);
    $UID = $row['UID'];
    $UUID = $row['UUID'];
    $sqlGetDate = "SELECT * FROM `Arbeitszeiten` WHERE `UID` = $UID";
    $resultGetDate = $conn->query($sqlGetDate);
    $row = mysqli_fetch_array($resultGetDate);
    $Datum = DateFormat($row['Datum']);
    echo "<h1 style=\"background-color: red;\">!!!Du hast dich vergessen auszuloggen. Bitte gebe die Zeit an, wann du am $Datum gegangen bist!!!</h1>";
    echo "<form method=\"post\" action=\"autoLogOff.php\">
    <p> Arbeitsende: <input type=\"time\" name=\"EndeZeit\" id=\"EndeZeit\" value=\"00:00\" required/></p>
    <input type=\"hidden\" name=\"UID\" id=\"UID\" value=\"$UID\"/>
    <input type=\"hidden\" name=\"UUID\" id=\"UUID\" value=\"$UUID\"/>
    <button type=\"submit\">Bearbeiten</button>
    </form><br><br><br><br><br><br><br><br>
    <script> playSound(); </script>";
  }


  //echo " es ist über 0 ";
  //echo $Zeit;
  //$row = mysqli_fetch_array($resultMitarbeiterTest);
  //echo " user Id: " . $row['MitarbeiterID'];
  if($Zeit == "Status")
  {
    $sqlStatusAbfrage = "SELECT Status FROM Mitarbeiter WHERE MitarbeiterID = $Mitarbeiter";
    $resultStatusAbfrage = $conn->query($sqlStatusAbfrage);
    $row = mysqli_fetch_array($resultStatusAbfrage);
    $StatusAbfrage = $row['Status']; 

    if ($StatusAbfrage == "Abwesend")
    {
        $FarbeStatus = "background-color: lightgrey;color: black;";
    }
    if ($StatusAbfrage == "Arbeit")
    {
        $FarbeStatus = "background-color: green;color: white;";
    }
    if ($StatusAbfrage == "Pause")
    {
        $FarbeStatus = "background-color: orange;color: white;";
    }
    if ($StatusAbfrage == "Dienstgang")
    {
        $FarbeStatus = "background-color: chartreuse; color: black;";
    }

    echo " Dein Status lautet: ";
    if ($StatusAbfrage == "Pause")
      {
            echo "<div id=\"zeitdifferenz\">aktuelle Pausendauer: </div>";
      }
    echo "<p align='center' style='width:15%;$FarbeStatus'>". $StatusAbfrage ."</p> 
    <br>";
    if($case == 1)
    {
        echo "<a href='/shortcutTool.php'>zurück</a>";
    }
    else
    {
        echo "<a href='./buero.html'>Nächster Mitarbeiter</a>";
    }

    $sqlEndePause1 = "set @LASTUUID = (SELECT p1.UID FROM Pausen p1 where p1.MitarbeiterID=$Mitarbeiter order by p1.UID desc limit 0,1)";

    $sqlTESTPAUSE="SELECT * FROM Pausen WHERE UID = @LASTUUID AND EndePause= '00:00:00'";

      if ($conn->query($sqlEndePause1) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a>";
        //echo "Checked";
      } 
      else 
      {
        echo $error =  "Error: " . $sqlEndePause1 . "<br>" . $conn->error;
        include 'send_dev.php';
      }

      $resultTESTPAUSE = $conn->query($sqlTESTPAUSE);
      $row = mysqli_fetch_array($resultTESTPAUSE);
      $anfangPauseBeginn = $row['BeginnPause'];

      

      echo "<input type=\"hidden\" id=\"anfangPauseBeginn\" value=\"$anfangPauseBeginn\"/>";



  }
  if($Zeit == "BeginnZeit")
  {
    //echo " here 54"; 
    $sqlVorhandenTest = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' AND `EndeZeit` = '00:00:00'";

    $resultVorhandenTest = $conn->query($sqlVorhandenTest);

    if ($resultVorhandenTest->num_rows == 1)
    {
      echo " Wurde schon eingetragen! <a href='/buero.html'>Versuche es nochmal!</a>";
      
    } 
    else
    {
      $sqlKommtInsert = "INSERT INTO `Arbeitszeiten` (`UID`, `MitarbeiterID`, `Datum`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`) VALUES (NULL, '$Mitarbeiter', '$datum', '$uhrzeit', '00:00:00', '00:00:00', '00:00:00')";
      $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Arbeit' WHERE `Mitarbeiter`.`MitarbeiterID` = '$Mitarbeiter'";

      if ($conn->query($sqlStatusUpdate) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }
      if ($conn->query($sqlKommtInsert) === TRUE) 
      {
        if($case == 1)
            {
                echo "Checked <a href='/shortcutTool.php'>zurück</a><br>Beginn Zeit";
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Beginn Zeit";

            }
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }
    }
  }
  if($Zeit == "EndeZeit")
  { 
    //echo " here 91"; 
    $sqlUIDVomTag = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' ORDER BY `UID` DESC";

    $resultUIDVomTag = $conn->query($sqlUIDVomTag);

    $row = mysqli_fetch_array($resultUIDVomTag);
    //echo " user UID: " . $row['UID'];
    $UID = $row['UID'];
    $BeginnZeit = $row['BeginnZeit'];
    
    $sqlPausenZusammenrechnen = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`DauerPause`))) AS `Ergebnis` FROM `Pausen` WHERE `BeginnPause` > '$BeginnZeit'  AND `EndePause` < '$uhrzeit' AND `MitarbeiterID` = $Mitarbeiter AND `Datum` = '$datum'";

    $resultPausenZusammenrechnen = $conn->query($sqlPausenZusammenrechnen);

    $row = mysqli_fetch_array($resultPausenZusammenrechnen);
    //echo " user Dauer: " . $row['Ergebnis'];
    $DauerPausen = $row['Ergebnis'];
    if ($DauerPausen == null)
    {
      $DauerPausen = "00:00:00";
    }

    $sqlStundenAnzahlAbfrage = "SELECT TIMEDIFF('$uhrzeit', '$BeginnZeit') as Ergebnis";

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

    $sqlGehtUpdate = "UPDATE `Arbeitszeiten` SET `EndeZeit` = '$uhrzeit', `DauerPausen` = '$DauerPausen', `DauerArbeitszeit` = SUBTIME( TIMEDIFF('$uhrzeit', '$BeginnZeit') , '$DauerPausen') WHERE `Arbeitszeiten`.`UID` = $UID";
    //echo $sqlGehtUpdate;

    if ($conn->query($sqlGehtUpdate) === TRUE) 
    {
      if($case == 1)
            {
                echo "Checked <a href='/shortcutTool.php'>zurück</a><br>Ende Zeit";
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Ende Zeit";

            }
    } 
    else 
    {
      echo $error = "Error: " . $sqlGehtUpdate . "<br>" . $conn->error;
      include 'send_dev.php';
    }

    $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Abwesend' WHERE `Mitarbeiter`.`MitarbeiterID` = $Mitarbeiter";

      if ($conn->query($sqlStatusUpdate) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
        
      } 
      else 
      {
        echo $error = "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }

      $sqlSaldoTag = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' ORDER BY `UID` DESC";

      $resultSaldoTag = $conn->query($sqlSaldoTag);

      $row = mysqli_fetch_array($resultSaldoTag);
      $date = $row['Datum'];
      $BeginnZeit = $row['BeginnZeit'];
      $EndeZeit = $row['EndeZeit'];
      $DauerPausen = $row['DauerPausen'];
      $DauerZeit = $row['DauerArbeitszeit'];

      echo "<p>Du hast heute von: ".$BeginnZeit." bis ".$EndeZeit. " und du hast ".$DauerPausen." Pause gemacht. Insgesamt gearbeitet hast du: ".$DauerZeit."</p>";
  }
  if($Zeit == "BeginnPause")
  {
    $sqlVorhandenTest = "SELECT * FROM `Pausen` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' AND `EndePause` = '00:00:00'";

    $resultVorhandenTest = $conn->query($sqlVorhandenTest);

    if ($resultVorhandenTest->num_rows > 0)
    {
      echo " Wurde schon eingetragen! <a href='/buero.html'>Versuche es nochmal!</a>";
    } 
    else
    {
      //echo "here 136";
      $sqlBeginnPause = "INSERT INTO `Pausen` (`UID`, `MitarbeiterID`, `BeginnPause`, `EndePause`, `DauerPause`, `Datum`) VALUES (NULL, $Mitarbeiter, '$uhrzeit', '00:00:00', '00:00:00', '$datum')";

      $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Pause' WHERE `Mitarbeiter`.`MitarbeiterID` = $Mitarbeiter";

        if ($conn->query($sqlStatusUpdate) === TRUE) 
        {
          //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
          
        } 
        else 
        {
          echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
          include 'send_dev.php';
        }

      if ($conn->query($sqlBeginnPause) === TRUE) 
        {
          if($case == 1)
            {
                echo "Checked <a href='/shortcutTool.php'>zurück</a><br>Beginn Pause";
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Beginn Pause";

            }
        
        } 
        else 
        {
        echo $error =  "Error: " . $sqlBeginnPause . "<br>" . $conn->error;
        include 'send_dev.php';
        }
    }
  }
  if($Zeit == "EndePause")
  {
    //echo " here 163"; 
    $sqlEndePause1 = "set @LASTUUID = (SELECT p1.UID FROM Pausen p1 where p1.MitarbeiterID=$Mitarbeiter order by p1.UID desc limit 0,1)";

    $sqlTESTPAUSE="SELECT * FROM Pausen WHERE UID = @LASTUUID AND EndePause= '00:00:00'";

    $sqlEndePause2 = "update Pausen p set p.EndePause='$uhrzeit', p.DauerPause = TIMEDIFF(p.EndePause,p.BeginnPause) where p.MitarbeiterID=$Mitarbeiter and p.UID=@LASTUUID";
    $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Arbeit' WHERE `Mitarbeiter`.`MitarbeiterID` = $Mitarbeiter";

      if ($conn->query($sqlStatusUpdate) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }
      if ($conn->query($sqlEndePause1) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a>";
        //echo "Checked";
      } 
      else 
      {
      echo $error =  "Error: " . $sqlEndePause1 . "<br>" . $conn->error;
      include 'send_dev.php';
      }

      $resultTESTPAUSE = $conn->query($sqlTESTPAUSE);

      if ($resultTESTPAUSE->num_rows > 0)
      {
        if ($conn->query($sqlEndePause2) === TRUE) 
        {

          $sqlPausenDauer = "SELECT * FROM Pausen WHERE UID = @LASTUUID";

          $resultPausenDauer = $conn->query($sqlPausenDauer);

          $row = mysqli_fetch_array($resultPausenDauer);
          $PausenDauer = $row['DauerPause'];
          if($case == 1)
            {
              echo "Checked <a href='/shortcutTool.php'>Nächster Mitarbeiter</a><br><br>Ende Pause<br><br> Deine Pause ging: " . $PausenDauer;
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br><br>Ende Pause<br><br> Deine Pause ging: " . $PausenDauer;

            }
        } 
        else 
        {
        echo $error =  "Error: " . $sqlEndePause2 . "<br>" . $conn->error;
        include 'send_dev.php';
        }
      }
      else
      {
        echo "Keine Pause die beendet werden kann! <a href='/buero.html'>Versuche es nochmal!</a>";
      }
  }
  if($Zeit == "Rauchen")
  {
    $sqlVorhandenTest = "SELECT * FROM `Pausen` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' AND `EndePause` = '00:00:00'";

    $resultVorhandenTest = $conn->query($sqlVorhandenTest);

    if ($resultVorhandenTest->num_rows > 0)
    {
      echo " Wurde schon eingetragen! <a href='/buero.html'>Versuche es nochmal!</a>";
    } 
    else
    {
      $uhrzeitZukunft = time() + 8*60;
      $uhrzeitZukunft = date("H:i:s", $uhrzeitZukunft);


      //echo "here 136";
      $sqlBeginnPause = "INSERT INTO `Pausen` (`UID`, `MitarbeiterID`, `BeginnPause`, `EndePause`, `DauerPause`, `Datum`) VALUES (NULL, $Mitarbeiter, '$uhrzeit', '$uhrzeitZukunft', TIMEDIFF('$uhrzeitZukunft','$uhrzeit'), '$datum')";

      //$sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Pause' WHERE `Mitarbeiter`.`MitarbeiterID` = $Mitarbeiter";

        // if ($conn->query($sqlStatusUpdate) === TRUE) 
        // {
          // echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
          
        // } 
        // else 
        // {
          // echo "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        // }

      if ($conn->query($sqlBeginnPause) === TRUE) 
        {
          if($case == 1)
            {
                echo "Checked <a href='/shortcutTool.php'>zurück</a><br>Pause Rauchen";
            }
            else
            {
              echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Pause Rauchen";

            }
        
        } 
        else 
        {
        echo $error =  "Error: " . $sqlBeginnPause . "<br>" . $conn->error;
        include 'send_dev.php';
        }
    }
  }
  if($Zeit == "DienstgangBeginn")
  {
    $sqlVorhandenTest = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' AND `EndeZeit` = '00:00:00'";

    $resultVorhandenTest = $conn->query($sqlVorhandenTest);

    if ($resultVorhandenTest->num_rows == 1)
    {
      echo " Zuerst muss Geht gewählt werden! <a href='/buero.html'>Versuche es nochmal!</a>";
    } 
    else
    {
      $sqlKommtInsert = "INSERT INTO `Arbeitszeiten` (`UID`, `MitarbeiterID`, `Datum`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`,`Anmerkung`) VALUES (NULL, '$Mitarbeiter', '$datum', '$uhrzeit', '00:00:00', '00:00:00', '00:00:00','Dienstgang')";
      $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Dienstgang' WHERE `Mitarbeiter`.`MitarbeiterID` = '$Mitarbeiter'";

      if ($conn->query($sqlStatusUpdate) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }
      if ($conn->query($sqlKommtInsert) === TRUE) 
      {
        echo "Checked <a href='./buero.html'>Nächster Mitarbeiter</a><br>Beginn Dienstgang";
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlKommtInsert . "<br>" . $conn->error;
        include 'send_dev.php';
      }
    }
  }
  if($Zeit == "DienstgangEnde")
  {
    //echo " here 91"; 
    $sqlUIDVomTag = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' ORDER BY `UID` DESC";

    $resultUIDVomTag = $conn->query($sqlUIDVomTag);

    $row = mysqli_fetch_array($resultUIDVomTag);
    //echo " user UID: " . $row['UID'];
    $UID = $row['UID'];
    $BeginnZeit = $row['BeginnZeit'];
    
    // $sqlPausenZusammenrechnen = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`DauerPause`))) AS `Ergebnis` FROM `Pausen` WHERE `MitarbeiterID` = $Mitarbeiter AND `Datum` = '$datum'";

    // $resultPausenZusammenrechnen = $conn->query($sqlPausenZusammenrechnen);

    // $row = mysqli_fetch_array($resultPausenZusammenrechnen);
    //echo " user Dauer: " . $row['Ergebnis'];
    // $DauerPausen = $row['Ergebnis'];
    // if ($DauerPausen == null)
    // {
      // $DauerPausen = "00:00:00";
    // }

    $sqlStundenAnzahlAbfrage = "SELECT TIMEDIFF('$uhrzeit', '$BeginnZeit') as Ergebnis";

    $resultStundenAnzahlAbfrage = $conn->query($sqlStundenAnzahlAbfrage);

    $row = mysqli_fetch_array($resultStundenAnzahlAbfrage);
    $StundenAnzahl  = $row['Ergebnis'];
    //echo $StundenAnzahl;

    // if($StundenAnzahl > "06:00:00")
    // {
      // if ($DauerPausen < "00:30:00")
      // {
        // $DauerPausen = "00:30:00";
      // }
    // }
    // if ($StundenAnzahl > "09:00:00")
    // {
    //   if ($DauerPausen < "00:45:00")
    //   {
    //     $DauerPausen = "00:45:00";
    //   }
    // }

    $sqlGehtUpdate = "UPDATE `Arbeitszeiten` SET `EndeZeit` = '$uhrzeit', `DauerArbeitszeit` = TIMEDIFF('$uhrzeit', '$BeginnZeit') WHERE `Arbeitszeiten`.`UID` = $UID";
    //echo $sqlGehtUpdate;

    if ($conn->query($sqlGehtUpdate) === TRUE) 
    {
      echo "Checked <a href='/buero.html'>Nächster Mitarbeiter</a><br>Ende Dienstgang<br>";
      //echo $sqlGehtUpdate;
    } 
    else 
    {
      echo $error =  "Error: " . $sqlGehtUpdate . "<br>" . $conn->error;
      include 'send_dev.php';
    }

    $sqlStatusUpdate = "UPDATE `Mitarbeiter` SET `Status` = 'Abwesend' WHERE `Mitarbeiter`.`MitarbeiterID` = $Mitarbeiter";

      if ($conn->query($sqlStatusUpdate) === TRUE) 
      {
        //echo "Checked <a href='/buero.html'>Nächser Mitarbeiter</a><br>Beginn Zeit";
        
      } 
      else 
      {
        echo $error =  "Error: " . $sqlStatusUpdate . "<br>" . $conn->error;
        include 'send_dev.php';
      }

      /*$sqlSaldoTag = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID`= $Mitarbeiter AND `Datum` = '$datum' ORDER BY `UID` DESC";

      $resultSaldoTag = $conn->query($sqlSaldoTag);

      $row = mysqli_fetch_array($resultSaldoTag);
      $date = $row['Datum'];
      $BeginnZeit = $row['BeginnZeit'];
      $EndeZeit = $row['EndeZeit'];
      $DauerPausen = $row['DauerPausen'];
      $DauerZeit = $row['DauerArbeitszeit'];

      echo "Du warst heute von: ".$BeginnZeit." bis ".$EndeZeit. "da und du hast ".$DauerPausen." Pause gemacht. Insgesamt gearbeitet hast du: ".$DauerZeit;*/
  }
}
else
{
  //echo " here 198"; 
  echo "<a href='/buero.html'>Versuche es nochmal!</a>";
}

  /*if ($conn->query($sqltest) === TRUE) {
    echo "Checked";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }*/

  $conn->close();

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //echo "Connected successfully";


?>
