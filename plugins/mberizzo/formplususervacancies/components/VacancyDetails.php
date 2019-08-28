<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Event;
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
        $this->addJs('assets/js/main.js');
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
        $user = Auth::getUser();

        // First of all detect if has CV
        if (! $user->curriculum) {
            throw new \AjaxException([
                'error' => 'You should save your Curriculum.',
            ]);
        }

        $job = $this->getJob();

        JobUser::firstOrCreate([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        Event::fire('mberizzo.formplususervacancies.userAppliedToJob', [$user, $job]);
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
