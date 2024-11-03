<?php
    session_start();
    $Mitarbeiter = $_POST['MitarbeiterID'];

    $_SESSION['MitarbeiterID'] = $Mitarbeiter;

    echo "Checked";
    header ("Location: //192.168.178.40/zeiterfassung/adminPortal.php");
    exit;