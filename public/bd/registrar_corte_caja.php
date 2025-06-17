<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios"; // Cambia al nombre correcto de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida"]));
}

// Verificar si la tabla existe
$check_table = $conn->query("SHOW TABLES LIKE 'cortes_caja'");
if ($check_table->num_rows == 0) {
    die(json_encode(['success' => false, 'error' => 'La tabla cortes_caja no existe. Por favor, ejecute el script de creación.']));
}

try {
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['success' => false, 'error' => 'No has iniciado sesión']);
        exit;
    }

    $usuario_id = $_SESSION['usuario_id'];

    // Obtener el total de ventas del día
    $sql = "SELECT COALESCE(SUM(total), 0) as total_dia 
            FROM ventas 
            WHERE usuario_id = ? 
            AND DATE(fecha) = CURRENT_DATE()";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total_dia = $resultado->fetch_assoc()['total_dia'];
    
    // Registrar el corte de caja (insertar o actualizar)
    $sql_corte = "INSERT INTO cortes_caja (usuario_id, fecha, total_ventas) 
                  VALUES (?, CURRENT_DATE(), ?) 
                  ON DUPLICATE KEY UPDATE total_ventas = ?";
    
    $stmt_corte = $conn->prepare($sql_corte);
    $stmt_corte->bind_param('sdd', $usuario_id, $total_dia, $total_dia);
    $stmt_corte->execute();
    
    // Obtener ventas de la última semana
    $sql_semana = "SELECT DATE(fecha) as fecha, total_ventas 
                   FROM cortes_caja 
                   WHERE usuario_id = ? 
                   AND fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) 
                   ORDER BY fecha ASC";
    
    $stmt_semana = $conn->prepare($sql_semana);
    $stmt_semana->bind_param('s', $usuario_id);
    $stmt_semana->execute();
    $resultado_semana = $stmt_semana->get_result();
    
    $ventas_semana = [];
    while ($row = $resultado_semana->fetch_assoc()) {
        $ventas_semana[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'total_dia' => $total_dia,
        'ventas_semana' => $ventas_semana
    ]);

} catch (Exception $e) {
    error_log('Error en corte de caja: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al procesar el corte de caja: ' . $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($stmt_corte)) $stmt_corte->close();
    if (isset($stmt_semana)) $stmt_semana->close();
    if (isset($conn)) $conn->close();
}
