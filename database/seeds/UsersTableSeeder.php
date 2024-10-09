<?php

use Illuminate\Database\Seeder;
 use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'f_name' => "Admin",
            'l_name' => "Milma",
            'image' => "user.jpg",
            'role'  => 'admin',
            'email' => 'admin@milma.com',
            'password' => bcrypt('admin@milma'),
        ]);

        DB::table('users')->insert([
          'f_name' => "SalesMan",
          'l_name' => "A",
          'role'  => 'sales',
          'image' => "user.jpg",
          'email' => 'salesman_a@milma.com',
          'password' => bcrypt('salesman_a@milma'),
        ]);

        DB::table('users')->insert([
          'f_name' => "SalesMan",
          'l_name' => "B",
          'role'  => 'sales',
          'image' => "user.jpg",
          'email' => 'salesman_b@milma.com',
          'password' => bcrypt('salesman_b@milma'),
        ]);
    }
}
