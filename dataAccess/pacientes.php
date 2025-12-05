<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Pacientes
    {
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
                        T4.NombreOS as ObraSocial, T3.NombrePlan as Plan
                        FROM pacientes T0 
                        INNER JOIN usuarios T1 ON T0.IdUsuario = T1.IdUsuario
                        INNER JOIN direcciones T2 ON T0.IdDireccion = T2.IdDireccion
                        INNER JOIN planes_obrassociales T3 ON T0.IdPlan_OS = T3.IdPlan
                        INNER JOIN obrassociales T4 ON T3.IdOS = T4.IdOS
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

        
        public static function actualizarEstadoPaciente($idPaciente, $estado) 
        {
            try{
                if (is_null($idPaciente)) {
                    throw new Exception("El campo idPaciente no puede estar vacío");
                }else if (is_null($estado)) {
                    throw new Exception("El campo estado no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "UPDATE pacientes
                    SET Estado = :estado
                    WHERE IdPaciente = :paciente;";

                $params = [
                    ["clave" => ":paciente", "valor" => $idPaciente],
                    ["clave" => ":estado", "valor" => $estado]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar estado de paciente: " . $e);
            }
        }
    }
?>