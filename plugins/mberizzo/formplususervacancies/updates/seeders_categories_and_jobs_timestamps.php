<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Mberizzo\FormPlusUserVacancies\Models\Category;
use Mberizzo\FormPlusUserVacancies\Models\Job;
use October\Rain\Database\Updates\Seeder;

class SeedCategoriesAndUpdateJobs extends Seeder
{
    public function run()
    {
        Category::insert($this->makeCategories());

        foreach (Job::all() as $job) {
            $job->update([
                'category_id' => Category::inRandomOrder()->first()->id,
                'tags' => $this->makeTags(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function makeCategories()
    {
        return [
            [
                'name' => 'Lorem ipsum',
                'slug' => 'lorem-ipsum',
            ],
            [
                'name' => 'Dolor sit',
                'slug' => 'dolor-sit',
            ],
            [
                'title' => 'Velit esse',
                'slug' => 'velit-esse',
            ],
        ];
    }

    private function makeTags()
    {
        $tags = ['Mar del plata', 'Doctor', 'Rosario', 'Mayonesa', 'Hola', 'Como'];

        return implode(',', array_random($tags, 3));
    }

}
