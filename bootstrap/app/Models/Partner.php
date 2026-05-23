<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    // Membuka izin input untuk kolom sesuai soal UTS
    protected $fillable = ['name', 'logo_url'];
}
