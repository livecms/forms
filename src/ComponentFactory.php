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

    protected function hasMediaLibrary()
    {
        return class_exists(\LiveCMS\MediaLibrary\MediaLibraryController::class);
    }

    protected function getComponent($type)
    {
        $available = [
            'hidden' => \LiveCMS\Form\Components\Hidden::class,
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
            'button' => \LiveCMS\Form\Components\Button::class,
            'link' => \LiveCMS\Form\Components\Link::class,
            'link-button' => \LiveCMS\Form\Components\LinkButton::class,
            'image-midia' => $this->hasMediaLibrary() ? \LiveCMS\Form\Components\ImageMidia::class : \LiveCMS\Form\Components\Image::class,
            'tinymce' => \LiveCMS\Form\Components\TinyMCE::class,
            'tinymce-midia' => $this->hasMediaLibrary() ? \LiveCMS\Form\Components\TinyMCEMidia::class : \LiveCMS\Form\Components\TinyMCE::class,
        ];

        $components = array_replace($available, config('form.components', []));

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
