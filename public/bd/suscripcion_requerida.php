<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Suscripción Requerida</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-10 rounded-2xl shadow-lg text-center max-w-md">
        <h1 class="text-2xl font-bold text-red-600 mb-4">⚠️ Acceso Restringido</h1>
        <p class="text-gray-700 mb-6">Necesitas tener una <strong>suscripción activa</strong> para acceder a esta sección del sistema.</p>
        <a href="../suscripciones.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition duration-200">
            Ver planes de suscripción
        </a>
    </div>

</body>
</html>
