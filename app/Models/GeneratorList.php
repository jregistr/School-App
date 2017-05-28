<?php

namespace App\Models;

use App\Util\C;

/**
 * Class GeneratorList
 * @package App\Models
 * @property int id
 * @property int student_id
 */
class GeneratorList extends BaseModel
{

    public $timestamps = false;
    protected $fillable = [
        C::STUDENT_ID
    ];

    public function entries()
    {

    }

}
