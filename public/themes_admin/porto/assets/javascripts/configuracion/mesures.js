
// Función para calcular la media
const calcularMedia = (arr) => arr.reduce((acc, val) => acc + val, 0) / arr.length;

// Función para calcular la mediana
const calcularMediana = (arr) => {
	const sorted = [...arr].sort((a, b) => a - b);
	const mid = Math.floor(sorted.length / 2);
	return sorted.length % 2 !== 0
		? sorted[mid]
		: (sorted[mid - 1] + sorted[mid]) / 2;
};

// Función para calcular un percentil (p es un número entre 0 y 100)
const calcularPercentil = (arr, p) => {
	const sorted = [...arr].sort((a, b) => a - b);
	const index = Math.floor(p / 100 * sorted.length);
	return sorted[index];
};

// Función para calcular los cuartiles
const calcularCuartiles = (arr) => {
	const sorted = [...arr].sort((a, b) => a - b);
	const Q2 = calcularMediana(sorted);
	const mid = Math.floor(sorted.length / 2);
	let lowerHalf, upperHalf;
	if (sorted.length % 2 === 0) {
		lowerHalf = sorted.slice(0, mid);
		upperHalf = sorted.slice(mid);
	} else {
		lowerHalf = sorted.slice(0, mid);  // sin incluir la mediana
		upperHalf = sorted.slice(mid + 1);
	}
	const Q1 = calcularMediana(lowerHalf);
	const Q3 = calcularMediana(upperHalf);
	return { Q1, Q2, Q3 };
};

// Función para calcular la desviación estándar
const calcularDesviacionEstandar = (arr) => {
	const media = calcularMedia(arr);
	const sumaDeDiferenciasCuadrado = arr.reduce((acc, val) => acc + Math.pow(val - media, 2), 0);
	return Math.sqrt(sumaDeDiferenciasCuadrado / arr.length);
};

const calcularTiempos = (arr) => {
	const tiempos = arr.map(item => item.time);
	const media = calcularMedia(tiempos);
	const percentil90 = calcularPercentil(tiempos, 90);
	const cantidadBajoMedia = tiempos.filter(time => time < media).length;
	const porcentajeBajoMedia = (cantidadBajoMedia / tiempos.length) * 100;
	const { Q1, Q2, Q3 } = calcularCuartiles(tiempos);
	const desviacionEstandar = calcularDesviacionEstandar(tiempos);
	return { media, percentil90, porcentajeBajoMedia, Q1, Q2, Q3, desviacionEstandar };
};

const printar = (data) => {

	console.log("Media:", data.media.toFixed(2));
	console.log(`Porcentaje por debajo de la media: ${data.porcentajeBajoMedia.toFixed(2)}%`);
	console.log("Percentil 90:", data.percentil90.toFixed(2));
	console.log("Cuartil 1 (Q1):", data.Q1.toFixed(2));
	console.log("Cuartil 2 (Mediana):", data.Q2.toFixed(2));
	console.log("Cuartil 3 (Q3):", data.Q3.toFixed(2));
	console.log("Desviación Estándar:", data.desviacionEstandar.toFixed(2));
};

const closeLotsData = calcularTiempos(closeLots ?? []);
const pujasData = calcularTiempos(pujas ?? []);

printar(pujasData);
printar(closeLotsData);
