<style>
    /* Estilos para las animaciones (igual que antes) */
    .service-card {
        transition: all 0.3s ease;
        border-bottom: 5px solid transparent;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        border-bottom: 5px solid #0d6efd;
    }

    .icon-box {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 2rem;
    }
    
    /* TRUCO: Desplazamiento suave al pulsar el botón */
    html {
        scroll-behavior: smooth;
    }
</style>

<div class="position-relative p-5 text-center bg-dark" style="
    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
    background-size: cover;
    background-position: center;
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
">
    <div class="col-lg-8">
        <h1 class="display-3 fw-bold text-white mb-3">PowerGym 🏋️‍♂️</h1>
        <p class="lead text-white mb-4 fs-3">
            Donde empieza tu transformación.
        </p>
        
        <?php if (session()->has('nombre')): ?>
            <a href="#servicios" class="btn btn-success btn-lg px-5 py-3 rounded-pill fw-bold shadow">
                Descubre más 👇
            </a>
        <?php else: ?>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a href="<?= base_url('register') ?>" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow">Empezar Ahora</a>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold">Ya soy socio</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="bg-primary text-white py-4 text-center shadow">
    <div class="container">
        <figure class="mb-0">
            <blockquote class="blockquote">
                <p id="frase-motivacion" class="mb-2 fs-4 fst-italic">
                    Cargando motivación... 🔋
                </p>
            </blockquote>
            <figcaption id="autor-frase" class="blockquote-footer text-white-50 mb-0 fs-6">
                PowerGym
            </figcaption>
        </figure>
    </div>
</div>
<div id="servicios" class="bg-light py-5">
    <div class="container my-4">
        
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Todo lo que necesitas</h2>
            <p class="text-muted fs-5">Diseñado para maximizar tu rendimiento.</p>
        </div>

        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm service-card p-4 text-center">
                    <div class="icon-box shadow-sm text-primary">
                        💪
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h4 fw-bold mb-3">Musculación</h3>
                        <p class="text-muted">
                            Zona de peso libre y máquinas guiadas de alta gama para esculpir tu cuerpo.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm service-card p-4 text-center">
                    <div class="icon-box shadow-sm text-danger">
                        ⏱️
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h4 fw-bold mb-3">CrossFit & HIIT</h3>
                        <p class="text-muted">
                            Entrenamientos de alta intensidad para quemar grasa y mejorar tu resistencia.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm service-card p-4 text-center">
                    <div class="icon-box shadow-sm text-success">
                        🧘
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h4 fw-bold mb-3">Zona Zen</h3>
                        <p class="text-muted">
                            Clases de Yoga y Pilates para conectar cuerpo y mente tras el esfuerzo.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><div id="contacto" class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">📍 Encuéntranos</h2>
            <p class="text-muted fs-5">Ven a probar nuestras instalaciones o escríbenos.</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3079.4502350400226!2d-0.4447332235451631!3d39.48174697166549!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd604f4cf30b200b%3A0x60078049281a1796!2sQuart%20de%20Poblet%2C%20Valencia!5e0!3m2!1ses!2ses!4v1700000000000!5m2!1ses!2ses" 
                        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                    
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">PowerGym Valencia</h5>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-2">📍 C/ Del Músculo, 23, Quart de Poblet</li>
                            <li class="mb-2">📞 +34 960 00 00 00</li>
                            <li class="mb-2">📧 info@powergym.com</li>
                            <li>⏰ L-V: 06:00 - 23:00 | S-D: 09:00 - 15:00</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <h4 class="fw-bold mb-4">Envíanos un mensaje ✉️</h4>
                    <form>
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-muted">Nombre</label>
                            <input type="text" class="form-control bg-light border-0 py-2" id="nombre" placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">Correo electrónico</label>
                            <input type="email" class="form-control bg-light border-0 py-2" id="email" placeholder="nombre@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label text-muted">Mensaje</label>
                            <textarea class="form-control bg-light border-0" id="mensaje" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Enviar Mensaje 🚀</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('<?= base_url('home/obtenerFrase') ?>')
        .then(response => response.json())
        .then(data => {
            // La API nos devuelve una frase
            if (data.q) {
                document.getElementById('frase-motivacion').innerHTML = `"${data.q}"`;
                document.getElementById('autor-frase').innerText = data.a; 
            }
        })
        .catch(error => {
            console.log("No se pudo cargar la frase", error);
            // Frase de respaldo por si falla algo
            document.getElementById('frase-motivacion').innerText = "El dolor de hoy es la fuerza de mañana.";
        });
});
</script>