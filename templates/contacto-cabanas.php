<!-- Contact Section -->
<section id="contacto" class="contact-cabanas">
    <div class="container">
        <h2 class="section-title">Contacta con nosotros</h2>
        <p class="section-subtitle">¿Listo para tu escapada a la naturaleza?</p>

        <div class="contact-wrapper">
            <div class="contact-info">
                <div class="info-item">
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        <h3>Ubicación</h3>
                        <p>Cabañas DapsaTimbues, Bosque Encantado, Timbues</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <h3>Reservaciones</h3>
                        <p>+54 9 11 1234-5678</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>reservas@dapsatimbues.com</p>
                    </div>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3358.066529174608!2d-60.781889474341185!3d-32.684276373702566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b643309fcbbd35%3A0x6b7fb9a107ffb61d!2sDapsa!5e0!3m2!1ses-419!2sar!4v1735851543095!5m2!1ses-419!2sar" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <form class="contact-form" action="controladores/procesar-contacto.php" method="post" id="contactFormCabanas">
                <input type="hidden" name="form_type" value="cabanas">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Tu nombre" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Tu email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Asunto" required>
                </div>
                <div class="form-group">
                    <textarea name="message" rows="5" placeholder="Tu mensaje" required></textarea>
                </div>
                <div class="form-messages">
                    <div class="loading d-none">Enviando mensaje...</div>
                    <div class="error-message" style="display: none;"></div>
                    <div class="sent-message d-none">Tu mensaje ha sido enviado. ¡Gracias!</div>
                </div>
                <button type="submit" class="submit-btn">Enviar mensaje</button>
            </form>
        </div>
    </div>
</section>
<!-- /Contact Section -->

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
    document.getElementById('contactFormCabanas').addEventListener('submit', function(e) {
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