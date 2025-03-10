<!DOCTYPE html>
<html>

<head>
    <title>Gráfica de Pujas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous" defer>
    </script>
</head>

<body>
    <div class="container">
        <canvas id="chartPujas"></canvas>
    </div>

    <script>
        var pujas = @json($data);
        var labels = pujas.map(item => item.id);
        var tiempos = pujas.map(item => item.tiempo);

        // Calculamos la media de los tiempos
        var promedio = tiempos.reduce((a, b) => a + b, 0) / tiempos.length;
        // Creamos un array con la media para cada punto
        var promedioArray = labels.map(() => promedio);

        var ctx = document.getElementById('chartPujas').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Tiempo (ms)',
                        data: tiempos,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0,
                        pointRadius: 5
                    },
                    {
                        label: 'Media',
                        data: promedioArray,
                        type: 'line',
                        borderColor: 'red',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        pointRadius: 0,
                        tension: 0
                    }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Tiempo (ms)') {
                                    var index = context.dataIndex;
                                    var dataItem = pujas[index];
                                    return [
                                        context.dataset.label + ': ' + context.formattedValue,
										'Hora:' + dataItem.fecha,
                                        'Ref: ' + dataItem.ref,
                                        'Licitador: ' + dataItem.licitador
                                    ];
                                }
                                return context.dataset.label + ': ' + context.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
