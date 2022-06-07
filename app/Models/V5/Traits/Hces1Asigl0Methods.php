<?php
namespace App\Models\V5\Traits;

trait Hces1Asigl0Methods
{
	public function getIsAwardedAttribute()
	{
		return ($this->cerrado_asigl0 == 'S' && !empty($this->implic_hces1));
	}

	/**
	 * return porcentaje
	 */
	public function getRevaluationAttribute()
	{
		if(empty($this->implic_hces1)){
			return 0;
		}
		return (($this->implic_hces1 / $this->impsalhces_asigl0) * 100);
	}

	/**
	 * return porcentaje
	 */
	public function getIncreaseAttribute()
	{
		if(empty($this->implic_hces1)){
			return 0;
		}
		return (($this->impsalhces_asigl0 / $this->implic_hces1) * 100);
	}
}
