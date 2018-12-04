<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Button extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['value'] = $name;
        $attributes['class'] = 'btn btn-primary';
        return FormFacade::button($label, $attributes);
    }
}
