<?php
require_once 'includes/db_connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta - Restaurant</title>
    <link href="https://fonts.cdnfonts.com/css/esphimere" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/panton" rel="stylesheet">
    <link href="assets/css/main-carta.css" rel="stylesheet">
</head>
<body>
    <div class="menu-container">
        <header class="menu-header">
            <div class="decorative-corner top-left"></div>
            <div class="decorative-corner top-right"></div>
            <h1>Restaurant</h1>
            <p class="subtitle">Carta de Especialidades</p>
        </header>

        <main class="menu-content">
            <?php
            try {
                $query_categorias = "SELECT * FROM categorias ORDER BY orden ASC";
                $result_categorias = mysqli_query($db, $query_categorias);

                if (!$result_categorias) {
                    throw new Exception("Error al obtener categorías: " . mysqli_error($db));
                }

                while ($categoria = mysqli_fetch_assoc($result_categorias)) {
                    ?>
                    <section class="menu-section">
                        <h2 class="category-title">
                            <span class="line-left"></span>
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            <span class="line-right"></span>
                        </h2>
                        <p class="category-description"><?php echo htmlspecialchars($categoria['descripcion_categoria']); ?></p>
                        
                        <div class="menu-items">
                            <?php
                            $query_productos = "SELECT * FROM productos WHERE id_categoria = ? ORDER BY precio_producto ASC";
                            $stmt = mysqli_prepare($db, $query_productos);
                            mysqli_stmt_bind_param($stmt, "i", $categoria['id_categoria']);
                            mysqli_stmt_execute($stmt);
                            $result_productos = mysqli_stmt_get_result($stmt);

                            while ($producto = mysqli_fetch_assoc($result_productos)) {
                                ?>
                                <div class="menu-item">
                                    <div class="item-header">
                                        <h3 class="item-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                                        <span class="item-price">$<?php echo number_format($producto['precio_producto'], 0, '', '.'); ?></span>
                                    </div>
                                    <p class="item-description"><?php echo htmlspecialchars($producto['descripcion_producto']); ?></p>
                                </div>
                                <?php
                            }
                            mysqli_stmt_close($stmt);
                            ?>
                        </div>
                    </section>
                    <?php
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </main>

        <footer class="menu-footer">
            <div class="decorative-corner bottom-left"></div>
            <div class="decorative-corner bottom-right"></div>
        </footer>
    </div>
</body>
</html>