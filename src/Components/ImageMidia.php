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
                frame.find('.media-select').css('display', 'block');
                frame.find('.media-change').css('display', 'none');
                frame.find('.media-remove').css('display', 'none');
                frame.find('.media-file').css('display', 'none');
                frame.find('[name=selecting__'+forInput+']').removeAttr('value');
                frame.find('[name=removing__'+forInput+']').val('1');
                if (frame.is('[required]')) {
                    frame.find('[name=selecting__'+forInput+']').prop('required', true);
                }
            });

            if (typeof $.fn.midia == 'function') {

                $.fn.midia.defaultSettings.onChoose = function (file) {
                    let preview = $('#' + file.preview);
                    let frame = preview.closest('.image-frame');
                    let forInput = frame.data('image');
                    frame.addClass('exists');
                    frame.find('.media-select').css('display', 'none');
                    frame.find('.media-change').css('display', 'block');
                    frame.find('.media-remove').css('display', 'block');
                    frame.find('.media-file').css('display', 'block');
                    frame.find('[name=selecting__'+forInput+']').val(file.identifier);
                    frame.find('[name=removing__'+forInput+']').removeAttr('value');
                };

                $(".midia-toggle").midia();
            }
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
        $exists = !empty($value);

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                '<div id="preview__'.$name.'" data-image="'.$name.'" class="'.($exists ? 'exists' : ''). ' image-frame text-center" style="max-width: 280px" '.(isset($attributes['required']) ? 'required' : '').'>'.
                    '<img id="image_preview__'.$name.'" src="'.($value ? $value->getFullUrl('thumb') : '').'" class="media-file img-responsive" alt="'.$label.'" style="margin: 20px auto;'. (!$exists ? 'display: none;' : ''). '">'.
                    '<a class="btn btn-primary btn-fill btn-block midia-toggle" data-preview="image_preview__'.$name.'" '.($value ? 'data-midia-initial_preview=""' : '').' style="background-color: #333; border-radius: 20px;">'.
                        '<span class="media-select" '. ($exists ? 'style="display: none;"' : '').'>'. __('Select Image') .'</span>'.
                        '<span class="media-change" '. (!$exists ? 'style="display: none;"' : '').'>'. __('Change Image') .'</span>'.
                    '</a>'.
                    '<a class="btn btn-danger btn-fill btn-block media-remove" for="'.$name.'" style="background-color: #bf6a6a; border-radius: 20px; '. (!$exists ? 'display: none;' : ''). '">'. __('Remove') .'</a>'.
                    FormFacade::hidden('selecting__'.$name, null, ['class' => 'input-image need-validation']).
                    FormFacade::hidden('removing__'.$name).
                '</div>'.
            '</div>';

    }
}
