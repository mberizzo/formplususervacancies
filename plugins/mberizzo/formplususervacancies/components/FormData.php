<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Exception\ApplicationException;
use RainLab\User\Facades\Auth;

class FormData extends ComponentBase
{

    public $data;

    public function componentDetails()
    {
        return [
            'name'        => 'FormData Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function onRun()
    {
        $user = $this->getUserOrFail();

        $form = $this->getPageFormOrFail();

        $this->addJs('assets/js/vue.js');

        // The user has a form related
        if (! $user->curriculum) {
            $data = [];

            $form->fields->each(function ($item) use (&$data) {
                $data[$item->name] = [
                    'label' => $item->label,
                    'value' => '',
                ];
            });

            $this->data = $data;

            return;
        }

        $formLog = $user->curriculum->log;
        $data = $formLog->form_data;

        // Rellenar $data con los fields que no han sido completados
        // por el usuario en su cv para que vue no de error de js.
        // @TODO: Aca tb podemos aprovechar para agregarle el v-model dinamicamente a $item
        $form->fields->each(function ($item) use ($formLog, &$data) {
            $names = array_keys($formLog->form_data);

            if (! in_array($item->name, $names)) {
                $data[$item->name] = [
                    'label' => $item->label,
                    'value' => '',
                ];
            }
        });

        $this->data = $data;
    }

    private function getUserOrFail()
    {
        if (! Auth::check()) {
            throw new ApplicationException('You must be logged in.');
        }

        return Auth::getUser();
    }

    private function getPageFormOrFail()
    {
        $renderForm = $this->page->renderForm;

        if (! $renderForm) {
            throw new ApplicationException(
                'You must be load some RenatioForm with alias "renderForm".'
            );
        }

        return $renderForm->form;
    }
}
