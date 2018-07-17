<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Boolean extends Radio
{
    public function render()
    {
        $this->params['options'] = [1 => 'Yes', 0 => 'No'];
        return parent::render();
    }
}
