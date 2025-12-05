

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


global $dirBaseFile;
require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

class SolicitudesDataAccess
{

    public static function obtenerSolicitudes($estado = "")
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "
                SELECT 
                    s.IdSolicitud, 
                    s.TipoHabitacion, 
                    s.FechaSolicitud, 
                    s.Estado,
                    p.IdPaciente,
                    p.Nombre AS PacienteNombre, 
                    p.Apellido AS PacienteApellido, 
                    p.DNI AS PacienteDNI,
                    u.Nombre AS SolicitanteNombre,
                    u.Apellido AS SolicitanteApellido
                FROM Solicitudes s
                JOIN Pacientes p ON s.IdPaciente = p.IdPaciente
                JOIN Usuarios u ON s.IdUsuarioSolicitante = u.IdUsuario
            ";
            
            $params = [];
            if (!empty($estado)) {
                $sql .= " WHERE s.Estado = :estado";
                $params[":estado"] = $estado;
            }
            
            $sql .= " ORDER BY s.FechaSolicitud DESC";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => "success",
                "data"   => $rows
            ];

        } catch (Exception $e) {
            return [
                "status"  => "error",
                "mensaje" => "Error DB al obtener solicitudes: " . $e->getMessage()
            ];
        } finally {
            ConexionDb::disconnect();
        }
    }


 
    public static function crearSolicitud($data)
    {
        try {
            global $conn;
            ConexionDb::connect();

            
            if (empty($data['IdPaciente']) || empty($data['TipoHabitacion']) || empty($data['IdUsuarioSolicitante'])) {
                throw new Exception("Datos incompletos para crear la solicitud.");
            }
            
            
            $estado = 'Pendiente'; 
            $fechaSolicitud = date('Y-m-d H:i:s');

            $sql = "
                INSERT INTO Solicitudes 
                (IdPaciente, TipoHabitacion, IdUsuarioSolicitante, FechaSolicitud, Estado) 
                VALUES 
                (:idPaciente, :tipoHabitacion, :idUsuarioSolicitante, :fechaSolicitud, :estado)
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":idPaciente"           => $data['IdPaciente'],
                ":tipoHabitacion"       => $data['TipoHabitacion'],
                ":idUsuarioSolicitante" => $data['IdUsuarioSolicitante'],
                ":fechaSolicitud"       => $fechaSolicitud,
                ":estado"               => $estado
            ]);

            return [
                "status"  => "success",
                "mensaje" => "Solicitud creada correctamente. Estado: Pendiente."
            ];

        } catch (Exception $e) {
            return [
                "status"  => "error",
                "mensaje" => "Error DB al crear solicitud: " . $e->getMessage()
            ];
        } finally {
            ConexionDb::disconnect();
        }
    }

  
    public static function actualizarEstadoSolicitud($idSolicitud, $nuevoEstado)
    {
        try {
            global $conn;
            ConexionDb::connect();

           
            $estadosValidos = ['Pendiente', 'Aceptada', 'Rechazada'];
            if (!in_array($nuevoEstado, $estadosValidos)) {
                throw new Exception("Estado de solicitud inválido.");
            }

            $sql = "
                UPDATE Solicitudes 
                SET Estado = :estado
                WHERE IdSolicitud = :id
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":estado" => $nuevoEstado,
                ":id"     => $idSolicitud
            ]);

            if ($stmt->rowCount() === 0) {
                 return [
                    "status" => "error",
                    "mensaje" => "No se encontró la solicitud o el estado ya era el mismo."
                ];
            }

            return [
                "status"  => "success",
                "mensaje" => "Estado de solicitud actualizado a: " . $nuevoEstado
            ];

        } catch (Exception $e) {
            return [
                "status"  => "error",
                "mensaje" => "Error DB al actualizar estado de solicitud: " . $e->getMessage()
            ];
        } finally {
            ConexionDb::disconnect();
        }
    }
    

    public static function obtenerPacientesDisponibles()
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "
                SELECT 
                    p.IdPaciente, 
                    p.Nombre, 
                    p.Apellido, 
                    p.DNI 
                FROM Pacientes p
                LEFT JOIN Internaciones i 
                    ON p.IdPaciente = i.IdPaciente AND i.FechaFin IS NULL
                WHERE i.IdInternacion IS NULL
                ORDER BY p.Apellido, p.Nombre
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => "success",
                "data"   => $rows
            ];

        } catch (Exception $e) {
            return [
                "status"  => "error",
                "mensaje" => "Error DB al obtener pacientes disponibles: " . $e->getMessage()
            ];
        } finally {
            ConexionDb::disconnect();
        }
    }
}
?>