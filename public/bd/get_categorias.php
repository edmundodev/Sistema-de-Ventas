<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "usuarios");
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

$sql = "SELECT id, nombre FROM categorias";
$result = $conn->query($sql);

$categorias = [];
while ($fila = $result->fetch_assoc()) {
    $categorias[] = $fila;
}

echo json_encode($categorias);
$conn->close();
?>
