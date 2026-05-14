<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'testimonials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'charge', 'position', 'image'];

    /**
     * Create testimony
     * @param array $data
     * @return array | object
     */
    static function createTestimonial($data) {
        try {
            $data['position'] = -1;
            return (new static)->create($data);
        } catch (Exception $ex) {
            report($ex);
        }
    }

    /**
     * Update testimony
     * @param array $data
     * @param string $testimony_id
     * @return array | object
     */
    public static function updateTestimonial($data, $testimony_id) {
        try {
            $testimony = Testimonial::find($testimony_id);
            $testimony->fill($data);
            $testimony->save();
            return $testimony;
        } catch (Exception $ex) {
            report($ex);
        }
    }

    /**
     * Get testimony
     * @param string $paginate
     * @return array | object
     */
    public static function getTestimonials($paginate = NULL) {

        $testimony = Testimonial::orderBy('position', 'asc')
                ->when(!is_null($paginate), function ($query) use ($paginate) {
                    return $query->offset(0)->limit($paginate);
                })
                ->get();

        return !empty($testimony) ? $testimony : [];
    }
}
