<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Data katalog toko. Aman dijalankan berulang (idempotent).
     */
    public function run(): void
    {
        $produk = [
            ['Kaos Polos Premium', 'Fashion', 89000, 25, 'Katun combed 30s, adem dan tidak mudah melar. Ukuran S-XL, warna hitam, putih, navy.', '👕'],
            ['Jaket Hoodie Fleece', 'Fashion', 215000, 12, 'Hoodie bahan fleece tebal, ada kantong depan. Ukuran M-XXL, warna abu dan hitam.', '🧥'],
            ['Sepatu Sneakers Urban', 'Fashion', 349000, 8, 'Sneakers ringan untuk harian, sol anti slip. Ukuran 39-44, warna putih.', '👟'],
            ['Kemeja Flannel Casual', 'Fashion', 159000, 16, 'Bahan flannel lembut, motif kotak. Ukuran S-XXL, cocok untuk santai maupun kerja.', '👔'],
            ['Celana Chino Stretch', 'Fashion', 179000, 14, 'Bahan chino dengan karet di pinggang, nyaman dipakai seharian. Ukuran 29-36.', '👖'],
            ['Tas Ransel Laptop 15 inch', 'Tas', 275000, 15, 'Tahan percikan air, muat laptop hingga 15 inch, ada port USB untuk charger.', '🎒'],
            ['Tas Selempang Canvas', 'Tas', 129000, 20, 'Bahan canvas tebal, 3 kompartemen, tali bisa dipendekkan. Cocok untuk harian.', '👜'],
            ['Headset Bluetooth X1', 'Elektronik', 199000, 30, 'Bluetooth 5.3, baterai hingga 20 jam, ada mikrofon untuk telepon.', '🎧'],
            ['Power Bank 10.000 mAh', 'Elektronik', 149000, 0, 'Kapasitas 10.000 mAh, 2 port USB, mendukung pengisian cepat.', '🔋'],
            ['Smartwatch Fit Pro', 'Elektronik', 459000, 10, 'Pemantau detak jantung dan tidur, tahan air, baterai 7 hari.', '⌚'],
            ['Keyboard Mechanical K87', 'Elektronik', 389000, 9, 'Switch clicky, lampu LED latar, koneksi USB-C dan Bluetooth.', '⌨️'],
            ['Kabel Data Type-C 1m', 'Elektronik', 45000, 60, 'Mendukung pengisian cepat 60W dan transfer data 480 Mbps.', '🔌'],
            ['Botol Minum Stainless 600ml', 'Rumah Tangga', 79000, 40, 'Menjaga suhu dingin 12 jam dan panas 6 jam, bebas BPA.', '🧴'],
            ['Lampu Meja LED', 'Rumah Tangga', 125000, 18, '3 mode cahaya, kecerahan bisa diatur, hemat listrik.', '💡'],
            ['Panci Stainless 24 cm', 'Rumah Tangga', 189000, 11, 'Panci stainless anti karat, tutup kaca, cocok untuk semua kompor.', '🍳'],
            ['Mouse Wireless Silent', 'Aksesoris Komputer', 99000, 22, 'Klik senyap, koneksi 2.4GHz, baterai awet hingga 12 bulan.', '🖱️'],
            ['Webcam Full HD 1080p', 'Aksesoris Komputer', 249000, 7, 'Kamera 1080p 30fps, mikrofon stereo, siap pakai untuk meeting.', '📷'],
            ['Flashdisk 64 GB USB 3.0', 'Aksesoris Komputer', 79000, 35, 'Kecepatan baca hingga 100 MB/s, ringkas dan tahan banting.', '💾'],
        ];

        foreach ($produk as [$nama, $kategori, $harga, $stok, $deskripsi, $ikon]) {
            Product::updateOrCreate(['nama' => $nama], compact('nama', 'kategori', 'harga', 'stok', 'deskripsi', 'ikon'));
        }
    }
}
