<?php
include_once 'includes/auth.php';
requireAuth();
//INCLUYO HEAD
include 'includes/head-admin.php';
?>

<body class="admin-page">

    <?php include 'includes/nav-admin.php'; ?>

    <main class="main">
        <?php
        include 'templates/admin-main-ofertas.php';
        include 'includes/footer-admin.php';
        ?>
    </main>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Luego Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Después DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
</body>

</html>