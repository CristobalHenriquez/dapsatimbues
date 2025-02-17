<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/DapsaLogo.png" alt="Logo de Dapsa Timbues">
            <!-- <h1 class="sitename">Dapsa</h1> -->
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#hero" class="nav-link">Inicio</a></li>
                <li><a href="#about" class="nav-link">Restaurant</a></li>
                <li><a href="#services" class="nav-link">Servicios</a></li>
                <li><a href="#contact" class="nav-link">Contacto</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', function() {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 60) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
    });
</script>