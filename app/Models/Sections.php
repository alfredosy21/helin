<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model {

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'status',
        'position',
        'name_button',
        'url_button',
        'status',
        'status_content'
    ];

    /**
     * Set name_button attribute
     * @param string $value
     */
    public function setNameBotonAttribute($value) {

        $value = strip_tags($value);
        $value = preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value = trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['name_button'] = $value;
    }

    /**
     * Set url_button attribute
     * @param string $value
     */
    public function setUrlBotonAttribute($value) {

        $value = strtolower($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value = str_replace('http://', 'https://', $value);
        $value = trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url_button'] = $value;
    }

    /**
     * Get sections
     * @param string $id
     * @return array | object
     */
    public static function getSection($id) {
        $sections = Sections::where('id', $id)->first();
        return isset($sections) != 0 ? $sections : null;
    }
}
