<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Admin;

class ThemNguoiChoi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addAdmin = Admin::create(
        	['username'=>'minhtan','password'=>Hash::make('admin'),'phone'=>'0389998953','name'=>'Tân']
        );
    }
}
