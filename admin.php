<?php
    session_start();

    $Admin = $_POST['Admin'];
    $Password = $_POST['Password'];

    $_SESSION['Admin'] = $Admin;
    $_SESSION['Password'] = $Password;

    header('Location: http://192.168.1.3/Projekt_Emin/adminPortal.php');
    exit();

     
     