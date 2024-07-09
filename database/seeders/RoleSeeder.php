<?php

namespace Database\Seeders;

use App\helpers\CargosModelos;
use App\helpers\Myhelp;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);

       $constantes = CargosModelos::CargosYModelos();

        //ex: istrabajador
        foreach ($constantes['nombresDeCargos'] as $value) {
            $$value = Role::create(['name' => $value]);
            $$value->givePermissionTo(['is' . $value]);
        }

        $crudCompleto = ['delete', 'update', 'read', 'create'];
        foreach ($constantes['Models'] as $model) {
            foreach ($crudCompleto as $crud) {
                $superadmin->givePermissionTo([$crud . ' ' . $model]);
                $admin->givePermissionTo([$crud . ' ' . $model]);
            }
        }
        $isSomeThing = array_merge(  [ "isSuper", "isAdmin" ], $constantes['isSome']);
        $superadmin->givePermissionTo($isSomeThing);
        unset($isSomeThing[0]);
        $admin->givePermissionTo($isSomeThing);



        //variableee
        $jefe->givePermissionTo([
            'read formulario',  'create formulario',  // 'delete formulario',
        ]);
        $jefe->givePermissionTo([
            'read formulario',  'create formulario', 'delete formulario', 'update formulario',
            'read user',  'create user', 'delete user', 'update user',
            //'downloadAllBD'
        ]);

        // $role->revokePermissionTo($permission);
        // $permission->removeRole($role);
    }
}
