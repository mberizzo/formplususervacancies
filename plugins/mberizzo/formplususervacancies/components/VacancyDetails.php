<?php namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use Mberizzo\FormPlusUserVacancies\Models\Job;
use Mberizzo\FormPlusUserVacancies\Models\JobUser;
use RainLab\User\Facades\Auth;

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
        $this->vacancyDetails = $this->page['vacancyDetails'] = $this->getJob();
        $this->ifUserHasAlreadyApplied = $this->page['ifUserHasAlreadyApplied'] = $this->ifUserHasAlreadyApplied();
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
        $slug = $this->param('slug');

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
