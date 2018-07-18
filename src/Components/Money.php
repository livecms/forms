<?php

namespace LiveCMS\Form\Components;

class Money extends Number
{
    public function render()
    {
        $this->params['label'] .= sprintf(' (%s)', config('currency.currencies.'.config('currency.default')));
        return parent::render();
    }
}
