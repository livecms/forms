<?php

namespace LiveCMS\Form\Components;

abstract class BaseComponent
{
    protected $params = [
        'name' => null,
        'label' => null,
        'value' => null,
        'default' => null,
        'options' => [],
        'attributes' => [],
    ];
    protected $javascript = [];

    public function __construct($name, array $setting)
    {
        $this->params['name'] = $name;

        $params = array_only($setting, array_keys($this->params));
        $this->params = array_replace_recursive($this->params, $params);

        $additionals = array_except($setting, array_keys($this->params));
        $this->params['attributes'] = array_replace_recursive($this->params['attributes'], $additionals);

        $this->addJavascript($this->defaultJavascript());
        if (isset($setting['javascript'])) {
            $this->addJavascript($setting['javascript']);
        }
    }

    public function setValue($value = null)
    {
        $this->params['value'] = $value ?? $this->params['default'];
        return $this;
    }

    protected function defaultJavascript()
    {
        return null;
    }

    protected function addJavascript($js)
    {
        $this->javascript[] = $js;
        return $this;
    }

    public function toJavascript()
    {
        return $this->javascript;
    }

    public function toHtml()
    {
        return $this->render();
    }

    abstract public function render();
}
