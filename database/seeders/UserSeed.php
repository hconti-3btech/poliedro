<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();

        $user->name = 'Administrador';
        $user->email = 'poliedrottc@monitoramentottc.com.br';
        $user->password = Hash::make('admin.$3nh@'); //
        $user->status = 'ativado';
        $user->nv_acesso = 'admin';
        $user->created_at = date('Y-m-d H:m:i');
        $user->updated_at = date('Y-m-d H:m:i');

        $user->save();
    }
}
