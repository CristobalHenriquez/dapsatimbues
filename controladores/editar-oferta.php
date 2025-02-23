<?php
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $dia_semana = $_POST['dia_semana'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $visible = isset($_POST['visible']) ? 1 : 0;

    // Obtener la imagen actual
    $query_img = "SELECT imagen FROM ofertas WHERE id = ?";
    $stmt_img = mysqli_prepare($db, $query_img);
    mysqli_stmt_bind_param($stmt_img, "i", $id);
    mysqli_stmt_execute($stmt_img);
    $result = mysqli_stmt_get_result($stmt_img);
    $oferta = mysqli_fetch_assoc($result);
    $imagen_actual = $oferta['imagen'];
    mysqli_stmt_close($stmt_img);

    // Preparar la consulta base
    $query = "UPDATE ofertas SET dia_semana = ?, titulo = ?, descripcion = ?, visible = ?";
    $params = array($dia_semana, $titulo, $descripcion, $visible);
    $types = "sssi";

    $new_db_path = $imagen_actual; // Por defecto, mantener la imagen actual

    // Si hay una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["imagen"]["name"];
        $filetype = $_FILES["imagen"]["type"];
        $filesize = $_FILES["imagen"]["size"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Verificar extensión
        if (!array_key_exists($ext, $allowed)) {
            echo json_encode(["success" => false, "message" => "Error: Por favor seleccione un formato de archivo válido."]);
            exit;
        }

        // Verificar tamaño
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            echo json_encode(["success" => false, "message" => "Error: El tamaño del archivo es mayor que el límite permitido (5 MB)."]);
            exit;
        }

        // Verificar tipo MIME
        if (!in_array($filetype, $allowed)) {
            echo json_encode(["success" => false, "message" => "Error: Tipo de archivo no permitido."]);
            exit;
        }

        // Crear nuevo nombre de archivo
        $new_filename = $dia_semana . '_' . date('Y-m-d') . '_' . uniqid() . '.' . $ext;
        $upload_path = "../uploads/ofertas/" . $new_filename;
        $new_db_path = "uploads/ofertas/" . $new_filename;

        // Subir nueva imagen
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $upload_path)) {
            // Borrar imagen anterior si existe
            if ($imagen_actual && file_exists("../" . $imagen_actual) && $imagen_actual != $new_db_path) {
                unlink("../" . $imagen_actual);
            }
            
            // Agregar imagen a la consulta
            $query .= ", imagen = ?";
            $params[] = $new_db_path;
            $types .= "s";
        } else {
            echo json_encode(["success" => false, "message" => "Error al subir la imagen."]);
            exit;
        }
    }

    // Completar la consulta
    $query .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    // Ejecutar la actualización
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "success" => true, 
            "message" => "Oferta actualizada correctamente",
            "imagen" => $new_db_path
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Error al actualizar la oferta: " . mysqli_error($db)
        ]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>