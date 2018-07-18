<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Image extends HtmlComponent
{
    public function render()
    {
        extract($this->params);

        $labelHtml = FormFacade::label($name, $label, ['class' => 'control-label']);
        $exists = !empty($value) ? 'exists' : '';
        $imageHtml = $value === null
                        ? '<div class="no-image">'. __('No Image') .'</div>'
                        : '<img src="'.$value.'" class="img-responsive" alt="'.$label.'" style="max-width: 300px">';
        $btnLabel = $value === null ? __('Choose Photo To Upload') : __('Choose Photo To Change');
        $fileHtml = FormFacade::file($name, array_merge(['class' => 'form-control border-input', 'data-label' => $label], $attributes));
        $imageHtml = 
        $checkbox = $value !== null
                    ?   '<div class="checkbox">'.
                            FormFacade::checkbox('removing__'.$name, true).
                            '<label for="removing__'.$name.'">'.
                                     __('Remove image when save').
                            '</label>'.
                        '</div>'
                    : '';



        return <<<HTML
            <div class="form-group" id="{$name}__parent">
                {$labelHtml}
                <div data-image="{$name}" class="{$exists}">
                    <div class="image-frame">
                        {$imageHtml}
                    </div>
                    <div class="block">
                        <label for="{$name}">{$btnLabel}</label>
                        {$fileHtml}
                        {$checkbox}
                    </div>
                </div>
            </div>
HTML;

    }
}
