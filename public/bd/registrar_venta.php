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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // 1. Obtener precio actual y stock
    $sql = "SELECT precio, stock FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $producto_id);
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

    // 2. Calcular total
    $total = $precio * $cantidad;

    // 3. Registrar la venta
    $stmt = $conn->prepare("INSERT INTO ventas (producto_id, cantidad, total) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $producto_id, $cantidad, $total);
    $stmt->execute();

    // 4. Actualizar el stock
    $nuevo_stock = $stock - $cantidad;
    $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevo_stock, $producto_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
?>
