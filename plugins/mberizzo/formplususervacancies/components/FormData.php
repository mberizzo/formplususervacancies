<?php

namespace Mberizzo\FormPlusUserVacancies\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Exception\ApplicationException;
use RainLab\User\Facades\Auth;
use System\Models\File;

class FormData extends ComponentBase
{

    public $data;
    public $files;
    public $form;

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

        $this->form = $form = $this->getPageFormOrFail();

        $this->addJs('assets/js/vue.js');

        // Check if the user has not curriculum
        if (! $user->curriculum) {
            $this->data = $this->fillWithEmpty($form->fields);
            return;
        }

        $formLog = $user->curriculum->log;
        $data = $formLog->form_data;

        $form->fields->each(function ($item) use ($formLog, &$data) {
            // @TODO change this to model observer when creating form fields
            $this->addVModelToField($item);

            $names = array_keys($formLog->form_data);

            // @TODO: why this?
            if (! in_array($item->name, $names)) {
                $data[$item->name] = [
                    'label' => $item->label,
                    'value' => '',
                ];
            }
        });

        $this->data = $data;
        $this->files = $formLog->files;
    }

    public function onRemoveFile()
    {
        return File::find(post('id'))->delete();
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

    /**
     * Fill the data with empty values
     * To avoid js errors.
     *
     * @param  $fields
     * @return
     */
    private function fillWithEmpty($fields)
    {
        $data = [];

        $fields->each(function ($item) use (&$data) {
            $data[$item->name] = [
                'label' => $item->label,
                'value' => '',
            ];
        });

        return $data;
    }

    /**
     * Add v-model to field
     *
     * @param $field
     */
    private function addVModelToField($field)
    {
        $notAllowed = ['submit', 'file_uploader'];
        $type = $field->field_type->code;

        if (in_array($type, $notAllowed)) {
            return;
        }

        $vModel = 'v-model="form_data.' . $field->name . '.value"';

        // @TODO: checkbox_list is not supported
        /*if ($type == 'checkbox_list') {
            $vModel = 'v-model="form_data.' . $field->name . '[].value"';
        }*/

        $field->custom_attributes = $vModel;
        $field->save();
    }
}
