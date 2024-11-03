<title>Admin-Portal</title>

<script>
function abmelden()
    {
        localStorage.removeItem("Admin");
        localStorage.removeItem("Password");
        localStorage.removeItem("MerkenA");
    }

    // Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
	</script>
<?php
    session_start();

    require "dbconn.php";

    $Mitarbeiter = $_SESSION["MitarbeiterID"];
    $Monat = $_POST['Monat'];
    $Jahr = $_POST['Jahr'];
    $PausenDatum = $_POST['Datum'];
    $Admin = $_SESSION['Admin'];
    $Password = $_SESSION['Password'];

    $sqlCheckAdmin = "SELECT * FROM `Admin` WHERE `Anmeldename` = '$Admin' AND `Passwort` = '$Password'";
    $resultsqlCheckAdmin = $conn->query($sqlCheckAdmin);

    if ($resultsqlCheckAdmin->num_rows > 0) 
    {        
        echo "<h1>Zeiterfassungssystem</h1>";

        $sqlMitarbeiterDaten = "SELECT * FROM Mitarbeiter ORDER BY `Mitarbeiter`.`Status` DESC";
        $resultMitarbeiterDaten = $conn->query($sqlMitarbeiterDaten);
        
        ?>
        <div align="center" valign="top" style="border-style: solid;
        border-color: black;border-width: 1px; width:25%">
        Monatsstunden aller Mitarbeiter anzeigen<br><br>
          

        <form method="post" action="adminExport.php" >
    <!-- <input type="hidden" id="MitarbeiterID" name="MitarbeiterID" value=" echo $_SESSION['Admin'] ?>" /> -->
    <!-- <input type="hidden" id="PIN" name="PIN" value=" $_SESSION['Password'] ?>" /> -->
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
          $min = $year - 3;
          $max = $year;
          for( $i=$max; $i>=$min; $i-- ) {
            echo '<option value='.$i.'>'.$i.'</option>';
          }
        ?>
      </select>
    </span>

    <button type="subbmit">Filter anwenden</button><br>
        
    </form></div>
    <p> 
    <a href="kontostand.php"><button>Zeiten Guthabenkonten</button></a>
    </p>
    <p> 
    <a href="adminverwaltung.html"><button>Einstellungen</button></a>
    </p>
    <!-- <p> 
    <a href="shortcutTool.php"><button>Shortcut-Tool</button></a>
    </p> -->
    <p>
    <a href="admin.html"><button onclick="abmelden()">Abmelden</button></a>
    </p>
    <!-- Mitarbeiter ******************************************************************************************************************************************-->
        <table style="width:100%;">
            <tr><td align="center" valign="top" style="width:15%;">
            <h3>Mitarbeiter</h3>
        <?php

        while ($row = $resultMitarbeiterDaten->fetch_assoc())
        {

            $Vorname = $row['Vorname'];
            $Nachname = $row['Nachname'];
            $MitarbeiterID = $row['MitarbeiterID'];
            $Status = $row['Status'];
            if ($Status == "Abwesend")
            {
                $FarbeStatus = "background-color: grey;color: white;";
            }
            if ($Status == "Arbeit")
            {
                $FarbeStatus = "background-color: green;color: white;";
            }
            if ($Status == "Pause")
            {
                $FarbeStatus = "background-color: orange;color: white;";
            }
            if ($Status == "Dienstgang")
            {
                $FarbeStatus = "background-color: chartreuse; color: black;";
            }
            if ($MitarbeiterID == $Mitarbeiter)
            {
                $FarbeStatus = "background-color: red; color: white;";
            }

            $angestellt = $row['Angestellt'];

            if($angestellt == 1)
            {
                echo "<form method='post' action='adminZeiten.php' >
                <input type='hidden' id='MitarbeiterID' name='MitarbeiterID' value=".$MitarbeiterID.">
                <input type='hidden' id='Admin' name='Admin' value=".$_SESSION['Admin']."/>
                <input type='hidden' id='Password' name='Password' value=".$_SESSION['Password']."/>
                <button type='subbmit' style=\"width:100%;$FarbeStatus border:none;\">".$Vorname. " ".$Nachname."<div> Status: ".$Status."</div></button>
                </form>";
            }           
        }

        ?>  </td> 
<!-- Arbeitszeiten für den ausgewählen Mitarbeiter *****************************************************************************************************************-->
        <td align="center" valign="top" style="width:70%;">
        <h3 style="color: black;">Arbeitszeiten</h3>
        <b style="color: black;">von in rot markiert</b>
        <div class="div">
        <table style="width:75%;margin-left:auto;margin-right:auto;border: 1px solid black;border-collapse: collapse;">
                    <tr style="border: 1px solid black;
                            border-collapse: collapse;">
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Datum</th>
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Wochentag</th>
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Arbeitsbeginn</th>
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Arbeitsende</th>
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Gesamte Pausendauer</th>
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Gesamt Arbeitszeit</th>
                        <th></th>
                    </tr>
                    <?php
                    $sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $Mitarbeiter ORDER BY `Arbeitszeiten`.`Datum` DESC";
                    $resultDaten = $conn->query($sqlDaten);
                    $resultDaten_2 = $conn->query($sqlDaten);
                    
                    if ($resultDaten->num_rows >0)
                    {
                        $row = $resultDaten_2->fetch_assoc();
                        $DatumOF = date_create($row["Datum"]);

                        $Monat_Jahr = date_format($DatumOF, "F.Y");
                        $Monat_Jahr = translateMonthToGerman($Monat_Jahr);

                        echo "<tr style=\"border: 1px solid black;
                        border-collapse: collapse; background-color: #04AA6D;\">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>$Monat_Jahr</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>";

                        // $date1 = NULL;
                        while ($row = $resultDaten->fetch_assoc())
                        {
                            $date1 = $PhpDatum;
                            $UID = $row["UID"];
                            $BeginnZeit = $row["BeginnZeit"];
                            $EndeZeit = $row["EndeZeit"];
                            $DauerPausen = $row["DauerPausen"];
                            $DauerArbeitszeit = $row["DauerArbeitszeit"];
                            $PhpDatum = $row["Datum"];
                            $DatumOF = date_create($row["Datum"]);
                            $Datum = date_format($DatumOF, "d.m.Y");
                            $monat = date_format($DatumOF, "m");
                            $jahr = date_format($DatumOF, "Y");

                            $wochentag = EngToDeu(date_format($DatumOF, "l"));

                            $newdate = mktime(0, 0, 0, $monat, 1, $jahr);
                            $SummeDatum = date("Y-m", strtotime("+1 months", $newdate));

                            $sqlMonat = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(DauerArbeitszeit))) AS Ergebnis FROM Arbeitszeiten 
                            WHERE MitarbeiterID = $Mitarbeiter AND Datum LIKE '%$SummeDatum%' ORDER BY Datum"; //echo $sqlMonat;
                            $resultMonat = $conn->query($sqlMonat);
            
                            while ($row = $resultMonat->fetch_assoc())
                            {
                                $Summe = $row['Ergebnis'];
                            }
                            if ($DauerArbeitszeit <= "00:00:00")
                            {
                                $falsche_zeit = "background-color: #ffc526;";
                                $auto_log_off = "<form method='post' action='auto_log_off_insert.php'>
                                <input type='hidden' name='UID' value='$UID'/>
                                <button type='submit'>Korrigieren lassen</button></form>";
                            }
                            else
                            {
                                $falsche_zeit = "";
                                $auto_log_off = "";
                            }

                            $date2 = $PhpDatum;

                            $Monat_Jahr = date_format($DatumOF, "F.Y");
                            $Monat_Jahr = translateMonthToGerman($Monat_Jahr);


                            if (hasMonthChanged($date1, $date2)) {
                                echo "<tr style=\"border: 1px solid black;
                                border-collapse: collapse;\">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td> 
                                <td>Summe:</td>
                                <td>$Summe</td>
                                <td></td>
                                <td></td>
                                </tr>
                                <tr style=\"border: 1px solid black;
                                border-collapse: collapse; background-color: #04AA6D;\">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>$Monat_Jahr</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>
                                ";
                                echo "<tr style=\"border: 1px solid black;
                            border-collapse: collapse;$falsche_zeit\">
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$Datum."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$wochentag."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$BeginnZeit."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$EndeZeit."</td>
                            <form method='post'>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerPausen.
                            "<input type='hidden' name='Datum' value='$PhpDatum'/>
                            <input type='submit' value='Details' style=\"float:right;\" />
                            </td>
                            </form>   
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerArbeitszeit."</td>
                            <td>
                            <form method='post' action='loeschen.php'>
                            <input type='hidden' name='UID' value='$UID'/>
                            <button type='submit'>Löschen</button></form>
                            $auto_log_off
                            </td>
                            </tr>";
                            }
                            else 
                            {
                            
                                echo "<tr style=\"border: 1px solid black;
                            border-collapse: collapse;$falsche_zeit\">
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$Datum."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$wochentag."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$BeginnZeit."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$EndeZeit."</td>
                            <form method='post'>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerPausen.
                            "<input type='hidden' name='Datum' value='$PhpDatum'/>
                            <input type='submit' value='Details' style=\"float:right;\" />
                            </td>
                            </form>   
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerArbeitszeit."</td>
                            <td>
                            <form method='post' action='loeschen.php'>
                            <input type='hidden' name='UID' value='$UID'/>
                            <button type='submit'>Löschen</button></form>
                            $auto_log_off
                            </tr>";
                            }
                        }

                        // echo "</table>";
                    }
                    ?>
        </table></div></td>
                    <style> 
                        .div {
                        height: 1000px;
                        width: 100%;
                        margin: 0 auto;
                        overflow: hidden;
                        overflow-y: scroll;
                        }

                        #div {
                        height: 250px;
                        width: 100%;
                        margin: 0 auto;
                        overflow: hidden;
                        color: chartreuse;
                        }

                        #div:hover {
                        overflow-y: scroll;
                        }
                    </style>
<!-- Anträge**************************************************************************************************************************************************** -->
            
            <td align="center" valign="top" style="width:15%;">
            <h3>Anträge</h3>
            <div class="div">
            <?php
            $sqlAntraege = "SELECT * FROM Anträge WHERE `Status` = 'Ausstehend' ORDER BY `UID` DESC";

            $resultAntraege = $conn->query($sqlAntraege);

while ($row = $resultAntraege->fetch_assoc())
{
    $DatumOF = date_create($row["Datum"]);
    $Datum = date_format($DatumOF, "d.m.Y");
    $Vorname = $row["Vorname"];
    $Nachname = $row["Nachname"];
    $BeginnZeit = $row["BeginnZeit"];
    $EndeZeit = $row["EndeZeit"];
    $DauerPausen = $row["DauerPausen"];
    $Beschreibung = $row["Beschreibung"];
    $ArbeitszeitUID = $row["ArbeitszeitUID"];
    $Gestellt = $row["Gestellt am"];
    $Mitarbeiter = $row["MitarbeiterID"];
    $Status = $row['Status'];
    $UID = $row['UID'];
    $Art = $row['Art'];
    $gesamtarbeitsdauer = calculateTimeDifference($BeginnZeit, $EndeZeit, $DauerPausen);

    echo "<form method='post' action='antreage.php'>
    <input type='hidden' id='MitarbeiterID' name='MitarbeiterID' value=".$Mitarbeiter.">
    <input type='hidden' id='Admin' name='Admin' value=".$_SESSION['Admin'].">
    <input type='hidden' id='Password' name='Password' value=".$_SESSION['Password'].">
    <input type='hidden' id='UID' name='UID' value='$UID'>
    <input type='hidden' id='Datum' name='Datum' value=".$Datum.">
    <input type='hidden' id='BeginnZeit' name='BeginnZeit' value=".$BeginnZeit.">
    <input type='hidden' id='EndeZeit' name='EndeZeit' value=".$EndeZeit.">
    <input type='hidden' id='DauerPausen' name='DauerPausen' value=".$DauerPausen.">
    <input type='hidden' id='ArbeitszeitUID' name='ArbeitszeitUID' value=".$ArbeitszeitUID.">
    <input type='hidden' id='Art' name='Art' value=".$Art.">
    <input type='hidden' id='Anmerkung' name='Anmerkung' value='$Beschreibung'>
    

    <p><table align='center' valign='top' style=\"border: 1px solid black;border-collapse: collapse;width:100%;\">
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;width:40%;\">Vorname</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Vorname."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Nachname</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Nachname."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Datum</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Datum."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Arbeitsbeginn</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$BeginnZeit."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Arbeitsende</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$EndeZeit."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Gesamte Pausendauer</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$DauerPausen."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Gesamt Arbeitszeit</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">". $gesamtarbeitsdauer ."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Beschreibung</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Beschreibung."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Art</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Art."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Gestellt am</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Gestellt."</td>
        </tr>
        <tr style=\"border: 1px solid black;
        border-collapse: collapse;\">
            <th style=\"border: 1px solid black;
            border-collapse: collapse;width:40%;\">Status</th>
            <td style=\"border: 1px solid black;
            border-collapse: collapse;\">".$Status."</td>
        </tr>
        </table>
        <button type='submit' value='Akzeptiert' name='Status' style=\"width:49%; background-color: green;color: white;border:none;\">Akzeptieren</button>
        <button type='submit' value='Abgelehnt' name='Status' style=\"width:49%;background-color: red;color: white;border:none;\">Ablehnen</button></form></p>";
}?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:15%;"></td>
                <td style="width:70%;"> <?php
            if ($PausenDatum != null)
            {
                
                $Mitarbeiter = $_SESSION["MitarbeiterID"];
                $sqlGetAllePausen = "SELECT * FROM Pausen WHERE MitarbeiterID = $Mitarbeiter AND Datum = '$PausenDatum'";
                $resultGetAllePausen = $conn->query($sqlGetAllePausen);

                echo "<div id=\"id01\" class=\"modal\">
                <button type=\"button\" onclick=\"document.getElementById('id01').style.display='none'\" class=\"close\">X</button>
                <table align=\"center\" class=\"modal-content\"style=\"border: 1px solid black;
                border-collapse: collapse; width:75%;\"><tr style=\"border: 1px solid black;
                border-collapse: collapse;\">
                <td style=\"border: 1px solid black;
                border-collapse: collapse;\">Pausendatum</td>
                <td style=\"border: 1px solid black;
                border-collapse: collapse;\">Pausenbeginn</td>
                <td style=\"border: 1px solid black;
                border-collapse: collapse;\">Pausenende</td>
                <td style=\"border: 1px solid black;
                border-collapse: collapse;\">Dauer der Pause</td>
                </tr>";

                $PausenDatum = date_create($PausenDatum);

                $PausenDatum = date_format($PausenDatum, "d.m.Y");

                while ($row = $resultGetAllePausen->fetch_assoc())
                {
                    $BeginnPause = $row["BeginnPause"];
                    $EndePause = $row["EndePause"];
                    $DauerPause = $row["DauerPause"];
            
                    echo "<tr style=\"border: 1px solid black;
                    border-collapse: collapse;\">
                    <td style=\"border: 1px solid black;
                    border-collapse: collapse;\">".$PausenDatum."</td>
                    <td style=\"border: 1px solid black;
                    border-collapse: collapse;\">".$BeginnPause."</td>
                    <td style=\"border: 1px solid black;
                    border-collapse: collapse;\">".$EndePause."</td>
                    <td style=\"border: 1px solid black;
                    border-collapse: collapse;\">".$DauerPause."</td>
                    </tr>";
                    
                }
                echo "</table></div>";
                echo "<script>
                var pop = document.getElementById('id01');
                pop.style.display='block';
                </script>";
            }
            ?>
            </td>
            <td align="center" valign="top" style="width:15%;">
            <h3 style="color: black;">Archive</h3>    
            <div id="div">
            <?php
            $Mitarbeiter = $_SESSION["MitarbeiterID"];
            if ($Mitarbeiter != null)
            {
                $sqlGetAlleAntrgaege = "SELECT * FROM Anträge WHERE MitarbeiterID = $Mitarbeiter ORDER BY `Datum` DESC";
                $resultGetAlleAntrgaege = $conn->query($sqlGetAlleAntrgaege);

                while ($row = $resultGetAlleAntrgaege->fetch_assoc())
                {
                    $DatumOF = date_create($row["Datum"]);
                    $Datum = date_format($DatumOF, "d.m.Y");
                    $BeginnZeit = $row["BeginnZeit"];
                    $EndeZeit = $row["EndeZeit"];
                    $DauerPausen = $row["DauerPausen"];
                    $Beschreibung = $row["Beschreibung"];
                    $Status = $row['Status'];

                    echo "
                    <table class=\"archive\">
                        <tr class=\"archive\">
                            <th class=\"archive\">Datum</th>
                            <td class=\"archive\">".$Datum."</td>
                        </tr>
                        <tr class=\"archive\">
                            <th class=\"archive\">Arbeitsbeginn</th>
                            <td class=\"archive\">".$BeginnZeit."</td>
                        </tr>
                        <tr class=\"archive\">
                            <th class=\"archive\">Arbeitsende</th>
                            <td class=\"archive\">".$EndeZeit."</td>
                        </tr>
                        <tr class=\"archive\">
                            <th class=\"archive\">Gesamte Pausendauer</th>
                            <td class=\"archive\">".$DauerPausen."</td>
                        </tr>
                        <tr class=\"archive\">
                            <th class=\"archive\">Beschreibung</th>
                            <td class=\"archive\">".$Beschreibung."</td>
                        </tr>
                        <tr class=\"archive\">
                            <th class=\"archive\">Status</th>
                            <td class=\"archive\">".$Status."</td>
                        </tr>
                    </table><br>";
                }
            }
            ?></div></td>
        </tr>
        </table>
        <style>
    .archive {
    border: 1px solid black;
    border-collapse: collapse;
    }
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
    }
    .modal-content {
    background-color: #fefefe;
    margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
    border: 1px solid #888;
    width: 100%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button (x) */
    .close {
    position: absolute;
    right: 25px;
    top: 0;
    color: #000;
    font-size: 35px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: red;
    cursor: pointer;
    }
    body, h1, button, input, table, td, th{
    font-family: 'Arial', sans-serif;
    }

    </style>

        <!-- <footer style="border-top: solid;width: 100%;text-align: center;">
        Programmiert und verwaltet von Emin Sivac
    </footer> -->
        
<?php     
}
    else
    {
        echo "<a href='http:'>Versuche es nochmal</a>";
    }
    $conn->close();

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  function hasMonthChanged($date1, $date2) {
    // Die Datumswerte in PHP-Datetime-Objekte umwandeln
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);

    // Extrahieren des Monats und des Jahres aus den Datumswerten
    $month1 = $datetime1->format('m');
    $year1 = $datetime1->format('Y');
    $month2 = $datetime2->format('m');
    $year2 = $datetime2->format('Y');

    // Überprüfen, ob der Monat oder das Jahr unterschiedlich sind
    if ($month1 !== $month2 || $year1 !== $year2) {
        return true;
    }
    return false;
}

function translateMonthToGerman($dateString) {
    // Array zur Zuordnung der englischen zu den deutschen Monatsnamen
    $months = [
        'January' => 'Januar',
        'February' => 'Februar',
        'March' => 'März',
        'April' => 'April',
        'May' => 'Mai',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'August',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Dezember'
    ];

    // Den Monatsnamen und das Jahr aus der Eingabe extrahieren
    list($englishMonth, $year) = explode('.', $dateString);

    // Den deutschen Monatsnamen ermitteln
    if (isset($months[$englishMonth])) {
        $germanMonth = $months[$englishMonth];
    } else {
        $germanMonth = 'Unbekannter Monat';
    }

    // Den neuen Datumsstring zusammenbauen und zurückgeben
    return $germanMonth . ' ' . $year;
}

function calculateTimeDifference($time1, $time2, $timeToSubtract) {
    // Create DateTime objects
    $datetime1 = new DateTime($time1);
    $datetime2 = new DateTime($time2);
    $datetimeToSubtract = new DateTime($timeToSubtract);

    // Calculate the difference between the first two times
    $interval = $datetime1->diff($datetime2);

    // Convert the interval to seconds
    $secondsDifference = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

    // Convert the third time to seconds
    $secondsToSubtract = ($datetimeToSubtract->format('H') * 3600) + ($datetimeToSubtract->format('i') * 60) + $datetimeToSubtract->format('s');

    // Subtract the third time in seconds from the difference in seconds
    $finalSeconds = $secondsDifference - $secondsToSubtract;

    // Handle cases where the result is negative
    if ($finalSeconds < 0) {
        $finalSeconds = 0; // Set to 0 or handle as needed
    }

    // Convert the final seconds back to HH:MM:SS format
    $hours = floor($finalSeconds / 3600);
    $minutes = floor(($finalSeconds % 3600) / 60);
    $seconds = $finalSeconds % 60;

    // Format the result as HH:MM:SS
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}
function EngToDeu($Wochentag)
{
    if ($Wochentag == "Monday")
    {
        $Wochentag = "Montag";
    }
    if ($Wochentag == "Tuesday")
    {
        $Wochentag = "Dienstag";
    }
    if ($Wochentag == "Wednesday")
    {
        $Wochentag = "Mittwoch";
    }
    if ($Wochentag == "Thursday")
    {
        $Wochentag = "Donnerstag";
    }
    if ($Wochentag == "Friday")
    {
        $Wochentag = "Freitag";
    }
    if ($Wochentag == "Saturday")
    {
        $Wochentag = "Samstag";
    }
    if ($Wochentag == "Sunday")
    {
        $Wochentag = "Sonntag";
    }
    return $Wochentag;
}
?>