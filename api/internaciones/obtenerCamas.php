<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    try {
        $pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;

        $resultado = internaciones::ObtenerCamas($pagina);

        echo json_encode([
            "success" => true,
            "camas" => $resultado["camas"],
            "totalPaginas" => $resultado["totalPaginas"]
        ]);

    } catch (Exception $e) {

        echo json_encode([
            "success" => false,
            "camas" => [],
            "error" => $e->getMessage()
        ]);
    }
?>