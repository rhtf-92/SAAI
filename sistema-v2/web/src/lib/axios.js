import axios from 'axios';
import { API_BASE_URL, TOKEN_KEY } from '@/config/constants';

// Create axios instance
const axiosInstance = axios.create({
    baseURL: API_BASE_URL,
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Request interceptor
axiosInstance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem(TOKEN_KEY);
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor
axiosInstance.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            // Handle 401 Unauthorized
            if (error.response.status === 401) {
                localStorage.removeItem(TOKEN_KEY);
                window.location.href = '/login';
            }

            // Handle other errors
            const message = error.response.data?.message || 'An error occurred';
            return Promise.reject(new Error(message));
        }

        return Promise.reject(error);
    }
);

export default axiosInstance;
