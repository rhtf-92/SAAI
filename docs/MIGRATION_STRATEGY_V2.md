# 🚀 Estrategia de Migración V2: "Cortes Verticales"

## 1. Análisis de Situación Actual
Hemos completado con éxito el primer "Corte Vertical": **Autenticación y Usuarios**.
- **Logro:** Un usuario del sistema legado puede iniciar sesión en el nuevo `sistema-v2` con sus credenciales originales.
- **Validación:** El Frontend (Next.js) se comunica correctamente con el Backend (Laravel) y la Base de Datos.
- **Lección Aprendida:** Migrar datos "a ciegas" (sin UI para verlos) es arriesgado. La estrategia de implementar *Backend + Frontend + Migración* en conjunto funciona mejor.

## 2. El Problema de la Estrategia Anterior
El plan original separaba demasiado la "Fase de Migración de Datos" (Phase 13) de la "Implementación de Módulos" (Phase 2, 3, etc.).
Esto crea un "punto ciego": Podríamos pasar semanas migrando datos de notas y matrículas, solo para descubrir que el modelo de datos no se ajusta a la UI que necesitamos construir.

## 3. Nueva Estrategia: Migración Modular Vertical
En lugar de migrar *todo* y luego construir *todo*, avanzaremos por módulos funcionales completos. Para cada módulo, haremos:
1.  **Modelado (Backend):** Definir Modelos y Relaciones en Laravel.
2.  **ETL (Migración):** Crear el `Importer` para traer datos reales del legado.
3.  **API (Backend):** Exponer esos datos vía API Resources.
4.  **UI (Frontend):** Crear vistas de "Solo Lectura" para validar que los datos se ven bien.

## 4. Roadmap Replanteado

### 📦 Módulo 1: Estructura Académica (La Base)
Antes de matricular alumnos, necesitamos saber *dónde* matricularlos.
- **Datos a Migrar:**
    - `programs` (Carreras/Programas)
    - `plans` (Planes de Estudio)
    - `semesters` (Semestres Académicos)
    - `classrooms` (Aulas/Secciones)
    - `courses` (Unidades Didácticas - *Falta implementar modelo*)
- **Entregable:** Un Dashboard Académico donde puedas ver el árbol: *Carrera -> Plan -> Ciclo -> Cursos*.

### 🎓 Módulo 2: Gestión de Estudiantes (El Cliente)
Ya tenemos usuarios, pero falta su perfil académico.
- **Datos a Migrar:**
    - `students` (Tabla `alumnos` del legado).
    - Relación con `users`.
- **Entregable:** Perfil del Estudiante en el Frontend con sus datos personales completos.

### 📝 Módulo 3: Matrículas (El Vínculo)
Conectar Estudiantes con la Estructura Académica.
- **Datos a Migrar:**
    - `enrollments` (Tabla `matriculas`).
- **Entregable:** Ficha de Matrícula visible en el Frontend.

### 📊 Módulo 4: Evaluación (El Objetivo Final)
Las notas.
- **Datos a Migrar:**
    - `grades` (Tabla `notas`).
- **Entregable:** Boleta de Notas y Récord Académico.

### 💰 Módulo 5: Tesorería (Soporte)
Pagos y deudas.
- **Datos a Migrar:**
    - `payments`, `debts`.
- **Entregable:** Estado de Cuenta del Estudiante.

## 5. Próximos Pasos Inmediatos
Recomendamos comenzar inmediatamente con el **Módulo 1: Estructura Académica**.
1.  Implementar Modelo y Migración para `Course` (Unidades Didácticas).
2.  Crear `AcademicImporter` para importar Programas, Planes, Semestres y Cursos.
3.  Crear una vista en el Frontend: "Listado de Programas y Cursos".
