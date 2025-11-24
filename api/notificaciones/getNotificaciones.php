<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/Notificaciones.php');

    header("Content-Type: application/json; charset=utf-8");

    $notifs = Notificaciones::obtenerNoLeidas("medico");

    echo json_encode($notifs);
?>