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
            'building_id' => 'admin'
        ]);
        DB::table('building')->insert([
            'building_id' => 'a'
        ]);
        
        
        DB::table('password')->insert([
            'building_id' => '1',
            'pass' => md5('admin')
        ]);
        DB::table('password')->insert([
            'building_id' => '2',
            'pass' => md5('a')
        ]);

        DB::table('room')->insert([
            'building_id' => '2',
            'room' => '201'
        ]);
    }
}