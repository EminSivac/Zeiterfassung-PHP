<?php
    $MitarbeiterID = $_POST['MitarbeiterID'];
    $NFC = $_POST['NFC'];

    require "dbconn.php";

    function updateNFC($conn, $MitarbeiterID, $NFC)
    {
        $sqlNFCUpdate = "UPDATE `NfcKarten` SET `NFC` = '$NFC' WHERE `NfcKarten`.`MitarbeiterID` = '$MitarbeiterID'";

        if ($conn->query($sqlNFCUpdate) === TRUE) 
        {
            echo "Checked <a href='http://192.168.1.3/Projekt_Emin/neueMitarbeiter.html'>Nächser Mitarbeiter</a>";
        } 
        else 
        {
            echo "Error: " . $sqlNFCUpdate . "<br>" . $conn->error;
        }
    }

    updateNFC($conn, $MitarbeiterID, $NFC);
?>