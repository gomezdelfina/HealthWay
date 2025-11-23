<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Internaciones
    {
        public static function buscarInternaciones(){
             ConexionDb::connect();

            $sql = "
                SELECT 
                    i.IdInternacion,
                    i.IdCama,
                    i.IdHabitacion,
                    i.EstadoInternacion,
                    CONCAT(u.Nombre, ' ', u.Apellido) AS NombrePaciente
                FROM internaciones i
                INNER JOIN pacientes p ON i.IdPaciente = p.IdPaciente
                INNER JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                WHERE CONCAT(u.Nombre, ' ', u.Apellido) LIKE :busqueda
                OR i.IdCama LIKE :busqueda
                OR i.IdHabitacion LIKE :busqueda
                ORDER BY i.IdInternacion DESC
            ";

            $result = ConexionDb::consult($sql);

            ConexionDb::disconnect();

            return $result;

        }

        public static function getInternacionActivaByPaciente($IdPaciente)
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.*
                        FROM internaciones T0 
                        INNER JOIN pacientes T1 ON T0.IdPaciente = T1.IdPaciente
                        WHERE 1 = 1
                        AND T0.EstadoInternacion = 'Activa'
                        AND T1.IdPaciente = :idPaciente;";

                $params = [
                    ["clave" => ":idPaciente", "valor" => $IdPaciente]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception) {
                return [];
            }
        }

        public static function getInternaciones()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.*
                        FROM internaciones T0 
                        INNER JOIN pacientes T1 ON T0.IdPaciente = T1.IdPaciente
                        WHERE 1 = 1
                        AND T0.EstadoInternacion = 'Activa';";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception) {
                return [];
            }
        }
    }
?>