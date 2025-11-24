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

        public static function editRecordatorioEstado($recordatorio){
            try{
                if(is_null($recordatorio)){
                    throw new Exception("El Recordatorio no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "UPDATE `recordatorio` 
                        SET `recordatorio` = :estado
                        WHERE `IdRecordatorio` = :idRec";

                $params = [
                    ['clave' => ':idRec', 'valor' => $recordatorio['IdRecordatorio']],
                    ['clave' => ':estado', 'valor' => $recordatorio['Estado']]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar estado de la revision: " . $e);
            }
        }

        public static function getRecordatoriosAtrasados($userId)
        {
            try{
                if (is_null($userId)) {
                    throw new Exception("El campo Id Usuario no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.IdRecordatorio, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T7.DescEstadoRev, T0.Estado, T0.*
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN estadorevisiones T7 ON T0.EstadoRevision = T7.IdEstadoRev
                    WHERE T0.Estado = 'Atrasado'
                    AND T0.TipoRevision IN (
                            SELECT 
                            CASE T.idPermiso 
                                WHEN 15 THEN 1 
                                WHEN 16 THEN 2
                                WHEN 17 THEN 3
                                WHEN 18 THEN 4
                                WHEN 19 THEN 5
                                WHEN 20 THEN 6
                                WHEN 27 THEN 7
                                WHEN 21 THEN 1
                                WHEN 22 THEN 2
                                WHEN 23 THEN 3
                                WHEN 24 THEN 4
                                WHEN 25 THEN 5
                                WHEN 26 THEN 6
                                WHEN 28 THEN 7
                            END AS idTipoRevision
                            FROM roles_permisos T
                            INNER JOIN roles_usuarios TT on T.IdRol = TT.IdRol
                            WHERE TT.IdUsuario = :idUser)
                    ORDER BY T0.IdRecordatorio DESC;
                ";

                $params = [
                    ['clave' => ':idUser', 'valor' => $userId]
                ];

                $result = ConexionDb::consult($sql,$params);

                ConexionDb::disconnect();

                $resultadosHoy = [];
                $fechaHoyString = date('Y-m-d');

                foreach ($result as $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha) {
                        if ($objFecha->format('Y-m-d') === $fechaHoyString) {
                            
                            $row['ProximaEjecucion'] = $objFecha->format('Y-m-d H:i:s');
                            
                            $resultadosHoy[] = $row;
                        }
                    }
                }
                
                return $resultadosHoy;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios atrasados: " . $e);
            }
        }

        public static function getRecordatoriosPendientes($userId)
        {
            try{
                if (is_null($userId)) {
                    throw new Exception("El campo Id Usuario no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.IdRecordatorio, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T0.Estado
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    WHERE T0.Estado = 'No Hecho'
                    AND T0.TipoRevision IN (
                            SELECT 
                            CASE T.idPermiso 
                                WHEN 15 THEN 1 
                                WHEN 16 THEN 2
                                WHEN 17 THEN 3
                                WHEN 18 THEN 4
                                WHEN 19 THEN 5
                                WHEN 20 THEN 6
                                WHEN 27 THEN 7
                                WHEN 21 THEN 1
                                WHEN 22 THEN 2
                                WHEN 23 THEN 3
                                WHEN 24 THEN 4
                                WHEN 25 THEN 5
                                WHEN 26 THEN 6
                                WHEN 28 THEN 7
                            END AS idTipoRevision
                            FROM roles_permisos T
                            INNER JOIN roles_usuarios TT on T.IdRol = TT.IdRol
                            WHERE TT.IdUsuario = :idUser)
                    ORDER BY T0.IdRecordatorio DESC;";

                $params = [
                    ['clave' => ':idUser', 'valor' => $userId]
                ];

                $result = ConexionDb::consult($sql,$params);
                foreach ($result as $key => $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha) {
                        $result[$key]['ProximaEjecucion'] = $objFecha->format('Y-m-d H:i:s');
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();
                
                $resultadosHoy = [];
                $fechaHoyString = date('Y-m-d');

                foreach ($result as $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha) {
                        if ($objFecha->format('Y-m-d') === $fechaHoyString) {
                            
                            $row['ProximaEjecucion'] = $objFecha->format('Y-m-d H:i:s');
                            
                            $resultadosHoy[] = $row;
                        }
                    }
                }
                
                return $resultadosHoy;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios pendientes: " . $e);
            }
        }

        private static function calcularProximaFecha($row) {
            try {
                $ahora = new DateTime(); 
                $inicio = new DateTime($row['FechaInicioRec']);
                $fin = $row['FechaFinRec'] ? new DateTime($row['FechaFinRec']) : null;
                $frecuencia = $row['Frecuencia'];

                if ($inicio > $ahora) return $inicio;

                $proximaFecha = clone $inicio;

                switch ($frecuencia) {
                    case 'Unica Vez':
                        if ($inicio < $ahora) return null;
                        return $inicio;

                    case 'Horas':
                        $intervalo = (int)$row['FrecuenciaHoras'];
                        if($intervalo <= 0) return null; 
                        while ($proximaFecha <= $ahora) {
                            $proximaFecha->modify("+{$intervalo} hours");
                        }
                        break;

                    case 'Diaria':
                        $intervalo = (int)$row['FrecuenciaDias'];
                        if($intervalo <= 0) return null;
                        $diffDias = $ahora->diff($inicio)->days;
                        $pasos = ceil($diffDias / $intervalo);
                        $proximaFecha->modify("+".($pasos * $intervalo)." days");
                        while ($proximaFecha <= $ahora) {
                            $proximaFecha->modify("+{$intervalo} days");
                        }
                        break;

                    case 'Semanal':
                        $freqSemana = (int)$row['FrecuenciaSem'];
                        if($freqSemana <= 0) return null;

                        $diasHabilitados = [];
                        if ($row['RepetirLunes']) $diasHabilitados[] = 1;
                        if ($row['RepetirMartes']) $diasHabilitados[] = 2;
                        if ($row['RepetirMiercoles']) $diasHabilitados[] = 3;
                        if ($row['RepetirJueves']) $diasHabilitados[] = 4;
                        if ($row['RepetirViernes']) $diasHabilitados[] = 5;
                        if ($row['RepetirSabado']) $diasHabilitados[] = 6;
                        if ($row['RepetirDomingo']) $diasHabilitados[] = 7;

                        if (empty($diasHabilitados)) return null;

                        $buscando = clone $ahora;
                        $encontrado = false;

                        // Buscamos en los próximos 365 días
                        for ($i = 0; $i < 365; $i++) {
                            $diaSemanaActual = (int)$buscando->format('N');
                            
                            if (in_array($diaSemanaActual, $diasHabilitados)) {
                                // Revisar semanas transcurridas
                                // NOTA: Para simplificar, aquí usamos diff en días / 7
                                $diferenciaDias = $inicio->diff($buscando)->days;
                                // Ajuste para asegurar que contamos semanas completas desde inicio
                                $semanasPasadas = floor($diferenciaDias / 7);
                                
                                if ($semanasPasadas % $freqSemana === 0) {
                                    // Verificar hora
                                    $candidato = clone $buscando;
                                    $horaOriginal = explode(':', $inicio->format('H:i:s'));
                                    $candidato->setTime($horaOriginal[0], $horaOriginal[1], $horaOriginal[2]);
                                    
                                    if ($candidato > $ahora) {
                                        $proximaFecha = $candidato;
                                        $encontrado = true;
                                        break;
                                    }
                                }
                            }
                            $buscando->modify('+1 day');
                        }
                        if (!$encontrado) return null;
                        break;
                }

                if ($fin && $proximaFecha > $fin) return null;

                return $proximaFecha;

            } catch (Exception $e) {
                return null; // Si falla el cálculo, retornamos null para no romper el flujo
            }
        }
    }
?>