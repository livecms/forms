<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Text extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::text($name, old($name, $value), array_merge(['class' => 'form-control border-input'], $attributes)).
            '</div>';
    }
}
