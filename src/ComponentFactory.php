<?php

namespace LiveCMS\Form;

use Exception;

class ComponentFactory
{
    protected $class;

    public function __construct($type = null)
    {
        if ($type) {
            $this->class = $this->getComponent($type);
            return $this;
        }
    }

    protected function getComponent($type)
    {
        $available = [
            'text' => \LiveCMS\Form\Components\Text::class,
            'textarea' => \LiveCMS\Form\Components\Textarea::class,
            'number' => \LiveCMS\Form\Components\Number::class,
            'checkbox' => \LiveCMS\Form\Components\Checkbox::class,
            'email' => \LiveCMS\Form\Components\Email::class,
            'image' => \LiveCMS\Form\Components\Image::class,
            'money' => \LiveCMS\Form\Components\Money::class,
            'password' => \LiveCMS\Form\Components\Password::class,
            'radio' => \LiveCMS\Form\Components\Radio::class,
            'select' => \LiveCMS\Form\Components\Select::class,
            'select2' => \LiveCMS\Form\Components\Select2::class,
            'separator' => \LiveCMS\Form\Components\Separator::class,
            'tel' => \LiveCMS\Form\Components\Tel::class,
            'boolean' => \LiveCMS\Form\Components\Boolean::class,
        ];

        $components = array_replace($available, config('form.component.available', []));

        if (isset($components[$type])) {
            return $components[$type];
        }

        throw new Exception("Component class \"{$type}\" not found", 1);
        
    }

    public function make($name, array $setting)
    {
        $class = $this->class;
        return new $class($name, $setting);
    }

    public static function create($name, array $setting)
    {
        $instance = new static($setting['type'] ?? null);
        unset($setting['type']);
        return $instance->make($name, $setting);
    }
}
