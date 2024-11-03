<?php 
    $Vorname = $_POST['Vorname'];
    $Nachname = $_POST['Nachname'];
    $Email = $_POST['Email'];

    require "dbconn.php";

    $MitarbeiterID = rand(1000, 9999);

    function miterarbeiterIDvergeben($conn, $MitarbeiterID)
    {
        $sqlMiterarbieterIDvergeben = "SELECT * FROM Mitarbeiter WHERE MitarbeiterID = '$MitarbeiterID'";

        return $conn->query($sqlMiterarbieterIDvergeben);
    }

    function neuenMitarbeiterInsert($conn, $MitarbeiterID, $Vorname, $Nachname, $Email)
    {
        $PIN = rand(1000, 9999);

        $sqlNeuenMitarbeiterInsert = "INSERT INTO `Mitarbeiter` (`UID`, `MitarbeiterID`, `Vorname`, `Nachname`, `PIN`, `Email`, `Status`) VALUES (Null, '$MitarbeiterID', '$Vorname', '$Nachname', '$PIN', '$Email', 'Abwesend')";
        
        $sqlSollzeitenInsert = "INSERT INTO `Sollzeiten` (`UID`, `MitarbeiterID`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`, `Wochentag`) 
        VALUES (NULL, '$MitarbeiterID', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'Montag');INSERT INTO `Sollzeiten` (`UID`, `MitarbeiterID`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`, `Wochentag`) 
        VALUES (NULL, '$MitarbeiterID', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'Dienstag');INSERT INTO `Sollzeiten` (`UID`, `MitarbeiterID`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`, `Wochentag`) 
        VALUES (NULL, '$MitarbeiterID', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'Mittwoch');INSERT INTO `Sollzeiten` (`UID`, `MitarbeiterID`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`, `Wochentag`) 
        VALUES (NULL, '$MitarbeiterID', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'Donnerstag');INSERT INTO `Sollzeiten` (`UID`, `MitarbeiterID`, `BeginnZeit`, `EndeZeit`, `DauerPausen`, `DauerArbeitszeit`, `Wochentag`) 
        VALUES (NULL, '$MitarbeiterID', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'Freitag');";
        $sqlNFCInsert = "INSERT INTO `NfcKarten` (`UID`, `MitarbeiterID`, `NFC`) VALUES (NULL, '$MitarbeiterID', '0')";

        if ($conn->query($sqlNeuenMitarbeiterInsert) === TRUE) 
        {
            echo "Checked <a href='neueMitarbeiter.html'>Nächser Mitarbeiter</a>
            <p>
            MitarbeiterID: $MitarbeiterID<br>
            Vorname: $Vorname<br>
            Nachname: $Nachname<br>
            PIN: $PIN<br>
            E-Mail: $Email
            </p>";
        } 
        else 
        {
            echo "Error: <br>" . $sqlNeuenMitarbeiterInsert . "<br><br>" . $conn->error;
        }
        while(mysqli_next_result($conn))
        {

        }
        if ($conn->multi_query($sqlSollzeitenInsert) === TRUE) 
        {
            // echo "Checked <a href='./neueMitarbeiter.html'>Nächser Mitarbeiter</a>
            // <p>
            // MitarbeiterID: $MitarbeiterID<br>
            // Vorname: $Vorname<br>
            // Nachname: $Nachname<br>
            // PIN: $PIN<br>
            // E-Mail: $Email
            // </p>";
        } 
        else 
        {
            echo "Error: <br>" . $sqlSollzeitenInsert . "<br><br><br>" . $conn->error;
        }
        while(mysqli_next_result($conn)){;}
        if ($conn->query($sqlNFCInsert) === TRUE) 
        {
            // echo "Checked <a href='./neueMitarbeiter.html'>Nächser Mitarbeiter</a>
            // <p>
            // MitarbeiterID: $MitarbeiterID<br>
            // Vorname: $Vorname<br>
            // Nachname: $Nachname<br>
            // PIN: $PIN<br>
            // E-Mail: $Email
            // </p>";
        } 
        else 
        {
            echo "Error: " . $sqlNFCInsert . "<br>" . $conn->error;
        }
    }

    // Erstes Ergebnis, ob es vorhanden ist.[Anzahl der Reihen]
    $resultMiterarbieterIDvergeben = miterarbeiterIDvergeben($conn, $MitarbeiterID);

    while($resultMiterarbieterIDvergeben->num_rows > 0)
    {
        $MitarbeiterID = rand(1000, 9999);
        $resultMiterarbieterIDvergeben = miterarbeiterIDvergeben($conn, $MitarbeiterID);
    }

    neuenMitarbeiterInsert($conn, $MitarbeiterID, $Vorname, $Nachname, $Email);

    $sqlPostInsert = "INSERT INTO `Mitarbeiter` (`UID`, `Name`, `Email`, `Angestellt`) VALUES (NULL, '$Vorname $Nachname', '$Email', '1')";

    if ($conn->query($sqlPostInsert) === TRUE) 
        {
            /*echo "Checked <a href='./neueMitarbeiter.html'>Nächser Mitarbeiter</a>
            <p>
            MitarbeiterID: $MitarbeiterID<br>
            Vorname: $Vorname<br>
            Nachname: $Nachname<br>
            PIN: $PIN<br>
            E-Mail: $Email
            </p>";*/
        } 
        else 
        {
            echo "Error: " . $sqlPostInsert . "<br>" . $conn->error;
        }

    ?>