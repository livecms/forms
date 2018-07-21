<?php

namespace LiveCMS\Form\Components;

use Collective\Html\FormFacade;

class Select2 extends HtmlComponent
{
    protected function select2Script()
    {
        return <<<HTML

            var makeActive = function (input) {
                let dependencies = input.data('dependencies');
                if (dependencies) {
                    let active = true;
                    dependencies.split(',').forEach(function (item) {
                        if (!$('[name='+item+']').val()) {
                            active = false;
                        }
                    });
                    if (active) {
                        input.prop('disabled', false);
                        const validator = input.closest('form').data('validation');
                        if (validator) {
                            validator.element('#'+input.attr('id'));
                        }
                    } else {
                        input.prop('disabled', true);
                    }
                } else {
                    input.prop('disabled', false);
                }
            }

            var select2 = function (input) {
                let url = input.data('options-url');
                let value = input.data('value');
                let dependencies = input.data('dependencies');
                input.select2({
                    ajax: {
                        url: url+'/search',
                        method: 'POST',
                        data: function (params) {
                            var query = {
                                search: params.term,
                            }
                            if (dependencies) {
                                dependencies.split(',').forEach(function (item) {
                                    query[item] = $('[name='+item+']').val();
                                });
                            }
                            return query;
                        },
                        processResults: function (data) {
                          // Tranforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: data
                            };
                        },
                        cache: true,
                    },
                    allowClear: true,
                }).on('select2:unselect', function () {
                    $(this).valid();
                }).on('change', function (e) {
                    let container = '#select2-' + $(this).attr('id') + '-container';
                    if ($(this).hasClass('error')) {
                        $(this).valid();
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: url + '/' + value
                }).then(function (data) {
                    // create the option and append to Select2
                    var option = new Option(data.name, data.id, true, true);
                    input.append(option).trigger('change');
                });

                if (dependencies) {
                    dependencies.split(',').forEach(function (item) {
                        $('[name='+item+']').on('change', function () {
                            makeActive(input);
                        });
                    });
                } else {
                    input.prop('disabled', false);
                }
            }

            $('[select2-ajax]').prop('disabled', true);

            $('[select2-ajax]').each(function () {
                select2($(this));
            });

            $('.select2-selection--single').css('height', '40px').css('padding', '5px 13px');

            $(window).on('resize', function() {
                $('.form-group').each(function() {
                    var formGroup = $(this),
                        formgroupWidth = formGroup.outerWidth();

                    formGroup.find('.select2-container').css('width', formgroupWidth);

                });
            });

            if ($.validator) {
                $.validator.setDefaults({ 
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
            }
HTML;
    }

    protected function defaultJavascript()
    {
        return $this->select2Script();
    }

    public function render()
    {
        extract($this->params);
        $attributes = $attributes ?? [];
        $attributes['select2-ajax'] = true;
        $attributes['class'] = 'form-control border-input';
        $attributes['placeholder'] = $attributes['data-placeholder'] = ($attributes['placeholder'] ?? __('Please Choose'));
        $attributes['data-value'] = old($name, $value);

        return
            '<div class="form-group" id="'.$name.'__parent">'.
                FormFacade::label($name, $label, ['class' => 'control-label']).
                FormFacade::select($name, [], $value, $attributes).
            '</div>';
    }
}
