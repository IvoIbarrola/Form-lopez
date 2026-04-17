## Trabajo en equipo con Git + Docker + PHP

## 🎯Objetivo

Desarrollar una aplicación básica en PHP trabajando en equipos de 2 integrantes, utilizando buenas prácticas de control de versiones con Git, gestión de ramas y entorno de desarrollo con Docker.

## 👥Modalidad de trabajo

Equipos de hasta 3 integrantes
Se trabajará mediante GitHub Classroom
Cada grupo contará con un único repositorio compartido

## 📦Estructura general del proyecto

El repositorio deberá cumplir con la siguiente estructura de ramas:

## 🌿Ramas obligatorias

## main
Debe mantenerse limpia y estable no hacer merge
Solo se integrara código validado mas adelante - no ahora - se avisara

## dev (Esta la pueden mergear al final no antes de testear)

## Rama base de desarrollo

Debe contener:
Docker funcional
Apache + PHP
Estructura inicial del proyecto
Ramas de desarrollo
form
back

## 🐳 Requerimiento técnico – Docker

## En la rama dev se debe: (todas las ramas deben partir de dicha rama)

Crear un entorno con Docker que incluya:
Apache
PHP (versión 7.4 o superior)
Aplicar buenas prácticas:
Uso de Dockerfile
Uso de docker-compose.yml
Volúmenes para persistencia del código
Exposición de puertos

## 🧩 Desarrollo funcional
🔹 Rama: form

Desarrollar un formulario web para registrar asistencia a un evento de tecnología.

📋 Campos obligatorios:
Nombre
Apellido
Correo electrónico
Fecha de nacimiento
Contacto telefonico
Puesto laboral (select)

📌 Campos adicionales:
Selección de eventos (checkbox o múltiple selección), por ejemplo:
Backend
Frontend
DevOps
Inteligencia Artificial
Seguridad Informática

Selección de redes sociales (checkbox o múltiple selección)
En este evento se realizara en 3 días armar una grilla con dias y horarios en donde en cada día puede haber un maximo de 4 eventos entre las 12hrs y las 17hrs en donde cada uno tiene una duración maxima de 90min (sin BD o archivos)

Encuesta final (optativa para los que quieran responder)

Realizar un rango de sueldos para que se pueda identificar el participante.


✔ Validaciones requeridas:
Campos obligatorios completos
Formato válido de email
Edad mínima (ej: 16 años)
Al menos un evento seleccionado

## Rama: back

Desarrollar la lógica backend en PHP que:

Reciba los datos del formulario
Valide nuevamente los datos (validación backend obligatoria)
Procese la información 
Genere un diagrama de la estructura y espere a su aprobación para luego continuar con el siguiente paso
Guarde los datos en archivos planos simulando una base de datos relacional

## Rama estadistica

Habiendo conversado con su grupo, se le pide analizar la estructura del formulario, archivo/s generados y elaborar un front basico para un 
usuario en donde el mismo pueda realizar distintas busquedas de acuerdo a algun filtro.
Por ejemplo cantidad de participantes totales y por eventos
