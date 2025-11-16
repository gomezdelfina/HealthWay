<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Revisiones
    {
        public static function getRevisiones()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdRevisiones, T1.IdHabitacion, T1.IdCama, T3.Nombre, T3.Apellido,
                    T0.FechaCreacion, T0.TipoRevision, T0.EstadoRevision
                    FROM revisiones T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN pacientes T2 ON T2.IdPaciente = T1.IdPaciente
                    INNER JOIN usuarios T3 on T2.IdPaciente = T3.IdUsuario;
                ";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception) {
                return [];
            }
        }
    }
?>