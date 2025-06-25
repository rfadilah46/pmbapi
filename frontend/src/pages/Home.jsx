import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { pmbService } from '../services/pmbService';

const Home = () => {
  const [pengumuman, setPengumuman] = useState([]);
  const [biaya, setBiaya] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [pengumumanData, biayaData] = await Promise.all([
          pmbService.getPublishedPengumuman(),
          pmbService.getCurrentBiaya(),
        ]);
        setPengumuman(pengumumanData.data || []);
        setBiaya(biayaData.data);
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Section */}
      <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div className="max-w-7xl mx-auto px-4 py-20">
          <div className="text-center">
            <h1 className="text-4xl md:text-6xl font-bold mb-6">
              Penerimaan Mahasiswa Baru
            </h1>
            <p className="text-xl md:text-2xl mb-8 text-blue-100">
              Bergabunglah dengan universitas terbaik untuk masa depan yang cerah
            </p>
            <div className="space-x-4">
              <Link
                to="/register"
                className="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-200"
              >
                Daftar Sekarang
              </Link>
              <Link
                to="/login"
                className="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-200"
              >
                Login
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Biaya Pendaftaran Section */}
      {biaya && (
        <div className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Biaya Pendaftaran
              </h2>
            </div>
            <div className="max-w-md mx-auto">
              <div className="card text-center">
                <h3 className="text-xl font-semibold mb-2">{biaya.nama}</h3>
                <p className="text-3xl font-bold text-blue-600 mb-4">
                  Rp {biaya.nominal?.toLocaleString('id-ID')}
                </p>
                <p className="text-gray-600">{biaya.keterangan}</p>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Pengumuman Section */}
      {pengumuman.length > 0 && (
        <div className="py-16 bg-gray-50">
          <div className="max-w-7xl mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold text-gray-900 mb-4">
                Pengumuman Terbaru
              </h2>
            </div>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {pengumuman.map((item) => (
                <div key={item.id} className="card">
                  <h3 className="text-xl font-semibold mb-3">{item.judul}</h3>
                  <p className="text-gray-600 mb-4 line-clamp-3">{item.konten}</p>
                  <div className="text-sm text-gray-500">
                    {new Date(item.created_at).toLocaleDateString('id-ID')}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Features Section */}
      <div className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">
              Mengapa Memilih Kami?
            </h2>
          </div>
          <div className="grid md:grid-cols-3 gap-8">
            <div className="text-center">
              <div className="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <h3 className="text-xl font-semibold mb-2">Pendidikan Berkualitas</h3>
              <p className="text-gray-600">Program studi terakreditasi dengan kurikulum yang selalu update</p>
            </div>
            <div className="text-center">
              <div className="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <h3 className="text-xl font-semibold mb-2">Dosen Berpengalaman</h3>
              <p className="text-gray-600">Tenaga pengajar profesional dengan pengalaman industri</p>
            </div>
            <div className="text-center">
              <div className="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <h3 className="text-xl font-semibold mb-2">Fasilitas Lengkap</h3>
              <p className="text-gray-600">Laboratorium modern dan fasilitas penunjang pembelajaran</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
