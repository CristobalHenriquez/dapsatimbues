<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_categoria = isset($data['id']) ? (int)$data['id'] : 0;

        if ($id_categoria <= 0) {
            throw new Exception('ID de categoría inválido');
        }

        // Verificar si la categoría existe
        $query_check = "SELECT id_categoria FROM categorias WHERE id_categoria = ?";
        $stmt_check = mysqli_prepare($db, $query_check);
        mysqli_stmt_bind_param($stmt_check, "i", $id_categoria);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) === 0) {
            throw new Exception('La categoría no existe');
        }

        // Eliminar la categoría
        $query_delete = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt_delete = mysqli_prepare($db, $query_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $id_categoria);

        if (mysqli_stmt_execute($stmt_delete)) {
            // Reordenar las categorías restantes
            $query_reorder = "SET @rank := 0; UPDATE categorias SET orden = @rank:= @rank + 1 ORDER BY orden";
            mysqli_multi_query($db, $query_reorder);

            $response['success'] = true;
            $response['message'] = 'Categoría eliminada correctamente';
        } else {
            throw new Exception('Error al eliminar la categoría: ' . mysqli_error($db));
        }

        mysqli_stmt_close($stmt_delete);
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
}

mysqli_close($db);