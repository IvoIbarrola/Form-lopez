<?php
/**
 * 1. Lógica de Extracción de Datos
 * Lee el archivo plano y lo convierte en un array asociativo.
 */
function obtenerDatos() {
    $archivo = 'datos/asistentes.txt'; 
    $registros = [];
    
    if (file_exists($archivo)) {
        // file() lee el archivo y devuelve cada línea como un elemento de un array
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lineas as $linea) {
            // El separador "|" debe coincidir con el que usaste en la rama 'back'
            $datos = explode('|', $linea); 
            
            // Usamos el operador null coalescing (??) para evitar errores de índice inexistente
            $registros[] = [
                'nombre'   => $datos[0] ?? 'Sin nombre',
                'apellido' => $datos[1] ?? '',
                'email'    => $datos[2] ?? 'N/A',
                'eventos'  => $datos[3] ?? 'Ninguno',
                'sueldo'   => $datos[4] ?? 'No especificado'
            ];
        }
    }
    return $registros;
}

/**
 * 2. Lógica de Procesamiento y Filtrado
 */
$todosLosRegistros = obtenerDatos();
$terminoBusqueda = $_GET['buscar'] ?? '';
$resultadosFiltrados = [];

if (!empty($terminoBusqueda)) {
    foreach ($todosLosRegistros as $r) {
        // Buscamos coincidencia en nombre o en la cadena de eventos (insensible a mayúsculas)
        if (stripos($r['nombre'], $terminoBusqueda) !== false || 
            stripos($r['eventos'], $terminoBusqueda) !== false) {
            $resultadosFiltrados[] = $r;
        }
    }
} else {
    $resultadosFiltrados = $todosLosRegistros;
}

// Métricas para la vista
$totalResultados = count($resultadosFiltrados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rama Estadística - Panel de Control</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 900px; margin: 0 auto; padding: 20px; }
        .card { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .busqueda { margin-bottom: 30px; }
        input[type="text"] { padding: 10px; width: 250px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .reset-link { margin-left: 10px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

    <header>
        <h1>Panel de Estadísticas</h1>
        <p>Entorno: <strong>Docker + PHP 7.4+</strong></p>
    </header>

    <section class="card">
        <h2>Resumen General</h2>
        <p>Total de registros encontrados: <strong><?= $totalResultados ?></strong></p>
    </section>

    <section class="busqueda">
        <form method="GET">
            <input type="text" name="buscar" 
                   placeholder="Filtrar por nombre o evento..." 
                   value="<?= htmlspecialchars($terminoBusqueda) ?>">
            <button type="submit">Buscar</button>
            <?php if ($terminoBusqueda): ?>
                <a href="estadistica.php" class="reset-link">Limpiar filtros</a>
            <?php endif; ?>
        </form>
    </section>

    <table>
        <thead>
            <tr>
                <th>Asistente</th>
                <th>Eventos Seleccionados</th>
                <th>Rango Salarial</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalResultados > 0): ?>
                <?php foreach ($resultadosFiltrados as $r): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($r['nombre'] . " " . $r['apellido']) ?></strong><br>
                            <small><?= htmlspecialchars($r['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($r['eventos']) ?></td>
                        <td><?= htmlspecialchars($r['sueldo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No hay datos que coincidan con la búsqueda.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>