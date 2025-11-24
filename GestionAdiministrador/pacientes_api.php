<?php
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

function responder($success, $message, $data = []){
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

function obtenerObrasSociales($conn){
    $sql = "SELECT NombreOS FROM ObrasSociales";
    $result = $conn->query($sql);
    
    if ($result === false) {
        // Manejo de error de SQL
        throw new Exception("Error SQL al obtener Obras Sociales: " . $conn->error);
    }
    
    $os = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $os[] = $row['NombreOS'];
        }
    }
    return $os;
}

function obtenerPacientes($conn, $search = ''){
    // NOTA: Se asume que Nombre, Apellido, Email y Telefono estan en la tabla Usuarios.
    $sql = "SELECT p.IdPaciente, u.Nombre, u.Apellido, p.DNI, p.FechaNac, p.Genero, p.EstadoCivil, u.Email, u.Telefono, os.NombreOS, u.Habilitado 
            FROM Pacientes p
            JOIN Usuarios u ON p.IdUsuario = u.IdUsuario
            LEFT JOIN ObrasSociales os ON p.IdOS = os.IdOS";
    
    if ($search) {
        $search = $conn->real_escape_string($search);
        $sql .= " WHERE u.Nombre LIKE '%$search%' OR u.Apellido LIKE '%$search%' OR p.DNI LIKE '%$search%'";
    }
    
    $result = $conn->query($sql);

    if ($result === false) {
        // Aquí capturamos el error de SQL (el que causaba el warning)
        throw new Exception("Error SQL al obtener Pacientes: " . $conn->error . ". Consulta: " . $sql);
    }
    
    $pacientes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $pacientes[] = $row;
        }
    }
    return $pacientes;
}

function crearPaciente($conn, $data){
    $conn->begin_transaction();
    try {
        $stmt_os = $conn->prepare("SELECT IdOS FROM ObrasSociales WHERE NombreOS = ?");
        $stmt_os->bind_param("s", $data['nombreOS']);
        $stmt_os->execute();
        $result_os = $stmt_os->get_result();
        if ($result_os->num_rows === 0) {
            throw new Exception("Obra social no encontrada.");
        }
        $idOS = $result_os->fetch_assoc()['IdOS'];
        $stmt_os->close();

        $idRol = 3; 
        $usuario = $data['dni'];
        $clave = password_hash($data['dni'], PASSWORD_DEFAULT);
        $habilitado = 1;

        // Insercion en Usuarios: Se guarda Nombre, Apellido, Email y Telefono.
        $stmt_user = $conn->prepare("INSERT INTO Usuarios (IdRol, Usuario, Clave, Habilitado, Nombre, Apellido, Email, Telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_user->bind_param("isssisss", $idRol, $usuario, $clave, $habilitado, $data['nombre'], $data['apellido'], $data['email'], $data['telefono']);
        if (!$stmt_user->execute()){
            throw new Exception("Error al crear usuario: " . $stmt_user->error);
        }
        $idUsuario = $conn->insert_id;
        $stmt_user->close();

        // Insercion en Pacientes: Se guarda DNI, FechaNac, Genero, EstadoCivil.
        $stmt_paciente = $conn->prepare("INSERT INTO Pacientes (IdUsuario, IdOS, DNI, FechaNac, Genero, EstadoCivil) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_paciente->bind_param("iisiss", $idUsuario, $idOS, $data['dni'], $data['fechaNac'], $data['genero'], $data['estadoCivil']);
        if (!$stmt_paciente->execute()){
            throw new Exception("Error al crear paciente: " . $stmt_paciente->error);
        }
        $stmt_paciente->close();

        $conn->commit();
        responder(true, "Paciente y Usuario creados correctamente.");

    } catch (Exception $e) {
        $conn->rollback();
        responder(false, "Fallo la creacion: " . $e->getMessage());
    }
}

function actualizarPaciente($conn, $id, $data){
    $conn->begin_transaction();
    try {
        $stmt_os = $conn->prepare("SELECT IdOS FROM ObrasSociales WHERE NombreOS = ?");
        $stmt_os->bind_param("s", $data['nombreOS']);
        $stmt_os->execute();
        $result_os = $stmt_os->get_result();
        if ($result_os->num_rows === 0) {
            throw new Exception("Obra social no encontrada.");
        }
        $idOS = $result_os->fetch_assoc()['IdOS'];
        $stmt_os->close();

        $stmt_get_user = $conn->prepare("SELECT IdUsuario FROM Pacientes WHERE IdPaciente = ?");
        $stmt_get_user->bind_param("i", $id);
        $stmt_get_user->execute();
        $result_user = $stmt_get_user->get_result();
        if ($result_user->num_rows === 0) {
            throw new Exception("Paciente no encontrado.");
        }
        $idUsuario = $result_user->fetch_assoc()['IdUsuario'];
        $stmt_get_user->close();

        // Actualiza campos de usuario
        $stmt_user = $conn->prepare("UPDATE Usuarios SET Habilitado = ?, Nombre = ?, Apellido = ?, Email = ?, Telefono = ? WHERE IdUsuario = ?");
        $stmt_user->bind_param("issisi", $data['habilitado'], $data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $idUsuario);
        if (!$stmt_user->execute()){
            throw new Exception("Error al actualizar usuario: " . $stmt_user->error);
        }
        $stmt_user->close();

        // Actualiza campos de paciente
        $stmt_paciente = $conn->prepare("UPDATE Pacientes SET IdOS = ?, DNI = ?, FechaNac = ?, Genero = ?, EstadoCivil = ? WHERE IdPaciente = ?");
        $stmt_paciente->bind_param("iisssi", $idOS, $data['dni'], $data['fechaNac'], $data['genero'], $data['estadoCivil'], $id);
        if (!$stmt_paciente->execute()){
            throw new Exception("Error al actualizar paciente: " . $stmt_paciente->error);
        }
        $stmt_paciente->close();

        $conn->commit();
        responder(true, "Paciente y Usuario actualizados correctamente.");

    } catch (Exception $e) {
        $conn->rollback();
        responder(false, "Fallo la actualizacion: " . $e->getMessage());
    }
}

function eliminarPaciente($conn, $id){
    $conn->begin_transaction();
    try {
        $stmt_get_user = $conn->prepare("SELECT IdUsuario FROM Pacientes WHERE IdPaciente = ?");
        $stmt_get_user->bind_param("i", $id);
        $stmt_get_user->execute();
        $result_user = $stmt_get_user->get_result();
        if ($result_user->num_rows === 0) {
            throw new Exception("Paciente no encontrado.");
        }
        $idUsuario = $result_user->fetch_assoc()['IdUsuario'];
        $stmt_get_user->close();

        $stmt_paciente = $conn->prepare("DELETE FROM Pacientes WHERE IdPaciente = ?");
        $stmt_paciente->bind_param("i", $id);
        if (!$stmt_paciente->execute()){
            throw new Exception("Error al eliminar paciente: " . $stmt_paciente->error);
        }
        $stmt_paciente->close();

        $stmt_user = $conn->prepare("DELETE FROM Usuarios WHERE IdUsuario = ?");
        $stmt_user->bind_param("i", $idUsuario);
        if (!$stmt_user->execute()){
            throw new Exception("Error al eliminar usuario: " . $stmt_user->error);
        }
        $stmt_user->close();

        $conn->commit();
        responder(true, "Paciente y Usuario eliminados correctamente.");

    } catch (Exception $e) {
        $conn->rollback();
        responder(false, "Fallo la eliminacion: " . $e->getMessage());
    }
}

try {
    $conn = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if (isset($_GET['action']) && $_GET['action'] == 'obrasSociales') {
        responder(true, "Obras sociales obtenidas", obtenerObrasSociales($conn));
    }
    
    switch ($method) {
        case 'GET':
            $search = $_GET['search'] ?? '';
            responder(true, "Lista de pacientes", obtenerPacientes($conn, $search));
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) responder(false, "Datos invalidos.");
            crearPaciente($conn, $data);
            break;
            
        case 'PUT':
            $id = $_GET['id'] ?? null;
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$id || !$data) responder(false, "ID o datos invalidos.");
            actualizarPaciente($conn, $id, $data);
            break;
            
        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if (!$id) responder(false, "ID invalido.");
            eliminarPaciente($conn, $id);
            break;
            
        default:
            responder(false, "Metodo no soportado.");
    }
    
} catch (Exception $e) {
    // Si la conexion falla o hay un error SQL, se captura aquí y se devuelve en JSON
    responder(false, $e->getMessage());
}
?>