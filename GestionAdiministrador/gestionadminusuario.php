<?php

require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios - HealthWay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="estilo.css">
    
   

</head>
<body class="bg-light">

    <div class="container-fluid">
        <!-- Titulo del Modulo -->
        <h1 class="text-primary fw-bold mb-4 border-bottom border-primary pb-2">
            <i class="bi bi-person-gear me-2"></i> Modulo Administrador: Gestion de Usuarios
        </h1>

        <!-- Controles y Busqueda -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <!-- Boton de Alta (Anadir Nuevo Usuario) -->
                <button id="btn-add-user" onclick="openUserModal('add')" 
                        class="btn btn-success w-100 shadow rounded-pill">
                    <i class="bi bi-person-plus-fill me-2"></i> Anadir Nuevo Usuario
                </button>
            </div>

            <!-- Campo de Busqueda -->
            <div class="col-md-6 offset-md-2">
                <div class="input-group shadow rounded-pill overflow-hidden">
                    <input type="text" placeholder="Buscar por Nombre, Email, Usuario o Rol"
                           class="form-control border-0"
                           id="search-input">
                    <span class="input-group-text bg-white text-primary border-0">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabla de Listado de Usuarios -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white fw-medium rounded-top-3">
                Lista de Usuarios Registrados
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead class="table-light sticky-top shadow-sm">
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col">Nombre Completo</th>
                                <th scope="col">Usuario (Login)</th>
                                <th scope="col">Email</th>
                                <th scope="col">Telefono</th>
                                <th scope="col">Rol</th>
                                <th scope="col" class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            <!-- Contenido cargado por JavaScript (AJAX) -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted text-end rounded-bottom-3">
                <small>Datos de la tabla Usuarios - Base de Datos HealthWay</small>
            </div>
        </div>
    </div>


    <!-- Modal de Gestion de Usuarios (ABM) -->
    <div class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Anadir Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body (Formulario) -->
                <form id="user-form">
                    <div class="modal-body row g-3">
                        <input type="hidden" id="user-id" name="id">
                        
                        <!-- Campo Nombre -->
                        <div class="col-md-6">
                            <label for="user-name" class="form-label">Nombre</label>
                            <input type="text" id="user-name" name="name" required
                                   class="form-control rounded-pill">
                        </div>
                        
                        <!-- Campo Apellido -->
                        <div class="col-md-6">
                            <label for="user-lastname" class="form-label">Apellido</label>
                            <input type="text" id="user-lastname" name="lastname" required
                                   class="form-control rounded-pill">
                        </div>
                        
                        <!-- Campo Usuario (Login) -->
                        <div class="col-md-6">
                            <label for="user-username" class="form-label">Nombre de Usuario (Login)</label>
                            <input type="text" id="user-username" name="username" required
                                   class="form-control rounded-pill">
                        </div>

                        <!-- Campo Email -->
                        <div class="col-md-6">
                            <label for="user-email" class="form-label">Correo Electronico</label>
                            <input type="email" id="user-email" name="email" required
                                   class="form-control rounded-pill">
                        </div>
                        
                        <!-- Campo Telefono -->
                        <div class="col-md-6">
                            <label for="user-phone" class="form-label">Telefono (ej: 1112345678)</label>
                            <input type="tel" id="user-phone" name="phone" required
                                   class="form-control rounded-pill">
                        </div>

                        <!-- Campo Contrasena (para Alta o Cambio Opcional en Edicion) -->
                        <div class="col-md-6" id="password-group">
                            <label for="user-password" class="form-label" id="password-label">Contrasena</label>
                            <input type="password" id="user-password" name="password" required
                                   class="form-control rounded-pill">
                        </div>
                        
                        <!-- Campo Rol -->
                        <div class="col-md-6">
                            <label for="user-role" class="form-label">Rol de Usuario</label>
                            <!-- El contenido de este select se llena via AJAX en script.js -->
                            <select id="user-role" name="role" required
                                    class="form-select rounded-pill">
                                <option value="" disabled>Cargando roles...</option>
                            </select>
                        </div>
                        
                        <!-- Campo Habilitado (solo para Edicion) -->
                        <div class="col-md-6 d-none align-self-center" id="habilitado-group">
                            <div class="form-check pt-4">
                                <input class="form-check-input" type="checkbox" id="user-habilitado" name="habilitado" checked>
                                <label class="form-check-label fw-medium" for="user-habilitado">
                                    Usuario Habilitado (Activo)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill shadow-sm" onclick="closeUserModal()">
                            Cancelar
                        </button>
                        <button type="submit" id="save-user-btn" 
                                class="btn btn-primary rounded-pill shadow">
                            Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Contenedor para mensajes de notificacion -->
    <div id="notification-container"></div>

    <!-- Incluir Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="logicafrontadmin.js"></script>

</body>
</html>