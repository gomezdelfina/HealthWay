<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../includes/globals.php');
require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

header('Content-Type: application/json; charset=utf-8');

try {
    ConexionDb::connect();

    
    $idInternacion = isset($_GET['idInternacion']) ? intval($_GET['idInternacion']) : 0;

    if ($idInternacion <= 0) {
        echo json_encode([]);
        ConexionDb::disconnect();
        exit;
    }

    // Obtener revisiones según la internación
    $query = "
        SELECT 
            r.IdRevision,
            r.IdInternacion,
            r.TipoRevision,
            r.Sintomas,
            r.Diagnostico,
            r.Tratamiento,
            r.Observaciones,
            r.EstadoRevision,
            r.FechaCreacion
        FROM revisiones r
        WHERE r.IdInternacion = :idInternacion
        ORDER BY r.FechaCreacion DESC
    ";

    $params = [
        ["clave" => ":idInternacion", "valor" => $idInternacion]
    ];

    $datos = ConexionDb::consult($query, $params);

    echo json_encode($datos ?: [], JSON_UNESCAPED_UNICODE);

    ConexionDb::disconnect();

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
