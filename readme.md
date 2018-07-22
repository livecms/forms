# Welcome to Laravel Form Component For Laravel 5.5 or above

## What is ?
This package can make form component (ie. textbox, select, radio, checkbox) based on array of setting.

Besides that, You can also create your own component consist of html and javascript.

## Features
- All benefits of LaravelCollective HTML packages
- Create form and the components based array of settings
- Support Jquery Validation
- Available Components that ready to use :
    - Textbox
    - Telephone
    - Number
    - Money
    - Email
    - Password
    - Textarea
    - Checkbox
    - Radio
    - Select
    - Select2
    - Image
    - Boolean (Radio button with Yes/No options)
- Define your own component
- Define your own validation script

## How to use?

### Install via composer
```shell
composer require livecms/forms
```

### Publish config file :
```shell
php artisan vendor:publish --provider="LiveCMS\Form\FormServiceProvider"
```

Edit 'form.php' config file.

### Creating form :
```php
// controller

use LiveCMS\Form\Forms;

$form = Forms::create()->setComponents([...])->render();

// view
{!! Form::open(['url' => '/your/route']) !!}
{!! $form !!}
{!! Form::close() !!}

<script>
{!! Form::javascript()  !!}
</script>
```

### Set <form></form> in form :
it has same arguments for Form::open() in [LaravelCollective HTML](https://laravelcollective.com/docs/5.2/html#opening-a-form)
```php
// controller
$form = Forms::create(['url' => '/your/route', 'method' => 'PUT', 'class' => 'form-inline'])
            ->setComponents([...])
            ->render();

// view
{!! $form !!}
<script>
{!! Form::javascript()  !!}
</script>
```

### You can create multiple forms and set a name for each form :
```php
// controller
Forms::create([...])->setComponents([...])->setName('form1');
Forms::create([...])->setComponents([...])->setName('form2');

// view
{!! Form::render('form1') !!}
{!! Form::render('form2') !!}

<script>
{!! Form::javascript()  !!}
</script>
```

### Available components :
you can see the files in folder :
```
/vendor/livecms/forms/src/Components
```
- Textbox, type : 'text'
- Telephone, type : 'tel'
- Number, type : 'number'
- Money, type : 'money'
- Email, type : 'email'
- Password, type : 'password'
- Textarea, type : 'textarea'
- Checkbox, type : 'checkbox'
- Radio, type : 'radio'
- Select, type : 'select'
- Select2, type : 'select2'
- Image, type : 'image'
- Boolean, type : 'boolean'

### How to use components :
```php
$components = [
    'comp1' => [
        'type' => 'text', // required
        'label' => null, // optional
        'value' => null, // optional
        'default' => null, // optioal
        'options' => [], // optional and this only for checkbox, radio, select, select2
        'attributes' => [], // optional
    ],
    ...
    'comp-n' => [
        'type' => 'select',
        'options' => [
            'one', 'two', 'three',
        ],
        'attributes' => [
            'required' => true,
            'class' => 'input-select',
        ]
    ]
];

Form::create([...])->setComponents($components)->setName('form1');
```
### Add global properties :
```php
Forms::create([...])
    ->setComponents([...])
    ->addGlobalProperties([
        'required' => '*', // implemented to all components, you can use empty array []
        'class:input-select' => ['province', 'city', 'region'],
        'data-image:landscape' => ['image', 'cover'],
    ])
    ->setName('form1');

// the result all of defined components will get what you write in key of the array
// example for : 'data-image:landscape' => ['image', 'cover']
// the result  :
/**
 * <input type="file" name="image" data-image="landscape" />
 * <input type="file" name="cover" data-image="landscape" />
 */
```

### Add custom component :
- Create a Class, you can extends a class from 'LiveCMS\Form\Components\BaseComponent' class in
    ```
    /vendor/livecms/forms/src/Components/BaseComponent.php
    ```
    Since it contains an abstract method render(), you have to define your own render() method.
    You can also see the example from available components or extends from it. See all files in folder 
    ```
    /vendor/livecms/forms/src/Components
    ```
- Define your custome components in 'form' config file :
    ```
    'components' => [
        'text' => \App\Forms\CustomeTextbox::class,
        'image' => \App\Forms\CustomeImage::class,
    ],
    ```

### Use Validation Script
```php
Forms::create([...])->setComponents([...])->useValidation()->setName('form1');
```
You can disable validation by :
```php
Forms::create([...])->setComponents([...])->useValidation(false)->setName('form1');
```
**Note** : by default, validation script require jquery validation js

### Custom Validation
- Create your own javascript file
- Define it in 'form' config file :
    ```php
        'scripts' => [
            'validation' => '/path/to/javascript/file',
        ],
    ```

### Add custom scripts
- Create your own javascript file
- Define it in 'form' config file, but use other name despite 'validation' because it is reserved only for validation :
    ```php
        'scripts' => [
            'validation' => '/path/to/javascript/file',
            'customscript' => '/path/to/javascript/file',
        ],
    ```
- Call it 
    ```php
    Forms::create([...])->setComponents([...])->addScript('customscript')->setName('form1');
    ```

If you want to cancel adding script, fill second argument with 'false':
```php
->addScript('script_name', false)
```

### Fill form with datas
```php
$components = [
    'name' => ['type' => 'text'],
    'email' => ['type' => 'email'],
];
$datas = ['name' => 'Mokhamad Rofiudin', 'email' => 'mokh@rofiudin.com'];
Form::create([...])
    ->setComponents($components)
    ->fill($datas)
    ->setName('form1')
    ->render();
```

## LICENSE
MIT

## CONTRIBUTING
Fork this repo and make a pull request

## ISSUE AND DISCUSSION
Please create new issue or see the closed issues too.

