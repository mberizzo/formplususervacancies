<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Mberizzo\FormPlusUserVacancies\Models\Job;

class VacancyList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Vacancy List',
            'description' => 'It display a list of the jobs offers.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['vacancyList'] = $this->getJobList();
    }

    private function getJobList()
    {
        return Job::all();
    }
}
