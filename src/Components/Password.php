<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Password extends HtmlComponent
{
    protected function passwordConfirmationValidationScript()
    {
        return <<<HTML
            $('[type=password]').each(function () {
                let passwordConfirmation = $(this).attr('target-confirmation');
                let pc = $(passwordConfirmation);
                if (pc.length) {
                    $(this).change(function () {
                        if ($(this).val()) {
                            pc.attr("required", "required"); 
                            pc.closest("form").data("validation").element(passwordConfirmation);
                        } else {
                            pc.removeAttr("required"); pc.valid()
                        }
                    });
                }
            });
HTML;
    }

    protected function defaultJavascript()
    {
        return $this->passwordConfirmationValidationScript();
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::password($name, array_merge(['class' => 'form-control border-input'], $attributes)).
            '</div>';
    }
}
