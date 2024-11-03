<?php

session_start();

require "dbconn.php";

if ($_POST['MitarbeiterID'] == null)
{
    $Mitarbeiter = $_SESSION['Mitarbeiter'];
}
else
{
    $Mitarbeiter = $_POST['MitarbeiterID'];
}
$Jahr = date("Y");
$sqlGetUrlaub = "SELECT * FROM `Urlaub` WHERE `MitarbeiterID` = $Mitarbeiter AND `Jahr` = $Jahr";
$resultGetUrlaub = $conn->query($sqlGetUrlaub);

$row = $resultGetUrlaub->fetch_assoc();
$Urlaubstage = $row['RestUrlaub'];
?>
<h1>Zeiterfassungssystem</h1>
<?php echo "<h3>Freie Urlaubstage: ".$Urlaubstage."</h3>"; ?>
<p><a href='webterminal.php'><button>zurück</button></a></p>
<p><a href="antragstellen.html"><button>Nachtragen</button></a></p>
<style>
        body {
        font-family: 'Arial', sans-serif;
        }
      </style>

<div class="div">
        <table style="width:100%;border: 1px solid black;border-collapse: collapse;">
                    <tr style="border: 1px solid black;
                            border-collapse: collapse;">
                        <th style="border: 1px solid black;
                        border-collapse: collapse;">Datum</th>
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
                    //$sqlDaten = "SELECT * FROM Arbeitszeiten WHERE MitarbeiterID = $Mitarbeiter ORDER BY `Arbeitszeiten`.`Datum` DESC";
                    $sqlDaten = "SELECT * FROM `Arbeitszeiten` WHERE `MitarbeiterID` = $Mitarbeiter AND `EndeZeit` != '00:00:00' ORDER BY `Arbeitszeiten`.`Datum` DESC";
                    $resultDaten = $conn->query($sqlDaten);
                    
                    if ($resultDaten->num_rows >0)
                    {
                        while ($row = $resultDaten->fetch_assoc())
                        {
                            $UID = $row["UID"];
                            $BeginnZeit = $row["BeginnZeit"];
                            $EndeZeit = $row["EndeZeit"];
                            $DauerPausen = $row["DauerPausen"];
                            $DauerArbeitszeit = $row["DauerArbeitszeit"];
                            $PhpDatum = $row["Datum"];
                            $DatumOF = date_create($row["Datum"]);
                            $Datum = date_format($DatumOF, "d.m.Y");
            
                            echo "<tr style=\"border: 1px solid black;
                            border-collapse: collapse;\">
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$Datum."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$BeginnZeit."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$EndeZeit."</td>
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerPausen.
                            "<input type='hidden' name='Datum' value='$PhpDatum'/>
                            </td>
                            
                            <td style=\"border: 1px solid black;
                            border-collapse: collapse;\">".$DauerArbeitszeit."</td>
                            <form method='post' action='antragstellenKorrektur.php'>
                            <td>
                            <input type='hidden' name='UID' value='$UID'/>
                            <input type='hidden' name='Datum' value='$PhpDatum'/>
                            <input type='hidden' name='BeginnZeit' value='$BeginnZeit'/>
                            <input type='hidden' name='EndeZeit' value='$EndeZeit'/>
                            <input type='hidden' name='DauerPausen' value='$DauerPausen'/>
                            <button type='submit'>Korrigieren</button></form>
                            </td>
                            </tr>";
                                
                        }

                        // echo "</table>";
                    }
                    ?>
</table></div>
        <style> 
                        .div {
                        width: 100%;
                        height: 650px;
                        overflow: hidden;
                        }

                        .div:hover {
                        overflow-y: scroll;
                        }
                    </style>
