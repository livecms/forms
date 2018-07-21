<?php

namespace LiveCMS\Form;

use Collective\Html\FormFacade;
use Exception;
use LiveCMS\Transport\HtmlTransport;
use LiveCMS\Transport\JavascriptTransport;

class Forms
{
    protected $id;
    protected $form;
    protected $factory;
    protected $setting;
    protected $formTransport;
    protected $javascriptTransport;
    protected $components = [];
    protected $data = [];
    protected $globalProperties = [];
    protected $additionalProperties = [];
    protected $validation = false;

    public function __construct(ComponentFactory $factory, HtmlTransport $form, JavascriptTransport $js)
    {
        $this->factory = $factory;
        $this->formTransport = $form;
        $this->javascriptTransport = $js;
        $this->generateFormId();
    }

    public static function create(array $options = [])
    {
        $instance = app(static::class);
        if (count($options)) {
            $instance->form = FormFacade::open($options);
        }
        return $instance;
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

    public function setComponents($setting)
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
        if ($file = config('form.scripts.validation')) {
            if (file_exists($file)) {
                return file_get_contents($file);
            }
            throw new Exception("File not found {$file}");
        }
        return null;
    }

    public function useValidation($true = true)
    {
        $this->validation = $true;
        return $this;
    }

    public function render($flush = false)
    {
        $this->extract();
        $html = '';

        foreach ($this->components as $key => $component) {
            $html .= $component->setValue($this->getData($key))->toHtml();
            foreach ($component->toJavascript() as $js) {
                $this->javascriptTransport->append($js);
            }
        }

        $close = $this->form ? FormFacade::close() : null;

        $this->formTransport->append(
            $code = $this->form . $html . $close,
            $this->id
        );

        if ($flush) {
            $this->formTransport->flush($this->id);
        }

        if ($this->validation) {
            $this->javascriptTransport->append($this->validationScript());
        }

        return $code;
    }
}
