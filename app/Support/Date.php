<?php

namespace App\Support;

class Date
{
	/**
	 * Formatea una cadena de fecha a un formato especificado.
	 *
	 * @param string $date La cadena de fecha a formatear.
	 * @param string $format El formato al que se debe convertir la fecha. Por defecto es 'd/m/Y H:i:s'.
	 * @return string La cadena de fecha formateada.
	 */
	public static function toFormat(string $date, string $format = 'd/m/Y H:i:s'): string
	{
		if (!$date) {
			return '';
		}
		return date($format, strtotime($date));
	}

	/**
	 * Combina una fecha con una hora específica y la formatea
	 *
	 * @param string|null $fecha Fecha en formato válido
	 * @param string|null $hora Hora específica a aplicar (opcional)
	 * @return string Fecha formateada en formato europeo o cadena vacía si no es válida
	 */
	public static function formatDateWithTime(?string $date, ?string $time = null): string
	{
		if (empty($date)) {
			return '';
		}

		// Si se proporciona una hora específica, combinarla con la fecha
		if (!empty($time)) {
			// Extraer solo la parte de la fecha (sin tiempo) y combinar con la nueva hora
			$dateOnly = date('Y-m-d', strtotime($date));
			$combinedDateTime = $dateOnly . ' ' . $time;
		} else {
			$combinedDateTime = $date;
		}

		// Convertir al formato europeo
		$timestamp = strtotime($combinedDateTime);
		if ($timestamp === false) {
			return '';
		}

		return date('d/m/Y H:i:s', $timestamp);
	}
}
