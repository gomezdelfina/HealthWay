<?php

require_once($dirBase . '/conexiones/conectorMySQL.php');

class Usuarios
{
    public static function getUsuarioById($idUsuario)
    {
        if (is_null($idUsuario)) {
            return [];
        }

        try{

            $sql = "SELECT T0.*, T1.DescRol 
                        FROM usuarios T0 
                        INNER JOIN roles T1 ON T0.IdRol = T1.IdRol 
                        WHERE T0.IdUsuario = :idUser";

            $params = ["clave" => ":idUser", "valor" => $idUsuario];

            $result = ConexionDb::consult($sql, $params);

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

            $sql = "SELECT T0.*, T1.DescRol 
                        FROM usuarios T0 
                        INNER JOIN roles T1 ON T0.IdRol = T1.IdRol 
                        WHERE T0.Email = :email";

            $params = ["clave" => ":email", "valor" => $email];

            $result = ConexionDb::consult($sql, $params);

            ConexionDb::disconnect();

            return $result;
            
        } catch (Exception) {
            return [];
        }
    }
}
