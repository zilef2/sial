<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lineadelplan extends Model
{
    use HasFactory;

    protected $table = 'lineadelplan';
    protected $fillable = ['value', 'label'];
}
