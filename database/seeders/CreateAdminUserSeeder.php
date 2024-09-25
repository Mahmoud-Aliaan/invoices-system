<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
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
            'name' => 'Mahmoud Aliaan', 
            'email' => 'M4_STORE@gmail.com',
            'password' => bcrypt('0123456789'),
            'roles_name' => ["owner"],
            'Status' => 'مفعل',
        ]);
    
        $role = Role::create(['name' => 'owner']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);
    }
}