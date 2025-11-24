<?php
// Título: gestion_internaciones.php
// Objetivo: Demostrar cómo PHP (lógica de servidor) genera dinámicamente el HTML (estructura visible).

// 1. Lógica de Back-end (simulada): Conexión a DB y consulta.
// En un entorno real, aquí iría el código para conectar a la base de datos y obtener los datos de la tabla 'Internaciones'.

// --- SIMULACIÓN DE DATOS OBTENIDOS DE LA BASE DE DATOS (BD HealthWay) ---
$internaciones = [
    [
        'id' => 101,
        'paciente' => 'Juan Pérez',
        'sala' => 'A-03',
        'fecha_ingreso' => '2025-11-20',
        'estado' => 'Activa'
    ],
    [
        'id' => 102,
        'paciente' => 'María García',
        'sala' => 'B-12',
        'fecha_ingreso' => '2025-11-23',
        'estado' => 'Activa'
    ],
    [
        'id' => 103,
        'paciente' => 'Carlos López',
        'sala' => 'C-01',
        'fecha_ingreso' => '2025-11-15',
        'estado' => 'Alta Pendiente'
    ]
];
// ------------------------------------------------------------------------

// 2. Generación del Front-end (HTML)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Internaciones - HealthWay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
    </style>
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Panel de Gestion de Internaciones</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Sala</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Ingreso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($internaciones as $internacion): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo $internacion['id']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo $internacion['paciente']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo $internacion['sala']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo $internacion['fecha_ingreso']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php
                                    // logica  para determinar el color del estado
                                    $color = ($internacion['estado'] == 'Activa') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                                    <?php echo $internacion['estado']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="detalle.php?id=<?php echo $internacion['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-4">Detalle</a>
                                <a href="alta.php?id=<?php echo $internacion['id']; ?>" class="text-red-600 hover:text-red-900">Dar Alta</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($internaciones)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay internaciones activas en este momento.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>