<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Checkbox extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $isAssoc = array_keys(array_keys($options)) !== array_keys($options);

        $html = '';
        foreach($options as $val => $option) {
            $val = $isAssoc ? $val : $option;
            $html .= '<div class="checkbox">'.
                FormFacade::checkbox($name, $val, $val === old($name, $value), array_merge($attributes, ['id' => $name.'__'.$val])).
                FormFacade::label($name.'__'.$val, $option).
                '</div>';
        }

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                $html.
            '</div>';
    }
}
