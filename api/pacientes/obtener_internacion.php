<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../includes/globals.php');
require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

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

        -- Datos del paciente
        p.DNI,
        u.Nombre,
        u.Apellido,

        -- Plan y obra social (relación según IdPlan_OS)
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

    -- JOIN con PLAN
    INNER JOIN planes_obrassociales pl ON pl.IdPlan = p.IdPlan_OS

    -- JOIN con OBRA SOCIAL (a través del plan)
    INNER JOIN obrassociales os ON os.IdOS = pl.IdOS

    WHERE u.IdUsuario = :idPaciente
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
