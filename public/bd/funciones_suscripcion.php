<?php
function obtenerSuscripcionActiva($conn, $usuario_id) {
    $hoy = date('Y-m-d');
    $sql = $conn->prepare("
        SELECT p.*
        FROM suscripciones s
        INNER JOIN planes p ON s.plan_id = p.id
        WHERE s.usuario_id = ? 
          AND s.activo = 1 
          AND s.fecha_inicio <= ? 
          AND s.fecha_fin >= ?
        LIMIT 1
    ");
    $sql->bind_param("iss", $usuario_id, $hoy, $hoy);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}
