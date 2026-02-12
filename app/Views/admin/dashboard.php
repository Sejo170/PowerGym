<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 text-primary">Panel de Control 🚀</h1>
        </div>

        <div class="row g-4 mb-5">
            
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small">Usuarios Registrados</h6>
                        <h2 class="display-6 fw-bold text-primary">
                            <?= $total_usuarios ?>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small">Productos en Tienda</h6>
                        <h2 class="display-6 fw-bold text-success">
                            <?= $total_productos ?>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small">Clases Activas</h6>
                        <h2 class="display-6 fw-bold text-warning">
                            <?= $total_clases ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">📊 Popularidad de las Clases</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="miGrafica"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // API (AJAX/FETCH)
        // Pedimos los datos al servidor sin recargar la página
        fetch('<?= base_url('admin/datosGrafica') ?>')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(data => {
                console.log("Datos recibidos:", data); // Para depurar si hace falta

                // Preparamos los arrays para Chart.js
                const etiquetas = data.map(item => item.nombre);
                const cantidades = data.map(item => item.total);

                // Configuramos la gráfica
                const ctx = document.getElementById('miGrafica').getContext('2d');
                
                new Chart(ctx, {
                    type: 'bar', // Puedes probar 'line', 'pie', 'doughnut'
                    data: {
                        labels: etiquetas,
                        datasets: [{
                            label: 'Reservas Totales',
                            data: cantidades,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 5 // Bordes redondeados en las barras
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Se adapta al contenedor
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2, 2] // Líneas discontinuas queda más moderno
                                }
                            },
                            x: {
                                grid: {
                                    display: false // Ocultamos líneas verticales para limpiar
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Ocultamos la leyenda si solo hay una serie
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error al cargar la gráfica:', error));
    </script>
</body>
</html>