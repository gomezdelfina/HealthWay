<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');
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

        public static function getUsuarioByUserPsw($user)
        {
            if (is_null($user)) {
                return [];
            }

            try{
                $clave_hasheada = hash('sha256', $user['Clave']); 

                ConexionDb::connect();

                $sql = "SELECT IdUsuario FROM usuarios WHERE Usuario = :user 
                        AND Clave = :psw AND Habilitado = 1";

                $params = [
                    ["clave" => ":user", "valor" => $user['Usuario']],
                    ["clave" => ":psw", "valor" => $clave_hasheada]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception) {
                return [];
            }
        }

        public static function getUsuarioByToken($token)
        {
            if (is_null($token)) {
                throw new Exception();
            }

            try{
                ConexionDb::connect();

                $sql = "SELECT IdUsuario FROM usuarios WHERE token_recuperacion = :token
                        AND Habilitado = 1";

                $params = [
                    ["clave" => ":token", "valor" => $token]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener el usuario por token: " . $e);
            }
        }

        public static function updateTokenSesion($token)
        {
            if (is_null($token)) {
                throw new Exception();
            }

            try{
                ConexionDb::connect();

                $sql = "UPDATE usuarios SET token_recuperacion = :token_recuperacion, token_expiracion = :token_expiracion 
                        WHERE email = :email";

                $params = [
                    ["clave" => ":email", "valor" => $token['email']],
                    ["clave" => ":token_recuperacion", "valor" => $token['token']],
                    ["clave" => ":token_expiracion", "valor" => $token['expiracion']]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception $e) {
                throw new Exception("Problemas al guardar el token: " . $e);
            }
        }

        public static function updatePassword($newPass)
        {
            if (is_null($newPass)) {
                throw new Exception();
            }

            try{
                $dateNow = date('Y-m-d H:i:s');

                ConexionDb::connect();

                $sql = "UPDATE usuarios SET Clave = SHA2(:clave,256)
                        WHERE token_recuperacion = :token AND token_expiracion > :dateNow";

                $params = [
                    ["clave" => ":token", "valor" => $newPass['token']],
                    ["clave" => ":clave", "valor" => $newPass['clave']],
                    ["clave" => ":dateNow", "valor" => $dateNow],
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();

                return $result;
                
            } catch (Exception $e) {
                throw new Exception("Problemas al restaurar clave: " . $e);
            }
        }
        
    }

?>