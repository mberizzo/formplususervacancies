<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Mberizzo\FormPlusUserVacancies\Models\Job;
use Mberizzo\FormPlusUserVacancies\Models\JobUser;
use RainLab\User\Facades\Auth;

class VacancyDetails extends ComponentBase
{

    public $vacancyDetails;
    public $relatedList;

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
                'type' => 'string',
            ],
            'relatedLimit' => [
                'title'             => 'mberizzo.formplususervacancies::lang.settings.jobs_related_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'mberizzo.formplususervacancies::lang.settings.jobs_per_page_validation',
                'default'           => '10',
            ],
        ];
    }

    public function onRun()
    {
        $this->vacancyDetails = $this->page['vacancyDetails'] = $this->getJob();
        $this->ifUserHasAlreadyApplied = $this->page['ifUserHasAlreadyApplied'] = $this->ifUserHasAlreadyApplied();
        $this->relatedList = $this->page['relatedList'] = $this->getRelatedJobs();
    }

    public function getRelatedJobs()
    {
        return Job::where([
            'category_id' => $this->vacancyDetails->category_id,
            ['id', '<>', $this->vacancyDetails->id]
        ])
        ->limit($this->property('relatedLimit'))
        ->get();
    }

    public function onApply()
    {
        JobUser::firstOrCreate([
            'job_id' => $this->getJob()->id,
            'user_id' => Auth::getUser()->id,
        ]);
    }

    private function getJob()
    {
        $slug = $this->property('slug');

        return Job::whereSlug($slug)->firstOrFail();
    }

    private function ifUserHasAlreadyApplied()
    {
        if (! Auth::check()) {
            return false;
        }

        $count = JobUser::where([
            'job_id' => $this->getJob()->id,
            'user_id' => Auth::getUser()->id,
        ])->count();

        return $count > 0;
    }
}
