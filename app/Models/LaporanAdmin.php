<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAdmin extends Model
{
    protected $table = 't_laporan_admin';
    protected $primaryKey = 'ID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
