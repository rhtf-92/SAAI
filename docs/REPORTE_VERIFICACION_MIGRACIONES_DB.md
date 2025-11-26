# Reporte de Verificación de Migraciones

## Resumen
- **Total Migraciones Origen (`sistema`):** 94 archivos
- **Total Migraciones Destino (`sistema-v2`):** 38 archivos
- **Estado:** Migración Parcial (Módulos Core, Académico, Tesorería, Trámite, Bolsa de Trabajo cubiertos).
- **Faltantes Identificados:** Admisión, Viáticos, Tablas de Sistema Legacy (`ts_`).

## Mapeo de Migraciones

### 1. Core & Usuarios
| Origen | Destino | Estado | Notas |
|--------|---------|--------|-------|
| `users`, `roles`, `permissions` | `users`, `roles`, `permissions` | ✅ Completo | Usando Spatie & Sanctum |
| `ubigeos` | `ubigeos` | ✅ Completo | |
| `students`, `teachers` | `student_profiles`, `teacher_profiles` | ✅ Completo | Refactorizado a perfiles |
| `administradores` | `users` (Role: Admin) | ✅ Completo | Integrado en tabla users |

### 2. Módulo Académico
| Origen | Destino | Estado | Notas |
|--------|---------|--------|-------|
| `programs`, `plans` | `programs`, `plans` | ✅ Completo | |
| `semesters`, `periods` | `semesters`, `plan_periods` | ✅ Completo | |
| `classrooms` | `classrooms` | ✅ Completo | |
| `courses`, `subjects` | `courses`, `subjects` | ✅ Completo | |
| `schedules` | `schedules` | ✅ Completo | |
| `enrollments`, `grades` | `enrollments`, `grades` | ✅ Completo | Refactorizado |

### 3. Tesorería
| Origen | Destino | Estado | Notas |
|--------|---------|--------|-------|
| `bancos`, `metodos_pagos` | `banks`, `payment_methods` | ✅ Completo | |
| `conceptos`, `pagos` | `concepts`, `payments` | ✅ Completo | |
| `pagos_matriculas` | `payment_enrollments` | ✅ Completo | |

### 4. Trámite Documentario
| Origen | Destino | Estado | Notas |
|--------|---------|--------|-------|
| `areas`, `td_tipos` | `areas`, `document_types` | ✅ Completo | |
| `td_documentos` | `documents` | ✅ Completo | |
| `td_movimientos` | `document_movements` | ✅ Completo | |
| `td_archivos` | `document_files` | ✅ Completo | |

### 5. Bolsa de Trabajo
| Origen | Destino | Estado | Notas |
|--------|---------|--------|-------|
| `jp_companies` | `company_profiles` | ✅ Completo | Integrado con Users |
| `jp_jobs`, `jp_applications` | `job_offers`, `job_applications` | ✅ Completo | |
| `jp_categories`, `jp_tags` | `job_categories`, `job_tags` | ✅ Completo | |

## Módulos Faltantes (No Migrados)

### ⚠️ Módulo de Admisión
Archivos en origen no migrados:
- `2023_10_13_024512_create_admissions_table.php`
- `2023_10_13_024517_create_admissionplans_table.php`
- `2023_10_13_024518_create_applicants_table.php` (Postulantes)
- `2023_10_26_044956_create_logadmissions_table.php`
- `2023_10_26_050242_create_logapplicants_table.php`
- `2023_10_26_050257_create_studentdocuments_table.php`
- `2023_10_26_050332_create_socioeconomicsheets_table.php` (Ficha Socioeconómica)

### ⚠️ Módulo de Viáticos (Sistema Legacy `ts_`)
Archivos en origen no migrados:
- `2023_11_27_155601_create_ts_conceptos_viaticos_table.php`
- `2023_11_27_160023_create_ts_solicitudes_viaticos_table.php`
- `2023_11_27_161324_create_ts_depositos_viaticos_table.php`
- `2023_11_27_162210_create_ts_registros_viaticos_table.php`
- `2024_01_05_060500_create_viaticos_table.php`

### ⚠️ Tablas de Sistema Legacy (`ts_`)
Varias tablas con prefijo `ts_` (posiblemente "Tramite Sistema" o "Tesoreria Sistema" antiguo) no han sido migradas explícitamente, aunque algunas funcionalidades de tesorería ya están cubiertas.
- `ts_instituciones`, `ts_conceptos`, `ts_bancos` (Cubierto por `banks`, `concepts`)
- `ts_usuarios_ventas`, `ts_estados_cuentas`

## Recomendación
1. **Prioridad Alta:** Migrar el **Módulo de Admisión** (Postulantes, Exámenes, Ingresantes) ya que es crítico para el flujo académico.
2. **Prioridad Media:** Evaluar si el **Módulo de Viáticos** es necesario en la v2 o si se puede simplificar dentro de Tesorería.
3. **Prioridad Baja:** Tablas `ts_` legacy, verificar si contienen datos históricos necesarios.
