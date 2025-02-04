<!-- Contact Section -->
<section id="contacto" class="hotel-contact">
    <div class="container">
        <h2 class="hotel-title">Contáctenos</h2>
        <p class="hotel-subtitle">Estamos a su disposición para hacer su estancia inolvidable</p>

        <div class="contact-wrapper">
            <div class="contact-info">
                <div class="info-item">
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        <h3>Ubicación</h3>
                        <p>Hotel DapsaTimbues, Av. Principal 123, Timbues</p>
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
                        <p>reservas@hoteldapsatimbues.com</p>
                    </div>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3358.066529174608!2d-60.781889474341185!3d-32.684276373702566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b643309fcbbd35%3A0x6b7fb9a107ffb61d!2sDapsa!5e0!3m2!1ses-419!2sar!4v1735851543095!5m2!1ses-419!2sar" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <form class="contact-form" action="forms/contact.php" method="post">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Su nombre" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Su email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Asunto" required>
                </div>
                <div class="form-group">
                    <textarea name="message" rows="5" placeholder="Su mensaje" required></textarea>
                </div>
                <button type="submit" class="hotel-button">Enviar mensaje</button>
            </form>
        </div>
    </div>
</section>
<!-- /Contact Section -->