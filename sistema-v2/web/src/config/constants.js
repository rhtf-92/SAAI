// API Base URL
export const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1';

// App Configuration
export const APP_NAME = 'SAAI';
export const APP_VERSION = '2.0.0';

// Local Storage Keys
export const TOKEN_KEY = 'auth_token';
export const USER_KEY = 'auth_user';

// Routes
export const ROUTES = {
    HOME: '/',
    LOGIN: '/login',
    DASHBOARD: '/dashboard',
    STUDENTS: '/students',
    ENROLLMENTS: '/enrollments',
    PAYMENTS: '/payments',
    GRADES: '/grades',
    PROFILE: '/profile',
};

// User Roles
export const ROLES = {
    ADMIN: 'admin',
    TEACHER: 'teacher',
    STUDENT: 'student',
    STAFF: 'staff',
};
