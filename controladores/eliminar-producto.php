<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_producto = isset($data['id']) ? (int)$data['id'] : 0;

        if ($id_producto <= 0) {
            throw new Exception('ID de producto inválido');
        }

        // Verificar si el producto existe
        $query_check = "SELECT id_producto FROM productos WHERE id_producto = ?";
        $stmt_check = mysqli_prepare($db, $query_check);
        mysqli_stmt_bind_param($stmt_check, "i", $id_producto);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) === 0) {
            throw new Exception('El producto no existe');
        }

        // Eliminar el producto
        $query_delete = "DELETE FROM productos WHERE id_producto = ?";
        $stmt_delete = mysqli_prepare($db, $query_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $id_producto);

        if (mysqli_stmt_execute($stmt_delete)) {
            $response['success'] = true;
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            throw new Exception('Error al eliminar el producto: ' . mysqli_error($db));
        }

        mysqli_stmt_close($stmt_delete);
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);
}

mysqli_close($db);