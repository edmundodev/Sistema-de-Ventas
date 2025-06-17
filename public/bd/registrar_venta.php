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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    $sql = "SELECT precio, stock FROM productos WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $producto_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if (!$producto) {
        echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
        exit;
    }

    $precio = $producto['precio'];
    $stock = $producto['stock'];

    if ($cantidad > $stock) {
        echo json_encode(['success' => false, 'error' => 'Stock insuficiente']);
        exit;
    }

    $total = $precio * $cantidad;

    $fecha = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO ventas (producto_id, usuario_id, cantidad, total, fecha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiids", $producto_id, $usuario_id, $cantidad, $total, $fecha);
    $stmt->execute();

    $nuevo_stock = $stock - $cantidad;
    $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevo_stock, $producto_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

?>
