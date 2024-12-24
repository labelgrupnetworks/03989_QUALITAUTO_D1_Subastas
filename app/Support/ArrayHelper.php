<?php

namespace App\Support;

class ArrayHelper
{
	const PREVIOUS = 'previous';
	const NEXT = 'next';

	public static function getAdjacentElementValue(array $array, $currentValue, string $direction)
	{
		$currentKey = array_search($currentValue, $array);

		if ($currentKey === false) {
			return false;
		}

		$keys = array_keys($array);
		$currentIndex = array_search($currentKey, $keys);

		if ($currentIndex === false) {
			return false;
		}

		$adjacentIndex = $direction === self::NEXT
			? $currentIndex + 1
			: $currentIndex - 1;

		if (isset($keys[$adjacentIndex])) {
			$adjacentKey = $keys[$adjacentIndex];
			return $array[$adjacentKey];
		}

		return false;
	}
}
