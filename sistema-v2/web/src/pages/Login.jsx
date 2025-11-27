import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { authAPI } from '@/api/auth';
import useAuthStore from '@/stores/authStore';
import Input from '@/components/ui/Input';
import Button from '@/components/ui/Button';
import Card from '@/components/ui/Card';
import { GraduationCap } from 'lucide-react';

const Login = () => {
    const navigate = useNavigate();
    const { setAuth, setLoading, setError } = useAuthStore();
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });
    const [isLoading, setIsLoading] = useState(false);
    const [error, setErrorState] = useState('');

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
        setErrorState('');
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setErrorState('');

        try {
            const response = await authAPI.login(formData);

            // Assuming API returns { user, token }
            if (response.user && response.token) {
                setAuth(response.user, response.token);
                navigate('/dashboard');
            }
        } catch (err) {
            setErrorState(err.message || 'Error al iniciar sesión');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center p-4">
            <div className="w-full max-w-md">
                {/* Logo */}
                <div className="text-center mb-8">
                    <div className="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                        <GraduationCap className="w-10 h-10 text-blue-600" />
                    </div>
                    <h1 className="text-3xl font-bold text-white mb-2">SAAI</h1>
                    <p className="text-blue-100">Sistema Académico y Administrativo Institucional</p>
                </div>

                {/* Login Card */}
                <Card>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Iniciar Sesión</h2>
                            <p className="text-gray-600">Ingresa tus credenciales para continuar</p>
                        </div>

                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                                {error}
                            </div>
                        )}

                        <Input
                            label="Correo electrónico"
                            type="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            placeholder="tu@email.com"
                            required
                            fullWidth
                        />

                        <Input
                            label="Contraseña"
                            type="password"
                            name="password"
                            value={formData.password}
                            onChange={handleChange}
                            placeholder="••••••••"
                            required
                            fullWidth
                        />

                        <div className="flex items-center justify-between">
                            <label className="flex items-center">
                                <input type="checkbox" className="mr-2" />
                                <span className="text-sm text-gray-600">Recordarme</span>
                            </label>
                            <a href="#" className="text-sm text-blue-600 hover:underline">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <Button
                            type="submit"
                            variant="primary"
                            fullWidth
                            isLoading={isLoading}
                        >
                            Iniciar Sesión
                        </Button>
                    </form>
                </Card>

                <p className="text-center text-blue-100 text-sm mt-4">
                    © 2025 SAAI. Todos los derechos reservados.
                </p>
            </div>
        </div>
    );
};

export default Login;
