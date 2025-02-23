<?php
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia_semana = $_POST['dia_semana'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $visible = isset($_POST['visible']) ? 1 : 0;

    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["imagen"]["name"];
        $filetype = $_FILES["imagen"]["type"];
        $filesize = $_FILES["imagen"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            echo json_encode(["success" => false, "message" => "Error: Por favor seleccione un formato de archivo válido."]);
            exit;
        }

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            echo json_encode(["success" => false, "message" => "Error: El tamaño del archivo es mayor que el límite permitido (5 MB)."]);
            exit;
        }

        // Verify MYME type of the file
        if (!in_array($filetype, $allowed)) {
            echo json_encode(["success" => false, "message" => "Error: Hubo un problema con la carga del archivo. Por favor, inténtelo de nuevo."]);
            exit;
        }

        // Crear el nombre del archivo
        $new_filename = $dia_semana . '_' . date('Y-m-d') . '.' . $ext;
        $upload_path = "../uploads/ofertas/" . $new_filename;

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $upload_path)) {
            $imagen = "uploads/ofertas/" . $new_filename;

            // Insertar en la base de datos
            $query = "INSERT INTO ofertas (dia_semana, titulo, descripcion, imagen, visible) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "ssssi", $dia_semana, $titulo, $descripcion, $imagen, $visible);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["success" => true, "message" => "Oferta agregada correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al agregar la oferta: " . mysqli_error($db)]);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(["success" => false, "message" => "Error al subir la imagen."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No se ha seleccionado ninguna imagen o ha ocurrido un error."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>