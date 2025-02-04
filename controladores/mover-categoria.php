<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_categoria = isset($data['id']) ? (int)$data['id'] : 0;
        $direccion = isset($data['direccion']) ? $data['direccion'] : '';

        if ($id_categoria <= 0) {
            throw new Exception('ID de categoría inválido');
        }

        if ($direccion !== 'up' && $direccion !== 'down') {
            throw new Exception('Dirección inválida');
        }

        // Obtener el orden actual de la categoría
        $query_orden = "SELECT orden FROM categorias WHERE id_categoria = ?";
        $stmt_orden = mysqli_prepare($db, $query_orden);
        mysqli_stmt_bind_param($stmt_orden, "i", $id_categoria);
        mysqli_stmt_execute($stmt_orden);
        $result_orden = mysqli_stmt_get_result($stmt_orden);
        $row_orden = mysqli_fetch_assoc($result_orden);
        $orden_actual = $row_orden['orden'];

        // Calcular el nuevo orden
        $nuevo_orden = ($direccion === 'up') ? $orden_actual - 1 : $orden_actual + 1;

        // Actualizar el orden de la categoría afectada
        $query_update = "UPDATE categorias SET orden = ? WHERE orden = ?";
        $stmt_update = mysqli_prepare($db, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ii", $orden_actual, $nuevo_orden);
        mysqli_stmt_execute($stmt_update);

        // Actualizar el orden de la categoría que se está moviendo
        $query_move = "UPDATE categorias SET orden = ? WHERE id_categoria = ?";
        $stmt_move = mysqli_prepare($db, $query_move);
        mysqli_stmt_bind_param($stmt_move, "ii", $nuevo_orden, $id_categoria);
        mysqli_stmt_execute($stmt_move);

        $response['success'] = true;
        $response['message'] = 'Categoría movida correctamente';

    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
}

mysqli_close($db);