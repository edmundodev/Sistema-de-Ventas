<?php
header('Content-Type: text/html; charset=utf-8');

// Recibir los datos de la venta
$datos = json_decode(file_get_contents('php://input'), true);
$carrito = $datos['carrito'];
$total = $datos['total'];
$dineroRecibido = $datos['dineroRecibido'];
$cambio = $datos['cambio'];

// Generar el contenido del ticket
$ticket = "<div style='font-family: monospace; width: 300px; padding: 10px;'>";
$ticket .= "<div style='text-align: center; margin-bottom: 10px;'>";
$ticket .= "<h2 style='margin: 5px 0;'>VentasPRO</h2>";
$ticket .= "<p style='margin: 5px 0;'>" . date('d/m/Y H:i:s') . "</p>";
$ticket .= "<hr>";
$ticket .= "</div>";

// Detalles de los productos
$ticket .= "<table style='width: 100%; margin-bottom: 10px;'>";
$ticket .= "<tr><th style='text-align: left;'>Producto</th><th>Cant</th><th style='text-align: right;'>Precio</th><th style='text-align: right;'>Total</th></tr>";

foreach ($carrito as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $ticket .= "<tr>";
    $nombre = $item['nombre'];
    if (isset($item['peso_total']) && $item['peso_total'] > 0) {
        $nombre .= " (" . $item['peso_total'] . "g)";
    }
    $ticket .= "<td style='text-align: left;'>" . $nombre . "</td>";
    $ticket .= "<td style='text-align: center;'>" . $item['cantidad'] . "</td>";
    $ticket .= "<td style='text-align: right;'>$" . number_format($item['precio'], 2) . "</td>";
    $ticket .= "<td style='text-align: right;'>$" . number_format($subtotal, 2) . "</td>";
    $ticket .= "</tr>";
}

$ticket .= "</table>";
$ticket .= "<hr>";

// Totales
$ticket .= "<div style='text-align: right; margin-top: 10px;'>";
$ticket .= "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
$ticket .= "<p>Efectivo: $" . number_format($dineroRecibido, 2) . "</p>";
$ticket .= "<p>Cambio: $" . number_format($cambio, 2) . "</p>";
$ticket .= "</div>";

$ticket .= "<div style='text-align: center; margin-top: 20px;'>";
$ticket .= "<p>¡Gracias por su compra vuelva pronto!</p>";
$ticket .= "</div>";
$ticket .= "</div>";

// Devolver el ticket HTML
echo json_encode(['success' => true, 'ticket' => $ticket]);