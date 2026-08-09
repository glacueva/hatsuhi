<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hatsuhi</title>
    <style>
        /* Reseteo básico de márgenes */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        /* Contenedor principal con la imagen de fondo */
        .hero-section {
            position: relative;
            width: 100vw;
            height: 100vh;
            /* Se hace referencia exacta al archivo solicitado */
            background-image: url('assets/png/welcome_background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden;
        }

        /* --- Elemento Superior Derecho (Texto Japonés Vertical) --- */
        .texto-vertical {
            position: absolute;
            top: 40px;
            right: 40px;
            color: white;
            /* Propiedades mágicas para el texto vertical de derecha a izquierda */
            writing-mode: vertical-rl;
            font-size: 1.5rem;
            letter-spacing: 0.15em;
            line-height: 1.6;
            /* Sombra para asegurar que se lea sobre el fondo claro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .texto-vertical p {
            margin: 0;
        }

        /* --- Elementos Inferior Izquierdo (Enlaces) --- */
        .enlaces-inferiores {
            position: absolute;
            bottom: 30px;
            left: 40px;
            display: flex;
            gap: 20px;
        }

        .enlaces-inferiores a {
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            transition: opacity 0.3s ease;
        }

        .enlaces-inferiores a:hover {
            opacity: 0.7;
        }

        /* --- Elemento Inferior Derecho (Logo) --- */
        .logo-hatsuhi {
            position: absolute;
            bottom: 30px;
            right: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        /* SVG básico simulando el icono del pájaro */
        .icono-logo {
            width: 40px;
            height: 40px;
            fill: white;
            filter: drop-shadow(2px 2px 3px rgba(0,0,0,0.5));
        }
        .texto-vertical[tooltip]:hover::after {
            content: attr(tooltip);
            position: absolute;
            top: -30px;
            right: 0;
            background-color: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            white-space: nowrap;
            writing-mode: horizontal-tb;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        
        <!-- Texto Japonés Arriba a la Derecha -->
        <div class="texto-vertical" tooltip="it's play for the cranes flying up to the clouds, the year's first sunrise (Chiyo-ni, translated by Michael R.Burch)">
            <p>鶴のあそび</p>
            <p>雲井にかなふ</p>
            <p>初日哉</p>
        </div>

        <!-- Enlaces Abajo a la Izquierda -->
        <div class="enlaces-inferiores">
            <a href="/admin">My Dashboard</a>
            <a href="/about">About</a>
            <a target="_blank" href="https://github.com/glacueva/hatsuhi">GitHub</a>
        </div>

        <!-- Logo Abajo a la Derecha -->
        <div class="logo-hatsuhi">
            <img src="assets/Hatsuhi.png" alt="Logo Hatsuhi" class="icono-logo">
            HATSUHI
        </div>

    </div>

</body>
</html>