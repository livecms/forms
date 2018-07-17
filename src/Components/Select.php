<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Select extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Please Choose');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::select($name, $options ?? [], old($name, $value), array_merge(['class' => 'form-control border-input'], array_except($attributes, ['options']))).
            '</div>';
    }
}
