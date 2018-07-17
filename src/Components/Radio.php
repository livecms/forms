<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Radio extends HtmlComponent
{
    protected function changeNumeric($string) {
        return is_integer($string+0) ? (int) $string : (float) $string;
    }

    public function render()
    {
        extract($this->params);
        $isAssoc = array_keys(array_keys($options)) !== array_keys($options);
        $theValue = old($name, $value);
        $theValue = $isAssoc && is_numeric($theValue) ? $this->changeNumeric($theValue) : $theValue;
        unset($attributes['options']);

        $html = '';
        foreach($options as $val => $option) {
            $val = $isAssoc ? (is_numeric($val) ? $this->changeNumeric($val) : $val) : $option;
            $html .= '<div class="radio">'.
                FormFacade::radio($name, $val, $val === $theValue, array_merge($attributes, ['id' => $name.'__'.$val, 'checked' => $val === $theValue])).
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

