<?php

use Dba\Connection;

    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Recordatorio
    {
        public static function getRecordatorios()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdRecordatorio, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T0.FechaCreacion AS ProximaEjecucion
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    ORDER BY T0.IdRecordatorio DESC
                    LIMIT 30;
                ";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios: " . $e);
            }
        }

        public static function createRecordatorio($recordatorio)
        {
            if (is_null($recordatorio)) {
                throw new Exception("El Recordatorio no puede estar vacío");
            }

            try{
                ConexionDb::connect();

                $mapaCampos = [
                    'IdInternacion'    => 'IdInternacion',
                    'IdUsuario'        => 'IdUsuario',
                    'TipoRevision'     => 'TipoRevision',
                    'FechaCreacion'    => 'FechaCreacion',
                    'Estado'           => 'Estado',
                    'FechaInicioRec'   => 'FechaInicioRec',
                    'FechaFinRec'      => 'FechaFinRec',      // Puede ser null
                    'Frecuencia'       => 'Frecuencia',
                    'FrecuenciaHoras'  => 'FrecuenciaHoras',  // Puede ser null
                    'FrecuenciaDias'   => 'FrecuenciaDias',   // Puede ser null
                    'FrecuenciaSem'    => 'FrecuenciaSem',    // Puede ser null
                    'RepetirLunes'     => 'RepetirLunes',
                    'RepetirMartes'    => 'RepetirMartes',
                    'RepetirMiercoles' => 'RepetirMiercoles',
                    'RepetirJueves'    => 'RepetirJueves',
                    'RepetirViernes'   => 'RepetirViernes',
                    'RepetirSabado'    => 'RepetirSabado',
                    'RepetirDomingo'   => 'RepetirDomingo',
                    'Observaciones'    => 'Observaciones',    // Puede ser null
                    'activo'           => 'activo'
                ];

                $columnasSql = [];
                $valoresSql  = [];
                foreach ($mapaCampos as $columnaBd => $claveArray) {
                    // Verificamos si la clave existe en el array y si su valor NO es null
                    if (array_key_exists($claveArray, $recordatorio) && $recordatorio[$claveArray] !== null) {
                        
                        $columnasSql[] = "`$columnaBd`";
                        
                        $placeholder = ":" . $claveArray;
                        $valoresSql[] = $placeholder;
                        
                        $params[] = [
                            "clave" => $placeholder, 
                            "valor" => $recordatorio[$claveArray]
                        ];
                    }
                }

                $sql = "INSERT INTO `recordatorio` (" . implode(', ', $columnasSql) . ") 
                        VALUES (" . implode(', ', $valoresSql) . ");";

                $result = ConexionDb::consult($sql,$params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al crear Recordatorio: " . $e);
            }
        }

        public static function getRecordatorioById($idRec)
        {
            try{
                if(is_null($idRec)){
                    throw new Exception("El campo Id Recordatorio no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = 'SELECT T0.IdRecordatorio, T1.IdPaciente, T0.IdUsuario, T0.TipoRevision, T0.FechaCreacion,
                        T0.Estado, T0.FechaInicioRec, T0.FechaFinRec, T0.Frecuencia, T0.FrecuenciaHoras, 
                        T0.FrecuenciaDias, T0.FrecuenciaSem, T0.RepetirLunes, T0.RepetirMartes, T0.RepetirMiercoles,
                        T0.RepetirJueves, T0.RepetirViernes, T0.RepetirSabado, T0.RepetirDomingo, T0.Observaciones, T0.activo
                        FROM recordatorio T0
                        INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                        WHERE T0.IdRecordatorio = :idRec;';

                $params = [
                    ['clave' => ':idRec', 'valor' => $idRec]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al obtener el recordatorio por ID: " . $e);
            }
        }

        public static function editRecordatorio($recordatorio, $idRec){
            try{
                if (is_null($idRec)) {
                    throw new Exception("El campo Id Recordatorio no puede estar vacío");
                }

                if(is_null($recordatorio)){
                    throw new Exception("El Recordatorio no puede estar vacío");
                }

                ConexionDb::connect();

                $mapaCampos = [
                    'TipoRevision' => 'TipoRevision',
                    'Estado' => 'Estado',
                    'FechaInicioRec' => 'FechaInicioRec',
                    'FechaFinRec' => 'FechaFinRec',
                    'Frecuencia' => 'Frecuencia',
                    'FrecuenciaHoras' => 'FrecuenciaHoras',
                    'FrecuenciaDias' => 'FrecuenciaDias',
                    'FrecuenciaSem' => 'FrecuenciaSem',
                    'RepetirLunes' => 'RepetirLunes',
                    'RepetirMartes' => 'RepetirMartes',
                    'RepetirMiercoles' => 'RepetirMiercoles',
                    'RepetirJueves' => 'RepetirJueves',
                    'RepetirViernes' => 'RepetirViernes',
                    'RepetirSabado' => 'RepetirSabado',
                    'RepetirDomingo' => 'RepetirDomingo',
                    'Observaciones' => 'Observaciones',
                    'activo' => 'activo'
                ];

                $setClauses = [];
                $params = [];

                foreach ($mapaCampos as $columnaBd => $claveArray) {
                    if (array_key_exists($claveArray, $recordatorio)) {
                        $placeholder = ":" . $claveArray;
                        $setClauses[] = "`$columnaBd` = $placeholder";
                        
                        $params[] = [
                            "clave" => $placeholder, 
                            "valor" => $recordatorio[$claveArray]
                        ];
                    }
                }
        
                $params[] = [
                    "clave" => ":idRec",
                    "valor" => $idRec
                ];

                $sql = "UPDATE `recordatorio` 
                        SET " . implode(', ', $setClauses) . " 
                        WHERE `IdRecordatorio` = :idRec";

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar la revision: " . $e);
            }
        }
    }
?>