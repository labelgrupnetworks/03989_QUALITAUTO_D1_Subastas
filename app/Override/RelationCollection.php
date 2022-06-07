<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class RelationCollection extends Collection
{

	public function testr($callback, $param1 = null, $param2 = null){
		$query = call_user_func_array($callback, [$param1]);
		dd($callback, $param1, $param2, $query);
	}

	public function relationWith($nameRelation, $relation, $callback, ...$secondRelation)
	{

		$relation = $this->whereAdapter($relation);

		if(Str::contains($nameRelation, '.')){
			//mover a otro metodo este y los siguientes
			[$instance, $nameRelation] = explode('.', $nameRelation);
			$instances = $this->pluck($instance);

			//en map
			//$query->whereIn($foreign_key, $this[0]->$instance->pluck($local_key));
		}

		$query = call_user_func($callback);

		foreach ($relation as [$foreign_key, $operator, $local_key]) {
			if($operator != '='){
				continue;
			}
			$query->whereIn($foreign_key, $this->pluck($local_key));
		}

		$itemsRelation = $query->get();

		return $this->map(function ($item, $key) use ($itemsRelation, $nameRelation, $relation) {

			foreach ($relation as [$foreign_key, $operator, $local_key]) {
				$itemsRelation = $itemsRelation->where( str_replace("\"", "", $foreign_key), $operator, $item->$local_key);
			}

			$item->setRelation($nameRelation, $itemsRelation->values());
			return $item;
		});
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
