<?php
    session_start();
    $Mitarbeiter = $_POST['MitarbeiterID'];
    $PIN = $_POST['PIN'];

    $_SESSION['Mitarbeiter'] = $Mitarbeiter;
    $_SESSION['PIN'] = $PIN;

    header('Location: //192.168.178.40/zeiterfassung/webterminal.php');
    exit();