<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class PacientesDataAccess 
    {
        /* ============================================================
            1) OBRAS SOCIALES
        ============================================================ */
        public static function obtenerObrasSociales() 
        {
            try {
                $conn = ConexionDb::connect();

                $sql = "SELECT IdOS, NombreOS FROM ObrasSociales";
                $stm = $conn->prepare($sql);
                $stm->execute();

                $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

                return [
                    "status" => "success",
                    "data" => $rows
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


        /* ============================================================
            2) OBTENER PACIENTES (con búsqueda)
        ============================================================ */
        public static function obtenerPacientes($search = "") 
        {
            try {
                $conn = ConexionDb::connect();

                $sql = "
                    SELECT 
                        p.IdPaciente,
                        u.Nombre,
                        u.Apellido,
                        p.DNI,
                        p.FechaNac,
                        p.Genero,
                        p.EstadoCivil,
                        u.Email,
                        u.Telefono,
                        os.NombreOS,
                        u.Habilitado
                    FROM Pacientes p
                    INNER JOIN Usuarios u ON p.IdUsuario = u.IdUsuario
                    LEFT JOIN ObrasSociales os ON p.IdPlan_OS = os.IdOS;
                ";

                if ($search !== "") {
                    $sql .= " 
                        WHERE u.Nombre LIKE :q 
                        OR u.Apellido LIKE :q 
                        OR p.DNI LIKE :q
                    ";
                }

                $stmt = $conn->prepare($sql);
                if ($search !== "") {
                    $stmt->bindValue(":q", "%$search%");
                }

                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return [
                    "status" => "success",
                    "data" => $rows
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


        /* ============================================================
            3) CREAR PACIENTE + USUARIO
        ============================================================ */
        public static function crearPaciente($data) 
        {
            try {
                $conn = ConexionDb::connect();
                $conn->beginTransaction();

                // Obtener IdOS
                $stmt = $conn->prepare("SELECT IdOS FROM ObrasSociales WHERE NombreOS = :os");
                $stmt->execute([":os" => $data["nombreOS"]]);

                $os = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$os)
                    throw new Exception("Obra social no encontrada.");

                $idOS = $os["IdOS"];

                // Crear usuario
                $sqlUser = "
                    INSERT INTO Usuarios 
                    (IdRol, Usuario, Clave, Habilitado, Nombre, Apellido, Email, Telefono)
                    VALUES (3, :user, :clave, 1, :nom, :ape, :email, :tel)
                ";

                $stmt = $conn->prepare($sqlUser);
                $stmt->execute([
                    ":user" => $data["dni"],
                    ":clave" => password_hash($data["dni"], PASSWORD_DEFAULT),
                    ":nom" => $data["nombre"],
                    ":ape" => $data["apellido"],
                    ":email" => $data["email"],
                    ":tel" => $data["telefono"]
                ]);

                $idUsuario = $conn->lastInsertId();

                // Crear paciente
                $sqlPac = "
                    INSERT INTO Pacientes 
                    (IdUsuario, IdOS, DNI, FechaNac, Genero, EstadoCivil)
                    VALUES (:idU, :os, :dni, :fec, :gen, :est)
                ";

                $stmt = $conn->prepare($sqlPac);
                $stmt->execute([
                    ":idU" => $idUsuario,
                    ":os" => $idOS,
                    ":dni" => $data["dni"],
                    ":fec" => $data["fechaNac"],
                    ":gen" => $data["genero"],
                    ":est" => $data["estadoCivil"]
                ]);

                $conn->commit();

                return [
                    "status" => "success",
                    "mensaje" => "Paciente creado correctamente"
                ];

            } catch (Exception $e) {
                if ($conn) $conn->rollBack();
                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];
            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================================
            4) ACTUALIZAR PACIENTE + USUARIO
        ============================================================ */
        public static function actualizarPaciente($id, $data) 
        {
            try {
                $conn = ConexionDb::connect();
                $conn->beginTransaction();

                // Obtener IdOS
                $stmt = $conn->prepare("SELECT IdOS FROM ObrasSociales WHERE NombreOS = :os");
                $stmt->execute([":os" => $data["nombreOS"]]);
                $os = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$os)
                    throw new Exception("Obra social no encontrada.");

                $idOS = $os["IdOS"];


                // Obtener IdUsuario del paciente
                $stmt = $conn->prepare("SELECT IdUsuario FROM Pacientes WHERE IdPaciente = :id");
                $stmt->execute([":id" => $id]);

                $pac = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$pac)
                    throw new Exception("Paciente no encontrado.");

                $idUsuario = $pac["IdUsuario"];

                // Actualizar usuario
                $sqlUsr = "
                    UPDATE Usuarios SET 
                        Habilitado = :hab,
                        Nombre = :nom,
                        Apellido = :ape,
                        Email = :email,
                        Telefono = :tel
                    WHERE IdUsuario = :idU
                ";

                $stmt = $conn->prepare($sqlUsr);
                $stmt->execute([
                    ":hab" => $data["habilitado"],
                    ":nom" => $data["nombre"],
                    ":ape" => $data["apellido"],
                    ":email" => $data["email"],
                    ":tel" => $data["telefono"],
                    ":idU" => $idUsuario
                ]);

                // Actualizar paciente
                $sqlPac = "
                    UPDATE Pacientes SET 
                        IdOS = :os,
                        DNI = :dni,
                        FechaNac = :fec,
                        Genero = :gen,
                        EstadoCivil = :est
                    WHERE IdPaciente = :id
                ";

                $stmt = $conn->prepare($sqlPac);
                $stmt->execute([
                    ":os" => $idOS,
                    ":dni" => $data["dni"],
                    ":fec" => $data["fechaNac"],
                    ":gen" => $data["genero"],
                    ":est" => $data["estadoCivil"],
                    ":id" => $id
                ]);

                $conn->commit();

                return [
                    "status" => "success",
                    "mensaje" => "Paciente actualizado correctamente"
                ];

            } catch (Exception $e) {
                if ($conn) $conn->rollBack();
                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];
            } finally {
                ConexionDb::disconnect();
            }
        }


        /* ============================================================
            5) ELIMINAR PACIENTE + USUARIO
        ============================================================ */
        public static function eliminarPaciente($id) 
        {
            try {
                $conn = ConexionDb::connect();
                $conn->beginTransaction();

                // Obtener IdUsuario
                $stmt = $conn->prepare("SELECT IdUsuario FROM Pacientes WHERE IdPaciente = :id");
                $stmt->execute([":id" => $id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row)
                    throw new Exception("Paciente no encontrado.");

                $idUsuario = $row["IdUsuario"];

                // Eliminar Paciente
                $stmt = $conn->prepare("DELETE FROM Pacientes WHERE IdPaciente = :id");
                $stmt->execute([":id" => $id]);

                // Eliminar Usuario
                $stmt = $conn->prepare("DELETE FROM Usuarios WHERE IdUsuario = :id");
                $stmt->execute([":id" => $idUsuario]);

                $conn->commit();

                return [
                    "status" => "success",
                    "mensaje" => "Paciente eliminado"
                ];

            } catch (Exception $e) {
                if ($conn) $conn->rollBack();
                return [
                    "status" => "error",
                    "mensaje" => "Error DB: " . $e->getMessage()
                ];
            } finally {
                ConexionDb::disconnect();
            }
        }
    }
?>