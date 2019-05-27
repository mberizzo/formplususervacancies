<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Mberizzo\FormPlusUserVacancies\Models\Job;
use October\Rain\Database\Updates\Seeder;

class SeedAllTables extends Seeder
{
    public function run()
    {
        Job::insert($this->makeVacancies());
    }

    private function makeVacancies()
    {
        return [
            [
                'title' => 'Lorem ipsum dolor sit',
                'slug' => 'lorem-ipsum-dolor-sit',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.',
            ],
            [
                'title' => 'Excepteur sint occaecat',
                'slug' => 'excepteur-sint-occaecat',
                'description' => '<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
            ],
            [
                'title' => 'Voluptate velit esse',
                'slug' => 'voluptate-velit-esse',
                'description' => '<p>Voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>Voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
            ],
        ];
    }
}
