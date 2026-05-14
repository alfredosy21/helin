<?php

namespace App\Models;

use App\Utils\Helpers;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model {

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'activity', 'ip'];

    /**
     * Get the user that owns the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Save user activity
     * @param string $activity
     * @return array | object
     */
    public static function saveActivity($activity) {
        try {
            return Activities::create([
                        'activity' => $activity,
                        'ip' => Helpers::getIp(),
                        'user_id' => auth()->guard('web')->User()->id
            ]);
        } catch (Exception $ex) {
            report($ex);
        }
        return false;
    }
}
