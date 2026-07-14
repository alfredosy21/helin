<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
// Importación de Componentes Livewire (Controladores de Nueva Generación)
use App\Http\Controllers\Cms\{
    AuthenticatedSessionController,
    PasswordResetLinkController,
    ProfileController,
    SettingsController,
    DashboardController,
    ProductsController,
    CategoriesController,
    BrandsController,
    LineController,
    SystemProductsController,
    ProductPlatformsController,
    TestimonialsController,
    BlogCategoriesController,
    BlogArticlesController,
    MenuController,
    PaymentMethodController,
    RolController,
    SectionController,
    UserController,
    PermissionsController,
    ResourceController,
    ResourceSpecialtyController,
    ResourceTypeController,
    CustomerTypesController,
    DeliveryMethodsController
};
use App\Http\Controllers\WebController;
use App\Http\Controllers\Web\ResourceFilterController;
use App\Http\Controllers\Web\ProductFilterController;
use App\Http\Controllers\Web\ContactController;

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
Route::get('/contactanos', [WebController::class, 'contactanos'])->name('contactanos');
Route::post('/contactanos/send', [ContactController::class, 'send'])->name('contactanos.send');
Route::get('/nuestra-empresa', [WebController::class, 'nuestraEmpresa'])->name('nuestra-empresa');
Route::get('/politicas', [WebController::class, 'politicas'])->name('politicas');
Route::get('/recursos-clinicos', [WebController::class, 'recursosClinicos'])->name('recursos-clinicos');
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
                ->middleware('permission:' . \App\Models\Module::ADMINISTRATORS);

        /* 2. Account & Profile ("Me" Module) */
        // Un solo componente Livewire maneja toda la lógica del perfil
        Route::get('/me', ProfileController::class)->name('profile.show');

        /* 3. Catalog & Medical Inventory */
        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::get('/products', ProductsController::class)->name('products.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCTS);
            Route::get('/products/create', ProductsController::class)->name('products.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCTS);
            Route::get('/family', CategoriesController::class)->name('family.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_FAMILIES);
            Route::get('/family/create', CategoriesController::class)->name('family.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_FAMILIES);
            Route::get('/brands', BrandsController::class)->name('brands.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_BRANDS);
            Route::get('/brands/create', BrandsController::class)->name('brands.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_BRANDS);
            Route::get('/lines', LineController::class)->name('lines.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_LINES);
            Route::get('/lines/create', LineController::class)->name('lines.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_LINES);
            Route::get('/system-products', SystemProductsController::class)->name('system-products.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::SYSTEM_PRODUCTS);
            Route::get('/system-products/create', SystemProductsController::class)->name('system-products.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::SYSTEM_PRODUCTS);
            Route::get('/product-platforms', ProductPlatformsController::class)->name('product-platforms.index')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_PLATFORMS);
            Route::get('/product-platforms/create', ProductPlatformsController::class)->name('product-platforms.create')
                    ->middleware('permission:' . \App\Models\Module::CATALOG . ',' . \App\Models\Submodule::PRODUCT_PLATFORMS);
        });

        /* 3.5. Content Management */
        Route::get('/testimonials', TestimonialsController::class)->name('testimonials.index')
                ->middleware('permission:5,1'); // Contenido (ID:5), Testimonios (submódulo 1)

        /* 3.6. Clinical Resources Management */
        Route::get('/resources', ResourceController::class)->name('resources.index')
                ->middleware('permission:5,2'); // Recursos Clínicos (submódulo 2)

        /* 3.7. Resource Types Management */
        Route::get('/resource-types', ResourceTypeController::class)->name('resource-types.index')
                ->middleware('permission:5,3'); // Tipos de Recursos (submódulo 3)

        /* 3.8. Resource Specialties Management */
        Route::get('/resource-specialties', ResourceSpecialtyController::class)->name('resource-specialties.index')
                ->middleware('permission:5,4'); // Especialidades (submódulo 4)

        /* 3.9. Payment Methods Management */
        Route::get('/payment-methods', PaymentMethodController::class)->name('payment-methods.index')
                ->middleware('permission:2,3'); // Configuración (ID:2), Métodos de Pago (submódulo 3)

        /* 3.11. Customer Types Management */
        Route::get('/customer-types', CustomerTypesController::class)->name('customer-types.index')
                ->middleware('permission:2,' . \App\Models\Submodule::CUSTOMER_TYPES);

        /* 3.12. Delivery Methods Management */
        Route::get('/delivery-methods', DeliveryMethodsController::class)->name('delivery-methods.index')
                ->middleware('permission:2,' . \App\Models\Submodule::DELIVERY_METHODS);

        /* 3.10. Website Menu Management */
        Route::get('/menu', MenuController::class)->name('menu.index')
                ->middleware('permission:2,4'); // Menú del Sitio (submódulo 4)

        /* 3.6. Blog Management */
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/categories', BlogCategoriesController::class)->name('categories.index')
                    ->middleware('permission:4,1'); // Blog (ID:4), Categorías (submódulo 1)
            Route::get('/categories/create', BlogCategoriesController::class)->name('categories.create')
                    ->middleware('permission:4,1');
            Route::get('/articles', BlogArticlesController::class)->name('articles.index')
                    ->middleware('permission:4,2'); // Artículos (submódulo 2)
            Route::get('/articles/create', BlogArticlesController::class)->name('articles.create')
                    ->middleware('permission:4,2');
        });

        /* 4. Global System Settings */
        Route::get('/settings', SettingsController::class)->name('settings.index')
                ->middleware('permission:2,1'); // Configuración General (submódulo 1)

        Route::get('/sections', SectionController::class)->name('sections.index')
                ->middleware('permission:2,2'); // Secciones (submódulo 2)

        /* 5. System Administration (RBAC & Users) */
        Route::prefix('system')->name('admin.')->group(function () {
            Route::get('/users', UserController::class)->name('users.index')
                    ->middleware('permission:Administradores,Usuarios');
            Route::get('/roles', RolController::class)->name('roles.index')
                    ->middleware('permission:Administradores,Roles');

            // Legacy permissions route (Si no se ha migrado a componente único aún)
            Route::get('/roles/{role}/permissions', [RolController::class, 'permission'])->name('roles.permissions')
                    ->middleware('permission:Administradores,Permisos');
        });

        // Permisos detallados por Rol (Nuevo componente Livewire) - Ruta CMS
        Route::get('/system/permissions/{roleId}', PermissionsController::class)->name('cms.permissions.index')
                ->middleware('permission:Administradores,Permisos');

        // Ruta CMS para roles (compatibilidad con vistas)
        Route::get('/system/roles', RolController::class)->name('cms.roles')
                ->middleware('permission:Administradores,Roles');

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
    Route::get('/recursos-clinicos/filtrar', [\App\Http\Controllers\Api\ResourceController::class, 'filtrar'])->name('api.recursos.filtrar');
});

// Enabled only for local development environment
if (app()->environment('local')) {
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('/routes', function () {
            return response()->json(collect(Route::getRoutes())->map(fn($r) => [
                        'method' => implode('|', $r->methods()),
                        'uri' => $r->uri(),
                        'name' => $r->getName()
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
