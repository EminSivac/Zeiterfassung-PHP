<?php
    $MitarbeiterID = $_POST['MitarbeiterID'];
    $NFC = $_POST['NFC'];

    require "dbconn.php";

    function updateNFC($conn, $MitarbeiterID, $NFC)
    {
        $sqlNFCUpdate = "UPDATE `NfcKarten` SET `NFC` = '$NFC' WHERE `NfcKarten`.`MitarbeiterID` = '$MitarbeiterID'";

        if ($conn->query($sqlNFCUpdate) === TRUE) 
        {
            echo "Checked <a href='./neueMitarbeiter.html'>Nächser Mitarbeiter</a>";
        } 
        else 
        {
            echo "Error: " . $sqlNFCUpdate . "<br>" . $conn->error;
        }
    }

    updateNFC($conn, $MitarbeiterID, $NFC);
?>