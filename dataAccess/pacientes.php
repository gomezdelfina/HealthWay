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
            } catch (Exception) {
                return [];
            }
        }
    }
?>