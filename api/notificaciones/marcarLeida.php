<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/Notificaciones.php');

    $id = $_GET["id"] ?? 0;

    if ($id) {
        Notificaciones::marcarLeida($id);
    }
?>
