# Plan Maestro de Migración de Módulos (Sistema V2)

Este documento detalla el plan estricto y exhaustivo para migrar la lógica de negocio y datos del proyecto `sistema` (Monolito) al `sistema-v2` (Headless).

**Estrategia:** Migración Modular Incremental.
**Principio:** "Backend First" (Primero API, luego UI).
**Estándar:** Evitar código basura. Reescribir controladores para API RESTful (no copiar/pegar lógica de vistas).

---

## Estándar de Implementación por Módulo

Para cada módulo, se seguirán estrictamente estos pasos para garantizar consistencia:

### 1. Backend (Laravel API)
1.  **Migración:** Crear archivo de migración (`php artisan make:migration`) basándose en la estructura de la tabla original de `sistema`.
    *   *Mejora:* Usar tipos de datos modernos, claves foráneas estrictas (`constrained()`) y nombres en inglés (estándar Laravel).
2.  **Modelo:** Crear Modelo (`php artisan make:model`) con `fillable`, `casts` y `relationships`.
3.  **Resource:** Crear API Resource (`php artisan make:resource`) para transformar la respuesta JSON.
4.  **Controller:** Crear API Controller (`php artisan make:controller Api/V1/XController`).
    *   Implementar CRUD: `index` (con paginación/filtros), `store`, `show`, `update`, `destroy`.
    *   Usar `FormRequests` para validación.
5.  **Route:** Registrar rutas en `routes/api.php` bajo el grupo `v1` y middleware `auth:sanctum`.

### 2. Frontend (Next.js)
1.  **Type:** Definir interfaz TypeScript en `src/types/`.
2.  **Service:** Agregar métodos en `src/lib/api.ts` o servicio específico.
3.  **Component:** Crear componentes de UI (Tablas, Formularios) en `src/features/[modulo]/components`.
4.  **Page:** Crear páginas en `app/(dashboard)/[modulo]/...`.

---

## Fases de Migración

### Fase 4: Módulo Académico Core (Configuración)
*Objetivo:* Migrar las tablas maestras necesarias para que el sistema académico funcione.
*   **Tablas Origen:** `periods`, `programs`, `plans`, `semesters`, `classrooms`, `modules`, `ubigeos`.
*   **Tareas:**
    1.  Migrar `Ubigeo` (Maestro de localizaciones).
    2.  Migrar `Program` (Carreras/Programas).
    3.  Migrar `Plan` (Planes de estudio, vinculados a Programas).
    4.  Migrar `Period` (Periodos académicos 2024-I, etc.).
    5.  Migrar `Semester` (Ciclos I, II, III...).
    6.  Migrar `Classroom` (Aulas físicas).

### Fase 5: Gestión de Usuarios Avanzada (Perfiles)
*Objetivo:* Vincular los usuarios del sistema (`users`) con sus perfiles de negocio.
*   **Tablas Origen:** `students`, `teachers`, `admins` (si existen separados).
*   **Estrategia:** El modelo `User` ya existe. Se crearán modelos `StudentProfile` y `TeacherProfile` con relación 1:1 a `User`.
*   **Tareas:**
    1.  Migrar `Student` -> `StudentProfile` (Datos académicos, código, ingreso).
    2.  Migrar `Teacher` -> `TeacherProfile` (Datos laborales, especialidad).
    3.  Actualizar `AuthController` para devolver el perfil junto con el usuario.

### Fase 6: Gestión Académica (Matrícula y Notas)
*Objetivo:* El núcleo del negocio. Cursos, horarios y matrículas.
*   **Tablas Origen:** `courses`, `subjects`, `schedules`, `enrollments` (matriculas), `grades`, `student_grades`.
*   **Tareas:**
    1.  Migrar `Course` (Cursos base).
    2.  Migrar `Subject` (Asignaturas/Unidades Didácticas vinculadas a un Plan).
    3.  Migrar `Schedule` (Horarios: Curso + Docente + Aula + Periodo).
    4.  Migrar `Enrollment` (Matrícula: Estudiante + Periodo).
    5.  Migrar `EnrollmentDetail` (Detalle: Matrícula + Schedule).
    6.  Migrar `Grade` (Registro de notas).

### Fase 7: Tesorería y Pagos
*Objetivo:* Control de pagos de estudiantes.
*   **Tablas Origen:** `bancos`, `metodos_pagos`, `conceptos`, `pagos`, `pagos_matriculas`.
*   **Tareas:**
    1.  Migrar Maestros: `Bank`, `PaymentMethod`, `Concept` (Conceptos de pago).
    2.  Migrar `Payment` (Cabecera de pago).
    3.  Migrar `PaymentDetail` (Detalle del pago).

### Fase 8: Trámite Documentario
*Objetivo:* Gestión de documentos internos y externos.
*   **Tablas Origen:** `td_documents`, `td_movements`, `td_files`, `areas`.
*   **Tareas:**
    1.  Migrar `Area` (Áreas de la institución).
    2.  Migrar `DocumentType`.
    3.  Migrar `Document` (El documento en sí).
    4.  Migrar `Movement` (Derivaciones del documento).
    5.  Migrar `File` (Adjuntos - Usar Laravel Storage).

### Fase 9: Bolsa de Trabajo
*Objetivo:* Ofertas laborales para estudiantes.
*   **Tablas Origen:** `jp_companies`, `jp_jobs`, `jp_applications`.
*   **Tareas:**
    1.  Migrar `Company` (Empresas).
    2.  Migrar `JobOffer` (Ofertas).
    3.  Migrar `JobApplication` (Postulaciones).

---

## Plan de Acción Inmediato (Siguientes Pasos)

1.  Actualizar `task.md` con estas nuevas fases.
2.  Comenzar **Fase 4: Módulo Académico Core**.
