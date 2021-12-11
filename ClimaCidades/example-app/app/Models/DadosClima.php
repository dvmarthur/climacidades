<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DadosClima extends Model
{



    use HasFactory;



    protected $fillable = ['city', 'description', 'temp', 'temp_min', 'temp_max', 'updated_at'];
}
