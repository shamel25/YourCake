<?php
    session_start();
    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION['userlogin']);
    header('location: ../login.php');
?>