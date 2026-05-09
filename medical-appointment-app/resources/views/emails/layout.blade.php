<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 2px solid #007bff; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .button { display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Healthify</h2>
        </div>
        
        @yield('content')
        
        <div class="footer">
            &copy; {{ date('Y') }} Healthify. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
