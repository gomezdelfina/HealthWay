<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    
    if (isset($_SESSION['usuario'])) {
    
        session_unset();

        session_destroy();

        header('Location: ' . $dirBaseUrl . '/index.php');
    }
?>