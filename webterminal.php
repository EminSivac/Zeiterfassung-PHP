<?php 
    session_start();

    if($_POST['Monat'] == null)
    {
      $Monat = date('m');
      $Jahr = date('Y');
    }
    else
    {
      $Jahr = $_POST['Jahr'];
      $Monat = $_POST['Monat'];
    }
    
    $Mitarbeiter = $_SESSION['Mitarbeiter'];
    $PIN = $_SESSION['PIN'];
    
    $timestamp = time();
    $datum = date("Y-m-d", $timestamp);
    //echo "MitarbeiterID: " . $Mitarbeiter . " Pin: " . $PIN . " Monat: " . $Monat . " Jahr: " . $Jahr;
    
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitarbeiter-Portal</title>
    <style>
    table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    }
    body, h1, button, input, table, td, th{
    font-family: 'Arial', sans-serif;
    }
    </style>
</head>
<body>

    <!--<a href="antragstellen.html"><button>Antrag stellen</button></a><br><br>
    <a href="einstellungen.php"><button>Einstellungen</button></a><br><br>

    <?php /* if (!null == ($Monat && $Jahr)){
    echo "<form method=\"post\" action=\"export.php\">
        <input type=\"hidden\" id=\"MitarbeiterID\" name=\"MitarbeiterID\" value=".$_SESSION['Mitarbeiter']." />
        <input type=\"hidden\" id=\"PIN\" name=\"PIN\" value=".$PIN." />
        <input type=\"hidden\" id=\"Monat\" name=\"Monat\" value=".$Monat." />
        <input type=\"hidden\" id=\"Jahr\" name=\"Jahr\" value=".$Jahr." />
        <button type=\"subbmit\" name=\"export\">PDF Export</button>
    </form>"; }*/?>
    <br>
    <form method="post" action="webterminal.php">
    <input type="hidden" id="MitarbeiterID" name="MitarbeiterID" value="<?php echo $Mitarbeiter ?>" />
    <input type="hidden" id="PIN" name="PIN" value="<?php echo $PIN ?>" />
    Monat:
    <span>
      <select name="Monat">
        <?php for( $m=1; $m<=12; ++$m ) { 
          $month_label = date('m', mktime(0, 0, 0, $m, 1));
        ?>
          <option value="<?php echo $month_label; ?>"><?php echo $month_label; ?></option>
        <?php } ?>
      </select> 
    </span>
    Jahr:
    <span>
      <select name="Jahr">
        <?php 
          $year = date('Y');
          $min = $year - 1;
          $max = $year;
          for( $i=$max; $i>=$min; $i-- ) {
            echo '<option value='.$i.'>'.$i.'</option>';
          }
        ?>
      </select>
    </span>

    <button type="subbmit">Filter anwenden</button>-->
        
    </form>
    <style> 
        .div {
        height: 200px;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
        overflow-y: scroll;

        }
    </style>
    <?php
    
        require "dbconn.php";
    
        $sqlPruefung = "SELECT * FROM Mitarbeiter WHERE MitarbeiterID = $Mitarbeiter AND PIN = $PIN";

        $resultPruefung = $conn->query($sqlPruefung);

        $row = mysqli_fetch_array($resultPruefung);

        if ($resultPruefung->num_rows>0)
        { 

        ?>
        <h1>Zeiterfassungssystem</h1>
        <p>
          <a href="antragstellenFront.php"><button>Antrag stellen</button></a>
          <a href="einstellungen.php"><button>Einstellungen</button></a>
          <a href="webterminal.html"><button onclick="abmelden()">Abmelden</button></a>
          <!-- <a href="http://192.168.1.3/dokuwiki/doku.php?id=fragen_und_antworten"><button>FAQ</button></a> -->
          Über- Minusstunden Konto: <?php echo $row['zeitendifferenz'] ?>
        </p>
    <form method="post" action="webterminal.php">
    <input type="hidden" id="MitarbeiterID" name="MitarbeiterID" value="<?php echo $Mitarbeiter ?>" />
    <input type="hidden" id="PIN" name="PIN" value="<?php echo $PIN ?>" />
    Monat:
    <span>
      <select name="Monat">
        <?php for( $m=1; $m<=12; ++$m ) { 
          $month_label = date('m', mktime(0, 0, 0, $m, 1));
        ?>
          <option <?php if (!null == ($Monat) && $m == $Monat){echo "selected";}?> value="<?php echo $month_label; ?>"><?php echo $month_label; ?></option>
        <?php } ?>
      </select> 
    </span>
    Jahr:
    <span>
      <select name="Jahr">
        <?php 
          $year = date('Y');
          $min = $year - 1;
          $max = $year;
          for( $i=$max; $i>=$min; $i-- ) {
            echo '<option value='.$i.'>'.$i.'</option>';
          }
        ?>
      </select>
    </span>
    <button type="subbmit">Filter anwenden</button>
    </form>
    <br>
    <?php if (!null == ($Monat && $Jahr)){
    echo "<form method=\"post\" action=\"export.php\">
        <input type=\"hidden\" id=\"MitarbeiterID\" name=\"MitarbeiterID\" value=".$_SESSION['Mitarbeiter']." />
        <input type=\"hidden\" id=\"PIN\" name=\"PIN\" value=".$PIN." />
        <input type=\"hidden\" id=\"Monat\" name=\"Monat\" value=".$Monat." />
        <input type=\"hidden\" id=\"Jahr\" name=\"Jahr\" value=".$Jahr." />
        <button type=\"subbmit\" name=\"export\">Export anzeigen</button>
    </form>";}?>
    <?php

        $sqlPausenZusammenrechnen = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`DauerPause`))) AS `Ergebnis` FROM `Pausen` WHERE `MitarbeiterID` = $Mitarbeiter AND `Datum` = '$datum'";
          //echo $sqlPausenZusammenrechnen;
        $resultPausenZusammenrechnen = $conn->query($sqlPausenZusammenrechnen);

        $row = mysqli_fetch_array($resultPausenZusammenrechnen);
        //echo " user Dauer: " . $row['Ergebnis'];
        $DauerPausen = $row['Ergebnis'];

        $row = $resultPruefung->fetch_assoc();
        // echo " user UID: " . $row['UID'];
        $StatusJetzt = $row['Status'];
        $Status = $row['Status'];
            if ($Status == "Abwesend")
            {
                $FarbeStatus = "background-color: lightgrey;color: black;";
            }
            if ($Status == "Arbeit")
            {
                $FarbeStatus = "background-color: green;color: white;";
            }
            if ($Status == "Pause")
            {
                $FarbeStatus = "background-color: orange;color: white;";
            }

            $sqlEndePause1 = "set @LASTUUID = (SELECT p1.UID FROM Pausen p1 where p1.MitarbeiterID=$Mitarbeiter order by p1.UID desc limit 0,1)";

            $sqlTESTPAUSE="SELECT * FROM Pausen WHERE UID = @LASTUUID AND EndePause= '00:00:00'";

      if ($conn->query($sqlEndePause1) === TRUE) 
      {
        //echo "Checked <a href='./buero.html'>Nächser Mitarbeiter</a>";
        //echo "Checked";
      } 
      else 
      {
        echo "Error: " . $sqlEndePause1 . "<br>" . $conn->error;
      }

      $resultTESTPAUSE = $conn->query($sqlTESTPAUSE);
      $row = mysqli_fetch_array($resultTESTPAUSE);
      $anfangPauseBeginn = $row['BeginnPause'];

      echo "<input type=\"hidden\" id=\"anfangPauseBeginn\" value=\"$anfangPauseBeginn\"/>";
    
        echo "<h4><div align='center' style='width:15%;$FarbeStatus'>Status: ". $StatusJetzt ."</div>
        <div>Pausendauer für heute: ".$DauerPausen."</div>";
        
        if ($Status == "Pause")
        {
          echo "here";
            echo "<div id=\"zeitdifferenz\">aktuelle Pausendauer: </div>";
        }

        echo "</h4><hr>";
        
        
        
            if (!null == ($Monat && $Jahr))
            {
                $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $Mitarbeiter AND Datum LIKE '%$Jahr-$Monat%' ORDER BY Datum ASC"; //echo $sqlDaten;
                $sqlMonat = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(DauerArbeitszeit))) AS Ergebnis FROM Arbeitszeiten 
                WHERE MitarbeiterID = $Mitarbeiter AND Datum LIKE '%$Jahr-$Monat%' ORDER BY Datum DESC"; //echo $sqlMonat;
                $resultMonat = $conn->query($sqlMonat);

                while ($row = $resultMonat->fetch_assoc())
                {
                    $TotalArbeitszeit = $row['Ergebnis'];
                    //echo "Total Arbeitszeit: " . $TotalArbeitszeit;
                }

                echo "<h2>Arbeitszeiten</h2>";
                echo "<h3>für den ausgewählten Monat</h3>";
            }
            else
            {
                $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $Mitarbeiter ORDER BY Datum DESC
                LIMIT 0,40";
                echo "<h2>Arbeitszeiten</h2>";
                echo "<h3>Die letzten 40 Einträge</h3>";
            }
            $resultDaten = $conn->query($sqlDaten);
            
            //echo "<h2>Arbeitszeiten</h2>";
            echo    "<table>
                    <tr>
                        <th>Datum</th>
                        <th>Arbeitsbeginn</th>
                        <th>Arbeitsende</th>
                        <th>Gesamte Pausendauer</th>
                        <th>Gesamt Arbeitszeit</th>
                    </tr>";

            while ($row = $resultDaten->fetch_assoc())
            {
                $BeginnZeit = $row["BeginnZeit"];
                $EndeZeit = $row["EndeZeit"];
                $DauerPausen = $row["DauerPausen"];
                $DauerArbeitszeit = $row["DauerArbeitszeit"];
                $DatumOF = date_create($row["Datum"]);
                $Datum = date_format($DatumOF, "d.m.Y");

                    echo "<tr>
                    <td>".$Datum."</td>
                    <td>".$BeginnZeit."</td>
                    <td>".$EndeZeit."</td>
                    <td>".$DauerPausen."</td>
                    <td>".$DauerArbeitszeit."</td>
                    </tr>";
                    
            }

            if (!null == ($Monat && $Jahr))
            {
                echo "<tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total:</th>
                    <td>".$TotalArbeitszeit."</td>
                    </tr></table>";
            }
            else
            {
            echo "</table>";
            }

            $sqlAntraege = "SELECT * FROM Anträge WHERE MitarbeiterID = $Mitarbeiter ORDER BY `UID` DESC";

            $resultAntraege = $conn->query($sqlAntraege);
            
            echo "<hr><h2>Anträge</h2>";

            ?> 
            <div class="div">
            <?php

            while ($row = $resultAntraege->fetch_assoc())
            {
                $DatumOF = date_create($row["Datum"]);
                $Datum = date_format($DatumOF, "d.m.Y");
                $BeginnZeit = $row["BeginnZeit"];
                $EndeZeit = $row["EndeZeit"];
                $DauerPausen = $row["DauerPausen"];
                $Beschreibung = $row["Beschreibung"];
                $Status = $row['Status'];

                echo "
                <table>
                    <tr>
                        <th>Datum</th>
                        <td>".$Datum."</td>
                    </tr>
                    <tr>
                        <th>Arbeitsbeginn</th>
                        <td>".$BeginnZeit."</td>
                    </tr>
                    <tr>
                        <th>Arbeitsende</th>
                        <td>".$EndeZeit."</td>
                    </tr>
                    <tr>
                        <th>Gesamte Pausendauer</th>
                        <td>".$DauerPausen."</td>
                    </tr>
                    <tr>
                        <th>Beschreibung</th>
                        <td>".$Beschreibung."</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>".$Status."</td>
                    </tr>
                </table><br>";

            }
            ?> </div>
      <!-- <footer style="border-top: solid;text-align: center;">
      Programmiert und verwaltet von Emin Sivac
      </footer> -->
      <?php
        }
        else
        {
            echo "Die Eingabe ist falsch  <a href='./webterminal.html'>Versuche es nochmal!</a>";
        }
        $conn->close();

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
    ?>
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

    function abmelden()
    {
      localStorage.removeItem("MitarbeiterID");
      localStorage.removeItem("PIN");
      localStorage.removeItem("Merken");
    }
	</script>
</body>
</html>

   
</body>
</html>