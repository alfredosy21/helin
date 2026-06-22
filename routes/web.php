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
    RolController,
    SectionController,
    UserController,
    PermissionsController
};
use App\Http\Controllers\WebController;

/*
  |--------------------------------------------------------------------------
  | Public Website Routes
  |--------------------------------------------------------------------------
  */

Route::name('web.')->group(function () {
    Route::get('/', [WebController::class, 'home'])->name('home');
    Route::get('/catalogo', [WebController::class, 'catalogo'])->name('catalogo');
    Route::get('/producto', [WebController::class, 'producto'])->name('producto');
    Route::get('/carrito', [WebController::class, 'carrito'])->name('carrito');
    Route::get('/solicitud', [WebController::class, 'solicitud'])->name('solicitud');
    Route::get('/contactanos', [WebController::class, 'contactanos'])->name('contactanos');
    Route::get('/nuestra-empresa', [WebController::class, 'nuestraEmpresa'])->name('nuestra-empresa');
    Route::get('/politicas', [WebController::class, 'politicas'])->name('politicas');
    Route::get('/recursos-clinicos', [WebController::class, 'recursosClinicos'])->name('recursos-clinicos');
});

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
                ->middleware('permission:Administradores');

        /* 2. Account & Profile ("Me" Module) */
        // Un solo componente Livewire maneja toda la lógica del perfil
        Route::get('/me', ProfileController::class)->name('profile.show');

        /* 3. Catalog & Medical Inventory */
        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::get('/products', ProductsController::class)->name('products.index')
                    ->middleware('permission:Catálogo,Productos');
            Route::get('/products/create', ProductsController::class)->name('products.create')
                    ->middleware('permission:Catálogo,Productos');
            Route::get('/family', CategoriesController::class)->name('family.index')
                    ->middleware('permission:Catálogo,Familias');
            Route::get('/family/create', CategoriesController::class)->name('family.create')
                    ->middleware('permission:Catálogo,Familias');
            Route::get('/brands', BrandsController::class)->name('brands.index')
                    ->middleware('permission:Catálogo,Marcas');
            Route::get('/brands/create', BrandsController::class)->name('brands.create')
                    ->middleware('permission:Catálogo,Marcas');
            Route::get('/lines', LineController::class)->name('lines.index')
                    ->middleware('permission:Catálogo,Líneas');
            Route::get('/lines/create', LineController::class)->name('lines.create')
                    ->middleware('permission:Catálogo,Líneas');
            Route::get('/system-products', SystemProductsController::class)->name('system-products.index')
                    ->middleware('permission:Catálogo,Sistema de Productos');
            Route::get('/system-products/create', SystemProductsController::class)->name('system-products.create')
                    ->middleware('permission:Catálogo,Sistema de Productos');
            Route::get('/product-platforms', ProductPlatformsController::class)->name('product-platforms.index')
                    ->middleware('permission:Catálogo,Plataforma de Productos');
            Route::get('/product-platforms/create', ProductPlatformsController::class)->name('product-platforms.create')
                    ->middleware('permission:Catálogo,Plataforma de Productos');
        });

        /* 3.5. Content Management */
        Route::get('/testimonials', TestimonialsController::class)->name('testimonials.index')
                ->middleware('permission:Contenido,Testimonios');
        Route::get('/testimonials/create', TestimonialsController::class)->name('testimonials.create')
                ->middleware('permission:Contenido,Testimonios');

        /* 3.6. Blog Management */
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/categories', BlogCategoriesController::class)->name('categories.index')
                    ->middleware('permission:Blog,Categorías');
            Route::get('/categories/create', BlogCategoriesController::class)->name('categories.create')
                    ->middleware('permission:Blog,Categorías');
            Route::get('/articles', BlogArticlesController::class)->name('articles.index')
                    ->middleware('permission:Blog,Artículos');
            Route::get('/articles/create', BlogArticlesController::class)->name('articles.create')
                    ->middleware('permission:Blog,Artículos');
        });

        /* 4. Global System Settings */
        Route::get('/settings', SettingsController::class)->name('settings.index')
                ->middleware('permission:Configuración,Configuración General');

        Route::get('/sections', SectionController::class)->name('sections.index')
                ->middleware('permission:Configuración,Secciones');

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
