<?php
namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
    public function run()
    {
        $user = User::create([
            'name' => 'fares tom',
            'email' => 'fares@gmail.com',
            'password' => bcrypt('123456789'),
            'roles_name' => ["admin"],
            'Status' => 'مفعل',
        ]);
        $role = Role::create(['name' => 'admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
