<?php

use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'sales'],
            ['name' => 'technical']
        ];

        foreach ($items as $item) {
            \App\Role::create($item);
        }

    }
}
