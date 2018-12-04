<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;
use Illuminate\Support\HtmlString;

class Link extends HtmlComponent
{
    public function render()
    {
        extract($this->params);
        $attributes['value'] = $name;
        $button = FormFacade::button($label, $attributes);
        return new HtmlString(str_replace(['<button ', 'value=', '</button'], ['<a ', 'href=', '</a'], $button));
    }
}
