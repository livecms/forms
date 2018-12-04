<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Hidden extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        return FormFacade::hidden($name, $value);
    }
}
