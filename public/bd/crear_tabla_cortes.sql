CREATE TABLE IF NOT EXISTS cortes_caja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    total_ventas DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_corte_dia (usuario_id, fecha),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índice para búsquedas por fecha
CREATE INDEX idx_fecha ON cortes_caja(fecha);

-- Índice para búsquedas por usuario
CREATE INDEX idx_usuario ON cortes_caja(usuario_id);