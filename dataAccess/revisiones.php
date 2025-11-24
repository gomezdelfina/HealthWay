<?php

use Dba\Connection;

    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Revisiones
    {
        public static function getRevisiones()
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdRevisiones, T2.NumeroHabitacion, T3.NumeroCama, T5.Nombre, T5.Apellido,
                    T0.FechaCreacion, T0.TipoRevision, T0.EstadoRevision
                    FROM revisiones T0
                    INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                    INNER JOIN habitaciones T2 ON T1.IdHabitacion = T2.IdHabitacion
                    INNER JOIN camas T3 ON T1.IdCama = T3.IdCama
                    INNER JOIN pacientes T4 ON T1.IdPaciente = T4.IdPaciente
                    INNER JOIN usuarios T5 on T4.IdUsuario = T5.IdUsuario
                    ORDER BY T0.IdRevisiones DESC
                    LIMIT 30;
                ";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Revisiones: " . $e);
            }
        }

        public static function getTiposRevByUser($idUser)
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdTipoRevision, T0.DescTipoRevision
                        FROM tiporevisiones T0
                        WHERE T0.IdTipoRevision IN (
                            SELECT 
                            CASE T.idPermiso 
                                WHEN 13 THEN 1 
                                WHEN 19 THEN 1
                                WHEN 14 THEN 2
                                WHEN 20 THEN 2
                                WHEN 15 THEN 3
                                WHEN 21 THEN 3
                                WHEN 16 THEN 4
                                WHEN 22 THEN 4
                                WHEN 17 THEN 5
                                WHEN 23 THEN 5
                                WHEN 18 THEN 6
                                WHEN 24 THEN 6
                                WHEN 25 THEN 7
                                WHEN 26 THEN 7
                            END AS idTipoRevision
                            FROM roles_permisos T
                            INNER JOIN roles_usuarios TT on T.IdRol = TT.IdRol
                            WHERE TT.IdUsuario = :idUser)";
                
                $params = [
                    ['clave' => ':idUser', 'valor' => $idUser]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Tipos de Revisiones por Usuario: " . $e);
            }
        }

        public static function getEstadosRevByUser($idUser)
        {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdEstadoRev, T0.DescEstadoRev 
                        FROM estadorevisiones T0
                        WHERE T0.IdEstadoRev IN (
                            SELECT 
                            CASE T.idPermiso 
                                WHEN 27 THEN 1 
                                WHEN 38 THEN 1
                                WHEN 29 THEN 2
                                WHEN 37 THEN 2
                                WHEN 28 THEN 3
                                WHEN 36 THEN 3
                                WHEN 30 THEN 4
                                WHEN 35 THEN 4
                                WHEN 31 THEN 5
                                WHEN 34 THEN 5
                                WHEN 32 THEN 6
                                WHEN 33 THEN 6
                            END AS idEstadoRev
                            FROM roles_permisos T
                            INNER JOIN roles_usuarios TT on T.IdRol = TT.IdRol
                            WHERE TT.IdUsuario = :idUser)";

                $params = [
                    ['clave' => ':idUser', 'valor' => $idUser]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Estados de Revisiones por Usuario: " . $e);
            }
        }   

        public static function createRevision($revision)
        {
            if (is_null($revision)) {
                return [];
            }

            try{
                ConexionDb::connect();

                $sql = "INSERT INTO `revisiones`(`IdInternacion`, `IdUsuario`, `FechaCreacion`, 
                        `TipoRevision`, `EstadoRevision`, `Sintomas`, `Diagnostico`, `Tratamiento`, `Observaciones`) 
                        VALUES (:idInternacion,:idUsuario,:fechaCreacion,:tipoRevision,:estadoRevision,
                        :sintomas,:diagnostico,:tratamiento,:observaciones);";

                $params = [
                    ["clave" => ":idInternacion", "valor" => $revision['IdInternacion']],
                    ["clave" => ":idUsuario", "valor" => $revision['IdUsuario']],
                    ["clave" => ":fechaCreacion", "valor" => $revision["FechaCreacion"]],
                    ["clave" => ":tipoRevision", "valor" => $revision["TipoRevision"]],
                    ["clave" => ":estadoRevision", "valor" => $revision["EstadoRevision"]],
                    ["clave" => ":sintomas", "valor" => $revision["Sintomas"]],
                    ["clave" => ":diagnostico", "valor" => $revision["Diagnostico"]],
                    ["clave" => ":tratamiento", "valor" => $revision["Tratamiento"]],
                    ["clave" => ":observaciones", "valor" => $revision["Observaciones"]],
                ];

                $result = ConexionDb::consult($sql,$params);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los Tipos de Revisiones por Rol: " . $e);
            }
        }

        public static function getRevisionById($idRev)
        {
            try{
                if(is_null($idRev)){
                    throw new Exception("El campo Id Revision no puede star vacío");
                }

                ConexionDb::connect();

                $sql = 'SELECT T0.IdRevisiones, T0.IdUsuario, CAST(T0.FechaCreacion AS DATE) AS FechaCreacion,
                        CAST(T0.FechaCreacion AS TIME) AS HoraCreacion, T0.TipoRevision, T0.EstadoRevision, 
                        T0.Sintomas, T0.Diagnostico, T0.Tratamiento, T2.IdPaciente
                        FROM revisiones T0 
                        INNER JOIN internaciones T1 ON T1.IdInternacion = T0.IdInternacion
                        INNER JOIN pacientes T2 ON T1.IdPaciente = T2.IdPaciente
                        WHERE T0.IdRevisiones = :idRev;';

                $params = [
                    ['clave' => ':idRev', 'valor' => $idRev]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al obtener la revision por ID: " . $e);
            }
        }

        public static function getRevisionByInter($idInt)
        {
            try{
                if(is_null($idInt)){
                    throw new Exception("El campo Id Internacion no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "SELECT T0.IdRevisiones, T0.FechaCreacion, T1.DescTipoRevision as Tipo,
                        T2.DescEstadoRev as Estado, T0.Sintomas, T0.Diagnostico, T0.Tratamiento, T0.Observaciones,
                        CONCAT(T3.Nombre, ' ', T3.Apellido) as UsuarioCreador
                        FROM revisiones T0 
                        INNER JOIN tiporevisiones T1 ON T0.TipoRevision = T1.idTipoRevision
                        INNER JOIN estadorevisiones T2 ON T0.EstadoRevision = T2.idEstadoRev
                        INNER JOIN usuarios T3 ON T0.IdUsuario = T3.idUsuario
                        WHERE T0.IdInternacion = :idInt
                        ORDER BY T0.FechaCreacion DESC;";
                $params = [
                    ['clave' => ':idInt', 'valor' => $idInt]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al obtener las revisiones por internacion: " . $e);
            }
        }

        public static function editRevision($revision){
            try{
                if (is_null($revision)) {
                    throw new Exception("El campo IdRevision no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = 'UPDATE revisiones SET
                            TipoRevision = :tipoRevision
                            EstadoRevision = :estadoRevision
                            Sintomas = :sintomas
                            Diagnostico = :diagnostico
                            Tratamiento = :tratamiento
                            Notas = :notas
                        WHERE IdRevisiones = :idRev';

                $params = [
                    ["clave" => ":idRev", "valor" => $revision['IdRevision']],
                    ["clave" => ":tipoRevision", "valor" => $revision["TipoRevision"]],
                    ["clave" => ":estadoRevision", "valor" => $revision["EstadoRevision"]],
                    ["clave" => ":sintomas", "valor" => $revision["Sintomas"]],
                    ["clave" => ":diagnostico", "valor" => $revision["Diagnostico"]],
                    ["clave" => ":tratamiento", "valor" => $revision["Tratamiento"]],
                    ["clave" => ":observaciones", "valor" => $revision["Observaciones"]]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar la revision: " . $e);
            }
        }
    }
?>