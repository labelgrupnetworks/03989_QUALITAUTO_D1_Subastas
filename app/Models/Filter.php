<?php

namespace App\Models;

use App\Providers\ToolsServiceProvider;

class Filter
{
	public const TYPE_LIKE = 'LIKE';
	public const TYPE_SAME = '=';
	public const TYPE_GREATER_THAN = '>';
	public const TYPE_SMALLER_THAN = '<';

	public $field;
	public $type;

	public function __construct($field, $type)
	{
		$this->field = $field;
		$this->type = $type;
	}

	public function getFilter($query, $field)
	{
		switch ($this->type) {
			case self::TYPE_LIKE:
				return $query->where("upper({$this->field})", 'like', "%". mb_strtoupper($field) ."%");

			case self::TYPE_SAME:
				return $query->where($this->field, $field);

			case self::TYPE_GREATER_THAN:
				return $query->where($this->field, '>=', ToolsServiceProvider::getDateFormat($field, 'Y-m-d', 'Y/m/d') . ' 00:00:00');

			case self::TYPE_SMALLER_THAN:
				return $query->where($this->field, '<=', ToolsServiceProvider::getDateFormat($field, 'Y-m-d', 'Y/m/d') . ' 00:00:00');

			default:
				return $query;
				break;
		}

	}
}
