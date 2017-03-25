<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name'=>'Admin',
            'email'=>'admin@easycash.com',
            'phone'=>'08151023770',
            'password'=>bcrypt('password')
        ]);
    }
}
