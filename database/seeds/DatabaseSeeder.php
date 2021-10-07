<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('InitalDataSeeder::class');
        DB::table('building')->insert([
            'building_id' => 'admin',
            'building_id' => 'a'
        ]);
        
        DB::table('password')->insert([
            'pass' => md5('admin'),
            'building_id' => '1',

            'pass' => md5('a'),
            'building_id' => '2'
        ]);
    }
}