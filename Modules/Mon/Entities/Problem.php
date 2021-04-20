<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use  SoftDeletes;

    protected $table = 'problems';
    protected $fillable = ['title'];
	public function problemPcategory()
	{
		return $this->hasMany(ProblemPcategory::class, 'problem_id');
	}
	public function pcategories()
	{
		return $this->belongsToMany(Pcategory::class, 'problem_pcategory', 'problem_id', 'pcategory_id');
	}
}
