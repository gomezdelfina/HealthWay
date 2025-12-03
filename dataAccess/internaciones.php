<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class internaciones
    {

        /* ============================================
            BUSCAR INTERNACIÓN
        ============================================ */
        public static function BuscarInternacion($busqueda) {

            if ($busqueda === '') return [];

            try {
                global $conn;
                $conn = ConexionDb::connect();

                $sql = "
                    SELECT 
                        i.IdInternacion,
                        i.IdCama,
                        i.IdHabitacion,
                        i.EstadoInternacion,
                        CONCAT(u.Nombre, ' ', u.Apellido) AS NombrePaciente
                    FROM internaciones i
                    INNER JOIN pacientes p ON i.IdPaciente = p.IdPaciente
                    INNER JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                    WHERE CONCAT(u.Nombre, ' ', u.Apellido) LIKE :busqueda
                    OR i.IdCama LIKE :busqueda
                    OR i.IdHabitacion LIKE :busqueda
                    ORDER BY i.IdInternacion DESC
                ";

                return ConexionDb::consult($sql, [
                    [ "clave" => ":busqueda", "valor" => "%$busqueda%" ]
                ]);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            REGISTRAR INTERNACIÓN
        ============================================ */
        public static function RegistrarInternacion($paciente, $solicitud, $estado, $habitacion, $cama, $fechaInicio, $fechaFin) {

            try {
                global $dirBaseFile, $conn;

                ConexionDb::connect();

                if (!$conn) {
                    error_log("❌ ERROR: Conexión a BD falló en RegistrarInternacion()");
                    return "No se pudo conectar a la base de datos";
                }

                $conn->beginTransaction();

                /* 1️⃣ Insertar internación */
                $stmt = $conn->prepare("
                    INSERT INTO internaciones 
                    (IdSolicitud, IdCama, IdHabitacion, IdPaciente, FechaInicio, FechaFin, EstadoInternacion)
                    VALUES 
                    (:solicitud, :cama, :habitacion, :paciente, :inicio, :fin, :estado)
                ");

                $stmt->execute([
                    ":solicitud" => $solicitud,
                    ":cama" => $cama,
                    ":habitacion" => $habitacion,
                    ":paciente" => $paciente,
                    ":inicio" => $fechaInicio,
                    ":fin" => $fechaFin,
                    ":estado" => $estado
                ]);

                $idInternacion = $conn->lastInsertId();


                /* 2️⃣ Generar QR */
                $url = $dirBaseFile . "/api/MostrarInternacionesQR.php?id=".$idInternacion;

                ob_start();
                QRcode::png($url, null, QR_ECLEVEL_L, 5);
                $qrImage = ob_get_clean();

                $stmtQR = $conn->prepare("
                    UPDATE internaciones 
                    SET qr = :qr
                    WHERE IdInternacion = :id
                ");

                $stmtQR->bindParam(":qr", $qrImage, PDO::PARAM_LOB);
                $stmtQR->bindParam(":id", $idInternacion);
                $stmtQR->execute();


                /* 3️⃣ Ocupa la cama */
                $stmt2 = $conn->prepare("
                    UPDATE camas 
                    SET EstadoCama = 'Ocupada', Habilitada = 0
                    WHERE IdCama = :cama AND EstadoCama = 'Disponible' AND Habilitada = 1
                ");

                $stmt2->execute([':cama' => $cama]);

                if ($stmt2->rowCount() === 0)
                    throw new Exception("La cama ya no está disponible.");


                /* 4️⃣ Cerrar solicitud */
                $stmt3 = $conn->prepare("
                    UPDATE solicitudesinternacion
                    SET EstadoSolicitud = 'Cerrada'
                    WHERE IdSolicitud = :solicitud AND EstadoSolicitud = 'Abierta'
                ");

                $stmt3->execute([':solicitud' => $solicitud]);

                if ($stmt3->rowCount() === 0)
                    throw new Exception("La solicitud ya está cerrada.");


                /* 5️⃣ Cambiar estado del paciente */
                $stmt4 = $conn->prepare("
                    UPDATE pacientes
                    SET Estado = 'Internado'
                    WHERE IdPaciente = :paciente AND Estado = 'Normal'
                ");

                $stmt4->execute([':paciente' => $paciente]);

                if ($stmt4->rowCount() === 0)
                    throw new Exception("El paciente ya estaba internado.");

                $conn->commit();

                require_once(__DIR__ . "/Notificaciones.php");

                Notificaciones::crear(
                    "medico",
                    "Internacion Creada",
                    "Se creó una internación para el paciente ID: $paciente"
                );

                return true;

            } catch (Exception $e) {

                if (isset($conn) && $conn->inTransaction())
                    $conn->rollBack();

                error_log("❌ ERROR RegistrarInternacion(): ".$e->getMessage());
                return $e->getMessage();

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            CAMAS POR HABITACIÓN
        ============================================ */
        public static function InternacionCama($numeroHab) {
            if ($numeroHab === '') return [];

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "
                    SELECT 
                        c.IdCama, 
                        c.NumeroCama
                    FROM camas c
                    JOIN habitaciones h ON c.IdHabitacion = h.IdHabitacion
                    WHERE 
                        (h.IdHabitacion = :numeroHab OR h.NumeroHabitacion = :numeroHab)
                        AND c.EstadoCama = 'Disponible'
                        AND c.Habilitada = 1
                ";

                return ConexionDb::consult($sql, [
                    [ "clave" => ":numeroHab", "valor" => $numeroHab ]
                ]);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            HABITACIONES POR TIPO
        ============================================ */
        public static function InternacionHabitacion($tipo) {
            if (!in_array($tipo, ["Compartida", "Individual"])) return [];

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "
                    SELECT IdHabitacion, NumeroHabitacion
                    FROM habitaciones
                    WHERE TipoHabitacion = :tipo
                ";

                return ConexionDb::consult($sql, [
                    [ "clave" => ":tipo", "valor" => $tipo ]
                ]);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            PACIENTES DISPONIBLES
        ============================================ */
        public static function InternacionPaciente() {
            try {
                global $conn;
                ConexionDb::connect();

                $sql = "
                    SELECT 
                        p.IdPaciente, 
                        u.Nombre, 
                        u.Apellido, 
                        p.DNI
                    FROM pacientes p
                    INNER JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                    WHERE p.Estado = 'Normal'
                ";

                return ConexionDb::consult($sql);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            SOLICITUD POR PACIENTE
        ============================================ */
        public static function InternacionSolicitud($idPaciente) {

            if ($idPaciente === '') return [];

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "
                    SELECT IdSolicitud, TipoSolicitud, EstadoSolicitud, FechaCreacion, MotivoSolicitud
                    FROM solicitudesinternacion
                    WHERE IdPaciente = :idPaciente AND EstadoSolicitud = 'Abierta'
                ";

                return ConexionDb::consult($sql, [
                    [ "clave" => ":idPaciente", "valor" => $idPaciente ]
                ]);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            OBTENER CAMAS (PAGINADO)
        ============================================ */
        public static function ObtenerCamas($pagina = 1, $porPagina = 30) {

            try {
                global $conn;
                ConexionDb::connect();

                $offset = ($pagina - 1) * $porPagina;

                $sql = "
                    SELECT 
                        c.IdCama,
                        c.NumeroCama,
                        c.Habilitada,
                        c.EstadoCama,
                        h.NumeroHabitacion,
                        i.IdInternacion,
                        i.EstadoInternacion,
                        CONCAT(u.Nombre, ' ', u.Apellido) AS NombrePaciente
                    FROM camas c
                    LEFT JOIN habitaciones h ON c.IdHabitacion = h.IdHabitacion
                    LEFT JOIN internaciones i 
                        ON c.IdCama = i.IdCama 
                        AND i.EstadoInternacion IN ('Activa','Reprogramada','Trasladada')
                    LEFT JOIN pacientes p ON i.IdPaciente = p.IdPaciente
                    LEFT JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                    ORDER BY c.NumeroCama ASC
                    LIMIT :offset, :porPagina
                ";

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
                $stmt->execute();

                $camas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $sqlTotal = "SELECT COUNT(*) AS total FROM camas";
                $total = ConexionDb::consult($sqlTotal)[0]["total"];
                $totalPaginas = ceil($total / $porPagina);

                return [
                    "camas" => $camas ?: [],
                    "totalPaginas" => $totalPaginas
                ];

            } catch (Exception $e) {
                throw new Exception("Error al obtener listado de camas: " . $e->getMessage());

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            VER INTERNACIÓN
        ============================================ */
        public static function VerInternacion($id) {

            if (!is_numeric($id) || $id <= 0) return [];

            try {
                global $conn;

                ConexionDb::connect();

                $sql = "
                    SELECT 
                        i.IdInternacion,
                        i.IdPaciente,
                        i.IdCama,
                        i.IdHabitacion,
                        i.FechaInicio,
                        i.FechaFin,
                        i.EstadoInternacion,
                        i.Observaciones,
                        CONCAT(u.Nombre, ' ', u.Apellido) AS NombrePaciente
                    FROM internaciones i
                    INNER JOIN pacientes p ON i.IdPaciente = p.IdPaciente
                    INNER JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                    WHERE i.IdInternacion = :id
                ";

                return ConexionDb::consultOne($sql, [
                    [ "clave" => ":id", "valor" => $id ]
                ]);

            } catch (Exception $e) {
                return [];

            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================
            OBTENER QR
        ============================================ */
        public static function ObtenerQR($id) {

            try {
                global $conn;

                ConexionDb::connect();

                $stmt = $conn->prepare("
                    SELECT qr 
                    FROM internaciones 
                    WHERE IdInternacion = :id
                ");

                $stmt->execute([":id" => $id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row && !empty($row["qr"])) {
                    return [
                        "status" => "success",
                        "qr" => $row["qr"]
                    ];
                }

                return [
                    "status" => "error",
                    "mensaje" => "QR no encontrado"
                ];

            } catch (PDOException $e) {
                return [
                    "status" => "error",
                    "mensaje" => "Error en la base de datos: " . $e->getMessage()
                ];

            } finally {
                ConexionDb::disconnect();
            }
        }

        public static function VerInternacionActivaByPac($idPaciente) {
            try {
                global $conn;
                ConexionDb::connect();

                $sql = "SELECT * from internaciones i
                        WHERE i.EstadoInternacion =  'Activa'
                        AND i.IdPaciente = :id;";

                $stmt = $conn->prepare($sql);
                $stmt->execute([":id" => $idPaciente]);

                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data)
                    return [ "status" => "success", "data" => $data ];

                return [
                    "status" => "error",
                    "mensaje" => "Internación no encontrada"
                ];
            } catch (Exception $e) {

                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];

            } finally {
                ConexionDb::disconnect();
            }
        }

        public static function VerInternacionesActivas() {

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "SELECT i.IdInternacion from internaciones i
                        WHERE i.EstadoInternacion =  'Activa';";

                $result = ConexionDb::consult($sql);

                if ($result)
                    return $result;

                return [
                    "status" => "error",
                    "mensaje" => "Internación no encontrada"
                ];

            } catch (Exception $e) {

                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];

            } finally {
                ConexionDb::disconnect();
            }
        }

        public static function VerInternacionesByPac($idPaciente) {

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "SELECT i.* from internaciones i
                        WHERE i.IdPaciente =  :idPaciente
                        ORDER BY i.IdInternacion DESC;";

                $params = [
                    [ 'clave' => ':idPaciente', 'valor' => $idPaciente]
                ];

                $result = ConexionDb::consult($sql, $params);

                if ($result)
                    return $result;

                return [
                    "status" => "error",
                    "mensaje" => "No hay internaciones para dicho paciente"
                ];

            } catch (Exception $e) {

                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];

            } finally {
                ConexionDb::disconnect();
            }
        }

        /* ============================================
            OBTENER INTERNACIÓN
        ============================================ */
        public static function ObtenerInternacion($id) {

            try {
                global $conn;
                ConexionDb::connect();

                $sql = "
                    SELECT 
                        i.IdInternacion,
                        i.IdCama,
                        i.IdHabitacion,
                        i.FechaInicio,
                        i.FechaFin,
                        i.EstadoInternacion,
                        p.IdPaciente,
                        CONCAT(u.Nombre, ' ', u.Apellido) AS NombrePaciente
                    FROM internaciones i
                    INNER JOIN pacientes p ON i.IdPaciente = p.IdPaciente
                    INNER JOIN usuarios u ON p.IdUsuario = u.IdUsuario
                    WHERE i.IdInternacion = :id
                ";

                $stmt = $conn->prepare($sql);
                $stmt->execute([":id" => $id]);

                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data)
                    return [ "status" => "success", "data" => $data ];

                return [
                    "status" => "error",
                    "mensaje" => "Internación no encontrada"
                ];

            } catch (Exception $e) {

                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];

            } finally {
                ConexionDb::disconnect();
            }
        }

        /* ============================================
            FINALIZAR INTERNACIÓN
        ============================================ */
        public static function FinalizarInternacion($id) {

            try {
                global $conn;
                ConexionDb::connect();

                $conn->beginTransaction();

                // Obtener datos de la internación
                $stmt = $conn->prepare("
                    SELECT IdCama, IdPaciente, EstadoInternacion 
                    FROM internaciones 
                    WHERE IdInternacion = :id
                ");
                $stmt->execute([":id" => $id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$result) {

                    throw new Exception("La internación no existe.");

                }

                $IdCama = $result["IdCama"];
                $IdPaciente = $result["IdPaciente"];
                $EstadInternacion = $result["EstadoInternacion"];

                // Liberar cama
                $stmt2 = $conn->prepare("
                    UPDATE camas 
                    SET EstadoCama = 'Disponible', Habilitada = 1
                    WHERE IdCama = :cama
                ");
                $stmt2->execute([":cama" => $IdCama]);

                // Restaurar estado del paciente
                $stmt3 = $conn->prepare("
                    UPDATE pacientes
                    SET Estado = 'Normal'
                    WHERE IdPaciente = :paciente
                ");
                $stmt3->execute([":paciente" => $IdPaciente]);

                // Eliminar internación
                if ($EstadInternacion === "Fallecido") {

                    $stmt4 = $conn->prepare("
                        UPDATE internaciones
                        SET EstadoInternacion = 'Fallecido'
                        WHERE IdInternacion = :id
                    ");
                    $stmt4->execute([":id" => $id]);

                } else {

                    $stmt4 = $conn->prepare("
                        UPDATE internaciones
                        SET EstadoInternacion = 'Finalizada'
                        WHERE IdInternacion = :id
                    ");
                    $stmt4->execute([":id" => $id]);

                }

                // Confirmar cambios
                $conn->commit();

                return ["ok" => true];

            } catch (Exception $e) {

                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }

                return ["ok" => false, "msg" => $e->getMessage()];

            } finally {

                ConexionDb::disconnect();

            }
        }

        public static function ModificarInternacion($id, $newEstado, $observacion) {

            try {
                global $conn;
                ConexionDb::connect();

                $stmt = $conn->prepare("
                    UPDATE internaciones
                    SET EstadoInternacion = :estado,
                        Observaciones = :obser
                    WHERE IdInternacion = :id
                ");

                $stmt->execute([
                    "estado" => $newEstado,
                    "obser"  => $observacion,
                    "id"    => $id
                ]);

                if (in_array($newEstado, ["Fallecido"])) {

                    // Llamamos a la función FINALIZAR
                    $resultado = self::FinalizarInternacion($id);

                    if (!$resultado["ok"]) {

                        return ["ok" => false, "msg" => $resultado["msg"]];

                    }

                }

                return ["ok" => true];

            } catch (Exception $e) {
                return ["ok" => false, "msg" => $e->getMessage()];
            } finally {
                ConexionDb::disconnect();
            }
        }

        public static function getPacientesInterAct() {
            try{
                ConexionDb::connect();

                $sql = "SELECT T0.IdPaciente, T1.Nombre, T1.Apellido
                        FROM pacientes T0 
                        INNER JOIN usuarios T1 ON T0.IdUsuario = T1.IdUsuario
                        INNER JOIN internaciones T2 ON T0.IdPaciente = T2.IdPaciente
                        WHERE 1 = 1
                        AND T2.EstadoInternacion = 'Activa';";

                $result = ConexionDb::consult($sql);

                ConexionDb::disconnect();
                
                return $result;
            } catch (Exception $e) {
                throw new Exception("Problemas al obtener los pacientes por internacion: " . $e);
            }
        }

    }
?>
