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
</div>