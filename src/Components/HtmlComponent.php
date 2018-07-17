<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class HtmlComponent extends BaseComponent
{
    public function __construct($name, array $setting)
    {
        parent::__construct($name, $setting);
        $this->addJavascript($this->requiredScript());
    }

    protected function requiredScript()
    {
        return <<<HTML
            $("[required]").each(function () {
                let label = $(this).closest(".form-group").find("label").first();
                let txt = label.text();
                let html = txt + '<sup class="text-danger"><b class="fa fa-asterisk fa-xs"></b></sup>';
                label.html(html);

                if ($(this).is("[type=file]")) {
                    if ($(this).closest("[data-image]").is(".exists")) {
                        $(this).removeAttr("required");
                    }
                }

                if ($(this).is("[data-image]")) {
                    if (!$(this).is(".exists")) {
                        $(this).find(".input-image").prop("required", true);
                    }
                }
            });
HTML;
    }

    public function render()
    {
        //
    }
}
