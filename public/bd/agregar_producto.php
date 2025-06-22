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

    $barcode        = isset($_POST['barcode']) ? $_POST['barcode'] : '';
    $nombre         = $_POST['nombre'];
    $descripcion    = $_POST['descripcion'];
    $precio         = floatval($_POST['precio']);
    $precio_compra  = isset($_POST['precio_compra']) ? floatval($_POST['precio_compra']) : 0;
    $precio_mayoreo = isset($_POST['precio_mayoreo']) ? floatval($_POST['precio_mayoreo']) : 0;
    $precio_kilo    = isset($_POST['precio_kilo']) ? floatval($_POST['precio_kilo']) : null;
    $peso_total     = isset($_POST['peso_total']) ? floatval($_POST['peso_total']) : null;
    $stock          = intval($_POST['stock']);
    $categoria      = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $codigo_clave   = isset($_POST['codigo_clave']) ? $_POST['codigo_clave'] : '';

    // Verificar si el código de barras ya existe
    if ($barcode !== '') {
        $check_sql = "SELECT id FROM productos WHERE barcode = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $barcode);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            echo json_encode(["error" => "Ya existe un producto con este código de barras."]);
            exit;
        }
        $check_stmt->close();
    }

    $sql = "INSERT INTO productos (barcode, nombre, descripcion, precio, precio_compra, precio_mayoreo, precio_kilo, peso_total, stock, categoria, codigo_clave, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdddddsssi", $barcode, $nombre, $descripcion, $precio, $precio_compra, $precio_mayoreo, $precio_kilo, $peso_total, $stock, $categoria, $codigo_clave, $usuario_id);
    //                      ↑ s=string, d=double, i=integer

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
