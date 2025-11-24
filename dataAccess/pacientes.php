<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Pacientes
    {
        public static function getPacientesInterAct()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdPaciente, T1.Nombre, T1.Apellido, T0.Habilitado
                        FROM pacientes T0 
                        INNER JOIN usuarios T1 ON T0.IdUsuario = T1.IdUsuario
                        INNER JOIN internaciones T2 ON T0.IdPaciente = T2.IdPaciente
                        WHERE 1 = 1
                        AND T0.Habilitado = 1
                        AND T2.EstadoInternacion = 'Activa';";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los pacientes por internacion: " . $e);
            }
        }

        public static function getPacienteById($idPac)
        {
            try{
                if(is_null($idPac)){
                    throw new Exception("El campo Id Paciente no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.IdPaciente, CONCAT(T1.Nombre, ' ', T1.Apellido) as Nombre,
                        T0.DNI, T0.FechaNac, T0.Genero, 
                        CONCAT(T2.Direccion, ' ', T2.Numero, ', ', T2.Ciudad, ', ', T2.Provincia, ', ', T2.Pais) AS Direccion,
                        T3.NombreOS as ObraSocial, T4.NombrePlan as Plan
                        FROM pacientes T0 
                        INNER JOIN usuarios T1 ON T0.IdUsuario = T1.IdUsuario
                        INNER JOIN direcciones T2 ON T0.IdDireccion = T2.IdDireccion
                        INNER JOIN obrassociales T3 ON T0.IdOS = T3.IdOS
                        INNER JOIN planes_obrassociales T4 ON T3.IdOS = T4.IdOS AND T0.IdPlanOS = T4.IdPlan
                        WHERE 1 = 1
                        AND T0.IdPaciente = :idPac;";
                $params = [
                    ['clave' => ':idPac', 'valor' => $idPac]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener paciente por id: " . $e);
            }
        }
    }
?>