<?php
    require_once("../../includes/globals.php");
    require_once($dirBaseFile . "/dataAccess/notificaciones.php");

    header("Content-Type: application/json");

    if (!isset($_GET["id"])) {
        echo json_encode(["ok" => false, "msg" => "Falta ID"]);
        exit;
    }

    $id = intval($_GET["id"]);

    $result = Notificaciones::marcarLeida($id);

    echo json_encode(["ok" => $result]);
?>