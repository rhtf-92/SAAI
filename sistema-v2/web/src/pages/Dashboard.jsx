import Card from '@/components/ui/Card';
import { Users, BookOpen, CreditCard, TrendingUp } from 'lucide-react';

const Dashboard = () => {
    const stats = [
        { title: 'Estudiantes Activos', value: '1,234', icon: Users, color: 'bg-blue-500' },
        { title: 'Matrículas Este Ciclo', value: '856', icon: BookOpen, color: 'bg-green-500' },
        { title: 'Pagos Pendientes', value: '42', icon: CreditCard, color: 'bg-yellow-500' },
        { title: 'Promedio General', value: '15.8', icon: TrendingUp, color: 'bg-purple-500' },
    ];

    return (
        <div>
            <div className="mb-6">
                <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p className="text-gray-600">Bienvenido al Sistema Académico y Administrativo</p>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {stats.map((stat, index) => (
                    <Card key={index} padding={false}>
                        <div className="p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600 mb-1">{stat.title}</p>
                                    <p className="text-3xl font-bold text-gray-900">{stat.value}</p>
                                </div>
                                <div className={`${stat.color} p-3 rounded-lg`}>
                                    <stat.icon className="w-6 h-6 text-white" />
                                </div>
                            </div>
                        </div>
                    </Card>
                ))}
            </div>

            {/* Recent Activity */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <Card title="Actividad Reciente">
                    <div className="space-y-3">
                        {[1, 2, 3, 4, 5].map((item) => (
                            <div key={item} className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                                <div className="w-10 h-10 bg-gray-200 rounded-full"></div>
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-gray-900">Nuevo estudiante matriculado</p>
                                    <p className="text-xs text-gray-500">Hace 2 horas</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </Card>

                <Card title="Próximos Eventos">
                    <div className="space-y-3">
                        {[1, 2, 3, 4, 5].map((item) => (
                            <div key={item} className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                                <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span className="text-blue-600 font-bold">{15 + item}</span>
                                </div>
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-gray-900">Evaluación Final - Matemáticas</p>
                                    <p className="text-xs text-gray-500">Diciembre {15 + item}, 2025</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </Card>
            </div>
        </div>
    );
};

export default Dashboard;
