<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proceso_que_solicita_presupuesto extends Model
{
    use HasFactory;

    protected $table = 'proceso_que_solicita_presupuesto';

    protected $fillable = ['value', 'label'];
}
