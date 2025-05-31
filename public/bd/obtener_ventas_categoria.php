<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios"; // o el nombre correcto de tu base

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida"]));
}

$sql = "
    SELECT p.categoria, SUM(v.total) AS total_ventas
    FROM ventas v
    INNER JOIN productos p ON v.producto_id = p.id
    GROUP BY p.categoria
";

$result = $conn->query($sql);

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos);
?>
