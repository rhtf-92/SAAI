import { User, LogOut, Bell, Settings } from 'lucide-react';
import useAuthStore from '@/stores/authStore';
import { authAPI } from '@/api/auth';
import { useNavigate } from 'react-router-dom';
import { useState } from 'react';

const Header = () => {
    const { user, logout } = useAuthStore();
    const navigate = useNavigate();
    const [showDropdown, setShowDropdown] = useState(false);

    const handleLogout = async () => {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            logout();
            navigate('/login');
        }
    };

    return (
        <header className="bg-white border-b border-gray-200 px-6 py-4">
            <div className="flex items-center justify-between">
                {/* Logo / Title */}
                <div>
                    <h1 className="text-2xl font-bold text-blue-600">SAAI</h1>
                    <p className="text-xs text-gray-500">Sistema Académico Administrativo</p>
                </div>

                {/* Right section */}
                <div className="flex items-center gap-4">
                    {/* Notifications */}
                    <button className="p-2 hover:bg-gray-100 rounded-lg relative">
                        <Bell className="w-5 h-5 text-gray-600" />
                        <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {/* User Menu */}
                    <div className="relative">
                        <button
                            onClick={() => setShowDropdown(!showDropdown)}
                            className="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg"
                        >
                            <div className="text-right">
                                <p className="text-sm font-medium text-gray-900">{user?.name || 'Usuario'}</p>
                                <p className="text-xs text-gray-500">{user?.email || 'email@example.com'}</p>
                            </div>
                            <div className="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <User className="w-6 h-6 text-white" />
                            </div>
                        </button>

                        {/* Dropdown */}
                        {showDropdown && (
                            <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <button className="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2">
                                    <User className="w-4 h-4" />
                                    Mi perfil
                                </button>
                                <button className="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2">
                                    <Settings className="w-4 h-4" />
                                    Configuración
                                </button>
                                <hr className="my-1" />
                                <button
                                    onClick={handleLogout}
                                    className="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2 text-red-600"
                                >
                                    <LogOut className="w-4 h-4" />
                                    Cerrar sesión
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </header>
    );
};

export default Header;
