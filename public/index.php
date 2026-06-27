<?php
session_start();

// existe a sessão do usuário, redireciona para o dashboard
if (isset($_SESSION["usuario"])) {
    header("Location: dashboard.php");
     exit;
    } 
    else {
    // Volta no Login
    header("Location: login.php");
    exit;
    }

