<!DOCTYPE html>
<html>

<head>
    <title>Gráfica de Pujas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.2.0"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous" defer>
    </script>



</head>

<body>
    <div class="container pt-5">
        <h1 class="h2 mb-5">Tiempos de acciones en tiempo real</h1>

        <div class="row">
            <div class="col-3">
                <h5>Logs</h4>
                    <div class="list-group list-group-flush opacity-75">
                        @foreach ($logs as $log)
                            <a href="{{ $toFile($log) }}" @class([
                                'list-group-item list-group-item-action',
                                'active' =>
                                    request('day', request('file', '')) == $log ||
                                    (empty(request('day')) && $loop->first),
                            ])>
                                <span>
                                    {{ $log }}
                                </span>
                            </a>
                        @endforeach
                    </div>
            </div>
            <div class="col-9">
                <div class="mb-4">
                    <h5>Pujas</h5>
                    <canvas id="chartPujas"></canvas>
                </div>

                <div class="mb-4">
                    <h5>Cierres de lotes</h5>
                    <canvas id="chartCierres"></canvas>
                </div>

            </div>
        </div>
    </div>
    </section>

    <script>
        const actions = @json($actions);
        const pujas = actions.filter(item => item.action_type === 'BID');
        const closeLots = actions.filter(item => item.action_type === 'END_LOT');

        var labels = pujas.map(item => new Date(item.date));
        var tiempos = pujas.map(item => item.time);

        var dataPujas = pujas.map(item => {
            return {
                x: new Date(item.date),
                y: item.time,
                ref: item.ref,
                licitador: item.licitador,
                imp: item.imp,
                uuid: item.uuid
            }
        });

        var labelsCierres = closeLots.map(item => new Date(item.date));
        var tiemposCierres = closeLots.map(item => item.time);
        var dataCierres = closeLots.map(item => {
            return {
                x: new Date(item.date),
                y: item.time,
                ref: item.ref,
                uuid: item.uuid
            }
        });

        // Calculamos la media de los tiempos
        // Creamos un array con la media para cada punto
        var promedio = tiempos.reduce((a, b) => a + b, 0) / tiempos.length;
        var promedioArray = labels.map(() => promedio);

        var promedioCierres = tiemposCierres.reduce((a, b) => a + b, 0) / tiemposCierres.length;
        var promedioArrayCierres = labelsCierres.map(() => promedioCierres);

        const lineChartsDatasets = (labels, data, promedioArray) => {
            return {
                labels: labels,
                datasets: [{
                        label: 'Tiempo (ms)',
                        data: data,
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
            }
        }

        const copyUuid = (uuid) => {
            if (navigator.clipboard && window.isSecureContext) {
                // Si se encuentra en un contexto seguro (HTTPS), se usa la API moderna
                navigator.clipboard.writeText(uuid)
                    .then(() => alert('uuid copiado'))
                    .catch(err => console.error('Error al copiar con Clipboard API', err));
            } else {
                // Fallback para entornos sin HTTPS
                const textArea = document.createElement("textarea");
                textArea.value = uuid;
                // Se ajustan estilos para que el textarea no sea visible
                textArea.style.position = "fixed";
                textArea.style.top = 0;
                textArea.style.left = 0;
                textArea.style.width = "2em";
                textArea.style.height = "2em";
                textArea.style.padding = 0;
                textArea.style.border = "none";
                textArea.style.outline = "none";
                textArea.style.boxShadow = "none";
                textArea.style.background = "transparent";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    const successful = document.execCommand('copy');
                    alert('uuid copiado');
                } catch (err) {
                    console.error('Fallback: error al copiar', err);
                }
                document.body.removeChild(textArea);
            }
        }

        const scales = {
            x: {
                type: 'time',
                min: labels[0],
                time: {
                    displayFormats: {
                        minute: 'HH:mm',
                    },
                    tooltipFormat: 'HH:mm:ss'
                },
                ticks: {
                    source: 'auto',

                },
                title: {
                    display: true,
                    text: 'Hora'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Tiempo (ms)'
                }
            }
        };

        const zoomOptionsFromLabels = (labelsToLimits) => {
            return {
                limits: {
                    x: {
                        min: new Date(labelsToLimits[0]),
                        max: new Date(labelsToLimits[labelsToLimits.length - 1]),
                        minRange: 1000 * 60 * 60, //1hour

                    },
                    y: {
                        min: 0,
                        minRange: 1000
                    }
                },
                pan: {
                    enabled: true, // Activa el panning
                    mode: 'xy', // Solo desplazamiento horizontal
                    threshold: 10, // Umbral en píxeles para iniciar el pan (opcional)
                },
                zoom: {
                    wheel: {
                        enabled: true // Zoom con la rueda
                    },
                    pinch: {
                        enabled: true // Zoom con pellizco en pantallas táctiles
                    },
                    mode: 'x' // Zoom solo en el eje horizontal
                }
            };
        };

        const onClickEvent = (evt, activeElements) => {
            if (activeElements.length > 0) {
                const index = activeElements[0].index;
                const dataItem = pujas[index];
                copyUuid(dataItem.uuid);
            }
        };

        var ctx = document.getElementById('chartPujas').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: lineChartsDatasets(labels, dataPujas, promedioArray),
            options: {
                onClick: onClickEvent,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Tiempo (ms)') {
                                    var index = context.dataIndex;
                                    var dataItem = pujas[index];
                                    return [
                                        context.dataset.label + ': ' + context.formattedValue,
                                        //'Hora:' + dataItem.fecha,
                                        'Ref: ' + dataItem.ref,
                                        'Licitador: ' + dataItem.licitador,
                                        'Imp: ' + dataItem.imp,
                                        'uuid: ' + dataItem.uuid
                                    ];
                                }
                                return context.dataset.label + ': ' + context.formattedValue;
                            }
                        }
                    },
                    zoom: zoomOptionsFromLabels(labels)
                },
                scales
            }
        });

        var ctxCierres = document.getElementById('chartCierres').getContext('2d');
        var chartCierres = new Chart(ctxCierres, {
            type: 'line',
            data: lineChartsDatasets(labelsCierres, dataCierres, promedioArrayCierres),
            options: {
                onClick: onClickEvent,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Tiempo (ms)') {
                                    var index = context.dataIndex;
                                    var dataItem = closeLots[index];
                                    return [
                                        context.dataset.label + ': ' + context.formattedValue,
                                        'Ref: ' + dataItem.ref,
                                        'uuid: ' + dataItem.uuid
                                    ];
                                }
                                return context.dataset.label + ': ' + context.formattedValue;
                            }
                        }
                    },
                    zoom: zoomOptionsFromLabels(labelsCierres)
                },
                scales
            }
        });
    </script>


</body>

</html>
