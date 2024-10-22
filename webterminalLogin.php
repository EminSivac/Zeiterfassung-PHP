<?php
    session_start();
    $Mitarbeiter = $_POST['MitarbeiterID'];
    $PIN = $_POST['PIN'];

    $_SESSION['Mitarbeiter'] = $Mitarbeiter;
    $_SESSION['PIN'] = $PIN;

    header('Location: http://192.168.1.3/Projekt_Emin/webterminal.php');
    exit();