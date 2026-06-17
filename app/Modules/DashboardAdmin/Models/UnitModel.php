<?php

namespace Modules\DashboardAdmin\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table            = 'unit_ps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_unit', 'tipe', 'harga_per_jam', 'status'];
}
