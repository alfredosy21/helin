<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Página principal (Home)
     */
    public function home()
    {
        return view('web.home');
    }

    /**
     * Catálogo de productos
     */
    public function catalogo()
    {
        return view('web.catalogo');
    }

    /**
     * Detalle de producto
     */
    public function producto(string $slug)
    {
        $product = \App\Models\Product::with(['category', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('web.producto', compact('product'));
    }

    /**
     * Carrito de compras
     */
    public function carrito()
    {
        return view('web.carrito');
    }

    /**
     * Solicitud comercial (checkout)
     */
    public function solicitud()
    {
        $customerTypes   = \App\Models\CustomerType::active()->ordered()->get();
        $deliveryMethods = \App\Models\DeliveryMethod::active()->ordered()->get();
        $paymentMethods  = \App\Models\PaymentMethod::active()->ordered()->get();
        $states          = \App\Models\State::ordered()->get();
        $cities          = \App\Models\City::all(); // all for JS filter

        return view('web.solicitud', compact('customerTypes', 'deliveryMethods', 'paymentMethods', 'states', 'cities'));
    }

    /**
     * Contacto
     */
    public function contactanos()
    {
        return view('web.contactanos');
    }

    /**
     * Nuestra empresa
     */
    public function nuestraEmpresa()
    {
        return view('web.nuestra-empresa');
    }

    /**
     * Políticas
     */
    public function politicas()
    {
        return view('web.politicas');
    }

    /**
     * Recursos clínicos
     */
    public function recursosClinicos()
    {
        return view('web.recursos-clinicos');
    }
}
