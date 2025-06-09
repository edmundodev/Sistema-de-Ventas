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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['categoria'])) {
        echo json_encode(["error" => "Faltan datos requeridos en el formulario."]);
        exit;
    }

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $categoria, $usuario_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Producto agregado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al agregar el producto: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Método HTTP no permitido."]);
}
?>
