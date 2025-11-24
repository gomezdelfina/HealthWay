<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $id = $_GET["id"] ?? null;

    if (!$id) {
        exit("ID no proporcionado");
    }

    $resultado = internaciones::ObtenerQR($id);

    if ($resultado["status"] === "success") {

        header("Content-Type: image/png");
        echo $resultado["qr"]; // Mostrar imagen QR directamente
        exit;

    } else {

        echo $resultado["mensaje"];
        exit;
    }
?>