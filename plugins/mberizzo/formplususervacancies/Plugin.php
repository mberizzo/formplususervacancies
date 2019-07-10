<?php namespace Mberizzo\FormPlusUserVacancies;

use Illuminate\Support\Facades\Event;
use Mberizzo\FormLogsPlus\Controllers\Logs;
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
        Event::listen('eloquent.creating: ' . FormLog::class, function ($data) {
            logger('eloquent.creating');

            // @TODO: Improve this comment or delete it (for now is useful):
            // El user puede estar relacionado con un solo CV,
            // entonces si buscamos en LogUser el id del usuario,
            // ya tendremos que obtener el log_id (y tb fijarnos que sea el form de cv
            // el que nos esta llegando) y alli actualizar los datos del log,
            // de este modo tambien se conservan los archivos adjuntos.
            // Pero tengo que evitar que se cree un nuevo log (ya que
            // esto se hace en el plugin de renatio)
            $user = Auth::getUser();
            $logUser = LogUser::where('user_id', $user->id)->first();

            // If the user already has Curriculum saved
            if ($logUser) {
                trace_log('If the user already has Curriculum saved');

                $log = FormLog::find($logUser->log_id);

                // Detect if the form_id saved (log_user table: $log->form_id) is the curriculum form
                // So we are detecting if the user is trying to update his curriculum.
                // If the form_id are diferrent maybe the user is trying to send a contact form or whatever.
                if ($log->form_id == $data->form_id) {
                    $formData = $log->form->getFormData();
                    $log->form_id = $log->form->id;
                    $log->form_data = $formData;
                    $log->save(null, post('_session_key'));

                    trace_log("Updated CV data. log_id: {$log->id}. user_id: {$user->id}. New data: ", $formData);

                    return false; // Stop creation
                }
            }
        });

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
            $model->bindEvent('model.afterCreate', function() use ($model) {
                trace_log('model.afterCreate');

                $user = Auth::getUser();

                // If user doenst has Curriculum and we are sending
                // the CV form so bind user with log
                if (! $user->curriculum && post('is_curriculum')) {
                    LogUser::create([
                        'user_id' => $user->id,
                        'log_id' => $model->id
                    ]);

                    trace_log("The user #{$user->id} uploaded her curriculum for the first time.");
                }
            });
        });
    }
}
