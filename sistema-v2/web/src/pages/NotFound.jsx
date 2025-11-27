import { useNavigate } from 'react-router-dom';
import Button from '@/components/ui/Button';
import { Home } from 'lucide-react';

const NotFound = () => {
    const navigate = useNavigate();

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
            <div className="text-center">
                <h1 className="text-9xl font-bold text-gray-200">404</h1>
                <h2 className="text-3xl font-bold text-gray-900 mb-4">Página no encontrada</h2>
                <p className="text-gray-600 mb-8">
                    Lo sentimos, la página que estás buscando no existe.
                </p>
                <Button
                    onClick={() => navigate('/dashboard')}
                    variant="primary"
                >
                    <Home className="w-5 h-5 mr-2" />
                    Volver al inicio
                </Button>
            </div>
        </div>
    );
};

export default NotFound;
