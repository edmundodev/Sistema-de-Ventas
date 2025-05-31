<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID inválido"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Producto eliminado correctamente"]);
    } else {
        // Error 1451 = FOREIGN KEY constraint fails
        if ($conn->errno === 1451) {
            echo json_encode([
                "status" => "error",
                "message" => "No se puede eliminar este producto porque tiene ventas registradas."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al eliminar el producto: " . $conn->error
            ]);
        }
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}

$conn->close();
