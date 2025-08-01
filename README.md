<p align="center">
  <a href="https://sites.google.com/site/educolibre/educovota-votaciones-escolares" target="_blank">
    <img src="https://i.postimg.cc/DzVFrkqK/Educo-Libre.jpg" alt="EducoVota Logo" width="120">
  </a>
</p>

<h1 align="center">EducoVota V.25.7</h1>
<p align="center">
  Sistema de Votaciones Estudiantiles — <a href="https://sites.google.com/site/educolibre/educovota-votaciones-escolares" target="_blank">Sitio oficial</a>
</p>

---

## ¿Qué es EducoVota?

**EducoVota V.25.7** es un sistema completo de votaciones escolares, diseñado para facilitar procesos democráticos al interior de instituciones educativas de manera rápida, segura y eficiente. Este software es de código abierto y puede ser configurado en menos de cinco minutos (versión portable).

🚀 **¡Descubre la nueva versión de EducoVota!** Ahora con una interfaz de usuario totalmente renovada, un moderno panel gráfico para visualizar resultados en tiempo real, diseño responsivo para acceder desde cualquier dispositivo, y mejoras significativas en seguridad. ¡Una experiencia más intuitiva, visual y confiable para tus jornadas electorales escolares!


---

## Características principales

- 🛠 **Configuración personalizada:** adapta el sistema fácilmente a las necesidades de cada institución educativa.
- ⚡ **Resultados inmediatos:** obtención automática de resultados tras el cierre de las votaciones.
- 🗳 **Soporte para múltiples tarjetones:** personaliza votaciones para personero, consejo estudiantil, por grado y más.
- 🔐 **Votación segura:** opción para proteger el acceso mediante contraseña.
- 📋 **Control del proceso:** seguimiento de estudiantes que han votado y los que están pendientes.
- 📥 **Carga masiva de votantes:** a través de archivos CSV.
- 🚫 **Cierre seguro del sistema:** evita votos fuera del tiempo habilitado.
- 👥 **Creación de administradores:** crea perfiles con privilegios de administrador para apoyar la logística.

---

## Descargar versión portable

Esta versión permite ejecutar EducoVota en tan solo **5 minutos**, sin necesidad de instalaciones previas ni configuraciones complejas. Es ideal para poner en marcha el sistema rápidamente, incluso sin conocimientos técnicos o sin contar con un servidor web propio.

### Pasos para ejecutarla:

1. Descarga el archivo comprimido `.7z` de la última versión desde la sección **[Releases](https://github.com/reinaldodu/educovota/releases)** de este repositorio (para descomprimir el archivo utilice [7-Zip](https://www.7-zip.org/)).
2. Extrae el contenido del archivo en una carpeta local.
3. Dentro de la carpeta extraída, haz doble clic en el archivo `EducoVota.bat` para iniciar el sistema.
4. Espera unos segundos mientras el sistema inicia. El navegador web se abrirá automáticamente en la página de administración de EducoVota (http://127.0.0.1/admin).
5. Inicia sesión con las siguientes credenciales de administrador:

   * **Correo:** `admin@email.co`
   * **Contraseña:** `admin`

🔹 Para configurar el sistema de EducoVota portable es importante hacerlo desde la url `http://127.0.0.1/admin` para que las imágenes se carguen correctamente.

🔹 Se recomienda cambiar la contraseña al ingresar al sistema, desde la opción **Perfil** (menú del usuario, parte superior derecha de la pantalla).

6. Para votar ingrese a: [http://127.0.0.1](http://127.0.0.1) o también desde otra máquina utilizando la dirección ip del servidor (donde tiene instalado EducoVota portable).

🔹 <em>Esta versión utiliza <a href="https://laragon.org" target="_blank">Laragon 6.0</a> como entorno web portátil.</em>


---

## Instalación versión estándar

Esta opción está pensada para entornos de producción o para quienes deseen tener un mayor control sobre la configuración y despliegue del sistema en un servidor web propio.

### Requisitos previos:

* PHP 8.2 o superior
 * Tener instalada y habilitadas las extensiones php ext-intl y ext-zip
* Composer
* Node.js y npm
* MySQL 8.0 o cualquier base de datos compatible con Laravel

### Pasos de instalación:

1. Clona el repositorio en tu entorno local o servidor:

   ```bash
   git clone https://github.com/reinaldodu/educovota.git
   ```
2. Copia el archivo de entorno:

   ```bash
   cp .env.example .env
   ```
3. Configura las variables del archivo `.env`, en especial los datos de conexión a la base de datos.
4. Instala las dependencias PHP con Composer:

   ```bash
   composer install
   ```
5. Instala las dependencias de JavaScript:

   ```bash
   npm install
   ```
6. Compila los assets para producción:

   ```bash
   npm run build
   ```
7. Genera la llave de la aplicación:

   ```bash
   php artisan key:generate
   ```
8. Ejecuta las migraciones y carga los datos base:

   ```bash
   php artisan migrate --seed
   ```
9. Crear enlace simbólico para los archivos públicos:

   ```bash
   php artisan storage:link
   ```
10. Inicia el servidor local (opcional si ya tienes un entorno de servidor configurado):

   ```bash
   php artisan serve --port=80
   ```

Una vez en marcha, puedes administrar el sistema desde [http://127.0.0.1/admin](http://127.0.0.1/admin) o reemplazando 127.0.0.1 por el dominio en caso de haberlo configurado.

Para acceder al tarjetón de votaciones lo puede hacer a través de la ip del servidor o el nombre del dominio.

🔹  Se recomienda revisar la documentación de filament para la [optimización para producción](https://filamentphp.com/docs/3.x/panels/installation#optimizing-filament-for-production).

🔹  También es importante revisar las opciones de [despliegue para producción](https://filamentphp.com/docs/3.x/panels/installation#deploying-to-production).

---

## Acerca de EducoVota

**EducoVota** es software libre desarrollado bajo el proyecto [Educolibre](https://sites.google.com/site/educolibre/), con el objetivo de empoderar a las comunidades educativas mediante herramientas tecnológicas accesibles, seguras y adaptables.

Este sistema está especialmente pensado para fines educativos y se alienta su uso bajo principios de colaboración y mejora continua. 


---

## Capturas de pantalla

A continuación, se presentan algunas capturas que muestran la interfaz renovada de EducoVota V.25.7:

### 🎯 Panel de resultados
![Panel gráfico 1](https://i.postimg.cc/FHKkZsHL/panel1.png)
![Panel gráfico 2](https://i.postimg.cc/L80Y0KJ0/panel2.png)

### 🗳️ Tarjetón de votación
![Tarjetón](https://i.postimg.cc/k56V018M/tarjeton.png)

### 👨‍🏫 Gestión de candidatos
![Candidatos](https://i.postimg.cc/J7bX72FG/candidatos.png)

### ⚙️ Configuración general del sistema
![Configuración](https://i.postimg.cc/RVdnKn4m/configuracion.png)

### 🧑‍ Lista de estudiantes
![Estudiantes](https://i.postimg.cc/gkmZxK94/estudiantes.png)

---

## Tecnología base

Este sistema ha sido desarrollado usando:

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://i.postimg.cc/bJqbrn54/Laravel.png" width="320" alt="Laravel Logo">
  </a>
</p>

**Laravel V.12** es un framework PHP moderno que ofrece herramientas elegantes para construir aplicaciones web robustas, seguras y escalables.

---

<p align="center">
  <a href="https://filamentphp.com" target="_blank">
    <img src="https://i.postimg.cc/sB2vcYdX/Filament.png" width="220" alt="Filament Logo">
  </a>
</p>

**Filament 3** es un moderno sistema de administración para Laravel. Con Filament se han construido las interfaces administrativas de EducoVota, ofreciendo una experiencia limpia y optimizada. 

---

## Licencia

**EducoVota V.25.7** está licenciado bajo los términos de la **Licencia Pública General GNU (GPL v3)**.

Esto significa que puedes usar, modificar y redistribuir el software libremente, siempre que mantengas la misma licencia y respetes las condiciones de uso.  
Este proyecto promueve el **uso educativo, la colaboración abierta y la mejora continua**.

Para más información, consulta el sitio web del proyecto o revisa el archivo `LICENSE` incluido en este repositorio.

---
