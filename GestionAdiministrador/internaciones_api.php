<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'conexion.php';

$conn = null;
$response = ['success' => false, 'message' => 'Solicitud invalida'];
$method = $_SERVER['REQUEST_METHOD'];

function getIdByValue($conn, $tableName, $columnName, $searchValue, $returnIdColumn) {
    $tableName = strtolower($tableName);
    
    if ($tableName === 'usuarios') {
        $sql = "SELECT IdUsuario FROM usuarios WHERE Usuario = ? AND IdRol = 2";
        $returnIdColumn = "IdUsuario";
    } else {
        $sql = "SELECT {$returnIdColumn} FROM {$tableName} WHERE {$columnName} = ?";
    }

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Error de preparacion en getIdByValue para tabla {$tableName} ({$sql}): " . $conn->error);
        return null;
    }

    $stmt->bind_param("s", $searchValue);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row ? $row[$returnIdColumn] : null;
}

function createInternacion($conn, $data) {
    $idPaciente = getIdByValue($conn, 'pacientes', 'DNI', $data['dniPaciente'], 'IdPaciente');
    $idCama = getIdByValue($conn, 'camas', 'nrocama', $data['nroCama'], 'IdCama'); 
    $idMedico = getIdByValue($conn, 'usuarios', 'Usuario', $data['usuarioMedico'], 'IdUsuario');
    $idPlan = getIdByValue($conn, 'planes_obrassociales', 'NombrePlan', $data['planOS'], 'IdPlan');

    if (!$idPaciente || !$idCama || !$idMedico || !$idPlan) {
        return ['success' => false, 'message' => 'Error: No se pudo encontrar el Paciente, Cama, Medico o Plan asociado. Asegurese que el DNI, Cama y Medico existan.'];
    }

    $fechaInicio = $data['fechaInicio'] ?? date('Y-m-d H:i:s');
    $estado = 'En Curso';

    $sql = "INSERT INTO internaciones (IdPaciente, IdCama, IdUsuario, IdPlan, TipoIngreso, FechaInicio, Motivo, Estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        return ['success' => false, 'message' => 'Error al preparar la Insercion de internacion: ' . $conn->error];
    }
    
    $stmt->bind_param("iiiissss", 
        $idPaciente, 
        $idCama, 
        $idMedico, 
        $idPlan, 
        $data['tipoIngreso'], 
        $fechaInicio, 
        $data['motivo'],
        $estado
    );

    if ($stmt->execute()) {
        $conn->query("UPDATE camas SET Ocupada = 1 WHERE IdCama = $idCama");
        return ['success' => true, 'message' => 'Internacion creada exitosamente.'];
    } else {
        return ['success' => false, 'message' => 'Error al crear la Internacion. Error SQL: ' . $stmt->error];
    }
}

function readInternaciones($conn, $searchTerm) {
    $sql = "SELECT 
                i.IdInternacion, 
                up.Nombre AS PacienteNombre, 
                up.Apellido AS PacienteApellido, 
                p.DNI AS PacienteDNI,
                c.nrocama,
                um.Usuario AS MedicoUsuario, 
                os.NombreOS AS NombreOS, 
                pl.NombrePlan AS NombrePlan,
                i.TipoIngreso, i.FechaInicio, i.FechaFin, i.Estado, i.Motivo
            FROM internaciones i
            INNER JOIN pacientes p ON i.IdPaciente = p.IdPaciente
            INNER JOIN usuarios up ON p.IdUsuario = up.IdUsuario
            INNER JOIN camas c ON i.IdCama = c.IdCama
            INNER JOIN usuarios um ON i.IdUsuario = um.IdUsuario
            INNER JOIN planes_obrassociales pl ON i.IdPlan = pl.IdPlan
            INNER JOIN obrassociales os ON pl.IdOS = os.IdOS";
    
    $params = [];
    $types = "";

    if (!empty($searchTerm)) {
        $sql .= " WHERE up.Nombre LIKE ? OR up.Apellido LIKE ? OR p.DNI LIKE ? OR c.nrocama LIKE ?";
        $searchTermWildcard = "%" . $searchTerm . "%";
        $params = [$searchTermWildcard, $searchTermWildcard, $searchTermWildcard, $searchTermWildcard];
        $types = "ssss";
    }

    $sql .= " ORDER BY i.IdInternacion DESC";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Error al preparar la consulta de internaciones (readInternaciones): " . $conn->error);
    }
    
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $internaciones = [];
    while ($row = $result->fetch_assoc()) {
        $internaciones[] = $row;
    }
    
    return ['success' => true, 'data' => $internaciones];
}

function updateInternacion($conn, $id, $data) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'ID de Internacion no proporcionado.'];
    }
    
    $stmt = $conn->prepare("SELECT IdCama, Estado, FechaFin FROM internaciones WHERE IdInternacion = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentInternacion = $result->fetch_assoc();
    
    if (!$currentInternacion) {
        return ['success' => false, 'message' => 'Internacion no encontrada.'];
    }
    
    $currentIdCama = $currentInternacion['IdCama'];
    $currentEstado = $currentInternacion['Estado'];
    $newEstado = $data['estado'];
    
    $sql = "UPDATE internaciones SET Estado = ?, FechaFin = ?";
    $types = "ss";
    
    $fechaFin = $currentInternacion['FechaFin'];

    if ($newEstado === 'Finalizada' || $newEstado === 'Cancelada') {
        $fechaFin = date('Y-m-d H:i:s');
    }
    
    $params = [$newEstado, $fechaFin];

    $sql .= " WHERE IdInternacion = ?";
    $types .= "i";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        return ['success' => false, 'message' => 'Error al preparar la Actualizacion de internacion: ' . $conn->error];
    }
    
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($currentEstado === 'En Curso' && ($newEstado === 'Finalizada' || $newEstado === 'Cancelada')) {
             $conn->query("UPDATE camas SET Ocupada = 0 WHERE IdCama = $currentIdCama");
        }
        return ['success' => true, 'message' => 'Internacion actualizada exitosamente.'];
    } else {
        return ['success' => false, 'message' => 'Error al actualizar la Internacion: ' . $stmt->error];
    }
}

function deleteInternacion($conn, $id) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'ID de Internacion no proporcionado.'];
    }

    $stmt = $conn->prepare("SELECT IdCama FROM internaciones WHERE IdInternacion = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $internacion = $result->fetch_assoc();
    
    if (!$internacion) {
        return ['success' => false, 'message' => 'Internacion no encontrada.'];
    }
    $idCama = $internacion['IdCama'];
    
    $sql = "DELETE FROM internaciones WHERE IdInternacion = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        return ['success' => false, 'message' => 'Error al preparar la Eliminacion de internacion: ' . $conn->error];
    }
    
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $conn->query("UPDATE camas SET Ocupada = 0 WHERE IdCama = $idCama");
            return ['success' => true, 'message' => 'Internacion eliminada (Fisica) exitosamente. La cama ha sido liberada.'];
        } else {
            return ['success' => false, 'message' => 'No se encontro la internacion con el ID: ' . $id];
        }
    } else {
        return ['success' => false, 'message' => 'Error al eliminar la internacion: ' . $stmt->error];
    }
}

try {
    $conn = getConnection();
    
    switch ($method) {
        case 'GET':
            if (isset($_GET['action'])) {
                if ($_GET['action'] === 'camasDisponibles') {
                    $result = $conn->query("SELECT Nrocama FROM camas WHERE Ocupada = 0 ORDER BY Nrocama ASC");
                    $data = [];
                    while ($row = $result->fetch_assoc()) $data[] = $row['nrocama'];
                    $response = ['success' => true, 'data' => $data];
                } elseif ($_GET['action'] === 'medicos') {
                    $result = $conn->query("SELECT Usuario FROM usuarios WHERE Habilitado = 1 AND IdRol = 2 ORDER BY Usuario ASC"); 
                    $data = [];
                    while ($row = $result->fetch_assoc()) $data[] = $row['Usuario'];
                    $response = ['success' => true, 'data' => $data];
                } elseif ($_GET['action'] === 'planes') {
                    $result = $conn->query("SELECT NombrePlan FROM planes_obrassociales ORDER BY NombrePlan ASC");
                    $data = [];
                    while ($row = $result->fetch_assoc()) $data[] = $row['NombrePlan'];
                    $response = ['success' => true, 'data' => $data];
                } else {
                    http_response_code(404);
                    $response = ['success' => false, 'message' => 'Accion GET no reconocida.'];
                }
            } 
            else {
                $searchTerm = $_GET['search'] ?? '';
                $response = readInternaciones($conn, $searchTerm);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $response = createInternacion($conn, $data);
            break;

        case 'PUT':
            $id = $_GET['id'] ?? null;
            $data = json_decode(file_get_contents("php://input"), true);
            $response = updateInternacion($conn, $id, $data);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            $response = deleteInternacion($conn, $id);
            break;
            
        case 'OPTIONS':
            http_response_code(200);
            exit();

        default:
            $response = ['success' => false, 'message' => 'Metodo no soportado'];
            http_response_code(405);
            break;
    }
} catch (Exception $e) {
    $response = ['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()];
    http_response_code(500);
}

echo json_encode($response);

if ($conn) {
    $conn->close();
}
?>