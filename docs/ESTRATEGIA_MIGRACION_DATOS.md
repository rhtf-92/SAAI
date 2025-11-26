# Estrategia de Migración de Datos Legacy (ETL)

Para migrar los datos reales "de la manera más correcta posible", implementaremos un proceso **ETL (Extract, Transform, Load)** utilizando las capacidades de Laravel.

## 1. Arquitectura de Migración

No usaremos scripts SQL directos ni herramientas externas. Usaremos **Comandos de Artisan** dentro de `sistema-v2`. Esto garantiza que los datos pasen por los Modelos y validaciones de la nueva arquitectura.

### Configuración de Base de Datos
Configuraremos dos conexiones en `config/database.php`:
1.  `mysql`: La base de datos nueva (`sistema-v2`).
2.  `mysql_legacy`: La base de datos antigua (Solo lectura).

## 2. Flujo de Trabajo (Workflow)

Crearemos un comando maestro `php artisan migrate:legacy` que ejecutará importadores específicos en orden de dependencia.

### Orden de Importación (Dependencias)
1.  **Core:** Usuarios, Roles, Permisos.
2.  **Académico Estático:** Ubigeos, Programas, Planes, Semestres, Aulas.
3.  **Perfiles:** Perfiles de Estudiantes y Docentes (vinculados a Usuarios).
4.  **Académico Dinámico:** Cursos, Asignaturas, Horarios.
5.  **Procesos:** Matrículas -> Notas -> Pagos.

## 3. Implementación Técnica

### Clase Base `LegacyImporter`
Todos los importadores heredarán de esta clase para manejar:
-   **Chunking:** Procesar registros de 1000 en 1000 para no saturar la memoria RAM.
-   **Logging:** Registrar errores específicos (ID fallido, motivo) en `storage/logs/migration.log`.
-   **Rollback:** (Opcional) Capacidad de deshacer un lote importado.

### Ejemplo de Comando
```php
// Ejemplo conceptual
DB::connection('mysql_legacy')->table('students')->orderBy('id')->chunk(1000, function ($legacyStudents) {
    foreach ($legacyStudents as $legacyStudent) {
        // Transformación
        $newStudentData = [
            'name' => $legacyStudent->nombres,
            'email' => $legacyStudent->correo,
            // ... mapeo de campos
        ];
        
        // Carga (Load) usando el Modelo Nuevo
        StudentProfile::create($newStudentData);
    }
});
```

## 4. Requisitos Previos
Para ejecutar esto, necesitaremos:
1.  Acceso a la base de datos antigua (credenciales de host, user, password).
2.  O un archivo `.sql` (dump) de la base de datos antigua para restaurarla localmente en una base de datos temporal llamada `sistema_legacy`.

## 5. Validación
El proceso incluirá un script de verificación que comparará:
-   Cantidad de registros (Origen vs Destino).
-   Suma de montos (para Pagos).
-   Promedios (para Notas).
