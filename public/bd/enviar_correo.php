<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

// Cargar configuración desde archivo seguro
$config = require 'C:/xampp/config/correo_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST["nombre"] ?? ''));
    $email = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST["mensaje"] ?? ''));

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        echo json_encode(["error" => "Faltan campos obligatorios."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config['correo'];
        $mail->Password = $config['clave'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email, $nombre);
        $mail->addAddress($config['correo'], 'Administrador');

        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de $nombre";
        $mail->Body    = "<strong>Nombre:</strong> $nombre<br><strong>Email:</strong> $email<br><br><strong>Mensaje:</strong><br>$mensaje";
        $mail->AltBody = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";

        $mail->send();
        echo json_encode(["success" => "Mensaje enviado correctamente."]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al enviar el correo: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["error" => "Método no permitido."]);
}
