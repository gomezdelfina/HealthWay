<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Permisos
    {
        public static function tienePermiso($idPermiso, $idUsuario)
        {
            if (is_null($idPermiso) || is_null($idUsuario)) {
                return false;
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT 1 
                        FROM permisos T0
                        INNER JOIN roles_permisos T1 ON T1.IdPermiso = T0.IdPermiso
                        INNER JOIN roles_usuarios T2 ON T2.IdRol = T1.IdRol
                        WHERE T2.IdUsuario = :idUsuario 
                        AND T0.IdPermiso = :idPermiso 
                        LIMIT 1";

                $params = [
                    ["clave" => ":idUsuario", "valor" => $idUsuario],
                    ["clave" => ":idPermiso", "valor" => $idPermiso]
                ];

                $result = ConexionDb::consult($sql, $params);

                if($result){
                    return true;
                }else{
                    return false;
                }

            } catch (Exception) {

                return false;
                
            } finally {

                ConexionDb::disconnect();

            }
            
        }

        public static function getPermisosByIdUser($idUsuario)
        {
            if (is_null($idUsuario)) {
                return [];
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT T0.*
                    FROM permisos T0
                    INNER JOIN roles_permisos T1 ON T1.IdPermiso = T0.IdPermiso
                    INNER JOIN roles_usuarios T2 ON T2.IdRol = T1.IdRol
                    WHERE T2.IdUsuario = :idUsuario;
                ";

                $params = [
                    ["clave" => ":idUsuario", "valor" => $idUsuario]
                ];

                $result = ConexionDb::consult($sql, $params);   
                
                return $result;
            } catch (Exception) {
                return [];
            } finally {

                ConexionDb::disconnect();

            }
        }

        public static function getRolByPermiso($idPermiso)
        {
            if (is_null($idPermiso)) {
                throw new Exception("El campo Id Permiso no puede estar vacío");
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT DISTINCT T1.IdRol, T2.DescRol
                        FROM roles_permisos T1
                        INNER JOIN roles T2 ON T1.IdRol = T2.IdRol
                        WHERE T1.IdPermiso = :idPermiso;
                ";

                $params = [
                    ["clave" => ":idPermiso", "valor" => $idPermiso]
                ];

                $result = ConexionDb::consult($sql, $params);   
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener el rol segun el permiso: " . $e);
            } finally {
                ConexionDb::disconnect();
            }
        }
    }
?>