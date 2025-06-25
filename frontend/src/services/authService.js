import api from './api';

export const authService = {
  async login(credentials) {
    const response = await api.post('/login', credentials);
    return response;
  },

  async register(userData) {
    const response = await api.post('/register', userData);
    return response;
  },

  async logout() {
    const response = await api.post('/logout');
    return response;
  },

  async getProfile() {
    const response = await api.get('/profile');
    return response;
  },
};
