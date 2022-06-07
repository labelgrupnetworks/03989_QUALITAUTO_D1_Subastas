<?php
namespace App\Models\V5\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ScopeFilter
{

	public function scopeWhenFilters(Builder $query, Request $request, array $filters) :Builder
	{
		foreach ($filters as $filter) {
			$query = $query->when($request->{$filter->field}, function($query, $field) use ($filter){
				return $filter->getFilter($query, $field);
			});
		}
		return $query;
	}
}
