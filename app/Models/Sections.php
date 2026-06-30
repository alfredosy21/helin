<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model {

    // ==========================================
    // ---------- CONSTANTS FOR SECTION IDS ----------
    // ==========================================

    // HOME SECTIONS
    const HERO_HOME = 1; // helin.
    const FEEDBACK_BANNER = 2; // ¡Nos encantaría conocer tu opinión!
    const CLINICAL_RESOURCES_HERO = 3; // Centro de conocimiento clínico
    const CLINICAL_LIBRARY = 4; // Biblioteca clínica Helin
    const CLINICAL_CONTENT_FEATURE = 5; // Contenido clínico pensado para acompañar tu práctica.
    const IMPLANTOLOGY_PRODUCTS = 6; // Más vendidos en Implantología
    const GBR_PRODUCTS = 7; // Más vendidos en Regeneración Ósea Guiada
    const INSTRUMENTS_PRODUCTS = 8; // Más vendidos en Instrumentos y Equipos
    const TESTIMONIALS = 9; // Testimonios
    const FLOW_HOW_TO = 10; // ¿Cómo solicitar productos Helin?
    const CTA_HOME = 11; // ¿Listo para transformar tu práctica clínica? (Home)

    // NUESTRA EMPRESA SECTIONS
    const COMPANY_HERO = 12; // Comprometidos con la excelencia en cada solución
    const ABOUT_US = 13; // Soluciones que impulsan mejores resultados clínicos
    const MISSION_VISION = 14; // Misión y Visión
    const TEAM = 15; // Un equipo que te acompaña
    const ALLIES = 16; // Trabajamos junto a marcas líderes
    const NEAR_YOU = 17; // Estamos cerca de ti
    const CTA_COMPANY = 18; // ¿Listo para transformar tu práctica clínica?

    // POLÍTICAS SECTIONS
    const SHIPPING_POLICIES = 19; // Políticas de envío y garantías
    const TERMS_CONDITIONS = 20; // Términos y condiciones
    const PRIVACY_POLICIES = 21; // Políticas de privacidad

    // CONTACTO SECTIONS
    const CONTACT_HERO = 22; // ¿Tienes preguntas? Hablemos.

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
