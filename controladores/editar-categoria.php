<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_categoria = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id_categoria > 0) {
        $query = "SELECT * FROM categorias WHERE id_categoria = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_categoria);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($categoria = mysqli_fetch_assoc($result)) {
            echo json_encode($categoria);
        } else {
            echo json_encode(['error' => 'Categoría no encontrada']);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'ID de categoría inválido']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    try {
        $id_categoria = $_POST['id_categoria'];
        $nombre_categoria = $_POST['nombre_categoria'];
        $descripcion_categoria = $_POST['descripcion_categoria'];

        $query = "UPDATE categorias SET nombre_categoria = ?, descripcion_categoria = ? WHERE id_categoria = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $nombre_categoria, $descripcion_categoria, $id_categoria);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Categoría actualizada correctamente';
        } else {
            throw new Exception('Error al actualizar la categoría: ' . mysqli_error($db));
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
}

mysqli_close($db);