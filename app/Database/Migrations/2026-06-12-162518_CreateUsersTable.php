<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'pelanggan'],
                'default'    => 'pelanggan',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_pelanggan_user');
        $this->forge->createTable('pelanggan');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['PS4', 'PS5'],
            ],
            'harga_per_jam' => [
                'type' => 'INT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tersedia', 'disewa', 'maintenance'],
                'default'    => 'tersedia',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('unit_ps');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pelanggan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'unit_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['online', 'offline'],
            ],
            'waktu_mulai' => [
                'type' => 'DATETIME',
            ],
            'waktu_selesai' => [
                'type' => 'DATETIME',
            ],
            'total_jam' => [
                'type' => 'INT',
            ],
            'harga_per_jam' => [
                'type' => 'INT',
            ],
            'total_harga' => [
                'type' => 'INT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'selesai', 'dibatalkan'],
                'default'    => 'aktif',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'CASCADE', 'CASCADE', 'fk_reservasi_pelanggan');
        $this->forge->addForeignKey('unit_id', 'unit_ps', 'id', 'CASCADE', 'CASCADE', 'fk_reservasi_unit');
        $this->forge->createTable('reservasi');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'reservasi_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'unique'   => true,
            ],
            'jumlah' => [
                'type' => 'INT',
            ],
            'metode' => [
                'type'       => 'ENUM',
                'constraint' => ['tunai', 'qris'],
                'default'    => 'tunai',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['lunas'],
                'default'    => 'lunas',
            ],
            'dibayar_at' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('reservasi_id', 'reservasi', 'id', 'CASCADE', 'CASCADE', 'fk_pembayaran_reservasi');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
        $this->forge->dropTable('reservasi');
        $this->forge->dropTable('unit_ps');
        $this->forge->dropTable('pelanggan');
        $this->forge->dropTable('users');
    }
}
