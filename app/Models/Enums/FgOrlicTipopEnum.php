<?php

namespace App\Models\Enums;

/**
 * Tipo de operación de FgOrlic
 */
enum FgOrlicTipopEnum: string
{
	case WEB = 'W';
	case TELEFONO = 'T';
	case TELEFONO_WEB = 'X';
	case SALA = 'S';
	case INTERNACIONAL = 'I';
	case LIBRO = 'E';
	case LIBRO_WEB = 'O';
	case PUJA = 'P';
	case REPLICA = 'R';
	case SUBALIA = 'U';


	/**
	 * Get the display name of the enum value.
	 *
	 * @return string
	 */
	public function displayName(): string
	{
		return match ($this) {
			self::WEB => 'Web',
			self::TELEFONO => 'Telefónica',
			self::TELEFONO_WEB => 'Telefónicas Web',
			self::SALA => 'Sala',
			self::INTERNACIONAL => 'Internacional',
			self::LIBRO => 'Libro',
			self::LIBRO_WEB => 'Libro Web',
			self::PUJA => 'Puja',
			self::REPLICA => 'Réplica',
			self::SUBALIA => 'Subalia',
			default => '',
		};
	}

	/**
	 * Get the enum value from a string.
	 *
	 * @param string $value
	 * @return self|null
	 */
	public static function fromValue(string $value): ?self
	{
		return self::tryFrom($value);
	}
}
