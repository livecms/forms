<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Money extends Number
{
    public function render()
    {
        $this->params['label'] .= sprintf(' (%s)', config('currency.currencies.'.config('currency.default')));
        return parent::render();
    }
}
