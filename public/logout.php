<?php
session_start();

// Destroi a sessão
session_unset();
session_destroy(); 
header("Location: login.php");
exit;
