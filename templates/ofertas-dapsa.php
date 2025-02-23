<?php
require_once __DIR__ . '/../includes/db_connection.php';

// Obtener ofertas visibles ordenadas por día de la semana
$query = "SELECT * FROM ofertas WHERE visible = 1 ORDER BY orden, dia_semana";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($db));
}
?>

<!-- Ofertas Section -->
<section id="clients" class="clients section" style="font-family: Poppins;">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Ofertas de la Semana</h2>
        <p>Descubre nuestras increíbles ofertas para cada día de la semana</p>
    </div>

    <div class="container">
        <div class="swiper init-swiper">
            <script type="application/json" class="swiper-config">
                {
                    "loop": true,
                    "speed": 600,
                    "autoplay": {
                        "delay": 5000
                    },
                    "slidesPerView": "auto",
                    "pagination": {
                        "el": ".swiper-pagination",
                        "type": "bullets",
                        "clickable": true
                    },
                    "breakpoints": {
                        "320": {
                            "slidesPerView": 2,
                            "spaceBetween": 40
                        },
                        "480": {
                            "slidesPerView": 3,
                            "spaceBetween": 60
                        },
                        "640": {
                            "slidesPerView": 4,
                            "spaceBetween": 80
                        },
                        "992": {
                            "slidesPerView": 6,
                            "spaceBetween": 120
                        }
                    }
                }
            </script>
            <div class="swiper-wrapper align-items-center">
                <?php while ($oferta = mysqli_fetch_assoc($result)): ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($oferta['imagen']); ?>"
                            class="img-fluid oferta-imagen"
                            alt="<?php echo htmlspecialchars($oferta['titulo']); ?>"
                            data-oferta='<?php echo json_encode($oferta); ?>'>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Modal de Ofertas -->
<div class="modal fade" id="ofertaModal" tabindex="-1" aria-labelledby="ofertaModalLabel" aria-hidden="true" style="font-family: Poppins;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ofertaModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="/placeholder.svg" alt="" class="img-fluid oferta-modal-imagen">
                    </div>
                    <div class="col-md-6">
                        <div class="oferta-detalles">
                            <div class="dia-semana mb-3"></div>
                            <h3 class="titulo mb-3"></h3>
                            <p class="descripcion"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .clients .swiper-slide img {
        max-width: 150px;
        opacity: 0.8;
        transition: 0.3s;
        cursor: pointer;
    }

    .clients .swiper-slide img:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Estilos para el modal */
    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        background-color: #666;
        color: black;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        padding: 1.5rem;
    }

    .modal-header h5 {
        color: #850506;
    }

    .modal-body {
        padding: 2rem;
    }

    .oferta-modal-imagen {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .oferta-detalles .dia-semana {
        display: inline-block;
        padding: 5px 15px;
        background-color: #850506;
        color: white;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .oferta-detalles .titulo {
        color: #4a3728;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .oferta-detalles .descripcion {
        color: #666;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
        }

        .modal-body {
            padding: 1rem;
        }

        .oferta-modal-imagen {
            margin-bottom: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar los elementos de la oferta
        const ofertaModal = new bootstrap.Modal(document.getElementById('ofertaModal'));
        const modalTitle = document.getElementById('ofertaModalLabel');
        const modalImagen = document.querySelector('.oferta-modal-imagen');
        const modalDiaSemana = document.querySelector('.oferta-detalles .dia-semana');
        const modalTitulo = document.querySelector('.oferta-detalles .titulo');
        const modalDescripcion = document.querySelector('.oferta-detalles .descripcion');

        // Agregar evento click a cada imagen de oferta
        document.querySelectorAll('.oferta-imagen').forEach(img => {
            img.addEventListener('click', function() {
                const oferta = JSON.parse(this.dataset.oferta);

                // Actualizar el contenido del modal
                modalTitle.textContent = 'Oferta del ' + oferta.dia_semana;
                modalImagen.src = oferta.imagen;
                modalImagen.alt = oferta.titulo;
                modalDiaSemana.textContent = oferta.dia_semana;
                modalTitulo.textContent = oferta.titulo;
                modalDescripcion.textContent = oferta.descripcion;

                // Mostrar el modal
                ofertaModal.show();
            });
        });
    });
</script>