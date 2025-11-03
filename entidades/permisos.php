<?php

require_once($dirBase . '/conexiones/conectorMySQL.php');

class Permisos
{
    public static function tienePermiso($idPermiso, $idUsuario)
    {
        if (is_null($idPermiso) || is_null($idUsuario)) {
            return false;
        }

        try{
            $sql = "
                SELECT 1 
                FROM permisos
                INNER JOIN roles_permisos ON roles_permisos.id_permiso = permisos.id
                INNER JOIN roles_usuarios ON roles_usuarios.id_rol = roles_permisos.id_rol
                WHERE roles_usuarios.id_usuario = :idUsuario 
                AND permisos.id_permiso = :idPermiso 
                LIMIT 1;
            ";

            $params = [
                ["clave" => ":idUsuario", "valor" => $idUsuario],
                ["clave" => ":idPermiso", "valor" => $idPermiso]
            ];

            $params = ["clave" => ":idUsuario", "valor" => $idUsuario];

            $result = ConexionDb::consult($sql, $params);

            return true;
        } catch (Exception) {
            return false;
        }
        
    }

    public static function getPermisos($idUsuario)
    {
        if (is_null($idUsuario)) {
            return [];
        }

        try{
            $sql = "
                SELECT permisos.nombre
                FROM permisos
                INNER JOIN roles_permisos ON roles_permisos.id_permiso = permisos.id
                INNER JOIN roles_usuarios ON roles_usuarios.id_rol = roles_permisos.id_rol
                WHERE roles_usuarios.id_usuario = :idUsuario;
            ";

            $params = ["clave" => ":idUsuario", "valor" => $idUsuario];

            $result = ConexionDb::consult($sql, $params);

            return $result;
        } catch (Exception) {
            return [];
        }
    }
}
