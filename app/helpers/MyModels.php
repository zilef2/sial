<?php

namespace App\helpers;

use Carbon\Carbon;
use Carbon\Translator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyModels {
    
    public static function CargosYModelos(){

        $nombresDeCargos = CargosModelos::CargosYModelos();
        $isSome = [];
        foreach ($nombresDeCargos as $key => $value) {
            $isSome[] = 'is' . $value;
        }

        return [
            'nombresDeCargos' => $nombresDeCargos,
            'isSome' => $isSome,
        ];
    }

    public static function getPermissionToNumber($permissions): int{
        $contador = 1;
        $nombresDeCargos = CargosModelos::CargosYModelos();
        foreach ($nombresDeCargos as $cargo) {
            if ($permissions === $cargo) return $contador;
            $contador++;
        }

        if ($permissions === 'admin') return 9000;
        if ($permissions === 'superadmin') return 10000;
        return 0;
    }
}
