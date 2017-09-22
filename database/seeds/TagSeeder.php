<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    const TAGS  = [
        'Home feed',
        'Popular',
        'Everything',
        'Gifts',
        'Videos',
        'Animals and pets',
        'Architecture',
        'Art',
        'Cars and motocyles',
        'Celebrities',
        'DIY and crafts',
        'Design',
        'Education',
        'Entertainment',
        'Food and drink',
        'Gardening',
        'Geek',
        'Hair and beauty',
        'Health and fitness',
        'History',
        'Holidays and events',
        'Humor',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::TAGS as $tag) {
            DB::table('tag')->insert([
                'name' => $tag,
                'description' => $tag
            ]);
        }
    }
}
