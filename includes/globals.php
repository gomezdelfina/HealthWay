<?php
    session_start();

    // url frontend
    $dirBaseUrl = '/2025/HeathWay/Codigo/HealthWay';
    $dirBaseFile = realpath($_SERVER['DOCUMENT_ROOT'] . $dirBaseUrl);

    // var 
    $errors = [];
    $module = '';
?>