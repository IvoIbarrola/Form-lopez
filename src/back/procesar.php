<?php

header('Content-Type: application/json');

// ================= CONFIG =================
$archivo = __DIR__ . '/../data/participantes.dat';


// ================= FUNCIONES =================

function responder($status, $mensaje, $data = null) {
    echo json_encode([
        'status' => $status,
        'mensaje' => $mensaje,
        'data' => $data
    ]);
    exit;
}

function obtenerSiguienteId($archivo) {

    if (!file_exists($archivo)) {
        return 1;
    }

    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (empty($lineas)) {
        return 1;
    }

    // Tomar última línea
    $ultimaLinea = end($lineas);

    $datos = explode('|', $ultimaLinea);

    // Validar que el primer campo sea número
    if (!is_numeric($datos[0])) {
        return 1;
    }

    return (int)$datos[0] + 1;
}

function validarDatos($d) {

    $errores = [];

    if (empty($d['nombre'])) $errores[] = "Nombre requerido";
    if (empty($d['apellido'])) $errores[] = "Apellido requerido";

    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email invalido";
    }

    if (empty($d['telefono'])) {
        $errores[] = "Telefono requerido";
    }

    if (empty($d['puesto'])) {
        $errores[] = "Puesto requerido";
    }

    if (empty($d['eventos'])) {
        $errores[] = "Debe seleccionar al menos un evento";
    }

    // Validar fecha
    $fecha = DateTime::createFromFormat('d/m/Y', $d['fecha_nacimiento']);
    $hoy = new DateTime();

    if ($fecha) {
        $edad = $hoy->diff($fecha)->y;
        if ($edad < 16) {
            $errores[] = "Debe ser mayor de 16 años";
        }
    } else {
        $errores[] = "Fecha invalida";
    }

    return $errores;
}

function crearArchivoSiNoExiste($archivo, $headers) {

    if (!file_exists($archivo)) {

        // Crear carpeta si no existe
        if (!file_exists(dirname($archivo))) {
            mkdir(dirname($archivo), 0777, true);
        }

        $fp = fopen($archivo, 'w');

        // Escribir encabezados
        fputcsv($fp, $headers, '|');

        fclose($fp);
    }
}

function guardarRegistro($archivo, $registro) {

    $fp = fopen($archivo, 'a');

    if (!$fp) {
        responder('error', 'No se pudo abrir el archivo');
    }

    fputcsv($fp, $registro, '|');

    fclose($fp);
}


// ================= VALIDAR METODO =================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Metodo no permitido');
}


// ================= OBTENER DATOS =================

$datos = $_POST;


// ================= VALIDACIONES =================

$errores = validarDatos($datos);

if (!empty($errores)) {
    responder('error', 'Errores de validacion', $errores);
}


// ================= PROCESAMIENTO =================

// Crear archivo si no existe
$headers = [
    'id',
    'nombre',
    'apellido',
    'email',
    'fecha_nacimiento',
    'telefono',
    'puesto',
    'eventos',
    'redes',
    'rango_salarial'
];

crearArchivoSiNoExiste($archivo, $headers);

// Obtener ID autoincremental
$id = obtenerSiguienteId($archivo);

// Armar registro
$registro = [
    $id,
    $datos['nombre'],
    $datos['apellido'],
    $datos['email'],
    $datos['fecha_nacimiento'],
    $datos['telefono'],
    $datos['puesto'],
    implode(',', $datos['eventos']),
    implode(',', $datos['redes'] ?? []),
    $datos['rango']
];


// ================= GUARDAR =================

guardarRegistro($archivo, $registro);


// ================= RESPUESTA =================

responder('ok', 'Registro guardado correctamente', $registro);