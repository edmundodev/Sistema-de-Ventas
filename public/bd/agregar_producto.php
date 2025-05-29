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
    // Verificar que todos los campos necesarios estén presentes
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

    // Procesar la imagen si se sube
    $imagenPath = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenExtension = pathinfo($imagenNombre, PATHINFO_EXTENSION);

        // Validar la extensión de la imagen (por ejemplo, solo imágenes jpg, jpeg, png)
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($imagenExtension), $allowedExtensions)) {
            echo json_encode(["error" => "Solo se permiten imágenes jpg, jpeg y png."]);
            exit;
        }

        // Definir el directorio donde se almacenará la imagen
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);  // Crear el directorio si no existe
        }

        
    }

    // Preparar la consulta SQL para insertar el producto en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Usar sentencias preparadas para evitar SQL Injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $categoria, $imagenPath);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Producto agregado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al agregar el producto."]);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Método HTTP no permitido."]);
}
?>
