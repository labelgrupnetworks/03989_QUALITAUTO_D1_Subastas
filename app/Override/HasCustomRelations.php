<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Support\Str;

trait HasCustomRelations
{
	use HasRelationships;


	public function hasCustomMany($related, $foreignKeys, $localKeys)
	{
		$instance = $this->newRelatedInstance($related);

		$foreignsTable = array_map(function($foreignKey) use ($instance){
			return $instance->getTable().'.'.$foreignKey;
		}, $foreignKeys);

		return $this->newHasCustomMany(
			$instance->newQuery(), $this, $foreignsTable, $localKeys
		);
	}

	public function newHasCustomMany(Builder $query, Model $parent, $foreignKey, $localKey)
	{

	}


	protected function whereAdapter($where)
	{
		if (count(array_keys($where)) === 1 && is_string($where[array_keys($where)[0]])) {
			return [[array_keys($where)[0], '=', $where[array_keys($where)[0]]]];
		}

		if (!is_array($where[0])) {

			if (count($where) == 2) {
				return [[$where[0], '=', $where[1]]];
			}

			return [$where];
		}

		return array_map(function ($values) {

			if (count($values) === 2) {
				return [$values[0], '=', $values[1]];
			}

			return $values;

		}, $where);
	}

}
