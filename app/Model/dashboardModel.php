<?php
// KAFETANI/layar/DashboardModel.php

class DashboardModel
{
    // Menjalankan fungsi mengambil data statistik bisnis Kafetani
    public function getStatistikBisnis()
    {
        // Data simulasi ringkasan bisnis
        return [
            'total_pendapatan' => 28000,
            'total_pesanan'    => 7,
            'total_produk'     => 9,
            'total_petani'     => 3
        ];
    }

    // Mengelola data menu navigasi sidebar agar dinamis
    public function getSidebarMenu()
    {
        return [
            ['label' => 'Dashboard', 'link' => 'dashboard.php', 'active' => true],
            ['label' => 'Produk',    'link' => 'products.php',  'active' => false],
        ];
    }
}
