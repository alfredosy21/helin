<?php

declare(strict_types=1);

use App\Http\Controllers\Cms\AttributesController;
// Importación de Componentes Livewire (Controladores de Nueva Generación)
use App\Http\Controllers\Cms\AttributeValuesController;
use App\Http\Controllers\Cms\AuthenticatedSessionController;
use App\Http\Controllers\Cms\BlogArticlesController;
use App\Http\Controllers\Cms\BlogCategoriesController;
use App\Http\Controllers\Cms\BrandsController;
use App\Http\Controllers\Cms\CategoriesController;
use App\Http\Controllers\Cms\CommercialRequestsController;
use App\Http\Controllers\Cms\ContactMessagesController;
use App\Http\Controllers\Cms\CustomerTypesController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\DeliveryMethodsController;
use App\Http\Controllers\Cms\LineController;
use App\Http\Controllers\Cms\MenuController;
use App\Http\Controllers\Cms\PageSeoController;
use App\Http\Controllers\Cms\PasswordResetLinkController;
use App\Http\Controllers\Cms\PaymentMethodController;
use App\Http\Controllers\Cms\PermissionsController;
use App\Http\Controllers\Cms\ProductPlatformsController;
use App\Http\Controllers\Cms\ProductsController;
use App\Http\Controllers\Cms\ProfileController;
use App\Http\Controllers\Cms\ResourceController;
use App\Http\Controllers\Cms\ResourceSpecialtyController;
use App\Http\Controllers\Cms\ResourceTypeController;
use App\Http\Controllers\Cms\RolController;
use App\Http\Controllers\Cms\SectionController;
use App\Http\Controllers\Cms\SettingsController;
use App\Http\Controllers\Cms\SystemProductsController;
use App\Http\Controllers\Cms\TestimonialsController;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\WhatsAppNumbersController;
use App\Http\Controllers\Web\CommercialRequestController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\ProductFilterController;
use App\Http\Controllers\Web\ResourceFilterController;
use App\Http\Controllers\WebController;
use App\Models\Module;
use App\Models\Submodule;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Public Website Routes
  |--------------------------------------------------------------------------
  */

Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/catalogo', [WebController::class, 'catalogo'])->name('catalogo');
Route::get('/producto/{slug}', [WebController::class, 'producto'])->name('producto');
Route::get('/carrito', [WebController::class, 'carrito'])->name('carrito');
Route::get('/solicitud', [WebController::class, 'solicitud'])->name('solicitud');
Route::post('/solicitud/send', [CommercialRequestController::class, 'store'])->name('solicitud.send');
Route::get('/contactanos', [WebController::class, 'contactanos'])->name('contactanos');
Route::post('/contactanos/send', [ContactController::class, 'send'])->name('contactanos.send');
Route::get('/nuestra-empresa', [WebController::class, 'nuestraEmpresa'])->name('nuestra-empresa');
Route::get('/politicas', [WebController::class, 'politicas'])->name('politicas');
Route::get('/recursos-clinicos', [WebController::class, 'recursosClinicos'])->name('recursos-clinicos');
Route::get('/caso-clinico/{slug}', [WebController::class, 'casoClinico'])->name('caso-clinico');
Route::get('/solicitud-enviada/{uuid}', [WebController::class, 'solicitudEnviada'])->name('solicitud-enviada');
Route::post('/api/resources/filter', [ResourceFilterController::class, 'filter'])->name('resources.filter');
Route::post('/api/products/filter', [ProductFilterController::class, 'filter'])->name('products.filter');
Route::get('/api/search/products', [WebController::class, 'searchProducts'])->name('api.search.products');

/*
  |--------------------------------------------------------------------------
  | CMS Main Architecture
  |--------------------------------------------------------------------------
 */

Route::prefix('cms')->group(function () {

    /* --- GUEST: AUTHENTICATION FLOW --- */
    Route::middleware('guest')->group(function () {

        // Login & Session Management
        Route::get('/', AuthenticatedSessionController::class)->name('login');

        // Password Recovery Flow
        Route::get('/forgot-password', PasswordResetLinkController::class)->name('password.request');
    });

    /* --- PROTECTED: CMS CORE (AUTH & VERIFIED) --- */
    Route::middleware(['auth', 'verified'])->group(function () {

        /* 1. Main Dashboard */
        Route::get('/dashboard', DashboardController::class)->name('dashboard')
            ->middleware('permission:'.Module::ADMINISTRATORS);

        /* 2. Account & Profile ("Me" Module) */
        // Un solo componente Livewire maneja toda la lógica del perfil
        Route::get('/me', ProfileController::class)->name('profile.show');

        /* 3. Catalog & Medical Inventory */
        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::get('/products', ProductsController::class)->name('products.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCTS);
            Route::get('/products/create', ProductsController::class)->name('products.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCTS);
            Route::get('/family', CategoriesController::class)->name('family.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_FAMILIES);
            Route::get('/family/create', CategoriesController::class)->name('family.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_FAMILIES);
            Route::get('/brands', BrandsController::class)->name('brands.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_BRANDS);
            Route::get('/brands/create', BrandsController::class)->name('brands.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_BRANDS);
            Route::get('/lines', LineController::class)->name('lines.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_LINES);
            Route::get('/lines/create', LineController::class)->name('lines.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_LINES);
            Route::get('/system-products', SystemProductsController::class)->name('system-products.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::SYSTEM_PRODUCTS);
            Route::get('/system-products/create', SystemProductsController::class)->name('system-products.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::SYSTEM_PRODUCTS);
            Route::get('/product-platforms', ProductPlatformsController::class)->name('product-platforms.index')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_PLATFORMS);
            Route::get('/product-platforms/create', ProductPlatformsController::class)->name('product-platforms.create')
                ->middleware('permission:'.Module::CATALOG.','.Submodule::PRODUCT_PLATFORMS);
        });

        /* 3.5. Content Management */
        Route::get('/testimonials', TestimonialsController::class)->name('testimonials.index')
            ->middleware('permission:'.Module::CONTENT.','.Submodule::TESTIMONIALS);

        /* 3.6. Clinical Resources Management */
        Route::get('/resources', ResourceController::class)->name('resources.index')
            ->middleware('permission:'.Module::CONTENT.','.Submodule::CLINICAL_RESOURCES);

        /* 3.7. Resource Types Management */
        Route::get('/resource-types', ResourceTypeController::class)->name('resource-types.index')
            ->middleware('permission:'.Module::CONTENT.','.Submodule::RESOURCE_TYPES);

        /* 3.8. Resource Specialties Management */
        Route::get('/resource-specialties', ResourceSpecialtyController::class)->name('resource-specialties.index')
            ->middleware('permission:'.Module::CONTENT.','.Submodule::RESOURCE_SPECIALTIES);

        /* 3.9. Payment Methods Management */
        Route::get('/payment-methods', PaymentMethodController::class)->name('payment-methods.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::PAYMENT_METHODS);

        /* 3.11. Customer Types Management */
        Route::get('/customer-types', CustomerTypesController::class)->name('customer-types.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::CUSTOMER_TYPES);

        /* 3.12. Delivery Methods Management */
        Route::get('/delivery-methods', DeliveryMethodsController::class)->name('delivery-methods.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::DELIVERY_METHODS);

        /* 3.13. Commercial Requests Management */
        Route::get('/commercial-requests', CommercialRequestsController::class)->name('commercial-requests.index')
            ->middleware('permission:'.Module::CONTACT.','.Submodule::COMMERCIAL_REQUESTS);

        /* 3.13b. Contact Messages Management */
        Route::get('/contact-messages', ContactMessagesController::class)->name('contact-messages.index')
            ->middleware('permission:'.Module::CONTACT.','.Submodule::CONTACT_MESSAGES);

        /* 3.14. WhatsApp Numbers Management */
        Route::get('/whatsapp-numbers', WhatsAppNumbersController::class)->name('whatsapp-numbers.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::WHATSAPP_NUMBERS); // Configuración (ID:2), Números de WhatsApp

        /* 3.17. Page SEO Management */
        Route::get('/page-seo', PageSeoController::class)->name('page-seo.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::PAGE_SEO); // Configuración (ID:2), SEO de Páginas

        /* 3.15. Attributes Management */
        Route::get('/attributes', AttributesController::class)->name('attributes.index')
            ->middleware('permission:'.Module::CATALOG.','.Submodule::ATTRIBUTES); // Catálogo (ID:3), Atributos de Productos

        /* 3.16. Attribute Values Management */
        Route::get('/attribute-values', AttributeValuesController::class)->name('attribute-values.index')
            ->middleware('permission:'.Module::CATALOG.','.Submodule::ATTRIBUTE_VALUES); // Catálogo (ID:3), Valores de Atributos

        /* 3.10. Website Menu Management */
        Route::get('/menu', MenuController::class)->name('menu.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::WEBSITE_MENU);

        /* 3.6. Blog Management */
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/categories', BlogCategoriesController::class)->name('categories.index')
                ->middleware('permission:'.Module::BLOG.','.Submodule::BLOG_CATEGORIES);
            Route::get('/categories/create', BlogCategoriesController::class)->name('categories.create')
                ->middleware('permission:'.Module::BLOG.','.Submodule::BLOG_CATEGORIES);
            Route::get('/articles', BlogArticlesController::class)->name('articles.index')
                ->middleware('permission:'.Module::BLOG.','.Submodule::BLOG_ARTICLES);
            Route::get('/articles/create', BlogArticlesController::class)->name('articles.create')
                ->middleware('permission:'.Module::BLOG.','.Submodule::BLOG_ARTICLES);
        });

        /* 4. Global System Settings */
        Route::get('/settings', SettingsController::class)->name('settings.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::GENERAL_SETTINGS);

        Route::get('/sections', SectionController::class)->name('sections.index')
            ->middleware('permission:'.Module::SETTINGS.','.Submodule::SECTIONS);

        /* 5. System Administration (RBAC & Users) */
        Route::prefix('system')->name('admin.')->group(function () {
            Route::get('/users', UserController::class)->name('users.index')
                ->middleware('permission:'.Module::ADMINISTRATORS.','.Submodule::USERS);
            Route::get('/roles', RolController::class)->name('roles.index')
                ->middleware('permission:'.Module::ADMINISTRATORS.','.Submodule::ROLES);

            // Legacy permissions route (Si no se ha migrado a componente único aún)
            Route::get('/roles/{role}/permissions', [RolController::class, 'permission'])->name('roles.permissions')
                ->middleware('permission:'.Module::ADMINISTRATORS);
        });

        // Permisos detallados por Rol (Nuevo componente Livewire) - Ruta CMS
        Route::get('/system/permissions/{roleId}', PermissionsController::class)->name('cms.permissions.index')
            ->middleware('permission:'.Module::ADMINISTRATORS);

        // Ruta CMS para roles (compatibilidad con vistas)
        Route::get('/system/roles', RolController::class)->name('cms.roles')
            ->middleware('permission:'.Module::ADMINISTRATORS.','.Submodule::ROLES);

        /* 6. Session Utilities & Security */
        // El componente AuthenticatedSessionController suele manejar el logout internamente,
        // pero definimos las rutas de bloqueo por seguridad.
        Route::get('/lock', [AuthenticatedSessionController::class, 'lock'])->name('session.lock');

        // Nota: El logout se dispara vía Livewire o mediante una ruta GET estándar
        Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
    });
});

/*
  |--------------------------------------------------------------------------
  | Internal API & Debugging
  |--------------------------------------------------------------------------
 */

Route::middleware(['auth'])->prefix('api/internal')->group(function () {
    Route::get('/session-check', [AuthenticatedSessionController::class, 'checkSession'])->name('api.session.check');
});

/*
|--------------------------------------------------------------------------
| API Routes - Public
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    Route::get('/recursos-clinicos/filtrar', [App\Http\Controllers\Api\ResourceController::class, 'filtrar'])->name('api.recursos.filtrar');
});

// Enabled only for local development environment
if (app()->environment('local')) {
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('/routes', function () {
            return response()->json(collect(Route::getRoutes())->map(fn ($r) => [
                'method' => implode('|', $r->methods()),
                'uri' => $r->uri(),
                'name' => $r->getName(),
            ]));
        })->name('routes');
    });
}

/*
  |--------------------------------------------------------------------------
  | Global Fallback Route
  |--------------------------------------------------------------------------
 */

Route::fallback(function () {
    return request()->expectsJson() ? response()->json(['message' => 'Resource not found in Helin CMS'], 404) : view('errors.404');
})->name('fallback');
