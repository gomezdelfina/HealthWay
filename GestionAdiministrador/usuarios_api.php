<?php
/**
 * API RESTful para la gestion de usuarios (CRUD) por el Administrador.
 * Implementa GET, POST, PUT, DELETE (Baja Logica) usando AJAX.
 */
require_once 'conexion.php';

// Establecer la cabecera para que el cliente sepa que esperamos JSON
header('Content-Type: application/json');

// Obtener el metodo de la solicitud y la accion si viene en la URL
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Funcion de respuesta JSON simple
function respond($success, $message, $data = []) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

// Funcion auxiliar para obtener el IdRol a partir de la descripcion (Ej: 'Administrador')

function getRoleId($conn, $roleDesc) {
    // Nota: Esta funcion es sensible a mayusculas/minusculas segun la BD
    $stmt = $conn->prepare("SELECT IdRol FROM Roles WHERE DescRol = ?");
    $stmt->bind_param("s", $roleDesc);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['IdRol'];
    }
    return null; // Rol no encontrado
}


// Accion: OBTENER ROLES (para llenar el select del modal)

if ($action === 'roles') {
    $sql = "SELECT DescRol FROM Roles ORDER BY DescRol ASC";
    $result = $conn->query($sql);
    
    if ($result) {
        $roles = $result->fetch_all(MYSQLI_ASSOC);
        // Transformar el array para ser mas facil de usar en JS
        $roleDescriptions = array_column($roles, 'DescRol');
        respond(true, "Roles obtenidos correctamente.", $roleDescriptions);
    } else {
        respond(false, "Error al obtener roles: " . $conn->error);
    }
}

// -----------------------------------------------------------------------------------
// Operacion GET (Leer todos los usuarios con su rol y buscar)
// -----------------------------------------------------------------------------------
if ($method === 'GET' && empty($action)) {
    // Consulta JOIN para obtener usuarios y su rol
    $sql = "SELECT 
                u.IdUsuario, u.Nombre, u.Apellido, u.Email, u.Usuario, u.Habilitado, u.Telefono, r.DescRol 
            FROM 
                Usuarios u
            INNER JOIN 
                Roles r ON u.IdRol = r.IdRol";
    
    // Aplicar filtro de busqueda si existe
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    if (!empty($searchTerm)) {
        $searchParam = '%' . $searchTerm . '%';
        // Busqueda en Nombre, Apellido, Email, Usuario o DescRol
        $sql .= " WHERE u.Nombre LIKE ? OR u.Apellido LIKE ? OR u.Email LIKE ? OR u.Usuario LIKE ? OR r.DescRol LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    } else {
        $stmt = $conn->prepare($sql);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        respond(true, "Usuarios obtenidos correctamente.", $users);
    } else {
        respond(false, "Error al obtener usuarios: " . $stmt->error);
    }
}

// -----------------------------------------------------------------------------------
// Operacion POST (Crear nuevo usuario - Alta)
// -----------------------------------------------------------------------------------
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name'], $data['lastname'], $data['username'], $data['password'], $data['email'], $data['role'], $data['phone'])) {
        respond(false, "Faltan datos obligatorios para el alta del usuario.");
    }
    
    $idRol = getRoleId($conn, $data['role']);
    if (!$idRol) {
        respond(false, "Rol de usuario no valido.");
    }

    // Hash de la contrasena
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $habilitado = 1; // Habilitado por defecto

    $sql = "INSERT INTO Usuarios (IdRol, Usuario, Clave, Habilitado, Nombre, Apellido, Email, Telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississss", 
        $idRol, 
        $data['username'], 
        $hashedPassword, 
        $habilitado, 
        $data['name'],
        $data['lastname'], 
        $data['email'], 
        $data['phone']
    );

    if ($stmt->execute()) {
        respond(true, "Usuario creado exitosamente.", ['IdUsuario' => $conn->insert_id]);
    } else {
        // En caso de error (ej: usuario duplicado), devolver el mensaje de error de MySQL
        $error_message = str_replace(array("\r", "\n"), '', $stmt->error);
        respond(false, "Error al crear el usuario. Verifique si el usuario o email ya existen. Detalle: " . $error_message);
    }
}

// -----------------------------------------------------------------------------------
// Operacion PUT (Modificar usuario - Actualizacion)
// -----------------------------------------------------------------------------------
if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idUsuario = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if (!$idUsuario) {
        respond(false, "ID de usuario faltante para la actualizacion.");
    }

    // Validacion basica de campos requeridos
    if (!isset($data['name'], $data['lastname'], $data['username'], $data['email'], $data['role'], $data['phone'], $data['habilitado'])) {
        respond(false, "Faltan datos obligatorios para la modificacion del usuario.");
    }
    
    $idRol = getRoleId($conn, $data['role']);
    if (!$idRol) {
        respond(false, "Rol de usuario no valido.");
    }
    
    // Si la clave viene definida, la incluimos en la actualizacion
    $updatePassword = !empty($data['password']);
    
    $sql = "UPDATE Usuarios SET 
                IdRol = ?, 
                Usuario = ?, 
                Habilitado = ?, 
                Nombre = ?, 
                Apellido = ?, 
                Email = ?, 
                Telefono = ?" .
            ($updatePassword ? ", Clave = ?" : "") . 
            " WHERE IdUsuario = ?";
    
    $stmt = $conn->prepare($sql);
    
    // Construir la lista de parametros para bind_param
    $types = "issssssi"; // idRol(i), Usuario(s), Habilitado(s), Nombre(s), Apellido(s), Email(s), Telefono(s), IdUsuario(i)
    $params = [
        $idRol, 
        $data['username'], 
        $data['habilitado'], 
        $data['name'], 
        $data['lastname'], 
        $data['email'], 
        $data['phone']
    ];
    
    if ($updatePassword) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $types = "isssssssi"; // idRol(i), Usuario(s), Habilitado(s), Nombre(s), Apellido(s), Email(s), Telefono(s), Clave(s), IdUsuario(i)
        $params[] = $hashedPassword;
    }
    
    $params[] = $idUsuario; // IdUsuario siempre al final
    
    // Llamar a bind_param dinamicamente
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            respond(true, "Usuario ID {$idUsuario} actualizado exitosamente.");
        } else {
            respond(true, "Usuario ID {$idUsuario} actualizado exitosamente (no se detectaron cambios).");
        }
    } else {
        $error_message = str_replace(array("\r", "\n"), '', $stmt->error);
        respond(false, "Error al actualizar el usuario. Verifique si el usuario o email ya existen. Detalle: " . $error_message);
    }
}

// -----------------------------------------------------------------------------------
// Operacion DELETE (Eliminar usuario - Baja Logica)
// -----------------------------------------------------------------------------------
if ($method === 'DELETE') {
    $idUsuario = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if (!$idUsuario) {
        respond(false, "ID de usuario faltante para la eliminacion.");
    }
    
    // Baja Logica (deshabilitar)
    $sql = "UPDATE Usuarios SET Habilitado = 0 WHERE IdUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            respond(true, "Usuario ID {$idUsuario} deshabilitado (Baja Logica) exitosamente.");
        } else {
            respond(false, "No se encontro el usuario o ya estaba deshabilitado.");
        }
    } else {
        respond(false, "Error al deshabilitar el usuario: " . $stmt->error);
    }
}

// Cierre de conexion
$conn->close();

if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
    respond(false, "Metodo de solicitud no compatible.");
}

?>