<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([    
        	'username'  			=> 'jxheredia',        	     	    		   
		    'email' 				=> 'test@gmail.com',		    		   
		    'enabled'				=> 1,
		    'salt'					=> 'Test',
		    'password' 				=> bcrypt('12345678'),
		    'first_name' 			=> 'Joel',
		    'last_name'				=> 'Heredia',
		    'phone_number'			=> '+5812345678',
		    'company_id'			=> 1,
		    'branch_office_id'		=> 1,
		    'is_active'				=> 1
		]);
    }
}
