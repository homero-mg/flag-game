<?php
session_start();

if (!isset($_SESSION['jugador'])) {
    header("Location: index_banderas.php");
    exit();
}

$jugador_final = $_SESSION['jugador'];
$puntos_finales = $_SESSION['puntos'];

$mensaje_db = "";

// 1. GUARDAR EN LA BASE DE DATOS USANDO LA RED DE SEGURIDAD TRY-CATCH
try {
    $conexion = new PDO("mysql:host=localhost;dbname=juego_banderas;charset=utf8mb4", "root", "");

    // Insertamos la marca del jugador usando consultas preparadas contra Inyección SQL
    $orden = $conexion->prepare("INSERT INTO ranking (jugador, puntuacion) VALUES (?, ?)");
    $orden->execute([$jugador_final, $puntos_finales]);
    
    $mensaje_db = "💾 ¡Tu puntuación ha sido registrada en el Leaderboard oficial!";

    // 2. RECUPERAR EL TOP 5 DE MEJORES JUGADORES PARA EL RANKING
    $consulta = $conexion->prepare("SELECT jugador, puntuacion FROM ranking ORDER BY puntuacion DESC LIMIT 5");
    $consulta->execute();
    $tabla_records = $consulta->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mensaje_db = "⚠️ Error al guardar el récord en el servidor: " . $e->getMessage();
    $tabla_records = []; // Array vacío para evitar fallos si la BD da problemas
}

// Destruimos la sesión para limpiar la memoria, la partida ya ha concluido
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados Finales - EuroQuiz</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #0f172a; 
            color: white; 
            padding: 40px 20px; 
            text-align: center; 
        }

        .caja-final { 
            background: #1e293b; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            max-width: 500px; margin: 0 auto; border: 1px solid #334155; }
            h1 { color: #10b981; margin-top: 0; }
        .puntuacion-grande { font-size: 48px; font-weight: bold; color: #f59e0b; margin: 20px 0; }
        .aviso-db { font-size: 13px; color: #94a3b8; background: #0f172a; padding: 10px; border-radius: 6px; margin-bottom: 30px; }
        
        /* TABLA DE LEADERBOARD */
        .tabla-ranking { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
            background: #0f172a; 
            border-radius: 8px; overflow: hidden; }
        .tabla-ranking th { background: #3b82f6; padding: 12px; font-size: 14px; }
        .tabla-ranking td { padding: 12px; border-bottom: 1px solid #1e293b; }
        .tabla-ranking tr:hover { background: #1e293b; }
        
        .btn-volver { display: inline-block; margin-top: 30px; background: #3b82f6; color: white; text-decoration: none; padding: 12px 25px; font-weight: bold; border-radius: 8px; transition: background 0.2s; }
        .btn-volver:hover { background: #2563eb; }
    </style>
</head>
<body>

<div class="caja-final">
    <h1>¡Fin de la Partida!</h1>
    <p>Buen trabajo, <strong><?php echo htmlspecialchars($jugador_final); ?></strong>. Has completado el recorrido de las 5 banderas.</p>
    
    <div class="puntuacion-grande"><?php echo $puntos_finales; ?> Pts</div>
    
    <div class="aviso-db"><?php echo $mensaje_db; ?></div>

    <h3>🏆 TOP 5 - Salón de la Fama</h3>
    <table class="tabla-ranking">
        <thead>
            <tr>
                <th>Posición</th>
                <th>Jugador</th>
                <th>Puntuación</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pos = 1;
            foreach ($tabla_records as $fila) {
                echo "<tr>";
                    echo "<td><strong>#$pos</strong></td>";
                    echo "<td>" . htmlspecialchars($fila['jugador']) . "</td>";
                    echo "<td style='color: #f59e0b; font-weight: bold;'>" . $fila['puntuacion'] . " pts</td>";
                echo "</tr>";
                $pos++;
            }
            if(count($tabla_records) == 0) {
                echo "<tr><td colspan='3' style='color:#64748b; font-style:italic;'>No hay marcas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="index_banderas.php" class="btn-volver">Jugar otra vez 🔁</a>
</div>

</body>
</html>