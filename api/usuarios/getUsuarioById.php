<?php
    require_once(__DIR__ . '/includes/globals.php');
    require_once($dirBaseFile . '/entidades/usuarios.php');

    if (isset($_GET['id'])) {
        $idUser = $_GET['id'];

        $data = Usuarios::getUsuarioById($idUser);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
?>