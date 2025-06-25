import api from './api';

export const pmbService = {
  // Formulir services
  async submitFormulir(formData) {
    const response = await api.post('/formulir', formData);
    return response;
  },

  async getFormulir() {
    const response = await api.get('/formulir');
    return response;
  },

  async uploadBuktiBayar(file) {
    const formData = new FormData();
    formData.append('bukti_bayar', file);
    
    const response = await api.put('/formulir/upload-bukti-bayar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response;
  },

  // Public services
  async getPublishedPengumuman() {
    const response = await api.get('/pengumuman/published');
    return response;
  },

  async getAvailableProdi() {
    const response = await api.get('/prodi/available');
    return response;
  },

  async getCurrentBiaya() {
    const response = await api.get('/biaya/current');
    return response;
  },

  // Admin services
  async getPendaftar() {
    const response = await api.get('/admin/pendaftar');
    return response;
  },

  async getPendaftarDetail(id) {
    const response = await api.get(`/admin/pendaftar/${id}`);
    return response;
  },

  async verifikasiPendaftar(id, status) {
    const response = await api.patch(`/admin/pendaftar/${id}/verifikasi`, { status });
    return response;
  },

  async downloadDokumen(id) {
    const response = await api.get(`/admin/pendaftar/${id}/dokumen`, {
      responseType: 'blob',
    });
    return response;
  },

  // Program Studi management
  async getProdi() {
    const response = await api.get('/admin/prodi');
    return response;
  },

  async createProdi(data) {
    const response = await api.post('/admin/prodi', data);
    return response;
  },

  async updateProdi(id, data) {
    const response = await api.put(`/admin/prodi/${id}`, data);
    return response;
  },

  async deleteProdi(id) {
    const response = await api.delete(`/admin/prodi/${id}`);
    return response;
  },

  // Pengumuman management
  async getPengumuman() {
    const response = await api.get('/admin/pengumuman');
    return response;
  },

  async createPengumuman(data) {
    const response = await api.post('/admin/pengumuman', data);
    return response;
  },

  async updatePengumuman(id, data) {
    const response = await api.put(`/admin/pengumuman/${id}`, data);
    return response;
  },

  async deletePengumuman(id) {
    const response = await api.delete(`/admin/pengumuman/${id}`);
    return response;
  },

  // Biaya Pendaftaran management
  async getBiaya() {
    const response = await api.get('/admin/biaya');
    return response;
  },

  async createBiaya(data) {
    const response = await api.post('/admin/biaya', data);
    return response;
  },

  async updateBiaya(id, data) {
    const response = await api.put(`/admin/biaya/${id}`, data);
    return response;
  },

  async deleteBiaya(id) {
    const response = await api.delete(`/admin/biaya/${id}`);
    return response;
  },

  // Tahun Ajaran management
  async getTahunAjaran() {
    const response = await api.get('/admin/tahun-ajaran');
    return response;
  },

  async createTahunAjaran(data) {
    const response = await api.post('/admin/tahun-ajaran', data);
    return response;
  },

  async updateTahunAjaran(id, data) {
    const response = await api.put(`/admin/tahun-ajaran/${id}`, data);
    return response;
  },

  async deleteTahunAjaran(id) {
    const response = await api.delete(`/admin/tahun-ajaran/${id}`);
    return response;
  },

  async setAktifTahunAjaran(id) {
    const response = await api.patch(`/admin/tahun-ajaran/${id}/set-aktif`);
    return response;
  },

  // Pembukaan Prodi management
  async getPembukaanProdi() {
    const response = await api.get('/admin/pembukaan-prodi');
    return response;
  },

  async createPembukaanProdi(data) {
    const response = await api.post('/admin/pembukaan-prodi', data);
    return response;
  },

  async updatePembukaanProdi(id, data) {
    const response = await api.put(`/admin/pembukaan-prodi/${id}`, data);
    return response;
  },

  async deletePembukaanProdi(id) {
    const response = await api.delete(`/admin/pembukaan-prodi/${id}`);
    return response;
  },
};
