<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        // Validar y obtener los datos del formulario
        $nombre_producto = trim($_POST['nombre_producto']);
        $id_categoria = (int)$_POST['id_categoria'];
        $descripcion_producto = trim($_POST['descripcion_producto']);
        $precio_producto = (float)$_POST['precio_producto'];

        // Validaciones básicas
        if (empty($nombre_producto)) {
            throw new Exception('El nombre del producto es requerido');
        }

        if ($precio_producto <= 0) {
            throw new Exception('El precio debe ser mayor que 0');
        }

        // Verificar que la categoría existe
        $query_check = "SELECT id_categoria FROM categorias WHERE id_categoria = ?";
        $stmt_check = mysqli_prepare($db, $query_check);
        mysqli_stmt_bind_param($stmt_check, "i", $id_categoria);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) === 0) {
            throw new Exception('La categoría seleccionada no existe');
        }

        // Preparar la consulta de inserción
        $query = "INSERT INTO productos (nombre_producto, id_categoria, descripcion_producto, precio_producto) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sisi", $nombre_producto, $id_categoria, $descripcion_producto, $precio_producto);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Producto agregado correctamente';
            $response['id'] = mysqli_insert_id($db);
        } else {
            throw new Exception('Error al insertar el producto: ' . mysqli_error($db));
        }

        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);
}

mysqli_close($db);