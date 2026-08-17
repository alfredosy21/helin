<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f4f7f8; margin: 0; padding: 24px; color: #1a3040; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(15,47,67,.08); }
        .header { background: #6BC2C3; padding: 32px 36px; }
        .header h1 { margin: 0; color: #fff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .header p  { margin: 4px 0 0; color: rgba(255,255,255,.85); font-size: 13px; }
        .body { padding: 32px 36px; }
        .field { margin-bottom: 20px; }
        .field label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #8fa8b0; margin-bottom: 4px; }
        .field p { margin: 0; font-size: 15px; color: #1a3040; }
        .message-box { background: #f4f7f8; border-radius: 10px; padding: 16px 20px; margin-top: 4px; }
        .message-box p { margin: 0; font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
        .divider { border: none; border-top: 1px solid #e8f0f0; margin: 24px 0; }
        .footer { padding: 16px 36px 28px; text-align: center; }
        .footer p { font-size: 12px; color: #8fa8b0; margin: 0; }
        .badge { display: inline-block; background: #6BC2C3; color: #fff; font-size: 11px; font-weight: 700; border-radius: 20px; padding: 3px 10px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>helin. — Nuevo mensaje de contacto</h1>
            <p>Recibiste un mensaje a través del formulario de contacto del sitio web.</p>
        </div>
        <div class="body">
            <div class="field">
                <label>Nombre</label>
                <p>{{ $senderName }}</p>
            </div>
            <div class="field">
                <label>Correo electrónico</label>
                <p><a href="mailto:{{ $senderEmail }}" style="color:#6BC2C3;">{{ $senderEmail }}</a></p>
            </div>
            @if($phone)
            <div class="field">
                <label>Teléfono</label>
                <p>{{ $phone }}</p>
            </div>
            @endif
            <div class="field">
                <label>Asunto</label>
                <p>{{ $subject }}</p>
            </div>
            <hr class="divider">
            <div class="field">
                <label>Mensaje</label>
                <div class="message-box">
                    <p>{{ $message }}</p>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>Este mensaje fue enviado desde <strong>helinlatam.com</strong></p>
        </div>
    </div>
</body>
</html>
