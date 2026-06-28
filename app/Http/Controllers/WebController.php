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
    public function producto($id)
    {
        $product = \App\Models\Product::with(['category', 'brand'])->find($id);

        if (!$product) {
            abort(404);
        }

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
        return view('web.solicitud');
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
