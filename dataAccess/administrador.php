<?php

global $dirBaseFile;
require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

class UsuariosDataAccess
{
    // --------------------------------------------------------------------
    //  OBTENER ROLES
    // --------------------------------------------------------------------
    public static function getRoles()
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "SELECT DescRol FROM Roles ORDER BY DescRol ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return [
                "status" => "success",
                "data"   => $data
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }

    // --------------------------------------------------------------------
    //  OBTENER ID DE ROL
    // --------------------------------------------------------------------
    public static function getRoleId($desc)
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "SELECT IdRol FROM Roles WHERE DescRol = :desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute([":desc" => $desc]);

            $data = $stmt->fetch(PDO::FETCH_COLUMN);

            if ($data)
                return ["status" => "success", "data" => $data];

            return [
                "status" => "error",
                "mensaje" => "Rol no encontrado"
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }

    // --------------------------------------------------------------------
    //  LISTAR USUARIOS
    // --------------------------------------------------------------------
    public static function getUsuarios($search = null)
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "SELECT 
                u.IdUsuario,
                u.Nombre,
                u.Apellido,
                u.Email,
                u.Usuario,
                u.Habilitado,
                u.Telefono,
                r.DescRol
            FROM usuarios u
            INNER JOIN roles_usuarios ru ON ru.IdUsuario = u.IdUsuario
            INNER JOIN roles r ON r.IdRol = ru.IdRol";

            $params = [];

            if ($search) {
                $sql .= " WHERE u.Nombre LIKE :s 
                       OR u.Apellido LIKE :s 
                       OR u.Email LIKE :s 
                       OR u.Usuario LIKE :s 
                       OR r.DescRol LIKE :s";

                $params = [":s" => "%$search%"];
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => "success",
                "data"   => $data
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }

    // --------------------------------------------------------------------
    //  CREAR USUARIO
    // --------------------------------------------------------------------
    public static function crearUsuario($data)
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "INSERT INTO usuarios
                    (IdRol, Usuario, Clave, Habilitado, Nombre, Apellido, Email, Telefono)
                    VALUES (:rol, :user, :clave, 1, :nombre, :apellido, :email, :tel)";

            $stmt = $conn->prepare($sql);

            $params = [
                ":rol"      => $data["user-role"],
                ":user"     => $data["user-username"],
                ":clave"    => password_hash($data["user-password"], PASSWORD_DEFAULT),
                ":nombre"   => $data["user-name"],
                ":apellido" => $data["user-lastname"],
                ":email"    => $data["user-email"],
                ":tel"      => $data["user-phone"]
            ];

            $stmt->execute($params);

            return [
                "status" => "success",
                "mensaje" => "Usuario creado correctamente"
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }


    // --------------------------------------------------------------------
    //  ACTUALIZAR USUARIO
    // --------------------------------------------------------------------
    public static function actualizarUsuario($id, $data)
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "UPDATE usuarios SET
                        IdRol = :rol,
                        Usuario = :user,
                        Habilitado = :hab,
                        Nombre = :nombre,
                        Apellido = :apellido,
                        Email = :email,
                        Telefono = :tel";

            $params = [
                ":rol"      => $data["user-role"],
                ":user"     => $data["user-username"],
                ":hab"      => isset($data["user-habilitado"]) ? 1 : 0,
                ":nombre"   => $data["user-name"],
                ":apellido" => $data["user-lastname"],
                ":email"    => $data["user-email"],
                ":tel"      => $data["user-phone"]
            ];

            // Agregar contraseña si viene en edición
            if (!empty($data["user-password"])) {
                $sql .= ", Clave = :clave";
                $params[":clave"] = password_hash($data["user-password"], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE IdUsuario = :id";
            $params[":id"] = $id;

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            return [
                "status" => "success",
                "mensaje" => "Usuario actualizado correctamente"
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }

    // --------------------------------------------------------------------
    //  BAJA LÓGICA
    // --------------------------------------------------------------------
    public static function eliminarUsuario($id)
    {
        try {
            global $conn;
            ConexionDb::connect();

            $sql = "UPDATE usuarios SET Habilitado = 0 WHERE IdUsuario = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([":id" => $id]);

            return [
                "status" => "success",
                "mensaje" => "Usuario deshabilitado correctamente"
            ];

        } catch (Exception $e) {

            return [
                "status"  => "error",
                "mensaje" => "Error DB: " . $e->getMessage()
            ];

        } finally {
            ConexionDb::disconnect();
        }
    }
}
