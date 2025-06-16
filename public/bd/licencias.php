<?php
header('Content-Type: application/json');

$licencia = $_GET['licencia'] ?? '';
$dominio = $_GET['dominio'] ?? '';

if (empty($licencia)) {
    echo json_encode(['status' => 'error', 'message' => 'Licencia no enviada']);
    exit;
}

$conn = new mysqli('localhost', 'usuario_db', 'password_db', 'licencias_sistema'); // AJUSTA ESTOS DATOS

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la conexión']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM licencias WHERE licencia = ? AND activo = 1");
$stmt->bind_param("s", $licencia);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $hoy = date('Y-m-d');
    if ($row['fecha_expiracion'] && $row['fecha_expiracion'] < $hoy) {
        echo json_encode(['status' => 'expirada']);
    } else {
        echo json_encode(['status' => 'ok']);
    }
} else {
    echo json_encode(['status' => 'invalida']);
}
