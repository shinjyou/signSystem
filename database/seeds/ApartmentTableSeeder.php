<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'namba',
            'pass' => 'nambanamba',
        ];
        DB::table('apartment')->insert($param);

        $param = [
            'name' => 'umeda',
            'pass' => 'umedaumeda',
        ];
        DB::table('apartment')->insert($param);

        $param = [
            'name' => 'tennouji',
            'pass' => 'tennoujitennouji',
        ];
        DB::table('apartment')->insert($param);

    }
}
