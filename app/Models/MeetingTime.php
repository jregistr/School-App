<?php

namespace App\Models;

use App\Util\C;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeetingTime
 * @package App\Models
 *
 * @property int id
 * @property int start
 * @property int end
 * @property string location
 * @property boolean sunday
 * @property boolean monday
 * @property boolean tuesday
 * @property boolean wednesday
 * @property boolean thursday
 * @property boolean friday
 * @property boolean saturday
 */
class MeetingTime extends Model
{

    public $timestamps = false;
    protected $fillable = [
        C::START,
        C::END,
        C::LOCATION,
        C::SUNDAY,
        C::MONDAY,
        C::TUESDAY,
        C::WEDNESDAY,
        C::THURSDAY,
        C::FRIDAY,
        C::SATURDAY
    ];

}
