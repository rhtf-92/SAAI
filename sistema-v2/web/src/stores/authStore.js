import { create } from 'zustand';
import { TOKEN_KEY, USER_KEY } from '@/config/constants';

const useAuthStore = create((set) => ({
    // State
    user: JSON.parse(localStorage.getItem(USER_KEY)) || null,
    token: localStorage.getItem(TOKEN_KEY) || null,
    isAuthenticated: !!localStorage.getItem(TOKEN_KEY),
    isLoading: false,
    error: null,

    // Actions
    setUser: (user) => {
        localStorage.setItem(USER_KEY, JSON.stringify(user));
        set({ user, isAuthenticated: true });
    },

    setToken: (token) => {
        localStorage.setItem(TOKEN_KEY, token);
        set({ token, isAuthenticated: true });
    },

    setAuth: (user, token) => {
        localStorage.setItem(USER_KEY, JSON.stringify(user));
        localStorage.setItem(TOKEN_KEY, token);
        set({ user, token, isAuthenticated: true, error: null });
    },

    logout: () => {
        localStorage.removeItem(USER_KEY);
        localStorage.removeItem(TOKEN_KEY);
        set({ user: null, token: null, isAuthenticated: false, error: null });
    },

    setLoading: (isLoading) => set({ isLoading }),

    setError: (error) => set({ error }),

    clearError: () => set({ error: null }),
}));

export default useAuthStore;
