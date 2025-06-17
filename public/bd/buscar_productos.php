<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión: ' . $conn->connect_error]));
}

try {
    $usuario_id = $_SESSION['usuario_id'];
    $termino = isset($_POST['termino']) ? '%' . $_POST['termino'] . '%' : '';

    $sql = "SELECT id, barcode, nombre, descripcion, precio, precio_compra, precio_mayoreo, stock, categoria, codigo_clave 
            FROM productos 
            WHERE usuario_id = ? AND 
                  (barcode LIKE ? OR 
                   nombre LIKE ? OR 
                   codigo_clave LIKE ?)
            ORDER BY nombre ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $usuario_id, $termino, $termino, $termino);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $productos]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al buscar productos']);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}