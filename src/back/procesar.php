<?php

// ================= CONFIG GENERAL =================

// Evitar que warnings rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

// Log de errores (opcional)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Siempre responder JSON
header('Content-Type: application/json');

// Ruta del archivo
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


// ---------- NORMALIZACIÓN ----------
function limpiarTexto($texto) {
    return trim($texto);
}

function normalizarEmail($email) {
    return strtolower(trim($email));
}

function normalizarTelefono($telefono) {
    return preg_replace('/\D/', '', $telefono);
}


// ---------- ID AUTOINCREMENTAL ----------
function obtenerSiguienteId($archivo) {

    if (!file_exists($archivo)) return 1;

    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($lineas)) return 1;

    $ultimaLinea = end($lineas);
    $datos = explode('|', $ultimaLinea);

    if (!is_numeric($datos[0])) return 1;

    return (int)$datos[0] + 1;
}


// ---------- VALIDACIONES ----------
function validarDatos($d) {

    $errores = [];

    // Nombre
    if (empty($d['nombre'])) {
        $errores[] = "Nombre requerido";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $d['nombre'])) {
        $errores[] = "Nombre solo debe contener letras";
    }

    // Apellido
    if (empty($d['apellido'])) {
        $errores[] = "Apellido requerido";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $d['apellido'])) {
        $errores[] = "Apellido solo debe contener letras";
    }

    // Email
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email invalido";
    }

    // Teléfono
    if (empty($d['telefono'])) {
        $errores[] = "Telefono requerido";
    }

    // Puesto
    if (empty($d['puesto'])) {
        $errores[] = "Puesto requerido";
    }

    // Eventos
    if (empty($d['eventos'])) {
        $errores[] = "Debe seleccionar al menos un evento";
    }

    // Fecha
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


// ---------- DUPLICADOS ----------
function emailExiste($archivo, $email) {

    if (!file_exists($archivo)) return false;

    $fp = fopen($archivo, 'r');

    while (($fila = fgetcsv($fp, 1000, '|')) !== false) {

        if ($fila[0] === 'id') continue;

        if (isset($fila[3]) && strtolower($fila[3]) === $email) {
            fclose($fp);
            return true;
        }
    }

    fclose($fp);
    return false;
}

function telefonoExiste($archivo, $telefono) {

    if (!file_exists($archivo)) return false;

    $fp = fopen($archivo, 'r');

    while (($fila = fgetcsv($fp, 1000, '|')) !== false) {

        if ($fila[0] === 'id') continue;

        if (isset($fila[5]) && $fila[5] === $telefono) {
            fclose($fp);
            return true;
        }
    }

    fclose($fp);
    return false;
}


// ---------- ARCHIVO ----------
function crearArchivoSiNoExiste($archivo, $headers) {

    if (!file_exists($archivo)) {

        if (!file_exists(dirname($archivo))) {
            mkdir(dirname($archivo), 0777, true);
        }

        $fp = fopen($archivo, 'w');
        fputcsv($fp, $headers, '|');
        fclose($fp);
    }
}

function guardarRegistro($archivo, $registro) {

    $fp = fopen($archivo, 'a');

    if (!$fp) {
        responder('error', 'No se pudo guardar el archivo');
    }

    fputcsv($fp, $registro, '|');
    fclose($fp);
}


// ================= FLUJO PRINCIPAL =================

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Metodo no permitido');
}

// Obtener datos
$datos = $_POST;

// Normalizar
$datos['nombre'] = limpiarTexto($datos['nombre'] ?? '');
$datos['apellido'] = limpiarTexto($datos['apellido'] ?? '');
$datos['email'] = normalizarEmail($datos['email'] ?? '');
$datos['telefono'] = normalizarTelefono($datos['telefono'] ?? '');

// Validar
$errores = validarDatos($datos);

if (!empty($errores)) {
    responder('error', 'Errores de validacion', $errores);
}

// Crear archivo
$headers = [
    'id','nombre','apellido','email','fecha_nacimiento',
    'telefono','puesto','eventos','redes','rango_salarial'
];

crearArchivoSiNoExiste($archivo, $headers);

// Validar duplicados
if (emailExiste($archivo, $datos['email'])) {
    responder('error', 'El email ya está registrado');
}

if (telefonoExiste($archivo, $datos['telefono'])) {
    responder('error', 'El telefono ya está registrado');
}

// ID
$id = obtenerSiguienteId($archivo);

// Registro
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

// Guardar
guardarRegistro($archivo, $registro);

// Responder OK
responder('ok', 'Registro guardado correctamente', $registro);