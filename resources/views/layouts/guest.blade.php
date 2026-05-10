<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #5C6F80; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .login-wrap { display: flex; width: 100%; max-width: 880px; border-radius: 20px; overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,.22); min-height: 540px; }
        .login-left  { background: #6D8196; flex: 1; padding: 52px 44px; display: flex; flex-direction: column; justify-content: space-between; min-width: 0; }
        .login-right { background: #fff; width: 380px; flex-shrink: 0; padding: 52px 40px; display: flex; flex-direction: column; justify-content: center; }
        .form-input { width: 100%; padding: 12px 14px; border: 1.5px solid #E7E0DC; border-radius: 10px; font-size: 14px; color: #374151; background: #FFFFE3; font-family: 'Inter', sans-serif; transition: border-color .15s, box-shadow .15s; }
        .form-input:focus { outline: none; border-color: #6D8196; background: #fff; box-shadow: 0 0 0 3px rgba(109,129,150,.12); }
        .form-input::placeholder { color: #CBCBCB; }
        .btn-login { width: 100%; padding: 14px; border-radius: 3px; background: #6D8196; color: #fff; font-size: 14px; font-weight: 700; border: none; cursor: pointer; font-family: 'Inter', sans-serif; letter-spacing: .3px; transition: background .15s; }
        .btn-login:hover { background: #5C6F80; }
        @media (max-width: 640px) {
            .login-left  { display: none; }
            .login-right { width: 100%; padding: 36px 28px; }
            .login-wrap  { border-radius: 16px; }
        }
    </style>
</head>
<body>
<div class="login-wrap">

    {{-- Panel izquierdo: marca --}}
    <div class="login-left">
        <div>
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:36px;">
                <div style="width:46px; height:46px; background:#BE185D; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:22px; font-weight:800; color:#fff; letter-spacing:2px; line-height:1;">CREDIESSEN</div>
                    <div style="font-size:9px; color:#E2EAF0; font-weight:600; letter-spacing:1.8px; text-transform:uppercase; margin-top:5px;">Crédito · Cobranza</div>
                </div>
            </div>
            <div style="width:36px; height:3px; background:#BE185D; border-radius:2px; margin-bottom:20px;"></div>
            <p style="font-size:14px; color:#E2EAF0; line-height:1.9;">Sistema de gestión<br>de crédito y cobranza</p>
        </div>
    </div>

    {{-- Panel derecho: formulario --}}
    <div class="login-right">
        {{ $slot }}
        <p style="font-size:11px; color:#D1C4BB; text-align:center; margin-top:28px;">Crediessen &copy; {{ date('Y') }} · Sistema Interno</p>
    </div>

</div>
</body>
</html>
