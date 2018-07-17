<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Number extends HtmlComponent
{
    protected function numberInputScript()
    {
        return <<<HTML
            $('input[type=number]').keyup(function (e) {
                let val = $(this).val();
                let id = $(this).attr('id');
                let text = $('.number-word[for='+id+']');
                if (!val) {
                    text.text($(this).attr('placeholder'));
                } else if (writtenNumber) {
                    text.text(writtenNumber(val));
                }
            })
            .change(function () {
                $(this).keyup();
            })
            .keyup();
HTML;
    }

    protected function defaultJavascript()
    {
        return $this->numberInputScript();
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                '<div class="number-word" for="'.$name.'">&nbsp;</div>'.
                FormFacade::number($name, old($name, $value), array_merge(['class' => 'form-control border-input'], $attributes)).
            '</div>';
    }
}
