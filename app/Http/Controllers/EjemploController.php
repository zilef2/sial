<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class EjemploController extends Controller
{
    public function ejemplo(){
        return Inertia::render('ejemplo', [
            'users' => (int)User::count(),
            'formulariosEnviados' => (int)Formulario::Where('enviado', 1)->count(),
            'formulariosGuardados' => (int)Formulario::Where('enviado', 0)->count(),
            'permissions' => (int)Permission::count(),
        ]);
    }
}
