<?php

namespace App\helpers;

use App\Models\User;
use Carbon\Carbon;
use Carbon\Translator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CargosModelos {

    //JUST THIS PROJECT
    public static function CargosYModelos() {
        //otros cargos NO_ADMIN
        $nombresDeCargos = [
            'empleado',
            'jefe',
        ];
        $isSome = [];
        foreach ($nombresDeCargos as $key => $value) {
            $isSome[] = 'is' . $value;
        }
        //arrrays for easyway
        $Models = [
            'role',
            'permission',
            'user',

            'formulario',//core
        ];
        return [
            'nombresDeCargos' => $nombresDeCargos,
            'Models' => $Models,
            'isSome' => $isSome,
        ];
    }

    //<editor-fold desc="zona estatica y permisos">
    public static function WebVueModels()
    {
        return [
            'venta',
        ];
    }

    public static function WebModels()
    {
        return array_merge(self::WebVueModels(), [
            'permission',
            'user',
            'role',
        ]);
    }

    public static function WebVueModels_side()
    {
        return [
            'venta',
        ];
    }

    public static function CrudCompletou()
    {
        return [
            'delete',
            'update',
            'read',
            'create',
            'specifyUpdate'
        ];
    }
    //</editor-fold>
    
    
}

?>
