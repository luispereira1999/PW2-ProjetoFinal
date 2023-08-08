<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VoteType;

class VoteTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $voteTypes = [
            [
                'id' => 1,
                'title' => 'up'
            ],
            [
                'id' => 2,
                'title' => 'down'
            ]
        ];

        foreach ($voteTypes as $voteType) {
            VoteType::updateOrCreate(['id' => $voteType['id']], $voteType);
        }
    }
}
