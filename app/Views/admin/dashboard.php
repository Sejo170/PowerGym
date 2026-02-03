<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <div class="container mt-5">
        <h1>Panel de Control 📊</h1>
        
        <div style="width: 50%;">
            <canvas id="miGrafica"></canvas>
        </div>
    </div>

    <script>
        // 1. Vamos a buscar los datos a la API
        fetch('<?= base_url('admin/datosGrafica') ?>')
            .then(response => response.json())
            .then(data => {
                
                // 2. Preparamos los datos para Chart.js
                const etiquetas = data.map(item => item.nombre); // Eje X (Nombres)
                const cantidades = data.map(item => item.total); // Eje Y (Números)

                // 3. Pintamos la gráfica
                const ctx = document.getElementById('miGrafica');

                new Chart(ctx, {
                    type: 'bar', // Tipo de gráfica: 'bar' (barras), 'pie' (quesito), 'line' (línea)
                    data: {
                        labels: etiquetas,
                        datasets: [{
                            label: 'Número de Reservas',
                            data: cantidades,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)', // Color de las barras (Azul)
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true // Que el eje Y empiece en 0
                            }
                        }
                    }
                });
            });
    </script>
</body>
</html>