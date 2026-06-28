<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre'   => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'asunto'   => ['required', 'string', 'max:100'],
            'mensaje'  => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'nombre.min'       => 'El nombre debe tener al menos 2 caracteres.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'Ingresa un correo electrónico válido.',
            'asunto.required'  => 'Selecciona un asunto.',
            'mensaje.required' => 'El mensaje es obligatorio.',
            'mensaje.min'      => 'El mensaje debe tener al menos 10 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->toArray(),
            ], 422);
        }

        $settings    = Settings::getSettings();
        $destination = $settings->email ?? config('mail.from.address');

        if (empty($destination)) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha configurado un correo de destino.',
            ], 500);
        }

        try {
            Mail::to($destination)->send(new ContactFormMail(
                senderName:  $request->input('nombre'),
                senderEmail: $request->input('email'),
                phone:       $request->input('telefono', ''),
                subject:     $request->input('asunto'),
                message:     $request->input('mensaje'),
            ));

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado! Te responderemos a la brevedad.',
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar el mensaje. Inténtalo de nuevo.',
            ], 500);
        }
    }
}
