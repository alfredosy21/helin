<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo span { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: #09b6a2; color: #fff; font-weight: 700; font-size: 20px; border-radius: 12px; }
        h1 { color: #1e293b; font-size: 20px; margin-bottom: 8px; text-align: center; }
        p { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 16px; text-align: center; }
        .password-box { background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 16px; text-align: center; margin: 24px 0; }
        .password-box code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 18px; font-weight: 600; color: #0f172a; letter-spacing: 0.5px; }
        .btn { display: block; width: 100%; text-align: center; background: #09b6a2; color: #ffffff; text-decoration: none; padding: 12px 0; border-radius: 8px; font-size: 14px; font-weight: 600; margin-top: 24px; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span>H</span>
        </div>
        <h1>Hola, {{ $name }}</h1>
        <p>Se ha generado una nueva contraseña para tu cuenta. Inicia sesión con la contraseña que aparece a continuación.</p>

        <div class="password-box">
            <code>{{ $password }}</code>
        </div>

        <p style="font-size: 12px; color: #94a3b8;">Por seguridad, te recomendamos cambiar esta contraseña una vez que accedas al sistema.</p>

        <a href="{{ $loginLink }}" class="btn">Ir al inicio de sesión</a>

        <div class="footer">
            <p>Si no solicitaste este cambio, contacta al administrador de inmediato.</p>
            <p>&copy; {{ date('Y') }} {{ $company }}</p>
        </div>
    </div>
</body>
</html>
