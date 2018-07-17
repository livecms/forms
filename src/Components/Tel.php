<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Tel extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::tel($name, old($name, $value), array_merge(['class' => 'form-control border-input', 'onkeypress' => 'return event.charCode < 65 || event.charCode > 122', 'maxlength' => '18'], $attributes)).
            '</div>';
    }
}
