<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../conexiones/conectorMySQL.php';

// ============================
//  VALIDAR PARAMETRO
// ============================
if (!isset($_GET['idInternacion']) || intval($_GET['idInternacion']) <= 0) {
    echo json_encode(["error" => "Debe enviar idInternacion"]);
    exit;
}

$idInternacion = intval($_GET['idInternacion']);

try {
    // Conectar BD
    ConexionDb::connect();

    // =======================
    //  QUERY DE REVISIONES
    // =======================
    $query = "
        SELECT 
            r.IdRevisiones,
            r.IdInternacion,
            r.IdUsuario,
            r.FechaCreacion,
            r.TipoRevision,
            r.EstadoRevision,
            r.Sintomas,
            r.Diagnostico,
            r.Tratamiento,
            r.Observaciones,
            u.Nombre,
            u.Apellido
        FROM revisiones r
        LEFT JOIN usuarios u ON u.IdUsuario = r.IdUsuario
        WHERE r.IdInternacion = :idInternacion
        ORDER BY r.FechaCreacion DESC
    ";

    $params = [
        ["clave" => ":idInternacion", "valor" => $idInternacion]
    ];

    $datos = ConexionDb::consult($query, $params);

    echo json_encode($datos, JSON_UNESCAPED_UNICODE);

    ConexionDb::disconnect();

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
