<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(App\User::class, 1)->create([
            'username'  => 'Administrator',
    	    'name'      => 'Administrator',
    	    'email'     => 'admin@gmail.com',
    	    'password'  => bcrypt('123456'),
    	]);

    	DB::table('roles')->insert([
    	    [
    	        'name'         => 'admin',
    	        'display_name' => 'Admin',
    	        'description'  => 'Quyền truy cập admin',
    	    ],
    	    [
    	        'name'         => 'superadmin',
    	        'display_name' => 'Super Admin',
    	        'description'  => 'Quyền tối thượng',
    	    ]
    	]);

    	DB::table('role_user')->insert([
    	    [
    	        'user_id' => 1,
    	        'role_id' => 1,
    	    ],
    	    [
    	        'user_id' => 1,
    	        'role_id' => 2,
    	    ]
    	]);
        $this->call(Categories::class);
        $this->call(Product::class);
        $this->call(Customer::class);
        $this->call(Pages::class);
        // $this->call(Permission::class);
    }
}
