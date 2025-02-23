<?php
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM ofertas WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($oferta = mysqli_fetch_assoc($result)) {
        echo json_encode($oferta);
    } else {
        echo json_encode(["error" => "Oferta no encontrada"]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["error" => "Método no permitido o ID no proporcionado"]);
}
?>