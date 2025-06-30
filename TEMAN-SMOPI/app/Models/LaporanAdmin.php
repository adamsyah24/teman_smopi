<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAdmin extends Model
{
    protected $table = 'T_LAPORAN_ADMIN';
    protected $primaryKey = 'ID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
