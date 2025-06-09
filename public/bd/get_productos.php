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
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT id, nombre, descripcion, precio, stock, categoria FROM productos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);

$conn->close();
?>
