<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Users;

class ThemQuanTriVien extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addAdmin = Users::create(
        	['username'=>'minhtan','password'=>Hash::make('user'),'phone'=>'0389998953','name'=>'Minh Tân', 'email' => 'tanminhvo12340@gmail.com']
        );
    }
}