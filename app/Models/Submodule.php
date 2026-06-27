<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submodule extends Model {

    // ==========================================
    // ---------- CONSTANTS FOR SUBMODULE IDS ----------
    // ==========================================

    // ADMINISTRATORS (MODULE 1)
    const USERS = 1; // Usuarios
    const ROLES = 2; // Roles

    // SETTINGS (MODULE 2)
    const GENERAL_SETTINGS = 3; // Configuración General
    const SECTIONS = 4; // Secciones
    const PAYMENT_METHODS = 5; // Métodos de Pago
    const WEBSITE_MENU = 6; // Menú del Sitio

    // CATALOG (MODULE 3)
    const PRODUCTS = 7; // Productos
    const PRODUCT_FAMILIES = 8; // Familias de Productos
    const PRODUCT_BRANDS = 9; // Marcas de Productos
    const PRODUCT_LINES = 10; // Líneas de Productos
    const SYSTEM_PRODUCTS = 11; // Sistema de Productos
    const PRODUCT_PLATFORMS = 12; // Plataforma de Productos

    // BLOG (MODULE 4)
    const BLOG_CATEGORIES = 13; // Categorías del Blog
    const BLOG_ARTICLES = 14; // Artículos

    // CONTENT (MODULE 5)
    const TESTIMONIALS = 15; // Testimonios
    const CLINICAL_RESOURCES = 16; // Recursos Clínicos
    const RESOURCE_TYPES = 17; // Tipos de Recursos
    const RESOURCE_SPECIALTIES = 18; // Especialidades

    // CONTACT (MODULE 6)
    const CONTACT_MESSAGES = 19; // Mensajes de Contacto
    const CONTACT_MANAGEMENT = 20; // Gestión de Contactos
    const CONTACT_FORM_CONFIG = 21; // Configuración de Formulario

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'submodules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'module_id'];
}
