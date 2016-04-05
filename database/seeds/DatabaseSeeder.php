<?php

use Illuminate\Database\Seeder;

use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $admin = new User();
        $admin->name = "emanuelsan";
        $admin->password = bcrypt('rotopercutor');
        $admin->email = 'emanuelsan@gmail.com';
        $admin->save();
    }
}
