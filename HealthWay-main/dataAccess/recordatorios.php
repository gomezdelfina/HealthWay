<?php

use Dba\Connection;

    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    class Recordatorio
    {
        public static function getRecordatorios()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.*, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
                    ORDER BY T0.IdRecordatorio DESC
                    LIMIT 30;
                ";

                $result = ConexionDb::consult($sql);

                foreach ($result as $key => $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha != null & $result[$key]['activo'] == 1) {
                        $result[$key]['ProximaEjecucion'] = $objFecha;
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios: " . $e);
            }
        }

        public static function getRecordatoriosActivos()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.*, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
                    WHERE activo = 1
                    ORDER BY T0.IdRecordatorio DESC
                    LIMIT 30;
                ";

                $result = ConexionDb::consult($sql);

                foreach ($result as $key => $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha != null & $result[$key]['activo'] == 1) {
                        $result[$key]['ProximaEjecucion'] = $objFecha;
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios: " . $e);
            }
        }

        public static function getRecordatoriosActivosByUser($userId)
        {
            try{
                if (is_null($userId)) {
                    throw new Exception("El campo Id Usuario no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.*, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador,T0.Observaciones
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
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
                    WHERE activo = 1 
                    AND (T0.FechaFinRec IS NULL OR T0.FechaFinRec >= CURDATE())
                    ORDER BY T0.IdRecordatorio DESC;";

                $params = [
                    ['clave' => ':idUser', 'valor' => $userId]
                ];

                $result = ConexionDb::consult($sql,$params);

                foreach ($result as $key => $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha != null & $result[$key]['activo'] == 1) {
                        $result[$key]['ProximaEjecucion'] = $objFecha;
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();

                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios pendientes: " . $e);
            }
        }

        public static function getRecordatoriosByInt($idInt)
        {
            try{
                if (is_null($idInt)) {
                    throw new Exception("El Id Internacion no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.IdRecordatorio, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T0.FechaCreacion, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
                    WHERE T0.IdInternacion = :idInt
                    ORDER BY T0.IdRecordatorio DESC
                    LIMIT 30;
                ";

                $params = [
                    ["clave" => ":idInt", "valor" => $idInt]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Recordatorios por internacion: " . $e);
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
                throw new Exception("Problemas al editar el recordatorio: " . $e);
            }
        }

        public static function inactivarRecordatorio($idRec, $act){
            try{
                if(is_null($idRec)){
                    throw new Exception("El Id Recordatorio no puede estar vacío");
                }elseif(is_null($act)){
                    throw new Exception("El activo no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "UPDATE `recordatorio` 
                        SET `activo` = :activo
                        WHERE `IdRecordatorio` = :idRec";

                $params = [
                    ['clave' => ':idRec', 'valor' => $idRec],
                    ['clave' => ':activo', 'valor' => $act],
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar el recordatorio: " . $e);
            }
        }

        public static function editRecordatorioEstado($recordatorio){
            try{
                if(is_null($recordatorio)){
                    throw new Exception("El Recordatorio no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "UPDATE `recordatorio` 
                        SET `Estado` = :estado
                        WHERE `IdRecordatorio` = :idRec";

                $params = [
                    ['clave' => ':idRec', 'valor' => $recordatorio['IdRecordatorio']],
                    ['clave' => ':estado', 'valor' => $recordatorio['Estado']]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar estado del recordatorio: " . $e);
            }
        }

        public static function getRecordatoriosAtrasados($userId)
        {
            try{
                if (is_null($userId)) {
                    throw new Exception("El campo Id Usuario no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.*, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
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

                foreach ($result as $key => $row) {
                    $objFecha = self::calcularProximaFecha($row);
                    
                    if ($objFecha != null & $result[$key]['activo'] == 1) {
                        $result[$key]['ProximaEjecucion'] = $objFecha;
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();
                
                $resultadosHoy = [];
                $fechaHoy = date('Y-m-d');

                foreach ($result as $row) {
                    $proximaFecha = $row['ProximaEjecucion'] == null ? null : date('Y-m-d', strtotime($row['ProximaEjecucion']));
                    if ($proximaFecha  === $fechaHoy) {
                        $resultadosHoy[] = $row;
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

                $sql = "SELECT T0.*, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T6.DescTipoRevision, T8.Nombre as NombreCreador, T8.Apellido as ApellidoCreador,T0.Observaciones
                    FROM recordatorio T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    INNER JOIN tiporevisiones T6 ON T0.TipoRevision = T6.IdTipoRevision
                    INNER JOIN usuarios T8 ON T0.IdUsuario = T8.IdUsuario
                    WHERE T0.Estado = 'Pendiente'
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
                    
                    if ($objFecha != null & $result[$key]['activo'] == 1) {
                        $result[$key]['ProximaEjecucion'] = $objFecha;
                    } else {
                        $result[$key]['ProximaEjecucion'] = null;
                    }
                }

                ConexionDb::disconnect();
                
                $resultadosHoy = [];
                $fechaHoy = date('Y-m-d');

                foreach ($result as $row) {
                    $proximaFecha = $row['ProximaEjecucion'] == null ? null : date('Y-m-d', strtotime($row['ProximaEjecucion']));
                    if ($proximaFecha  === $fechaHoy) {
                        $resultadosHoy[] = $row;
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
                $fin = $row['FechaFinRec'] != '0000-00-00 00:00:00' ? new DateTime($row['FechaFinRec']) : null;
                $frecuencia = $row['Frecuencia'];

                if ($inicio > $ahora) return $inicio;

                $proximaFecha = clone $inicio;

                switch ($frecuencia) {
                    case 'Unica Vez':
                        if ($inicio->format('Y-m-d H:i') < $ahora->format('Y-m-d H:i')) return null;
                        return $inicio->format('Y-m-d H:i');

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
                                $diferenciaDias = $inicio->diff($buscando)->days;
                                // Ajuste para asegurar que contamos semanas completas desde inicio
                                $semanasPasadas = floor($diferenciaDias / 7);
                                
                                if ($semanasPasadas % $freqSemana === 0) {
                                    // Verificar hora
                                    $candidato = clone $buscando;
                                    $horaOriginal = explode(':', $inicio->format('H:i'));
                                    $candidato->setTime($horaOriginal[0], $horaOriginal[1], 0);
                                    
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

                return $proximaFecha->format('Y-m-d H:i');

            } catch (Exception $e) {
                return null;
            }
        }

        private static function calcularUltimaFecha($row) {
            try {
                $ahora = new DateTime();
                $inicio = new DateTime($row['FechaInicioRec']);
                $fin = $row['FechaFinRec'] != '0000-00-00 00:00:00' ? new DateTime($row['FechaFinRec']) : null;
                $frecuencia = $row['Frecuencia'];

                // Si la fecha de inicio es en el futuro, no hay "última ejecución" todavía.
                if ($inicio > $ahora) return null;

                $ultimaFecha = null;

                switch ($frecuencia) {
                    case 'Unica Vez':
                        // Si ya pasó la fecha de inicio, esa fue la última y única.
                        if ($inicio <= $ahora) {
                            $ultimaFecha = clone $inicio;
                        }
                        break;

                    case 'Horas':
                        $intervalo = (int)$row['FrecuenciaHoras'];
                        if ($intervalo <= 0) return null;

                        // Calculamos cuántos intervalos han pasado matemáticamente
                        $diffSegundos = $ahora->getTimestamp() - $inicio->getTimestamp();
                        $pasos = floor($diffSegundos / ($intervalo * 3600));
                        
                        $ultimaFecha = clone $inicio;
                        $horasTotal = $pasos * $intervalo;
                        $ultimaFecha->modify("+{$horasTotal} hours");
                        break;

                    case 'Diaria':
                        $intervalo = (int)$row['FrecuenciaDias'];
                        if ($intervalo <= 0) return null;

                        // Calculamos pasos basado en días
                        $diffDias = $ahora->diff($inicio)->days;
                        $pasos = floor($diffDias / $intervalo);
                        
                        $ultimaFecha = clone $inicio;
                        $diasTotal = $pasos * $intervalo;
                        $ultimaFecha->modify("+{$diasTotal} days");

                        // Al sumar días, la hora se mantiene. 
                        // Si la hora resultante es mayor a "ahora" entonces la última ejecución real fue hace un intervalo atrás.
                        if ($ultimaFecha > $ahora) {
                            $ultimaFecha->modify("-{$intervalo} days");
                        }
                        break;

                    case 'Semanal':
                        $freqSemana = (int)$row['FrecuenciaSem'];
                        if ($freqSemana <= 0) return null;

                        $diasHabilitados = [];
                        if ($row['RepetirLunes']) $diasHabilitados[] = 1;
                        if ($row['RepetirMartes']) $diasHabilitados[] = 2;
                        if ($row['RepetirMiercoles']) $diasHabilitados[] = 3;
                        if ($row['RepetirJueves']) $diasHabilitados[] = 4;
                        if ($row['RepetirViernes']) $diasHabilitados[] = 5;
                        if ($row['RepetirSabado']) $diasHabilitados[] = 6;
                        if ($row['RepetirDomingo']) $diasHabilitados[] = 7;

                        if (empty($diasHabilitados)) return null;

                        // Empezamos a buscar desde HOY hacia el PASADO
                        $buscando = clone $ahora;
                        
                        // Forzamos la hora del evento para comparar correctamente
                        $horaInicio = explode(':', $inicio->format('H:i'));
                        $buscando->setTime($horaInicio[0], $horaInicio[1], 0);

                        // Si ajustando la hora resultamos en el futuro (ej: hoy a las 18:00, pero son las 15:00),
                        // hoy no cuenta como "pasado", empezamos a buscar desde ayer.
                        if ($buscando > $ahora) {
                            $buscando->modify('-1 day');
                        }

                        $encontrado = false;
                        // Buscamos hacia atrás hasta llegar a la fecha de inicio o un límite (1 año)
                        for ($i = 0; $i < 366; $i++) {
                            if ($buscando < $inicio) break; // No podemos ir antes del inicio

                            $diaSemanaActual = (int)$buscando->format('N');

                            if (in_array($diaSemanaActual, $diasHabilitados)) {
                                // Verificar la frecuencia de semanas (paridad)
                                $diferenciaDias = $inicio->diff($buscando)->days;
                                $semanasPasadas = floor($diferenciaDias / 7);

                                if ($semanasPasadas % $freqSemana === 0) {
                                    $ultimaFecha = clone $buscando;
                                    $encontrado = true;
                                    break;
                                }
                            }
                            $buscando->modify('-1 day');
                        }
                        if (!$encontrado) return null;
                        break;
                }

                // Si existe fecha fin y la última calculada es mayor a esa fecha fin, entonces el recordatorio ya expiró. 
                if ($ultimaFecha && $fin && $ultimaFecha > $fin) {
                    return $fin; 
                }

                return $ultimaFecha;

            } catch (Exception $e) {
                return null;
            }
        }
    }
?>