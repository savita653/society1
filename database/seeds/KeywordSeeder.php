<?php

namespace Database\Seeders;

use App\Keyword;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach( config('setting.area_of_interest_options') as $keyword ) {
            Keyword::create([
                'keyword_name' => $keyword,
            ]);
        }
    }
}
