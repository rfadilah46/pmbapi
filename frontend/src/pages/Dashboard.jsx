import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { pmbService } from '../services/pmbService';

const Dashboard = () => {
  const { user } = useAuth();
  const [formulir, setFormulir] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchFormulir = async () => {
      try {
        const response = await pmbService.getFormulir();
        setFormulir(response.data);
      } catch (error) {
        if (error.response?.status !== 404) {
          setError('Gagal memuat data formulir');
        }
      } finally {
        setLoading(false);
      }
    };

    fetchFormulir();
  }, []);

  const getStatusBadge = (status) => {
    const statusConfig = {
      pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Menunggu Verifikasi' },
      verified: { bg: 'bg-green-100', text: 'text-green-800', label: 'Terverifikasi' },
      rejected: { bg: 'bg-red-100', text: 'text-red-800', label: 'Ditolak' },
    };

    const config = statusConfig[status] || statusConfig.pending;
    
    return (
      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${config.bg} ${config.text}`}>
        {config.label}
      </span>
    );
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Dashboard Mahasiswa</h1>
          <p className="mt-2 text-gray-600">Selamat datang, {user?.name}</p>
        </div>

        {error && (
          <div className="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {error}
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Status Pendaftaran */}
          <div className="lg:col-span-2">
            <div className="card">
              <h2 className="text-xl font-semibold mb-4">Status Pendaftaran</h2>
              
              {formulir ? (
                <div className="space-y-4">
                  <div className="flex items-center justify-between">
                    <span className="text-gray-600">Status:</span>
                    {getStatusBadge(formulir.status)}
                  </div>
                  
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <span className="text-gray-600">Program Studi:</span>
                      <p className="font-medium">{formulir.program_studi?.nama}</p>
                    </div>
                    <div>
                      <span className="text-gray-600">Tanggal Daftar:</span>
                      <p className="font-medium">
                        {new Date(formulir.created_at).toLocaleDateString('id-ID')}
                      </p>
                    </div>
                  </div>

                  <div className="border-t pt-4">
                    <h3 className="font-medium mb-2">Data Pribadi:</h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                      <div>
                        <span className="text-gray-600">NIK:</span>
                        <p>{formulir.nik}</p>
                      </div>
                      <div>
                        <span className="text-gray-600">Tempat Lahir:</span>
                        <p>{formulir.tempat_lahir}</p>
                      </div>
                      <div>
                        <span className="text-gray-600">Tanggal Lahir:</span>
                        <p>{new Date(formulir.tanggal_lahir).toLocaleDateString('id-ID')}</p>
                      </div>
                      <div>
                        <span className="text-gray-600">Jenis Kelamin:</span>
                        <p>{formulir.jenis_kelamin}</p>
                      </div>
                    </div>
                  </div>

                  {formulir.status === 'pending' && (
                    <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                      <p className="text-yellow-800 text-sm">
                        Formulir Anda sedang dalam proses verifikasi. Mohon tunggu konfirmasi dari admin.
                      </p>
                    </div>
                  )}

                  {formulir.status === 'verified' && (
                    <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                      <p className="text-green-800 text-sm">
                        Selamat! Formulir Anda telah diverifikasi. Silakan lanjutkan ke tahap berikutnya.
                      </p>
                    </div>
                  )}

                  {formulir.status === 'rejected' && (
                    <div className="bg-red-50 border border-red-200 rounded-lg p-4">
                      <p className="text-red-800 text-sm">
                        Formulir Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.
                      </p>
                    </div>
                  )}
                </div>
              ) : (
                <div className="text-center py-8">
                  <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <h3 className="mt-2 text-sm font-medium text-gray-900">Belum ada formulir</h3>
                  <p className="mt-1 text-sm text-gray-500">
                    Anda belum mengisi formulir pendaftaran.
                  </p>
                  <div className="mt-6">
                    <Link
                      to="/formulir"
                      className="btn-primary"
                    >
                      Isi Formulir Pendaftaran
                    </Link>
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Menu Cepat */}
          <div className="space-y-6">
            <div className="card">
              <h2 className="text-xl font-semibold mb-4">Menu Cepat</h2>
              <div className="space-y-3">
                {!formulir && (
                  <Link
                    to="/formulir"
                    className="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200"
                  >
                    <div className="font-medium text-blue-900">Isi Formulir</div>
                    <div className="text-sm text-blue-600">Lengkapi data pendaftaran</div>
                  </Link>
                )}
                
                {formulir && formulir.status === 'verified' && (
                  <Link
                    to="/upload-bukti"
                    className="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200"
                  >
                    <div className="font-medium text-green-900">Upload Bukti Bayar</div>
                    <div className="text-sm text-green-600">Upload bukti pembayaran</div>
                  </Link>
                )}
                
                <Link
                  to="/pengumuman"
                  className="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200"
                >
                  <div className="font-medium text-gray-900">Pengumuman</div>
                  <div className="text-sm text-gray-600">Lihat pengumuman terbaru</div>
                </Link>
              </div>
            </div>

            {/* Informasi Kontak */}
            <div className="card">
              <h2 className="text-xl font-semibold mb-4">Butuh Bantuan?</h2>
              <div className="space-y-3 text-sm">
                <div>
                  <span className="font-medium">Email:</span>
                  <p className="text-gray-600">pmb@university.ac.id</p>
                </div>
                <div>
                  <span className="font-medium">Telepon:</span>
                  <p className="text-gray-600">(021) 123-4567</p>
                </div>
                <div>
                  <span className="font-medium">Jam Operasional:</span>
                  <p className="text-gray-600">Senin - Jumat, 08:00 - 16:00</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
