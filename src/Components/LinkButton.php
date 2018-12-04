<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;
use Illuminate\Support\HtmlString;

class LinkButton extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['value'] = $name;
        $attributes['class'] = 'btn btn-primary';
        $button = FormFacade::button($label, $attributes);
        return new HtmlString(str_replace(['<button ', 'value=', '</button'], ['<a ', 'href=', '</a'], $button));
    }
}
