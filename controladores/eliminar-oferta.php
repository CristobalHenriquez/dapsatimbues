<?php
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    // Primero, obtener la información de la imagen
    $query_select = "SELECT imagen FROM ofertas WHERE id = ?";
    $stmt_select = mysqli_prepare($db, $query_select);
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $oferta = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_select);

    // Eliminar la oferta de la base de datos
    $query_delete = "DELETE FROM ofertas WHERE id = ?";
    $stmt_delete = mysqli_prepare($db, $query_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $id);

    if (mysqli_stmt_execute($stmt_delete)) {
        // Si la eliminación fue exitosa, eliminar la imagen del servidor
        if ($oferta && isset($oferta['imagen'])) {
            $imagen_path = "../" . $oferta['imagen'];
            if (file_exists($imagen_path)) {
                if (unlink($imagen_path)) {
                    echo json_encode(["success" => true, "message" => "Oferta e imagen eliminadas correctamente"]);
                } else {
                    echo json_encode(["success" => true, "message" => "Oferta eliminada, pero hubo un problema al eliminar la imagen"]);
                }
            } else {
                echo json_encode(["success" => true, "message" => "Oferta eliminada, pero la imagen no se encontró"]);
            }
        } else {
            echo json_encode(["success" => true, "message" => "Oferta eliminada correctamente"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar la oferta: " . mysqli_error($db)]);
    }

    mysqli_stmt_close($stmt_delete);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>