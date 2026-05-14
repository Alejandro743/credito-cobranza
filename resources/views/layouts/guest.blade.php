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

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #C4B5FD 0%, #A78BFA 50%, #8B7CF6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-wrap {
            display: flex;
            width: 100%;
            max-width: 880px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(80, 50, 180, .30);
            min-height: 540px;
        }

        .login-left {
            background: linear-gradient(145deg, #7B6FE8 0%, #9B8FF5 100%);
            flex: 1;
            padding: 52px 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            position: relative;
            overflow: hidden;
        }

        .login-right {
            background: #fff;
            width: 390px;
            flex-shrink: 0;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #CBCBCB;
            pointer-events: none;
        }
        .input-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #CBCBCB;
            display: flex;
            align-items: center;
        }
        .input-eye:hover { color: #8B7CF6; }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: 14px;
            color: #1F2937;
            background: #fff;
            font-family: 'Inter', sans-serif;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-input:focus {
            outline: none;
            border-color: #8B7CF6;
            box-shadow: 0 0 0 3px rgba(139,124,246,.15);
        }
        .form-input::placeholder { color: #D1D5DB; }

        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, #8B7CF6 0%, #7B6FE8 100%);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            letter-spacing: .3px;
            transition: opacity .15s, transform .1s, box-shadow .15s;
            box-shadow: 0 4px 14px rgba(123,111,232,.4);
        }
        .btn-login:hover {
            opacity: .93;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(123,111,232,.5);
        }
        .btn-login:active { transform: translateY(0); }

        @media (max-width: 640px) {
            .login-left  { display: none; }
            .login-right { width: 100%; padding: 36px 28px; }
            .login-wrap  { border-radius: 16px; }
        }
    </style>
</head>
<body>
<div class="login-wrap">

    {{-- Panel izquierdo: marca + ilustración --}}
    <div class="login-left">

        {{-- Logo --}}
        <div>
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
                <div style="width:46px; height:46px; background:rgba(255,255,255,.20); border:1.5px solid rgba(255,255,255,.35); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:20px; font-weight:800; color:#fff; letter-spacing:2px; line-height:1;">CREDIESSEN</div>
                    <div style="font-size:9px; color:rgba(255,255,255,.65); font-weight:600; letter-spacing:1.8px; text-transform:uppercase; margin-top:5px;">Crédito · Cobranza</div>
                </div>
            </div>
            <p style="font-size:13px; color:rgba(255,255,255,.75); line-height:1.9;">Sistema de gestión<br>de crédito y cobranza</p>
        </div>

        {{-- Ilustración --}}
        <div style="display:flex; justify-content:center; align-items:flex-end; flex:1; padding-top:24px;">
            <svg width="240" height="170" viewBox="0 0 240 170" fill="none" xmlns="http://www.w3.org/2000/svg">
                {{-- Card principal --}}
                <rect x="20" y="45" width="180" height="110" rx="14" fill="rgba(255,255,255,.15)" stroke="rgba(255,255,255,.30)" stroke-width="1.2"/>
                {{-- Header card --}}
                <rect x="20" y="45" width="180" height="30" rx="14" fill="rgba(255,255,255,.20)"/>
                <rect x="20" y="60" width="180" height="15" fill="rgba(255,255,255,.20)"/>
                <rect x="34" y="54" width="52" height="7" rx="3.5" fill="rgba(255,255,255,.75)"/>
                <rect x="166" y="54" width="20" height="7" rx="3.5" fill="rgba(255,255,255,.45)"/>
                {{-- Línea de separación --}}
                <line x1="20" y1="75" x2="200" y2="75" stroke="rgba(255,255,255,.15)" stroke-width="1"/>
                {{-- Barras de chart --}}
                <rect x="40"  y="108" width="16" height="28" rx="4" fill="rgba(255,255,255,.40)"/>
                <rect x="63"  y="94"  width="16" height="42" rx="4" fill="rgba(255,255,255,.65)"/>
                <rect x="86"  y="101" width="16" height="35" rx="4" fill="rgba(255,255,255,.45)"/>
                <rect x="109" y="86"  width="16" height="50" rx="4" fill="rgba(255,255,255,.80)"/>
                <rect x="132" y="97"  width="16" height="39" rx="4" fill="rgba(255,255,255,.55)"/>
                <rect x="155" y="90"  width="16" height="46" rx="4" fill="rgba(255,255,255,.70)"/>
                {{-- Card flotante arriba derecha --}}
                <rect x="152" y="8" width="80" height="46" rx="10" fill="rgba(255,255,255,.20)" stroke="rgba(255,255,255,.35)" stroke-width="1"/>
                <rect x="164" y="20" width="34" height="6" rx="3" fill="rgba(255,255,255,.65)"/>
                <rect x="164" y="31" width="22" height="5" rx="2.5" fill="rgba(255,255,255,.40)"/>
                <circle cx="218" cy="27" r="9" fill="rgba(255,255,255,.25)"/>
                <path d="M214 27l3 3 5-5" stroke="rgba(255,255,255,.90)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                {{-- Card flotante izquierda --}}
                <rect x="2" y="96" width="60" height="42" rx="10" fill="rgba(255,255,255,.17)" stroke="rgba(255,255,255,.28)" stroke-width="1"/>
                <circle cx="20" cy="113" r="8" fill="none" stroke="rgba(255,255,255,.55)" stroke-width="2"/>
                <path d="M20 105v8l5 3" stroke="rgba(255,255,255,.80)" stroke-width="1.8" stroke-linecap="round"/>
                <rect x="33" y="108" width="20" height="5" rx="2.5" fill="rgba(255,255,255,.50)"/>
                <rect x="33" y="117" width="14" height="5" rx="2.5" fill="rgba(255,255,255,.35)"/>
            </svg>
        </div>

    </div>

    {{-- Panel derecho: formulario --}}
    <div class="login-right">
        {{ $slot }}
        <p style="font-size:11px; color:#D1D5DB; text-align:center; margin-top:28px;">Crediessen &copy; {{ date('Y') }} · Sistema Interno</p>
    </div>

</div>
</body>
</html>
