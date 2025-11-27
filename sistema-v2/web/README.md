# SAAI - Frontend Web

Sistema Académico y Administrativo Institucional - Aplicación Web Frontend

## 🚀 Tecnologías

- **React 18** - Biblioteca UI
- **Vite 7** - Build tool ultrarrápido
- **Tailwind CSS 4** - Framework de estilos
- **React Router** - Enrutamiento
- **Zustand** - Gestión de estado
- **Axios** - Cliente HTTP
- **Lucide React** - Iconos
- **Sonner** - Notificaciones toast

## 📋 Requisitos Previos

- Node.js >= 18
- npm >= 9

## 🛠️ Instalación

```bash
# Instalar dependencias
npm install

# Copiar archivo de variables de entorno
# Crear .env basado en las variables necesarias
# VITE_API_URL=http://localhost:8000/api/v1
```

## 💻 Desarrollo

```bash
# Iniciar servidor de desarrollo
npm run dev

# El servidor estará disponible en http://localhost:5173
```

## 🏗️ Build

```bash
# Construir para producción
npm run build

# Preview del build de producción
npm run preview
```

## 📁 Estructura del Proyecto

```
src/
├── api/              # API calls y endpoints
├── assets/           # Imágenes, fuentes, etc.
├── components/       # Componentes React
│   ├── ui/          # Componentes UI reutilizables
│   ├── layout/      # Componentes de layout
│   └── common/      # Componentes comunes
├── config/          # Configuración (constantes, etc.)
├── hooks/           # Custom React hooks
├── lib/             # Utilidades y helpers
├── pages/           # Páginas de la aplicación
├── routes/          # Configuración de rutas
├── services/        # Lógica de negocio
├── stores/          # Zustand stores (state management)
├── types/           # TypeScript types (futuro)
├── App.jsx          # Componente raíz
└── main.jsx         # Entry point
```

## 🔐 Autenticación

El sistema utiliza Laravel Sanctum para autenticación basada en tokens.

- El token se almacena en `localStorage`
- Las rutas protegidas redirigen automáticamente a `/login` si no hay token
- El token se envía en cada request mediante interceptor de Axios

## 🎨 Componentes UI Disponibles

- **Button** - Botón con variantes (primary, secondary, danger, etc.)
- **Input** - Input con label, error y helper text
- **Card** - Tarjeta con título, contenido y footer opcionales

## 📱 Rutas Disponibles

- `/login` - Página de inicio de sesión
- `/dashboard` - Dashboard principal
- `/students` - Gestión de estudiantes
- `/enrollments` - Matrículas
- `/grades` - Calificaciones
- `/payments` - Pagos
- `/documents` - Gestión documental
- `/jobs` - Bolsa laboral
- `/settings` - Configuración

## 🌐 Variables de Entorno

```env
VITE_API_URL=http://localhost:8000/api/v1
```

## 📝 Notas

- Asegúrate de que el backend esté corriendo en `http://localhost:8000`
- Para desarrollo local, el proxy está configurado en `vite.config.js`
