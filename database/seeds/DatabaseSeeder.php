<?php

use Database\Seeders\RoleSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\KeywordSeeder;
use Database\Seeders\TimezoneSeeder;
use Database\Seeders\MembershipSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TimezoneSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(MembershipSeeder::class);
        $this->call(KeywordSeeder::class);
    }
}
