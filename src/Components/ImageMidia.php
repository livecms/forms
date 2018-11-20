<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class ImageMidia extends HtmlComponent
{
    protected function midiaInputScript()
    {
        return <<<HTML
            $('.media-remove').click(function () {
                let frame = $(this).closest('.image-frame');
                let forInput = $(this).attr('for');
                frame.removeClass('exists');
                frame.find('[name=selecting__'+forInput+']').removeAttr('value');
                frame.find('[name=removing__'+forInput+']').val('1');
                if (frame.is('[required]')) {
                    frame.find('[name=selecting__'+forInput+']').prop('required', true);
                }
            });

            $.fn.midia.defaultSettings.onChoose = function (file) {
                let preview = $('#' + file.preview);
                let frame = preview.closest('.image-frame');
                let forInput = frame.data('image');
                frame.addClass('exists');
                frame.find('[name=selecting__'+forInput+']').val(file.identifier);
                frame.find('[name=removing__'+forInput+']').removeAttr('value');
            };
HTML;
    }

    protected function defaultJavascript()
    {
        return $this->midiaInputScript();
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                '<div id="preview__'.$name.'" data-image="'.$name.'" class="'.(!empty($value) ? 'exists' : ''). ' image-frame text-center" style="max-width: 280px" '.(isset($attributes['required']) ? 'required' : '').'>'.
                    '<img id="image_preview__'.$name.'" src="'.($value ? $value->getFullUrl('thumb') : '').'" class="media-file img-responsive" alt="'.$label.'" style="margin: 20px auto;">'.
                    '<a class="btn btn-fill btn-block midia-toggle" data-preview="image_preview__'.$name.'" '.($value ? 'data-midia-initial_preview=""' : '').'>'.
                        '<span class="media-select">'. __('Select Image') .'</span>'.
                        '<span class="media-change">'. __('Change Image') .'</span>'.
                    '</a>'.
                    '<a class="btn btn-fill btn-block media-remove" for="'.$name.'" style="background-color: #bf6a6a;">'. __('Remove') .'</a>'.
                    FormFacade::hidden('selecting__'.$name, null, ['class' => 'input-image need-validation']).
                    FormFacade::hidden('removing__'.$name).
                '</div>'.
            '</div>';

    }
}
