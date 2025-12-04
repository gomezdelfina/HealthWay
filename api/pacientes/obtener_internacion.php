<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../includes/globals.php');
require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

header('Content-Type: application/json; charset=utf-8');

try {
    ConexionDb::connect();

   
    $idUsuario = isset($_GET['idUsuario']) ? intval($_GET['idUsuario']) : 0;

    if ($idUsuario <= 0) {
        echo json_encode(["error" => "IdUsuario no válido o no provisto"]);
        ConexionDb::disconnect();
        exit;
    }

    // Obtener el IdPaciente correspondiente
    $queryPaciente = "SELECT IdPaciente FROM pacientes WHERE IdUsuario = :idUsuario LIMIT 1";
    $paramsPaciente = [
        ["clave" => ":idUsuario", "valor" => $idUsuario]
    ];

    $paciente = ConexionDb::consult($queryPaciente, $paramsPaciente);

    if (!$paciente || !isset($paciente[0]['IdPaciente'])) {
        echo json_encode([]); // simplemente vacío
        ConexionDb::disconnect();
        exit;
    }

    $idPacienteReal = intval($paciente[0]['IdPaciente']);

    // Obtener internaciones del paciente
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
            u.Apellido,

            pl.IdPlan,
            pl.NombrePlan,
            pl.TipoHabitacion,
            pl.HorasInternacion,
            pl.PrecioHora,
            pl.PrecioHoraExtra,

            os.IdOS,
            os.NombreOS

        FROM internaciones i
        INNER JOIN pacientes p ON p.IdPaciente = i.IdPaciente
        INNER JOIN usuarios u ON u.IdUsuario = p.IdUsuario
        INNER JOIN planes_obrassociales pl ON pl.IdPlan = p.IdPlan_OS
        INNER JOIN obrassociales os ON os.IdOS = pl.IdOS
        WHERE i.IdPaciente = :idPacienteReal
        ORDER BY i.FechaInicio DESC";

    $params = [
        ["clave" => ":idPacienteReal", "valor" => $idPacienteReal]
    ];

    $datos = ConexionDb::consult($query, $params);
    echo json_encode($datos ?: [], JSON_UNESCAPED_UNICODE);

    ConexionDb::disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
