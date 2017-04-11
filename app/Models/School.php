<?php

namespace App\Models;

use App\Util\C;

/**
 * Class School
 * @property integer id
 * @property string name
 * @property string country
 * @property string state
 * @property string city
 * @package App\Models
 */
class School extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::NAME,
        C::COUNTRY,
        C::STATE,
        C::CITY
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
