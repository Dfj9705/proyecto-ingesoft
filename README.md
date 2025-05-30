# Sistema de control y gestion de proyectos agiles con integracion de herramientas de IA

## Contenido

### Tabla de contenido

<!-- toc -->

- [Video del proyecto](#video-del-proyecto)
- [Enlaces importantes](#enlaces-importantes)
- [Participantes](#participantes)
- [Instrucciones](#instrucciones)
- [Planeacion del proyecto en Jira](#planeacion-del-proyecto-en-jira)
  * [Descripcion del problema](#descripcion-del-problema)
  * [Solucion propuesta](#solucion-propuesta)
  * [Herramientas de IA para el analisis](#herramientas-de-ia-para-el-analisis)
  * [Justificacion de uso de Jira y beneficios](#justificacion-de-uso-de-jira-y-beneficios)
  * [Capturas de pantalla de Jira](#capturas-de-pantalla-de-jira)
- [Arquitectura](#arquitectura)
  * [Backend (PHP)](#backend-php)
  * [Frontend (JavaScript + Bootstrap)](#frontend-javascript--bootstrap)
  * [Manejadores de paquetes](#manejadores-de-paquetes)
  * [Otras carpetas](#otras-carpetas)
- [Funcionalidades](#funcionalidades)
- [Uso de inteligencia artificial](#uso-de-inteligencia-artificial)
  * [Herramientas para ayudar a programar](#herramientas-para-ayudar-a-programar)
  * [Herramientas para hacer predicciones](#herramientas-para-hacer-predicciones)
- [Capturas de pantalla](#capturas-de-pantalla)
- [Requerimientos](#requerimientos)
- [Estructura mvc](#estructura-mvc)
  * [Pasos para iniciar](#pasos-para-iniciar)
    + [1. Verificar MOD_REWRITE](#1-verificar-mod_rewrite)
    + [2. Clonar repositorio](#2-clonar-repositorio)
    + [3. Crear archivo GIT IGNORE (.gitignore)](#3-crear-archivo-git-ignore-gitignore)
    + [4. Crear archivos HTACCESS](#4-crear-archivos-htaccess)
      - [Archivo htaccess de la raíz](#archivo-htaccess-de-la-raiz)
      - [Archivo htaccess de la carpeta public](#archivo-htaccess-de-la-carpeta-public)
    + [5. Crear archivo .env](#5-crear-archivo-env)
    + [6. Instalar paquetes de node](#6-instalar-paquetes-de-node)
    + [7. Instalar paquetes de composer](#7-instalar-paquetes-de-composer)
    + [8. Construir archivos en la carpeta publica](#8-construir-archivos-en-la-carpeta-publica)
    + [9. Configurar versiones y descripcion del proyecto](#9-configurar-versiones-y-descripcion-del-proyecto)

<!-- tocstop -->

## Video del proyecto

Hacer click en la imagen abajo **para ir al video**

<div align="left">
    <a href="https://youtu.be/GR7V5_7Wbd0">
        <img
          src="./README-img/250530-134536.avif"
          alt=" Download and test multiple Neovim distros and configurations - Without affecting your current config "
          width="600"
        />
    </a>
</div>

## Enlaces importantes

- [Link al video de YouTube](https://youtu.be/GR7V5_7Wbd0)
- [Link al proyecto en Jira](https://miumg-team-tfs4fhwk.atlassian.net/jira/software/projects/SCRUM/boards/1?atlOrigin=eyJpIjoiZmUyZmRjZDA1OWQ3NDJjNDg1MTI4Y2RmMTMxZTEyN2UiLCJwIjoiaiJ9)
- [Link al código en GitHub](https://github.com/Dfj9705/proyecto-ingesoft)
- [Link a la aplicación](https://scrum.procodegt.com/login)

## Participantes

- Universidad Mariano Gálvez de Guatemala
- Ingeniería en Ciencias de la Comunicación y Sistemas de Información
- Ingeniería de software
- Ing. Michael Asturias

| No  | Nombre                        | Carne         |
| --- | ----------------------------- | ------------- |
| 1   | Erick Ronel Cárdenas Cardenas | 0900-21-592   |
| 2   | Abner Daniel Fuentes Juárez   | 0900-21-539   |
| 3   | Christian Josué Arzu Carrillo | 0900-21-11243 |

## Instrucciones

- El propósito de este proyecto es diseñar e implementar un sistema que facilite
  la gestión y el seguimiento de proyectos ágiles, integrando soluciones basadas
  en inteligencia artificial en cada etapa del ciclo de vida del software.
- La incorporación de herramientas de IA mejorará la eficiencia en la toma de
  decisiones y permitirá explorar tecnologías emergentes.
- Objetivo General
  - Desarrollar un sistema integral de control y gestión de proyectos ágiles que
    incorpore herramientas de IA en cada fase del ciclo de vida del desarrollo
    de software.
- Objetivos Específicos
  - Investigación y Selección de Herramientas de IA: identificar y analizar
    herramientas aplicables.
  - Definición de Requisitos: elaborar requisitos funcionales y no funcionales.
  - Diseño del Sistema: incluir diagramas de flujo, modelos de datos e
    integración de módulos inteligentes.
  - Implementación y Validación: desarrollar un prototipo funcional y validar la
    eficacia de la IA.
  - Documentación y Presentación: generar documentación técnica y presentación
    final.
- Alcance y Descripción del Sistema
  - Planificación y Seguimiento: gestión de backlog, sprints y tareas con
    metodologías ágiles.
  - Monitoreo de Proyectos: reportes y métricas clave en tiempo real.
  - Integración de IA en:
    - Requerimientos y Análisis: NLP para priorizar requerimientos.
    - Diseño: asistentes de diseño basados en IA para generar diagramas UML.
    - Desarrollo: asistentes de codificación como GitHub Copilot.
    - Pruebas: generación automática de casos de prueba.
    - Despliegue y Mantenimiento: análisis predictivo de fallos y sugerencias de
      mejoras.
- Requisitos del Proyecto
  - Requisitos Funcionales
    - Gestión de Tareas y Sprints.
    - Integración de herramientas de IA en cada módulo.
    - Informes y Paneles de Control.
    - Interfaz de Usuario Intuitiva.
  - Requisitos No Funcionales
    - Escalabilidad y Rendimiento.
    - Seguridad.
    - Modularidad.
    - Usabilidad.
- Metodología de Trabajo
  - Fases del Proyecto
    - Planificación e Investigación: definir alcance, investigar herramientas de
      IA y planificar.
    - Análisis y Diseño: recopilar y documentar requerimientos y diseñar la
      arquitectura.
    - Implementación: desarrollar prototipo e integrar IA.
    - Validación y Pruebas: ejecutar pruebas y optimizar el sistema.
    - Documentación y Presentación Final: redactar documentación técnica y
      presentación final.
  - Herramientas y Tecnologías Sugeridas
    - Lenguajes y frameworks como Java, Python, JavaScript.
    - Plataformas de gestión ágil como Jira o Azure Boards.
    - Herramientas de IA como GitHub Copilot y NLP.
- Cronograma y Entregables
  - Semana 1-2: investigación y selección de herramientas de IA.
  - Semana 3-4: análisis de requerimientos y diseño.
  - Semana 5-8: desarrollo e integración de IA.
  - Semana 9-10: pruebas y ajustes.
  - Semana 11: documentación técnica.
  - Semana 12: presentación final.
  - Entregables Clave
    - Propuesta Inicial (29/03/2025): documento con solución y herramientas IA
      preliminares.
    - Informe Parcial (19/04/2025): avance en diseño y herramientas IA
      definitivas.
    - Entrega Final (31/05/2025): diseño de arquitectura, código fuente,
      pruebas, documentación y demo.
- Criterios de Evaluación
  - Calidad de la Gestión del Proyecto (20%): metodología ágil, requerimientos
    claros y manejo de tableros.
  - Aplicación de Conocimientos de IA (20%): investigación y selección de
    herramientas IA e integración exitosa.
  - Diseño, Arquitectura, Codificación y Pruebas (40%): claridad y uso de buenas
    prácticas.
  - Entrega de Documentación (20%): documentación clara, completa y coherente.
- Recursos y Referencias
  - Revisión de literatura sobre gestión ágil, aplicaciones de IA y buenas
    prácticas.
  - Recursos académicos, artículos científicos y documentación oficial de
    herramientas IA.
- Asuntos importantes
  - El proyecto debe realizarse en grupos de 3 personas, de no cumplirse quedará
    sin nota.
  - El proyecto pasará por un proceso de investigación que detectará copias,
    resultando en nota 0 y sanciones.
  - Los entregables deben ir acompañados de un video de presentación donde
    expongan claramente la solución.

## Planeacion del proyecto en Jira

- [Link al proyecto en Jira](https://miumg-team-tfs4fhwk.atlassian.net/jira/software/projects/SCRUM/boards/1?atlOrigin=eyJpIjoiZmUyZmRjZDA1OWQ3NDJjNDg1MTI4Y2RmMTMxZTEyN2UiLCJwIjoiaiJ9)

### Descripcion del problema

- En muchas organizaciones, ya sean empresas, colegios o instituciones públicas,
  es muy común tener problemas al manejar proyectos y tareas
- Esto pasa sobre todo cuando los proyectos necesitan que trabajen juntas
  personas con diferentes especialidades
- Si no se organiza bien el trabajo, puede haber confusión al asignar tareas,
  falta de comunicación, trabajos repetidos y problemas para saber cómo van las
  cosas
- No tener una herramienta que muestre todo en un solo lugar hace más difícil
  que los encargados puedan ver cómo va el trabajo, qué está hecho y qué falta
- Esto hace más difícil detectar problemas a tiempo y arreglarlos rápido para
  que los proyectos no se retrasen y se cumplan los plazos y metas que se
  pusieron
- Además, si no queda claro quién hace qué y cómo va el trabajo de cada uno, es
  difícil saber cómo trabaja cada persona y todo el equipo, y esto afecta el
  ambiente de trabajo y la motivación

### Solucion propuesta

- La idea es hacer una aplicación web para que sea más fácil organizar, asignar,
  revisar y dar seguimiento a las tareas y proyectos
- Usaremos un modelo que separa el diseño de las pantallas y la parte de la
  programación usando PHP y JavaScript
- Esto ayudará a que la organización pueda tener claro quién hace qué, ver
  avances, y coordinar mejor a las personas que están trabajando
- También vamos a usar inteligencia artificial para hacer que el sistema pueda
  aprender y ayudar a cada parte del proceso

### Herramientas de IA para el analisis

- Uso general
  - Chat GPT y GEMINI, para hacer preguntas, probar códigos y corregir errores
  - DiagramGPT, para crear diagramas UML fácilmente
  - Cursor y github copilot, para ayudar a escribir y corregir el código
    mientras lo editamos
  - Codium AI, para ayudar a probar el software
- Para las funciones del sistema
  - PHP-ML: para usar algoritmos como predicciones y clasificaciones en PHP
  - TensorFlow.js: para usar modelos de aprendizaje automático en la web, lo que
    ayuda a hacer validaciones y análisis rápidos
  - Brain.js: para crear redes simples en JavaScript, útil para hacer
    predicciones básicas y automatizar tareas en la web

### Justificacion de uso de Jira y beneficios

- Usamos Jira para tener más claro y ordenado qué tareas hay que hacer y en qué
  orden
- Jira nos ayudó a ver fácilmente en qué estado estaba cada tarea y detectar
  problemas rápido
- Con Jira, todo el equipo pudo comunicarse mejor usando comentarios y asignando
  tareas
- Los reportes y tableros de Jira nos ayudaron a ver cómo iba el trabajo y
  cumplir con los tiempos de entrega
- Jira nos permitió ver cómo trabajaba cada persona y el equipo completo, lo que
  ayudó a que todos cumplieran con lo que les tocaba hacer
- Gracias a Jira pudimos planificar mejor cada parte del proyecto, definir lo
  que había que hacer y tener todo más organizado

### Capturas de pantalla de Jira

- Summary o resumen del proyecto
- ![Image](./README-img/250530-130559.avif)
- Timeline con los diferentes sprints
- ![Image](./README-img/250530-130640.avif)
- Elementos en el backlog
- ![Image](./README-img/250530-130713.avif)
- El board o tablero
- ![Image](./README-img/250530-130733.avif)
- Acá se muestra una de las historias como ejemplo
- ![Image](./README-img/250530-130835.avif)

## Arquitectura

- El proyecto es una página web para ayudar a manejar proyectos, tareas, epics y
  sprints, usando ideas de Scrum
- La idea es que sea una herramienta sencilla para que todos puedan ver lo que
  tienen que hacer, lo que ya hicieron y lo que queda pendiente
- Se usa un modelo MVC para separar el diseño, la lógica y el control de datos

### Backend (PHP)

- Las carpetas están separadas para tener controladores, modelos, pantallas de
  usuario y ayudas como correos y autenticación
- El archivo Router.php controla las rutas para que el sistema sepa qué hacer en
  cada caso
- Se usa SQLite para que la base de datos sea fácil de mover
- La seguridad incluye verificación por correo y roles como Administrador o
  Scrum Master, así cada uno ve lo que le toca

### Frontend (JavaScript + Bootstrap)

- Bootstrap 5 ayuda a que la página se vea bien en celulares y computadoras
- Sass se usa para que los estilos estén más organizados
- JavaScript moderno con cosas como fetch y async/await para que todo funcione
  rápido
- Usamos ventanas emergentes y tablas para ver y editar datos fácilmente

### Manejadores de paquetes

- Composer se usa para las librerías de PHP como PHP-ML y PHP MAILER
- npm y Webpack ayudan a ordenar los archivos JS y CSS y a usar cosas como
  sweetalert2 o datatables.net-bs5

### Otras carpetas

- public/ es la parte que se ve en el navegador
- src/ guarda el código JS y CSS que Webpack ordena
- .gitignore hace que no se suban archivos innecesarios o externos

## Funcionalidades

- Inicio de sesión con verificación por correo y roles de usuario
- Se pueden crear, editar y borrar proyectos
- Se asignan usuarios con roles como Administrador, Scrum Master, Desarrollador,
  Tester y Analista
- Se pueden ver y cambiar las tareas de los proyectos
- Diseño adaptable a celulares y computadoras
- Tarjetas de proyectos en la página de inicio y ventanas rápidas para ver y
  asignar tareas

## Uso de inteligencia artificial

### Herramientas para ayudar a programar

- Usamos ChatGPT para generar código, revisar cómo funciona todo y dar ejemplos
  de IA en PHP

### Herramientas para hacer predicciones

- Queremos usar Brain.js para ver si una tarea se puede atrasar o para dar ideas
  de asignación
- Planeamos usar PHP-ML para ver si una tarea tiene “alto riesgo” o “bajo
  riesgo” y para ordenar tareas nuevas usando datos pasados y modelos sencillos
  como KNN y SVM

## Capturas de pantalla

- [Link a la aplicación](https://scrum.procodegt.com/login)
- Página de inicio de sesión
- ![Image](./README-img/250530-132422.avif)
- Confirmación correo electrónico
- ![Image](./README-img/250530-132711.avif)
- Pantalla inicial
- ![Image](./README-img/250530-133321.avif)
- Creación de nuevo proyecto
- ![Image](./README-img/250530-133249.avif)
- Click en ver proyecto
- ![Image](./README-img/250530-133226.avif)
- Asignar usuarios al proyecto
- ![Image](./README-img/250530-133715.avif)
- ![Image](./README-img/250530-133701.avif)
- Creación epica
- ![Image](./README-img/250530-133201.avif)
- Creacion sprints
- ![Image](./README-img/250530-133526.avif)
- Crear una tarea y asignar
- ![Image](./README-img/250530-133752.avif)
- 4 tareas en 2 diferentes sprints asignadas
- ![Image](./README-img/250530-133933.avif)
- Tablero kanban
- ![Image](./README-img/250530-134001.avif)
- Dashboard general
- ![Image](./README-img/250530-134024.avif)

## Requerimientos

- PHP V7.2.4 o superior
- NODE JS V17.9.0
- NPM V8.5
- COMPOSER V2.3 o superior
- GIT V2.35 o superior.
- MOD_REWRITE activo en el servidor

## Estructura mvc

### Pasos para iniciar

#### 1. Verificar MOD_REWRITE

El servidor deberá poseer al menos esta configuración

```conf

<Directory /var/www/html>
 AllowOverride All
</Directory>

```

En un servidor Ubuntu esta configuración debe colocarse en
**_/etc/apache2/sites-available/_**

#### 2. Clonar repositorio

Clonarlo en la carpeta que se este utilizando como base en el servidor (Ej.
C:\docker)

#### 3. Crear archivo GIT IGNORE (.gitignore)

Debe colocarse en la raíz del proyecto, con el siguiente contenido

```git
node_modules
vendor
composer.lock
packagelock.json
public/
build
.gitignore
.htaccess
public/.htaccess
temp
storage
includes/.env
```

#### 4. Crear archivos HTACCESS

Estos archivos se usaran para redirigir las consultas hacia el archivo
**_index.php_**

##### Archivo htaccess de la raíz

Deberá colocarse en la raíz del proyecto

```
RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
```

##### Archivo htaccess de la carpeta public

Deberá colocarse dentro de la carpeta public

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

#### 5. Crear archivo .env

Este archivo deberá contener la información según el entorno en que se ejecute
el proyecto y deberá contener esta información

```
DEBUG_MODE = 0
DB_HOST=host
DB_SERVICE=port
DB_SERVER=server_name
DB_NAME=db_name

APP_NAME = "app_name"
```

#### 6. Instalar paquetes de node

Ejecutar en consola el comando siguiente y esperar a que termine su ejecución

```
npm  install
```

#### 7. Instalar paquetes de composer

Ejecutar en consola el comando siguiente y esperar a que termine su ejecución

```
composer  install
```

#### 8. Construir archivos en la carpeta publica

Ejecutar en consola el comando siguiente y esperar a que termine su ejecución

```
npm run build
```

Este comando permanecerá en ejecución mientras se este trabajando en el proyecto

#### 9. Configurar versiones y descripcion del proyecto

Configurar los archivos con la información del proyecto y la versión en la que
se esta trabajando

- package.json
- composer.json
