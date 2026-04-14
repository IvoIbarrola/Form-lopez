# Form Lopez – Sistema de Registro a Evento Tecnológico

## Consigna

Desarrollar un sistema web que permita registrar participantes a un evento tecnológico, almacenando la información en archivos de texto (CSV) y trabajando de manera colaborativa en equipo mediante control de versiones.

El sistema debe:

* Permitir la carga de datos mediante un formulario web
* Validar la información ingresada
* Almacenar los datos en archivos `.dat` utilizando separadores personalizados
* Implementar lógica de backend independiente
* Trabajar en equipo utilizando ramas (Git)
* Simular comportamiento de base de datos (IDs, validaciones, etc.)

---

## Descripción del proyecto

Este sistema permite registrar participantes a un evento, validando los datos desde el backend y almacenándolos en un archivo CSV.

Incluye:

* Validación de datos (email, edad, formato)
* IDs autoincrementales
* Control de duplicados (email y teléfono)
* Separación entre frontend y backend
* Uso de Docker para entorno de desarrollo
* Generación de estadísticas a partir de los datos cargados

---

## Tecnologías utilizadas

* PHP
* HTML / CSS
* Docker
* Git / GitHub

---

## Estructura del proyecto

```
Form-lopez/
├── src/
│   ├── back/
│   │   └── procesar.php
│   ├── front/
│   │   └── index.html
│   ├── estadistica/
│   │   └── estadistica.php
│   └── data/ (ignorada en git)
├── docker-compose.yml
├── Dockerfile
└── .gitignore
```

---

## Cómo levantar el proyecto

### 1. Clonar el repositorio

```bash
git clone https://github.com/IvoIbarrola/Form-lopez.git
cd Form-lopez
```

---

### 🔹 2. Levantar Docker

```bash
sudo docker-compose up -d --build
```

---

### 3. Acceder al sistema

Frontend:

```
http://localhost:8080/front/index.html
```

Backend (endpoint):

```
http://localhost:8080/back/procesar.php
```

---

## Pruebas

Se puede probar el backend de dos formas:

### Desde el formulario web

Completar y enviar el formulario

### Desde Postman

Enviar una petición `POST` con:

* `Content-Type: application/x-www-form-urlencoded`

Campos requeridos:

```
nombre
apellido
email
fecha_nacimiento
telefono
puesto
eventos[]
redes[]
rango
```

---

## Consideraciones

* La carpeta `data/` está ignorada en `.gitignore`
* Cada usuario genera su propio archivo CSV localmente
* El backend valida todos los datos independientemente del frontend

---

## Trabajo en equipo

El proyecto está dividido en ramas:

* `main` → versión estable
* `dev` → integración general
* `front` → desarrollo del formulario y la interfaz
* `back` → desarrollo del backend (validaciones y almacenamiento)
* `estadistica` → procesamiento y análisis de datos

Cada integrante trabaja en su rama correspondiente y luego integra los cambios en `dev`.
