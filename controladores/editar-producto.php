<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json'); // Agregamos el header para JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        $id_producto = $_POST['id_producto'];
        $nombre_producto = $_POST['nombre_producto'];
        $id_categoria = $_POST['id_categoria'];
        $descripcion_producto = $_POST['descripcion_producto'];
        $precio_producto = $_POST['precio_producto'];

        $query = "UPDATE productos SET 
                nombre_producto = ?, 
                id_categoria = ?, 
                descripcion_producto = ?, 
                precio_producto = ? 
                WHERE id_producto = ?";

        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sisdi", $nombre_producto, $id_categoria, $descripcion_producto, $precio_producto, $id_producto);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Producto actualizado correctamente';
        } else {
            throw new Exception(mysqli_error($db));
        }

        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = array();
    
    try {
        if (!isset($_GET['id'])) {
            throw new Exception('ID no proporcionado');
        }

        $id_producto = $_GET['id'];
        
        $query = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_producto);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($db));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $producto = mysqli_fetch_assoc($result);
        
        if (!$producto) {
            throw new Exception('Producto no encontrado');
        }
        
        $response = $producto;
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);
}

mysqli_close($db);