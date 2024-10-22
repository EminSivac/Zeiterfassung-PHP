<?php 
    session_start();

    if ($_POST['MitarbeiterID'] == null)
    {
        $Mitarbeiter = $_SESSION['Mitarbeiter'];
    }
    else
    {
        $Mitarbeiter = $_POST['MitarbeiterID'];
    }
    $DauerPausen = $_POST['DauerPausen'];
    $Arbeitsende = $_POST['EndeZeit'];
    $Arbeitsbeginn = $_POST['BeginnZeit'];
    $Datum = $_POST['Datum'];
    if (isset($_POST['Grund']))
    {
        $Beschreibung = $_POST['Grund'].": ".$_POST['Beschreibung'];
    }
    else
    {
        $Beschreibung = $_POST['Beschreibung'];
    }
    $Grund = $_POST['Grund'];
    $Art = $_POST['Art'];
    $case = $_POST['case'];
    $UID = $_POST['UID'];

   require "dbconn.php";

    $sqlMitarbeiterTest = "SELECT * FROM `Mitarbeiter` WHERE `MitarbeiterID` = $Mitarbeiter";

    $resultMitarbeiterTest = $conn->query($sqlMitarbeiterTest);

    if ($resultMitarbeiterTest->num_rows > 0)
    {
        while ($row = $resultMitarbeiterTest->fetch_assoc())
        {
            $Vornamen = $row['Vorname'];
            $Nachnamen = $row['Nachname'];
        }

        $sqlAntragInsert = "INSERT INTO `Anträge` (`UID`, `Vorname`, `Nachname`, `MitarbeiterID`, `Datum`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `Beschreibung`, `Status`, `Art`, `ArbeitszeitUID`) VALUES (NULL, '$Vornamen', '$Nachnamen', $Mitarbeiter, '$Datum', '$Arbeitsbeginn', '$Arbeitsende', '$DauerPausen', '$Beschreibung', 'Ausstehend', '$Art' , '$UID')";

        if ($conn->query($sqlAntragInsert) === TRUE) 
        {
            if($case == 1)
            {
                echo "Checked <a href='http://192.168.1.3/Projekt_Emin/shortcutTool.php'>zurück</a>";
            }
            else
            {
                echo "Checked <a href='http://192.168.1.3/Projekt_Emin/antragstellen.html'>zurück</a>";
            }
        } 
        else 
        {
        echo "Error: " . $sqlAntragInsert . "<br>" . $conn->error;
        $error = "Error: " . $sqlAntragInsert . "<br>" . $conn->error;
        include 'send_dev.php';
        }
    }
    else
    {
        echo "Ein fehler ist aufgetreten!  <a href='http://192.168.1.3/Projekt_Emin/antragstellen.html'>Versuche es nochmal!</a>";
    }
    
?>
