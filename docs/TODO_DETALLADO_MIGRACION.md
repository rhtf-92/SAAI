# To-Do List Detallado de Migración (Sistema V2)

Este documento desglosa cada Fase del Plan Maestro en tareas atómicas de desarrollo.

---

## 🏛️ Fase 4: Módulo Académico Core

### 4.1. Ubigeos (Maestro)
- [ ] **Backend**
    - [ ] Migration: `create_ubigeos_table` (code, department, province, district).
    - [ ] Model: `Ubigeo` (timestamps: false).
    - [ ] Controller: `UbigeoController` (Solo `index` para selectores).
    - [ ] Route: `api/v1/ubigeos`.
- [ ] **Frontend**
    - [ ] Type: `Ubigeo`.
    - [ ] Service: `getUbigeos()`.

### 4.2. Programas (Carreras)
- [ ] **Backend**
    - [ ] Migration: `create_programs_table` (name, code, type).
    - [ ] Model: `Program`.
    - [ ] Resource: `ProgramResource`.
    - [ ] Controller: `ProgramController` (CRUD).
    - [ ] Route: `api/v1/programs`.
- [ ] **Frontend**
    - [ ] Page: `dashboard/academic/programs/page.tsx` (Listado).
    - [ ] Component: `ProgramForm.tsx` (Crear/Editar).

### 4.3. Planes de Estudio
- [ ] **Backend**
    - [ ] Migration: `create_plans_table` (program_id, name, year, credits).
    - [ ] Model: `Plan` (Relación: `belongsTo(Program)`).
    - [ ] Controller: `PlanController`.
- [ ] **Frontend**
    - [ ] Page: `dashboard/academic/plans/page.tsx`.

### 4.4. Periodos y Semestres
- [ ] **Backend**
    - [ ] Migration: `create_periods_table` (name, start_date, end_date, is_active).
    - [ ] Migration: `create_semesters_table` (name: I, II, III...).
    - [ ] Model: `Period`, `Semester`.
    - [ ] Controller: `PeriodController`, `SemesterController`.
- [ ] **Frontend**
    - [ ] Page: `dashboard/academic/periods/page.tsx`.

### 4.5. Aulas (Classrooms)
- [ ] **Backend**
    - [ ] Migration: `create_classrooms_table` (name, capacity, location).
    - [ ] Model: `Classroom`.
    - [ ] Controller: `ClassroomController`.
- [ ] **Frontend**
    - [ ] Page: `dashboard/academic/classrooms/page.tsx`.

---

## 👥 Fase 5: Gestión de Usuarios Avanzada

### 5.1. Perfiles de Estudiantes
- [ ] **Backend**
    - [ ] Migration: `create_student_profiles_table` (user_id, code, document_type, document_number, birthdate, phone, address, ubigeo_id).
    - [ ] Model: `StudentProfile` (Relación: `belongsTo(User)`).
    - [ ] Controller: `StudentController` (Crear User + Profile en transacción).
- [ ] **Frontend**
    - [ ] Page: `dashboard/users/students/page.tsx`.
    - [ ] Form: `StudentForm.tsx` (Datos personales + Cuenta).

### 5.2. Perfiles de Docentes
- [ ] **Backend**
    - [ ] Migration: `create_teacher_profiles_table` (user_id, specialty, degree).
    - [ ] Model: `TeacherProfile`.
    - [ ] Controller: `TeacherController`.
- [ ] **Frontend**
    - [ ] Page: `dashboard/users/teachers/page.tsx`.

---

## 📚 Fase 6: Gestión Académica (Matrícula)

### 6.1. Cursos y Asignaturas
- [ ] **Backend**
    - [ ] Migration: `create_courses_table` (General info).
    - [ ] Migration: `create_subjects_table` (plan_id, course_id, semester_id, credits, hours).
    - [ ] Model: `Course`, `Subject`.
    - [ ] Controller: `SubjectController`.

### 6.2. Horarios (Scheduling)
- [ ] **Backend**
    - [ ] Migration: `create_schedules_table` (subject_id, teacher_id, classroom_id, period_id, day, start_time, end_time).
    - [ ] Model: `Schedule`.
    - [ ] Controller: `ScheduleController`.

### 6.3. Matrícula (Enrollment)
- [ ] **Backend**
    - [ ] Migration: `create_enrollments_table` (student_id, period_id, status).
    - [ ] Migration: `create_enrollment_details_table` (enrollment_id, schedule_id).
    - [ ] Model: `Enrollment`, `EnrollmentDetail`.
    - [ ] Controller: `EnrollmentController` (Lógica compleja de validación de cruces/créditos).

### 6.4. Notas (Grades)
- [ ] **Backend**
    - [ ] Migration: `create_grades_table` (enrollment_detail_id, unit, value).
    - [ ] Controller: `GradeController`.

---

## 💰 Fase 7: Tesorería

### 7.1. Maestros de Tesorería
- [ ] **Backend**
    - [ ] Migrations: `banks`, `payment_methods`, `concepts`.
    - [ ] Models: `Bank`, `PaymentMethod`, `Concept`.

### 7.2. Pagos
- [ ] **Backend**
    - [ ] Migration: `create_payments_table` (student_id, total, status).
    - [ ] Migration: `create_payment_details_table` (payment_id, concept_id, amount).
    - [ ] Controller: `PaymentController`.

---

## 📂 Fase 8: Trámite Documentario

### 8.1. Documentos
- [ ] **Backend**
    - [ ] Migrations: `areas`, `document_types`, `documents`, `movements`.
    - [ ] Controller: `DocumentController` (Manejo de adjuntos con Storage).

---

## 💼 Fase 9: Bolsa de Trabajo

### 9.1. Ofertas Laborales
- [ ] **Backend**
    - [ ] Migrations: `companies`, `jobs`, `applications`.
    - [ ] Controller: `JobController`.
