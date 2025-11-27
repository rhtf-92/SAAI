import { createBrowserRouter, Navigate } from 'react-router-dom';
import AppLayout from '@/components/layout/AppLayout';
import ProtectedRoute from './ProtectedRoute';

// Pages
import Login from '@/pages/Login';
import Dashboard from '@/pages/Dashboard';
import NotFound from '@/pages/NotFound';

const router = createBrowserRouter([
    {
        path: '/',
        element: <Navigate to="/dashboard" replace />,
    },
    {
        path: '/login',
        element: <Login />,
    },
    {
        path: '/',
        element: (
            <ProtectedRoute>
                <AppLayout />
            </ProtectedRoute>
        ),
        children: [
            {
                path: 'dashboard',
                element: <Dashboard />,
            },
            {
                path: 'students',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Estudiantes</h1></div>,
            },
            {
                path: 'enrollments',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Matrículas</h1></div>,
            },
            {
                path: 'grades',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Calificaciones</h1></div>,
            },
            {
                path: 'payments',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Pagos</h1></div>,
            },
            {
                path: 'documents',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Documentos</h1></div>,
            },
            {
                path: 'jobs',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Bolsa Laboral</h1></div>,
            },
            {
                path: 'settings',
                element: <div className="p-8"><h1 className="text-2xl font-bold">Configuración</h1></div>,
            },
        ],
    },
    {
        path: '*',
        element: <NotFound />,
    },
]);

export default router;
