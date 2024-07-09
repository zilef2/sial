<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $sexos = ['Masculino', 'Femenino'];
        $genPa = env('sap_gen');
        $nombreAdmin = 'Admin';
        $App = env('APP_NAME');
        $genero = 'Masculino';

        $superadmin = User::create([
            'name'              => 'Superadmin',
            'email'             => 'ajelof2+8@gmail.com',
            'password'          => bcrypt($genPa.'~8~'), //
            'email_verified_at' => date('Y-m-d H:i'),
            'sexo' => $genero,
            'identificacion' => '11232454',
            'celular' => '11232454',

        ]);$superadmin->assignRole('superadmin');



        $admin = User::create([
            'name'              => "$nombreAdmin $App",
            'email'             => "alejofg2+8@gmail.com",
            'password'          => bcrypt($genPa.'uiu'), //
            'email_verified_at' => date('Y-m-d H:i'),
            'sexo' => $genero,
            'identificacion' => '11232411',
            'celular' => '11232454',

        ]);$admin->assignRole('admin');
        
        //baja categoria
        $nombresGenericos = [
            'jose_administrativo' => '1152888999',
            'madrid_administrativo' => '1152888199',
        ];

        // $genero = 'Femenino';
        foreach ($nombresGenericos as $key => $value) {
            $yearRandom = (random_int(20, 40));
            $anios = Carbon::now()->subYears($yearRandom)->format('Y-m-d H:i');
            $unUsuario = User::create([
                'name'              => $key,
                'email'             => $key . '@' . $key . '.com',
                'password'          => bcrypt($genPa.'123'),//
                'email_verified_at' => date('Y-m-d H:i'),
                'identificacion' => $value,
                'celular' => '123456',
                'fecha_nacimiento' => $anios,
                'sexo' => $genero,
            ]);
//            if($yearRandom < 20)
                $unUsuario->assignRole('administrativo');
//            else
//                $unUsuario->assignRole('lider');
        }
    }
}
/*
php artisan migrate --path=/database/migrations/nombre_de_la_migracion.php
php artisan db:seed --class=NombreDeLaSeeder
*/
