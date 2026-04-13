<?php

// Indicamos que la respuesta del servidor será en formato JSON
header('Content-Type: application/json');

// Verificamos que la petición sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Si no es POST, devolvemos un error en formato JSON
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Metodo no permitido'
    ]);

    // Cortamos la ejecución del script
    exit;
}

// Obtenemos todos los datos enviados desde Postman (o frontend)
$datos = $_POST;

// Creamos un array para almacenar posibles errores
$errores = [];


// ================= VALIDACIONES =================

// Validamos que el nombre no esté vacío
if (empty($datos['nombre'])) {
    $errores[] = "Nombre requerido";
}

// Validamos que el email tenga un formato correcto
if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Email invalido";
}

// Validamos la fecha de nacimiento

// Convertimos la fecha (string) a objeto DateTime usando el formato dd/mm/yyyy
$fecha = DateTime::createFromFormat('d/m/Y', $datos['fecha_nacimiento']);

// Obtenemos la fecha actual
$hoy = new DateTime();

// Verificamos que la fecha sea válida
if ($fecha) {

    // Calculamos la diferencia entre hoy y la fecha de nacimiento
    $edad = $hoy->diff($fecha)->y;

    // Validamos que tenga al menos 16 años
    if ($edad < 16) {
        $errores[] = "Menor de edad";
    }

} else {
    // Si la fecha no es válida
    $errores[] = "Fecha invalida";
}


// ================= RESPUESTA SI HAY ERRORES =================

// Si el array de errores NO está vacío
if (!empty($errores)) {

    // Devolvemos los errores en formato JSON
    echo json_encode([
        'status' => 'error',
        'errores' => $errores
    ]);

    // Cortamos ejecución
    exit;
}


// ================= PROCESAMIENTO =================

// Armamos un array con los datos listos para guardar
$registro = [

    // Generamos un ID único usando timestamp
    'id' => time(),

    // Datos básicos
    'nombre' => $datos['nombre'],
    'apellido' => $datos['apellido'],
    'email' => $datos['email'],
    'fecha_nacimiento' => $datos['fecha_nacimiento'],
    'telefono' => $datos['telefono'],
    'puesto' => $datos['puesto'],

    // Convertimos el array de eventos en string separado por coma
    'eventos' => implode(',', $datos['eventos'] ?? []),

    // Convertimos redes (si existe, si no usamos array vacío)
    'redes' => implode(',', $datos['redes'] ?? []),

    // Rango salarial
    'rango_salarial' => $datos['rango']
];


// ================= GUARDADO EN CSV =================

// Definimos la ruta del archivo CSV
$archivo = __DIR__ . '/../data/participantes.dat';

// Abrimos el archivo en modo "append" (agregar al final)
$fp = fopen($archivo, 'a');

// Validamos que el archivo se haya abierto correctamente
if (!$fp) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'No se pudo abrir el archivo'
    ]);
    exit;
}

// Escribimos una fila en el CSV usando separador "|"
fputcsv($fp, $registro, '|');

// Cerramos el archivo
fclose($fp);


// ================= RESPUESTA FINAL =================

// Devolvemos respuesta exitosa
echo json_encode([
    'status' => 'ok',
    'mensaje' => 'Registro guardado',
    'data' => $registro
]);