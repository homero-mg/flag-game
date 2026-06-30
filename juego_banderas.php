<?php
session_start();

// Control de seguridad: Si no hay jugador registrado en la sesión, lo expulsamos al inicio
if (!isset($_SESSION['jugador'])) {
    header("Location: index_banderas.php");
    exit();
}

// 1. BANCO DE DATOS: El mapa de países con sus respectivos Emojis de bandera
$paises_europa = [
    "España" => "🇪🇸", 
    "Francia" => "🇫🇷", 
    "Alemania" => "🇩🇪", 
    "Italia" => "🇮🇹",
    "Portugal" => "🇵🇹", 
    "Reino Unido" => "🇬🇧", 
    "Países Bajos" => "🇳🇱", 
    "Bélgica" => "🇧🇪",
    "Grecia" => "🇬🇷", 
    "Suecia" => "🇸🇪", 
    "Noruega" => "🇳🇴", 
    "Irlanda" => "🇮🇪"
];

$mensaje_feedback = "";

// 2. PROCESAR RESPUESTA DE LA RONDA ANTERIOR (Si el usuario ha hecho clic en una bandera)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bandera_pulsada'])) {
    $respuesta_usuario = $_POST['bandera_pulsada'];
    $pregunta_correcta = $_POST['pais_pregunta_anterior'];

    if ($respuesta_usuario === $pregunta_correcta) {
        $_SESSION['puntos'] += 10;
        $mensaje_feedback = "<div class='feedback correcto'>¡Correcto! +10 puntos 🌟</div>";
    } else {
        $_SESSION['puntos'] -= 5;
        $mensaje_feedback = "<div class='feedback incorrecto'>¡Fallo! Era la de $pregunta_correcta. -5 puntos ❌</div>";
    }

    // Avanzamos de ronda
    $_SESSION['ronda']++;

    // CONDICIÓN DE FINAL DE JUEGO: Si ya ha respondido las 5 preguntas, vamos al podio
    if ($_SESSION['ronda'] > 5) {
        header("Location: final_banderas.php");
        exit();
    }
}

// 3. GENERAR LA NUEVA PREGUNTA ALEATORIA PARA ESTE TURNO
// array_rand() escoge una clave (nombre del país) de nuestro array al azar
$pais_pregunta_actual = array_rand($paises_europa);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ronda <?php echo $_SESSION['ronda']; ?> - EuroQuiz</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #0f172a; 
            color: white; 
            padding: 20px; 
            margin: 0; 
        }

        .marcador-superior { 
            max-width: 800px; 
            margin: 0 auto 20px auto; 
            display: flex; 
            justify-content: space-between; 
            background: #1e293b; 
            padding: 15px 25px; 
            border-radius: 8px; 
            border: 1px solid #334155; 
        }

        .destacado { 
            color: #38bdf8; 
            font-weight: bold; 
        }
        
        .caja-juego { 
            max-width: 800px; 
            margin: 0 auto; 
            text-align: center; 
            background: #1e293b; 
            padding: 30px; 
            border-radius: 12px; 
            border: 1px solid #334155; 
        }

        .pregunta-titulo { 
            font-size: 28px; 
            margin-bottom: 30px; 
        }
        
        /* CUADRÍCULA DE BANDERAS */
        .cuadricula-banderas { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); 
            gap: 20px; 
            margin-top: 20px; 
        }
        
        /* Convertimos los botones con banderas en tarjetas gigantes interactivas */
        .btn-bandera { 
            background: #334155; 
            border: 2px solid transparent; 
            border-radius: 10px; 
            font-size: 55px; 
            padding: 20px; 
            cursor: pointer; 
            transition: transform 0.2s, background 0.2s, border-color 0.2s; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }

        .btn-bandera:hover { 
            transform: scale(1.08); 
            background: #475569; 
            border-color: #38bdf8; 
        }
        
        .feedback { 
            padding: 12px; 
            border-radius: 6px; 
            font-weight: bold; 
            margin-bottom: 20px; 
            font-size: 16px; 
            text-align: center; 
            max-width: 800px; 
            margin-left: auto; 
            margin-right: auto; 
        }

        .correcto { 
            background: #dcfce7; 
            color: #166534; 
        }

        .incorrecto { 
            background: #fee2e2; 
            color: #991b1b; 
        }

    </style>
</head>
<body>

    <div class="marcador-superior">
        <span>👤 Jugador: <span class="destacado"><?php echo htmlspecialchars($_SESSION['jugador']); ?></span></span>
        <span>📋 Pregunta: <span class="destacado"><?php echo $_SESSION['ronda']; ?> / 5</span></span>
        <span>🏆 Puntos: <span class="destacado"><?php echo $_SESSION['puntos']; ?> pts</span></span>
    </div>

    <?php echo $mensaje_feedback; ?>

    <div class="caja-juego">
        <div class="pregunta-titulo">
            ¿Cuál es la bandera de: <strong style="color: #f59e0b; text-decoration: underline;"><?php echo $pais_pregunta_actual; ?></strong>?
        </div>

        <form action="" method="POST" class="cuadricula-banderas">
            <input type="hidden" name="pais_pregunta_anterior" value="<?php echo $pais_pregunta_actual; ?>">
            
            <?php 
            // Dibujamos todos los botones recorriendo nuestro diccionario de países
            foreach ($paises_europa as $nombre_pais => $emoji_bandera) {
                // Al pulsar el botón, enviamos el nombre del país al que pertenece ese emoji
                echo "<button type='submit' name='bandera_pulsada' value='$nombre_pais' class='btn-bandera'>";
                echo $emoji_bandera;
                echo "</button>";
            }
            ?>
        </form>
    </div>

</body>
</html>