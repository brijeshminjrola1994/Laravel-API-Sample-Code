<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{

    public $incrementing = false;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
                'id' => 3,
                'login'=> 'testuser@gmail.com',
                'email'=> 'testuser@gmail.com',
                'login_type'=> 'email',
                'password'=> Hash::make('12345678'),
                'is_app_user'=> 0,
            ]
        ];
        foreach($userData as $user){
            User::create($user);
        }
    }
}
