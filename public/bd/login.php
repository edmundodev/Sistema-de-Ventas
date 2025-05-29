<?php
session_start();
header('Content-Type: application/json');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]));
}


if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $email = $_GET['email'];
    $password = $_GET['password'];

    // Buscar usuario por email
    $sql = "SELECT id, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Éxito en el login
            echo json_encode(['status' => 'success', 'message' => 'Bienvenido!']);
        } else {
            // Contraseña incorrecta
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        // No se encontró el usuario
        echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado.']);
    }
}

$conn->close();
?>
