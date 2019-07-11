<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Mberizzo\FormPlusUserVacancies\Models\Category;
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
        return [
            'categoryFilter' => [
                'title'       => 'mberizzo.formplususervacancies::lang.settings.jobs_filter',
                'description' => 'mberizzo.formplususervacancies::lang.settings.jobs_filter_description',
                'type'        => 'string',
                // 'default'     => ''
                'default'     => '{{ :category }}',
            ],
            'perPage' => [
                'title'             => 'mberizzo.formplususervacancies::lang.settings.jobs_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'mberizzo.formplususervacancies::lang.settings.jobs_per_page_validation',
                'default'           => '10',
            ],
        ];
    }

    public function onRun()
    {
        $this->page['vacancyList'] = $this->getJobList();
    }

    private function getJobList()
    {
        // Category slug filter
        $categoryFilter = $this->property('categoryFilter');
        $this->validateCategory($categoryFilter);

        $job = new Job;

        if ($categoryFilter) {
            $job = $job->whereHas('category', function($q) use ($categoryFilter) {
                return $q->where('slug', $categoryFilter);
            });
        }

        if ($tag = get('tag', false)) {
            $job = $job->where('tags', 'like', "%{$tag}%");
        }

        $perPage = $this->property('perPage');

        return $job->orderBy('id', 'desc')->paginate($perPage);
    }

    private function validateCategory($categoryFilter)
    {
        if ($categoryFilter) {
            return Category::where('slug', $categoryFilter)->firstOrFail();
        }
    }
}
