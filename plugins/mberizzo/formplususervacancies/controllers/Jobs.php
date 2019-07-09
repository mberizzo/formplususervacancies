<?php namespace Mberizzo\FormPlusUserVacancies\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use RainLab\User\Models\User;

class Jobs extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'compact-container';
        BackendMenu::setContext('Mberizzo.FormPlusUserVacancies', 'main-menu-item', 'jobs');
    }

    public function create()
    {
        parent::create();
        BackendMenu::setContext('Mberizzo.FormPlusUserVacancies', 'main-menu-item', 'create_job');
    }

    public function update($recordId)
    {
        parent::update($recordId);
        BackendMenu::setContext('Mberizzo.FormPlusUserVacancies', 'main-menu-item', 'jobs');
    }

    public $requiredPermissions = [
        'mberizzo.jobs'
    ];

    public function onRelationUsers()
    {
        $jobId = request()->get('jobId');

        // Specify which list configuration file use for this list
        $config = $this->makeConfig('$/mberizzo/formplususervacancies/models/job/relation/user_columns.yaml');

        // Specify the List model
        $config->model = new User;

        // Here we will actually make the list using Lists Widget
        $listWidget = $this->makeWidget('Backend\Widgets\Lists', $config);

        $listWidget->bindEvent('list.extendQueryBefore', function ($query) use ($jobId) {
            $query->whereHas('jobs', function($q) use ($jobId) {
                $q->where('job_id', $jobId);
            });
        });

        # Dont forget to bind the whole thing to the controller
        $listWidget->bindToController();

        $this->vars['listWidget'] = $listWidget;

        return $this->makePartial('user_custom_list');
    }

}
