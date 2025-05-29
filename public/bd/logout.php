<?php
session_start();
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión
header("Location: /proyecto/public/views/index.html");  // Redirige al usuario a la página de inicio
exit();
?>
