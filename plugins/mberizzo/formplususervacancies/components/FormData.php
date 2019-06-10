<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
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
        $form = $this->page->renderForm;

        if (! $form) {
            throw new ApplicationException('You must be load any RenatioForm!');
        }

        $user = Auth::getUser();

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

        $fields = $formLog->form->fields;

        // Rellenar $data con los fields que no han sido completados
        // por el usuario en su cv para que vue no de error de js.
        // @TODO: Aca tb podemos aprovechar para agregarle el v-model dinamicamente a $item
        $fields->each(function ($item) use (&$formLog) {
            $names = array_keys($formLog->form_data);

            if (! in_array($item->name, $names)) {
                $formLog->form_data[$item->name] = [
                    'label' => $item->label,
                    'value' => '',
                ];
            }
        });

        $this->data = $formLog->form_data;
    }
}
