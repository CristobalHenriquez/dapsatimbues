<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark nav-admin">
  <div class="container-fluid">
    <a class="navbar-brand" href="AdministracionProductos">Restaurant</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'AdministracionProductos') ? 'active' : ''; ?>" href="AdministracionProductos">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'AdministracionCategorias') ? 'active' : ''; ?>" href="AdministracionCategorias">Categorías</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'AdministracionOfertas') ? 'active' : ''; ?>" href="AdministracionOfertas">Ofertas</a>
        </li>
      </ul>
    </div>
    <a href="Carta" class="btn btn-warning carta-btn" target="_blank">CARTA</a>
  </div>
</nav>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-admin .nav-link');

    navLinks.forEach(link => {
      link.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px)';
      });

      link.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
      });
    });

    const cartaBtn = document.querySelector('.nav-admin .carta-btn');

    cartaBtn.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.05)';
    });

    cartaBtn.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });
</script>