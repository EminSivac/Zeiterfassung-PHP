<?php
    session_start();

    $Admin = $_POST['Admin'];
    $Password = $_POST['Password'];

    $_SESSION['Admin'] = $Admin;
    $_SESSION['Password'] = $Password;

    header('Location: //192.168.178.40/zeiterfassung/adminPortal.php');
    exit();

     
     