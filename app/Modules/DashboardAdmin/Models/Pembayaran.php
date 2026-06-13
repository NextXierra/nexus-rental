<?php

namespace Modules\DashboardAdmin\Models;

use CodeIgniter\Model;

class Pembayaran extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reservasi_id',
        'jumlah',
        'metode',
        'status',
        'dibayar_at'
    ];
}
