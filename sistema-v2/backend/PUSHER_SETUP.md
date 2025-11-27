# Configuración de Pusher para Broadcasting (WebSockets)

## Variables de Entorno Requeridas

Para habilitar las notificaciones en tiempo real vía Pusher, el usuario debe configurar las siguientes variables en el archivo `.env`:

```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=us2
```

## Cómo Obtener las Credenciales de Pusher

1. Crear una cuenta en https://pusher.com (gratis hasta 200k mensajes/día)
2. Crear un nuevo proyecto/App
3. En el Dashboard, ir a "App Keys"
4. Copiar:
   - `app_id` → PUSHER_APP_ID
   - `key` → PUSHER_APP_KEY
   - `secret` → PUSHER_APP_SECRET
   - `cluster` → PUSHER_APP_CLUSTER (ej: us2, eu, ap1)

## Integración en Frontend

### 1. Instalar Pusher JS

```bash
npm install pusher-js laravel-echo
```

### 2. Configurar Echo (src/lib/echo.js)

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: 'http://localhost:8000/broadcasting/auth',
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
        }
    }
});

export default echo;
```

### 3. Escuchar Notificaciones

```javascript
import echo from '@/lib/echo';

// En componente React
useEffect(() => {
    const userId = user.id;
    
    echo.private(`user.${userId}`)
        .listen('.notification.sent', (data) => {
            console.log('Nueva notificación:', data);
            
            // Mostrar toast
            toast.success(data.title, {
                description: data.message
            });
            
            // Actualizar contador
            fetchUnreadCount();
        });

    return () => {
        echo.leave(`user.${userId}`);
    };
}, [user]);
```

## Testing

### Test desde Backend (Tinker)

```php
php artisan tinker

$user = App\Models\User::find(1);
$service = new App\Services\NotificationService();
$service->create($user->id, 'test', 'Prueba', 'Mensaje de prueba');
```

### Test desde Frontend

1. Abrir la aplicación frontend
2. Login con un usuario
3. Desde otro navegador/sesión, crear una notificación para ese usuario
4. Verificar que aparezca en tiempo real sin recargar

## Notas

- El broadcasting es **OPCIONAL** y funciona sin él guardando solo en BD
- Sin Pusher configurado, las notificaciones aparecen al recargar o hacer polling
- Alternativa gratuita: Laravel Reverb (incluido en Laravel 11+)
