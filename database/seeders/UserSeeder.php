<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_list = [
            'Hajime', 
            'Test1', 
            'Test2', 
            'Test3', 
            'Test4', 
            'Test5', 
            'Test6'
        ];

        $email_list = [
            'hajime@foresky.co.jp', 
            'test@gmail.com', 
            'testuser1@gmail.com', 
            'testuser2@gmail.com',
            'testuser3@gmail.com', 
            'testuser4@gmail.com', 
            'testuser5@gmail.com'
        ];
        
        for($i = 0; $i < count($email_list); $i++) 
        {
            $user = new User;
            $user->name = $user_list[$i];
            $user->email = $email_list[$i];
            $user->role = "customer";
            $user->password = Hash::make('12345678');
            $user->save();
        }
    }
}
