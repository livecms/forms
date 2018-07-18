<?php

namespace LiveCMS\Form\Components;

class Separator extends HtmlComponent
{
    public function render()
    {
        extract($this->params);

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                '<hr class="bold" style="border-color: #aaa;">'.
            '</div>';
    }
}

