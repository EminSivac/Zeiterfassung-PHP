<?php
    session_start();
    $Mitarbeiter = $_POST['MitarbeiterID'];

    $_SESSION['MitarbeiterID'] = $Mitarbeiter;

    echo "Checked";
    header ("Location: http://192.168.1.3/Projekt_Emin/adminPortal.php");
    exit;