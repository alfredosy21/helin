<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CommercialRequest;
use App\Models\WhatsAppNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommercialRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_cliente' => 'required|exists:customer_types,slug',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'rif' => 'nullable|string|max:20',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'estado' => 'required|exists:states,code',
            'ciudad' => 'required|exists:cities,slug',
            'direccion' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:2000',
            'envio' => 'required|exists:delivery_methods,slug',
            'otra_empresa_entrega' => 'nullable|string|max:255',
            'destinatario_nombre' => 'nullable|string|max:255',
            'destinatario_documento' => 'nullable|string|max:50',
            'destinatario_telefono' => 'nullable|string|max:20',
            'envio_estado' => 'nullable|exists:states,code',
            'envio_ciudad' => 'nullable|exists:cities,slug',
            'agencia_destino' => 'nullable|string|max:255',
            'pago' => 'required|exists:payment_methods,name',
            'numero_comprobante' => 'nullable|string|max:100',
            'privacy_accepted' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get customer type ID from slug
            $customerType = \App\Models\CustomerType::where('slug', $request->tipo_cliente)->first();

            // Get state and city IDs
            $state = \App\Models\State::where('code', $request->estado)->first();
            $city = \App\Models\City::where('slug', $request->ciudad)->first();

            // Get delivery method
            $deliveryMethod = \App\Models\DeliveryMethod::where('slug', $request->envio)->first();

            // Get payment method
            $paymentMethod = \App\Models\PaymentMethod::where('name', $request->pago)->first();

            // Get shipping state and city if provided
            $shippingState = null;
            $shippingCity = null;
            if ($request->envio_estado && $request->envio_ciudad) {
                $shippingState = \App\Models\State::where('code', $request->envio_estado)->first();
                $shippingCity = \App\Models\City::where('slug', $request->envio_ciudad)->first();
            }

            // Get WhatsApp number for the state
            $whatsappNumber = WhatsAppNumber::getActiveByState($state->id);

            // Get cart data from session or request
            $cartData = session('cart', []);
            if (empty($cartData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ], 400);
            }

            $commercialRequest = CommercialRequest::create([
                'customer_type_id' => $customerType->id,
                'first_name' => $request->nombre,
                'last_name' => $request->apellido,
                'cedula' => $request->cedula,
                'company_name' => $request->empresa,
                'rif' => $request->rif,
                'phone' => $request->telefono,
                'email' => $request->email,
                'state_id' => $state->id,
                'city_id' => $city->id,
                'address' => $request->direccion,
                'observations' => $request->observaciones,
                'delivery_method_id' => $deliveryMethod->id,
                'other_delivery_company' => $request->otra_empresa_entrega,
                'recipient_name' => $request->destinatario_nombre,
                'recipient_document' => $request->destinatario_documento,
                'recipient_phone' => $request->destinatario_telefono,
                'shipping_state_id' => $shippingState?->id,
                'shipping_city_id' => $shippingCity?->id,
                'destination_agency' => $request->agencia_destino,
                'payment_method_id' => $paymentMethod->id,
                'payment_receipt_number' => $request->numero_comprobante,
                'whatsapp_number_id' => $whatsappNumber?->id,
                'status' => 'pending',
                'cart_data' => $cartData,
                'privacy_accepted' => true,
            ]);

            // Clear cart after successful submission
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada exitosamente',
                'request_id' => $commercialRequest->id,
                'redirect_url' => route('solicitud-enviada', ['uuid' => $commercialRequest->uuid]),
                'whatsapp_number' => $whatsappNumber?->phone_number
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}
