<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterReservasiPembayaranStatus extends Migration
{
    public function up()
    {
        // Alter reservasi status enum
        $this->db->query("ALTER TABLE reservasi MODIFY COLUMN status ENUM('pending', 'aktif', 'selesai', 'dibatalkan') DEFAULT 'aktif'");

        // Alter pembayaran status enum
        $this->db->query("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('belum_bayar', 'sudah_bayar', 'lunas') DEFAULT 'lunas'");
    }

    public function down()
    {
        // Restore reservasi status enum
        $this->db->query("ALTER TABLE reservasi MODIFY COLUMN status ENUM('aktif', 'selesai', 'dibatalkan') DEFAULT 'aktif'");

        // Restore pembayaran status enum
        $this->db->query("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('lunas') DEFAULT 'lunas'");
    }
}
