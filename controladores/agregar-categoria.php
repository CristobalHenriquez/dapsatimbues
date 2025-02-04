<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    try {
        $nombre_categoria = $_POST['nombre_categoria'];
        $descripcion_categoria = $_POST['descripcion_categoria'];

        // Obtener el máximo orden actual
        $query_max_orden = "SELECT MAX(orden) as max_orden FROM categorias";
        $result_max_orden = mysqli_query($db, $query_max_orden);
        $row_max_orden = mysqli_fetch_assoc($result_max_orden);
        $nuevo_orden = $row_max_orden['max_orden'] + 1;

        $query = "INSERT INTO categorias (nombre_categoria, descripcion_categoria, orden) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $nombre_categoria, $descripcion_categoria, $nuevo_orden);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Categoría agregada correctamente';
        } else {
            throw new Exception('Error al agregar la categoría: ' . mysqli_error($db));
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
}

mysqli_close($db);