<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../conexiones/conectorMySQL.php';

header('Content-Type: application/json; charset=utf-8');

try {
    ConexionDb::connect();

    $idPaciente = isset($_GET['idPaciente']) ? intval($_GET['idPaciente']) : 0;

    if ($idPaciente <= 0) {
        echo json_encode(["error" => "IdPaciente no válido o no provisto"]);
        ConexionDb::disconnect();
        exit;
    }

    $query = "
        SELECT 
            i.IdInternacion,
            i.IdSolicitud,
            i.IdCama,
            i.IdHabitacion,
            i.IdPaciente,
            i.FechaInicio,
            i.FechaFin,
            i.EstadoInternacion,
            i.Observaciones,
            p.DNI,
            u.Nombre,
            u.Apellido
        FROM internaciones i
        LEFT JOIN pacientes p ON p.IdPaciente = i.IdPaciente
        LEFT JOIN usuarios u ON u.IdUsuario = p.IdUsuario
        WHERE i.IdPaciente = :idPaciente
        ORDER BY i.FechaInicio DESC
    ";

    $params = [
        ["clave" => ":idPaciente", "valor" => $idPaciente]
    ];

    $datos = ConexionDb::consult($query, $params);

    echo json_encode($datos ?? ["error" => "Error en la consulta"], JSON_UNESCAPED_UNICODE);

    ConexionDb::disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
