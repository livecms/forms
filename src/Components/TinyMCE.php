<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class TinyMCE extends HtmlComponent
{
    protected function defaultJavascript()
    {
        return $this->tinyMCEScript();
    }

    protected function tinyMCEScript()
    {
        return <<<HTML
            var editor_config = {
                path_absolute: "{{url('')}}/",
                selector: "textarea.tinymce",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
            };

            tinymce.init(editor_config);

            tinymce.init({
                selector: 'textarea.tinymce'
            });
HTML;
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::textarea($name, old($name, $value), array_merge(['class' => 'form-control border-input tinymce'], $attributes)).
            '</div>';
    }
}
