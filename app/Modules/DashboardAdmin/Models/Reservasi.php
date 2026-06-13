<?php

namespace Modules\DashboardAdmin\Models;

use CodeIgniter\Model;

class Reservasi extends Model
{
    protected $table            = 'reservasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pelanggan_id',
        'unit_id',
        'tipe',
        'waktu_mulai',
        'waktu_selesai',
        'total_jam',
        'harga_per_jam',
        'total_harga',
        'status',
        'created_at'
    ];
}
