<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vigencias_anteriores extends Model
{
    use HasFactory;

    protected $table = 'vigencias_anteriores';

    protected $fillable = ['value', 'label'];
}
