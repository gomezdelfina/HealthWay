<?php

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (strpos($contentType, 'application/json') !== false) {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
    } else {
        $data = $_POST;
    }

    // Verificar si se recibieron datos
    if (empty($data)) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode([
            'error' => 'No se recibieron datos',
        ]);
        exit;
    }else{
        require_once(__DIR__ . '/includes/globals.php');
        require_once($dirBaseFile . '/entidades/usuarios.php');

        $email = $data['email'];

        $response = Usuarios::getUsuarioByEmail($email);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
?>