<?php
session_start();

require "dbconn.php";

$Mitarbeiter = $_SESSION['Mitarbeiter'];

$datum = $_POST['datum'];
$dauer = $_POST['dauer'];

$hour = rand(18, 19);
$minute = rand(01,59);
$seconds = rand(01,59);
$tolleranz = rand(01,17);

$zufaelligeZeit = "$hour:$minute:$seconds";

$beginn = new DateTime($zufaelligeZeit);

$beginn->modify("+$dauer hours +$tolleranz minutes");

$ende = $beginn->format('H:i');

$ende;

$sqlAntragPush = "INSERT INTO `Arbeitszeiten` (`UID`, `MitarbeiterID`, `Datum`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`,`Anmerkung`) VALUES (NULL, '$Mitarbeiter', '$datum', '$zufaelligeZeit', '$ende', '00:00:00', SUBTIME( TIMEDIFF( '$ende', '$zufaelligeZeit'), '00:00:00'),'')";
//echo $sqlAntragPush;
if ($conn->query($sqlAntragPush) === TRUE) 
{
  echo "Checked nachtragen here <a href='http://192.168.1.3/Projekt_Emin/webterminal.php'><button>zurück</button></a>";
  
  //header ("Location: http://192.168.1.3/Projekt_Emin/adminPortal.php");
  //exit;
}
else 
{
  echo "Error: " . $sqlAntragPush . "<br>" . $conn->error;
}