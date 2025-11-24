<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Notificaciones
    {
        public static function crear($rolDestino, $evento, $mensaje)
        {
            global $conn;
            
            ConexionDb::connect();

            $stmt = $conn->prepare("
                INSERT INTO notificaciones (rol_destino, evento, mensaje)
                VALUES (:rol, :evento, :mensaje)
            ");

            return $stmt->execute([
                ':rol' => $rolDestino,
                ':evento' => $evento,
                ':mensaje' => $mensaje
            ]);
        }

        public static function listarPorRol($rolDestino)
        {
            try {
                global $conn;
                ConexionDb::connect(); // tu método de conexión por PDO

                $stmt = $conn->prepare("
                    SELECT *
                    FROM notificaciones
                    WHERE rol_destino = :rol
                    ORDER BY fecha DESC
                ");

                $stmt->execute([
                    ':rol' => $rolDestino
                ]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (Exception $e) {
                error_log("Error en listarPorRol(): " . $e->getMessage());
                return [];
            }
        }

        public static function obtenerNoLeidas($rol)
        {
            global $conn;
            
            ConexionDb::connect();

            $stmt = $conn->prepare("
                SELECT id, rol_destino, evento, mensaje, fecha
                FROM notificaciones
                WHERE rol_destino = :rol
                AND leido = 0
            ");

            $stmt->execute([":rol" => $rol]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function marcarLeida($id)
        {
            global $conn;

            ConexionDb::connect();

            $stmt = $conn->prepare("
                UPDATE notificaciones
                SET leido = 1
                WHERE id = :id
            ");

            return $stmt->execute([":id" => $id]);
        }
    }
?>