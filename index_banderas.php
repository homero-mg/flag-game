<?php
session_start();

// Si el jugador introduce su nombre y pulsa "Empezar"
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['jugador'])) {
    // Limpiamos cualquier partida anterior
    session_unset();
    
    // Inicializamos las variables del juego en la sesión
    $_SESSION['jugador'] = trim($_POST['jugador']);
    $_SESSION['puntos'] = 0;
    $_SESSION['ronda'] = 1;
    
    // Redirigimos directamente a la pantalla de juego
    header("Location: juego_banderas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🌍 Quiz de Banderas de Europa</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #0f172a; 
            color: white; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }

        .caja-inicio { 
            background: #1e293b; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
            text-align: center; 
            max-width: 400px; 
            width: 100%; 
            border: 1px solid #334155; 
        }

        h1 { 
            color: #38bdf8; 
            margin-top: 0; 
        }

        input[type="text"] { 
            width: 100%; 
            padding: 12px; 
            margin: 20px 0; 
            border: 2px solid #475569; 
            border-radius: 8px; 
            background: #0f172a; 
            color: white; 
            font-size: 16px; 
            box-sizing: border-box; 
            text-align: center; 
        }

        input[type="text"]:focus { 
            outline: none; 
            border-color: #38bdf8; 
        }

        button { 
            background: #10b981; 
            color: white; 
            border: none; 
            padding: 12px; 
            width: 100%; 
            font-size: 16px; 
            font-weight: bold; 
            border-radius: 8px; 
            cursor: pointer; 
            transition: background 0.2s; 
        }

        button:hover { 
            background: #059669; 
        }

    </style>
</head>
<body>

<div class="caja-inicio">
    <h1>🇪🇺 EUROQUIZ</h1>
    <p>¿Cuánto sabes sobre las banderas de Europa? Responde a 5 preguntas para demostrarlo.</p>
    
    <form action="" method="POST">
        <input type="text" name="jugador" placeholder="Introduce tu nombre de jugador..." required maxlength="20" autocomplete="off">
        <button type="submit">Empezar Torneo</button>
    </form>
</div>

</body>
</html>