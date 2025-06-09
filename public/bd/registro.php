<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]));
}

$action = $_GET['action'] ?? '';

if ($action == 'register') {
    $nombre = trim($_GET['nombre']);
    $email = trim($_GET['email']);
    $password = password_hash($_GET['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "El email ya está registrado."]);
    } else {
        $stmt = $conn->prepare("INSERT INTO user (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $password);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error en el registro."]);
        }
    }
    $stmt->close();
}
?>
