<?php

namespace LiveCMS\Form;

use LiveCMS\Transport\HtmlTransport;
use LiveCMS\Transport\JavascriptTransport;

class Forms
{
    protected $id;
    protected $factory;
    protected $setting;
    protected $formTransport;
    protected $javascriptTransport;
    protected $components = [];
    protected $data = [];
    protected $globalProperties = [];
    protected $additionalProperties = [];

    public function __construct(ComponentFactory $factory, HtmlTransport $form, JavascriptTransport $js)
    {
        $this->factory = $factory;
        $this->formTransport = $form;
        $this->javascriptTransport = $js;
        $this->generateFormId();
    }

    protected function generateFormId()
    {
        $this->id = uniqid();
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function set($setting)
    {
        $this->setting = $setting;
        return $this;
    }

    public function addGlobalProperties(array $properties = [])
    {
        foreach ($properties as $key => $value) {
            list($propKey, $propValue) = explode(':', $key.':');
            $propValue = empty($propValue) ? true : $propValue;
            if (empty($value) || $value == '*') {
                $this->globalProperties[$propKey] = $propValue;
            } else {
                $inputs = (array) $value;
                foreach ($inputs as $input) {
                    if (isset($this->additionalProperties[$input])) {
                        $this->additionalProperties[$input][$propKey] = $propValue;
                    } else {
                        $this->additionalProperties[$input] = [$propKey => $propValue];
                    }
                }
            }
        }
        return $this;
    }

    protected function extract()
    {
        foreach ($this->setting as $key => $setting) {
            $properties = array_replace($setting, $this->globalProperties, $this->additionalProperties[$key] ?? []);
            $this->components[$key] = $this->factory->create($key, $properties);
        }
        return $this;
    }

    public function fill(array $data = [])
    {
        $this->data = array_replace($this->data, $data);
        return $this;
    }

    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }

    protected function validationScript()
    {
        return <<<HTML
            $.validator.setDefaults({ 
                ignore: ":hidden:not(.need-validation)",
                // any other default options and/or rules
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    elem.addClass(errorClass);
                    if (elem.hasClass("select2-hidden-accessible")) {
                       $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass).removeClass('validClass'); 
                    } else if ( element.type === "radio" ) {
                        this.findByName( element.name ).addClass( errorClass ).removeClass( validClass );
                    } else {
                       elem.addClass(errorClass);
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    elem.removeClass(errorClass);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass).addClass('validClass');
                    } else if ( element.type === "radio" ) {
                        this.findByName( element.name ).removeClass( errorClass ).addClass( validClass );
                    } else {
                        elem.removeClass(errorClass).addClass('validClass');
                    }
                },
                errorPlacement: function(error, element) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                       element = $("#select2-" + elem.attr("id") + "-container").parent(); 
                       error.insertAfter(element);
                    } else {
                       error.insertAfter(element);
                    }
                }
            });

            $('form').each(function () {
                $(this).data('validation', $(this).validate());
                $(this).data('identifier', id = $(this).find(':input').map(function () {
                    return $(this).attr('id');
                }).get().join('.'));
                $('<input type="hidden" name="_identifier" value="'+id+'">').insertAfter($(this).find('[name=_token]'));

            });
                
HTML;
    }

    public function addValidation()
    {
        $this->javascriptTransport->append($this->validationScript());
        return $this;
    }

    public function render($flush = false)
    {
        $this->extract();
        $html = '';
        foreach ($this->components as $key => $component) {
            $this->formTransport->append(
                $html .= $component->setValue($this->getData($key))->toHtml(),
                $this->id
            );
            foreach ($component->toJavascript() as $js) {
                $this->javascriptTransport->append($js);
                
            }
        }
        if ($flush) {
            $this->formTransport->flush($this->id);
        }
        return $html;
    }
}
