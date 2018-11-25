<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class TinyMCEMidia extends HtmlComponent
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
                selector: "textarea.tinymce-midia",
                plugins: [
                  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                  "searchreplace wordcount visualblocks visualchars code fullscreen",
                  "insertdatetime media nonbreaking save table contextmenu directionality",
                  "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function(field_name, url, type, win) {
                  var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                  var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                  var cmsURL = editor_config.path_absolute + 'midia/open/tinymce4?field_name=' + field_name;

                  tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                  });
                }
            };

            tinymce.init(editor_config);
HTML;
    }

    public function render()
    {
        extract($this->params);
        $attributes['placeholder'] = $attributes['placeholder'] ?? __('Empty');

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::textarea($name, old($name, $value), array_merge(['class' => 'form-control border-input tinymce-midia'], $attributes)).
            '</div>';
    }
}
