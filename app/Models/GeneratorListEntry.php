<?php

namespace App\Models;

use App\Util\C;

/**
 * Class GeneratorListEntry
 * @package App\Models
 * @property integer generator_list_id
 * @property integer section_id
 * @property integer meeting_id
 * @property boolean required
 */
class GeneratorListEntry extends BaseModel
{
    public $timestamps = false;
    protected $fillable = [
        C::GENERATOR_LIST_ID,
        C::SECTION_ID,
        C::MEETING_ID,
        C::REQUIRED
    ];

    public function section()
    {
        return Section::find($this->section_id);
    }

    public function meeting()
    {
        return MeetingTime::find($this->meeting_id);
    }

}