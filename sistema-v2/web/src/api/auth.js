import axios from '@/lib/axios';

export const authAPI = {
    // Login
    login: async (credentials) => {
        const response = await axios.post('/login', credentials);
        return response.data;
    },

    // Logout
    logout: async () => {
        const response = await axios.post('/logout');
        return response.data;
    },

    // Get current user
    getUser: async () => {
        const response = await axios.get('/user');
        return response.data;
    },

    // Refresh token
    refreshToken: async () => {
        const response = await axios.post('/refresh');
        return response.data;
    },
};
