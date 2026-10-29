
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengaturanController;

// =========================================
// HALAMAN AWAL
// =========================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    return redirect()->route('login');
});

// =========================================
// AUTHENTICATION: LOGIN, REGISTER, LOGOUT
// =========================================

Auth::routes();

// =========================================
// DASHBOARD
// =========================================

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

// =========================================
// DATA PELANGGAN
// =========================================

Route::resource('pelanggan', PelangganController::class)
    ->middleware('auth');

// =========================================
// DATA LAYANAN
// =========================================

Route::resource('layanan', LayananController::class)
    ->middleware('auth');

// =========================================
// ALUR PEMESANAN
// =========================================

// DATA PELANGGAN
Route::get('/pemesanan/alur/pelanggan', [
    PemesananController::class,
    'alurPelanggan'
])->name('pemesanan.alur.pelanggan')->middleware('auth');

Route::post('/pemesanan/alur/pelanggan', [
    PemesananController::class,
    'simpanPelangganAlur'
])->name('pemesanan.alur.pelanggan.simpan')->middleware('auth');

// PENCARIAN PELANGGAN
Route::get('/pemesanan/cari-pelanggan', [
    PemesananController::class,
    'cariPelanggan'
])->name('pemesanan.cari-pelanggan')->middleware('auth');

// PILIH LAYANAN
Route::get('/pemesanan/alur/layanan', [
    PemesananController::class,
    'alurLayanan'
])->name('pemesanan.alur.layanan')->middleware('auth');

Route::post('/pemesanan/alur/layanan', [
    PemesananController::class,
    'simpanLayananAlur'
])->name('pemesanan.alur.layanan.simpan')->middleware('auth');

// DETAIL LAYANAN
Route::get('/pemesanan/alur/detail', [
    PemesananController::class,
    'alurDetail'
])->name('pemesanan.alur.detail')->middleware('auth');

Route::post('/pemesanan/alur/detail', [
    PemesananController::class,
    'simpanDetailAlur'
])->name('pemesanan.alur.detail.simpan')->middleware('auth');

// METODE PENGAMBILAN
Route::get('/pemesanan/alur/pengambilan', [
    PemesananController::class,
    'alurPengambilan'
])->name('pemesanan.alur.pengambilan')->middleware('auth');

Route::post('/pemesanan/alur/pengambilan', [
    PemesananController::class,
    'simpanPengambilanAlur'
])->name('pemesanan.alur.pengambilan.simpan')->middleware('auth');

// CEK POIN / STEMPEL
Route::get('/pemesanan/alur/poin', [
    PemesananController::class,
    'alurPoin'
])->name('pemesanan.alur.poin')->middleware('auth');

// PILIH REWARD
Route::get('/pemesanan/alur/reward', [
    PemesananController::class,
    'alurReward'
])->name('pemesanan.alur.reward')->middleware('auth');

Route::post('/pemesanan/alur/reward', [
    PemesananController::class,
    'simpanRewardAlur'
])->name('pemesanan.alur.reward.simpan')->middleware('auth');

// RINGKASAN PESANAN
Route::get('/pemesanan/alur/ringkasan', [
    PemesananController::class,
    'alurRingkasan'
])->name('pemesanan.alur.ringkasan')->middleware('auth');

// KONFIRMASI DAN SIMPAN PESANAN
Route::post('/pemesanan/alur/konfirmasi', [
    PemesananController::class,
    'konfirmasiAlur'
])->name('pemesanan.alur.konfirmasi')->middleware('auth');

// CRUD PEMESANAN LAMA
Route::resource('pemesanan', PemesananController::class)
    ->middleware('auth');

// =========================================
// DATA REWARD
// =========================================

Route::resource('reward', RewardController::class)
    ->middleware('auth');

// =========================================
// TRANSAKSI DAN PEMBAYARAN
// =========================================

Route::get('/pembayaran', [
    PembayaranController::class,
    'index'
])->name('pembayaran.index')->middleware('auth');

Route::get('/pembayaran/{id}/bayar', [
    PembayaranController::class,
    'create'
])->name('pembayaran.create')->middleware('auth');

Route::post('/pembayaran/{id}/bayar', [
    PembayaranController::class,
    'store'
])->name('pembayaran.store')->middleware('auth');

// =========================================
// CETAK STRUK
// =========================================

Route::get('/pemesanan/{id}/struk', [
    PembayaranController::class,
    'struk'
])->name('pembayaran.struk')->middleware('auth');

// =========================================
// PENGATURAN LAUNDRY
// =========================================

Route::get('/pengaturan', [
    PengaturanController::class,
    'index'
])->name('pengaturan.index')->middleware('auth');

Route::put('/pengaturan', [
    PengaturanController::class,
    'update'
])->name('pengaturan.update')->middleware('auth');
