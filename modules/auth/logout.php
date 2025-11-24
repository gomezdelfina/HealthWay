<?php

    require_once(__DIR__ . '/../includes/globals.php');

    session_unset();

    session_destroy();

    header('Location: ' . $dirBaseUrl . '/index.php');

?>