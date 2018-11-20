<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class TinyMCE extends HtmlComponent
{
    protected function defaultJavascript()
    {
        return $this->tinyMCEScript();
    }

    protected function tinyMCEScript()
    {
        return <<<HTML
            tinymce.init({
                selector: 'textarea.tinymce'
            });
HTML;
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::textarea($name, old($name, $value), array_merge(['class' => 'form-control border-input tinymce'], $attributes)).
            '</div>';
    }
}
