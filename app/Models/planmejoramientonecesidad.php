<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class planmejoramientonecesidad extends Model
{
    use HasFactory;

    protected $table = 'planmejoramientonecesidad';

    protected $fillable = ['value', 'label'];
}
