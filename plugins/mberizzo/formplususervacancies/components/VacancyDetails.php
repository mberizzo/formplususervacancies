<?php namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Mberizzo\FormPlusUserVacancies\Models\Job;

class VacancyDetails extends ComponentBase
{

    public $vacancyDetails;

    public function componentDetails()
    {
        return [
            'name'        => 'Vacancy Details',
            'description' => 'It display the details of the job offer.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'   => 'mberizzo.formplususervacancies::lang.settings.job_id',
                'description' => 'description',
                'default' => '{{ :slug }}',
                'type'    => 'integer',
            ],
        ];
    }

    public function onRun()
    {
        $slug = $this->property('slug');

        $this->vacancyDetails = $this->page['vacancyDetails'] = $this->getJobDetails($slug);
    }

    private function getJobDetails($slug)
    {
        return Job::whereSlug($slug)->firstOrFail();
    }
}
