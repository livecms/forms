<?php

namespace LiveCMS\Form\Components;

class Boolean extends Radio
{
    public function render()
    {
        $this->params['options'] = [1 => __('Yes'), 0 => __('No')];
        return parent::render();
    }
}
