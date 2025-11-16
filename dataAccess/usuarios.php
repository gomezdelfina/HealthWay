<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Usuarios
    {
        public static function getUsuarioById($idUsuario)
        {
            if (is_null($idUsuario)) {
                return [];
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT T0.* FROM usuarios T0 WHERE T0.IdUsuario = :idUser";

                $params = [
                    ["clave" => ":idUser", "valor" => $idUsuario]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception) {
                return [];
            }
        }

        public static function getUsuarioByEmail($email)
        {
            if (is_null($email)) {
                return [];
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT T0.* FROM usuarios T0 WHERE T0.Email = :email";

                $params = [
                    ["clave" => ":email", "valor" => $email]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception) {
                return [];
            }
        }
    }

?>