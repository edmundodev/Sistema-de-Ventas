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

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    echo json_encode($resultado->fetch_assoc());
}


?>
