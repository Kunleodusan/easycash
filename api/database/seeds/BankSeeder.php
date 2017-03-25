<?php

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert([
            'name'=>'Access Bank',
            'email'=>'atm@accessbank.com',
            'phone'=>'08151023770',
            'password'=>bcrypt('password')
        ]);
    }
}
