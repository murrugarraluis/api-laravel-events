<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# API Laravel para Gestión de Eventos Presenciales

Esta API Laravel está diseñada para gestionar eventos presenciales, incluyendo categorías de eventos y ciudades asociadas.

## Levantar el Proyecto

Para levantar este proyecto Laravel de manera tradicional, sigue los siguientes pasos:

1. Clona este repositorio en tu máquina local:

    ```
    git clone <url-del-repositorio>
    ```

2. Instala las dependencias del proyecto utilizando Composer:

    ```
    composer install
    ```

3. Copia el archivo `.env.example` y renómbralo a `.env`. Luego, configura tus variables de entorno, incluyendo la configuración de la base de datos.

4. Genera una nueva clave de aplicación:

    ```
    php artisan key:generate
    ```

5. Ejecuta las migraciones de la base de datos para crear las tablas necesarias:

    ```
    php artisan migrate
    ```

6. (Opcional) Ejecuta los seeder si deseas tener datos de ejemplo en tu base de datos:

    ```
    php artisan db:seed
    ```

7. ¡Listo! Ahora puedes ejecutar tu servidor de desarrollo local:

    ```
    php artisan serve
    ```

## Características Técnicas

Esta API Laravel cuenta con las siguientes características técnicas:

- Controladores: Utiliza controladores para manejar las solicitudes HTTP y la lógica de negocio.
- Requests: Usa clases de Request para validar y procesar los datos de entrada.
- Resources: Utiliza recursos para dar formato a las respuestas de la API.
- Laravel Sanctum: Implementa Laravel Sanctum para la autenticación API basada en tokens.
- Permisos: Gestiona permisos utilizando el paquete Spatie Permissions.
- Relaciones: Define relaciones entre los modelos de Eloquent para manejar la lógica de la base de datos.
- Pruebas unitarias: Desarrollado con TDD (Test-Driven Development), lo que garantiza la calidad y la robustez del código.
- Transacciones: Utiliza transacciones de base de datos para garantizar la integridad de los datos en operaciones críticas.
- Paginación: Implementa paginación para manejar grandes conjuntos de datos de manera eficiente.

Para ejecutar las pruebas unitarias, utiliza el siguiente comando:

```
php artisan test
```

---
