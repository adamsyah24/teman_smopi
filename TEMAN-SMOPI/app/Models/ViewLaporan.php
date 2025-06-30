<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewLaporan extends Model
{
    protected $table = 'V_LAPORAN';
    public $timestamps = false;

    protected $primaryKey = 'ID';
    public $incrementing = false;
    protected $keyType = 'string';
}
