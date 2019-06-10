<?php namespace Mberizzo\FormPlusUserVacancies;

use Illuminate\Support\Facades\Event;
use Mberizzo\FormPlusUserVacancies\Models\Job;
use Mberizzo\FormPlusUserVacancies\Models\JobUser;
use Mberizzo\FormPlusUserVacancies\Models\LogUser;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;
use Renatio\FormBuilder\Models\FormLog;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Renatio.FormBuilder',
        'Mberizzo.FormLogsPlus',
        'RainLab.User',
    ];

    public function registerComponents()
    {
        return [
            'Mberizzo\FormPlusUserVacancies\Components\VacancyList' => 'vacancyList',
            'Mberizzo\FormPlusUserVacancies\Components\VacancyDetails' => 'vacancyDetails',
            'Mberizzo\FormPlusUserVacancies\Components\FormData' => 'formData',
        ];
    }

    public function boot()
    {
        Event::listen('formBuilder.beforeSendMessage', function ($form, $data, $files) {
            return true; // Avoid send email
        });

        // @TODO: maybe we can improve this relationship
        User::extend(function($model) {
            $model->hasOne['curriculum'] = [
                LogUser::class,
                'key' => 'user_id'
            ];

            $model->belongsToMany['jobs'] = [
                Job::class,
                'table' => 'mberizzo_formplususervacancies_job_user',
                'key' => 'user_id',
                'otherKey' => 'job_id'
            ];
        });

        FormLog::extend(function($model) {
            $model->hasOne['userRel'] = [
                LogUser::class,
                'key' => 'log_id',
            ];

            $model->bindEvent('model.afterSave', function() use ($model) {
                // 1. Debo intervenir el modelo Renatio\FormBuilder\Models\FormLog
                // en el metodo afterSave entoces cuando se termine de guardar el log, lo que hacemos es modificar el log_id en nuestra tabla logs_user entonces el user_id siempre va ser el mismo.
                // 1.A) Verificamos si el user tiene un cv previamente relacionado en la tabla "user_form_logs"
                // Si es asi, le actualizamos el form_log_id, sino creamos un registro con el id del user y el nuevo de id de form_log_id
                //
                $user = Auth::getUser();

                LogUser::updateOrCreate(
                    ['user_id' => $user->id],
                    ['log_id' => $model->id]
                );

                //
                // 2. luego deberia eliminar los registros de "renatio_formbuilder_form_logs" que no estan relacionados con nuestra tabla asi todos los registros de renatio_formbuilder_form_logs siempre van a estar relacionados con un user.
                //
                FormLog::doesntHave('userRel')->delete();
            });
        });
    }
}
