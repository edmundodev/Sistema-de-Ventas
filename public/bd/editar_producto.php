<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión expirada.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios"; // Ajusta si tu DB tiene otro nombre

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

$id = $_POST['id'];
$barcode = $_POST['barcode'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];
$precio_compra = $_POST['precio_compra'];
$precio_mayoreo = $_POST['precio_mayoreo'];
$codigo_clave = $_POST['codigo_clave'];

$stmt = $conn->prepare("UPDATE productos SET barcode=?, nombre=?, descripcion=?, precio=?, stock=?, categoria=?, precio_compra=?, precio_mayoreo=?, codigo_clave=? WHERE id=? AND usuario_id=?");
$stmt->bind_param("sssddsssdii", $barcode, $nombre, $descripcion, $precio, $stock, $categoria, $precio_compra, $precio_mayoreo, $codigo_clave, $id, $usuario_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar el producto.']);
}

$stmt->close();
$conn->close();
