<!-- Contact Section -->
<section id="contact" class="contact section" style="font-family: 'Esphimere', sans-serif;">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p>Comunicate con nosotros</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
            <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3358.066529174608!2d-60.781889474341185!3d-32.684276373702566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b643309fcbbd35%3A0x6b7fb9a107ffb61d!2sDapsa!5e0!3m2!1ses-419!2sar!4v1735851543095!5m2!1ses-419!2sar" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div><!-- End Google Maps -->

        <div class="row gy-4">

            <div class="col-lg-4">
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                        <h3>Direccion</h3>
                        <p>Timbues</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-telephone flex-shrink-0"></i>
                    <div>
                        <h3>Llama</h3>
                        <p>+1 5589 55488 55</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@example.com</p>
                    </div>
                </div><!-- End Info Item -->

            </div>

            <div class="col-lg-8">
                <form action="controladores/procesar-contacto.php" method="post" id="contactForm" class="contact-form">
                    <input type="hidden" name="form_type" value="dapsa">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Tu nombre" required>
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="Tu email" required>
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" placeholder="Asunto" required>
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Mensaje" required></textarea>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="loading d-none">Enviando mensaje...</div>
                            <div class="error-message" style="display: none;"></div>
                            <div class="sent-message d-none">Tu mensaje ha sido enviado. ¡Gracias!</div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Enviar Mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
</section>

<style>
    .contact-form .loading,
    .contact-form .error-message,
    .contact-form .sent-message {
        text-align: center;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .contact-form .loading {
        background: #ffd700;
    }

    .contact-form .error-message {
        color: #fff;
        background: #dc3545;
    }

    .contact-form .sent-message {
        color: #fff;
        background: #28a745;
    }

    .contact-form button[type="submit"] {
        background: var(--accent-color);
        border: none;
        padding: 12px 30px;
        color: #fff;
        transition: 0.4s;
        border-radius: 8px;
    }

    .contact-form button[type="submit"]:hover {
        background: var(--heading-color);
    }
</style>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const errorMessage = form.querySelector('.error-message');
        const loadingMessage = form.querySelector('.loading');
        const sentMessage = form.querySelector('.sent-message');
        const submitButton = form.querySelector('button[type="submit"]');

        // Reset messages
        errorMessage.style.display = 'none';
        loadingMessage.classList.remove('d-none');
        sentMessage.classList.add('d-none');
        submitButton.disabled = true;

        fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingMessage.classList.add('d-none');

                if (data.success) {
                    sentMessage.classList.remove('d-none');
                    form.reset();
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingMessage.classList.add('d-none');
                errorMessage.textContent = 'Ocurrió un error al enviar el mensaje. Por favor, inténtelo de nuevo.';
                errorMessage.style.display = 'block';
            })
            .finally(() => {
                submitButton.disabled = false;
            });
    });
</script>