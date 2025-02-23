<?php
session_start(); // Asegurarse de iniciar la sesión antes de cualquier salida
require_once '../includes/db_connection.php';

// Asegurarse de que no haya salida antes de los headers
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Considera usar password_hash() y password_verify()
    
    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, complete todos los campos'
        ]);
        exit;
    }
    
    try {
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Aquí deberías usar password_verify() para comparar contraseñas
            if ($password === $user['password']) { // Cambiar esto por password_verify() en producción
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                
                echo json_encode([
                    'success' => true,
                    'message' => '¡Inicio de sesión exitoso!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Contraseña incorrecta'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
exit;