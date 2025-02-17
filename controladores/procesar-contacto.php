<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function getEmailConfig($formType) {
    $configs = [
        'dapsa' => [
            'to_email' => 'contacto@dapsa.com',
            'to_name' => 'Dapsa Servicios',
            'subject_prefix' => 'Consulta Estación de Servicio: ',
            'template_title' => 'Nuevo mensaje de Estación de Servicio'
        ],
        'cabanas' => [
            'to_email' => 'reservas@dapsatimbues.com',
            'to_name' => 'Dapsa Cabañas',
            'subject_prefix' => 'Consulta Cabañas: ',
            'template_title' => 'Nueva consulta de Cabañas'
        ],
        'hoteles' => [
            'to_email' => 'reservas@hoteldapsatimbues.com',
            'to_name' => 'Dapsa Hotel',
            'subject_prefix' => 'Consulta Hotel: ',
            'template_title' => 'Nueva consulta de Hotel'
        ]
    ];

    return $configs[$formType] ?? $configs['dapsa'];
}

function sendContactEmail($formType, $name, $email, $subject, $message) {
    $mail = new PHPMailer(true);
    $config = getEmailConfig($formType);

    try {
        // Detectar si estamos en entorno local
        $isLocal = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1');

        if ($isLocal) {
            // Configuración para entorno local (MailHog)
            $mail->isSMTP();
            $mail->Host       = 'localhost';
            $mail->SMTPAuth   = false;
            $mail->Port       = 1025;
        } else {
            // Configuración para producción
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tu-email@gmail.com';
            $mail->Password   = 'tu-contraseña-de-aplicacion';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
        }

        // Configuración común del correo
        $mail->SMTPDebug  = 0;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom($email, $name);
        $mail->addAddress($config['to_email'], $config['to_name']);
        $mail->addReplyTo($email, $name);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $config['subject_prefix'] . $subject;
        $mail->Body    = "
            <h2>{$config['template_title']}</h2>
            <p><strong>Nombre:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Asunto:</strong> $subject</p>
            <p><strong>Mensaje:</strong></p>
            <p>$message</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo de contacto: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    
    $formType = $_POST['form_type'] ?? 'dapsa';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, complete todos los campos.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, ingrese un email válido.'
        ]);
        exit;
    }

    if (sendContactEmail($formType, $name, $email, $subject, $message)) {
        echo json_encode([
            'success' => true,
            'message' => 'Su mensaje ha sido enviado. ¡Gracias!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Hubo un error al enviar el mensaje. Por favor, inténtelo de nuevo más tarde.'
        ]);
    }
    exit;
}