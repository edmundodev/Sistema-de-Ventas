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
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit;
}

// Verificar si los datos llegaron correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['categoria'])) {
        echo json_encode(["error" => "Faltan datos requeridos en el formulario."]);
        exit;
    }

    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];

    // Preparar la consulta SQL para insertar el producto
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $categoria);

    // Ejecutar la consulta
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
