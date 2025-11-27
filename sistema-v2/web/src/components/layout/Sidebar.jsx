import { NavLink } from 'react-router-dom';
import {
    LayoutDashboard,
    Users,
    BookOpen,
    CreditCard,
    GraduationCap,
    FileText,
    Briefcase,
    Settings
} from 'lucide-react';

const Sidebar = () => {
    const menuItems = [
        { path: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
        { path: '/students', icon: Users, label: 'Estudiantes' },
        { path: '/enrollments', icon: BookOpen, label: 'Matrículas' },
        { path: '/grades', icon: GraduationCap, label: 'Calificaciones' },
        { path: '/payments', icon: CreditCard, label: 'Pagos' },
        { path: '/documents', icon: FileText, label: 'Documentos' },
        { path: '/jobs', icon: Briefcase, label: 'Bolsa Laboral' },
        { path: '/settings', icon: Settings, label: 'Configuración' },
    ];

    return (
        <aside className="w-64 bg-gray-900 text-white min-h-screen">
            <nav className="p-4 space-y-1">
                {menuItems.map((item) => (
                    <NavLink
                        key={item.path}
                        to={item.path}
                        className={({ isActive }) =>
                            `flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${isActive
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                            }`
                        }
                    >
                        <item.icon className="w-5 h-5" />
                        <span>{item.label}</span>
                    </NavLink>
                ))}
            </nav>
        </aside>
    );
};

export default Sidebar;
