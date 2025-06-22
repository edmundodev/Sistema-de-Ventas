<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$barcode = isset($_GET['barcode']) ? $_GET['barcode'] : null;

if ($barcode) {
    // Buscar producto por código de barras
    $sql = "SELECT id, barcode, nombre, descripcion, precio, precio_compra, precio_mayoreo, precio_kilo, peso_total, stock, categoria, codigo_clave 
            FROM productos 
            WHERE barcode = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $barcode, $usuario_id);
} else {
    // Obtener todos los productos
    $sql = "SELECT id, barcode, nombre, descripcion, precio, precio_compra, precio_mayoreo, precio_kilo, peso_total, stock, categoria, codigo_clave 
            FROM productos 
            WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$result = $stmt->get_result();
$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);

$stmt->close();
$conn->close();
?>
