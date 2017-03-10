# Helper.Validate Module
a core validator module for `codeHive 3 Framework`.

## Installation : 
you can simply install it from hive command line as :

`hive install Helper.Validate`


## Usage : 
initialize Asset Helper Module

```php
use App\Helper\Validate;
$validate = new Validate;

// as invokable
$validate($request->all, [
    'name'      => 'required|min:10|max:255|alpha',
    'email'     => 'required|email',
    'password'  => 'required|between:4:16',
    'country'   => 'required'
], [
    'name.required'    => 'The name is required.',
    'name.alpha'        => 'We only accept alphabet characters for name.',
    'email.required'    => 'The email is required.',
    'email.email'       => 'Please enter a valid email address.',
]);

// as function
$my_validator = $validate->check($request, $fields, $message);

// you can make custom messages from  message function or by 3d argument in check or invoke
$validate->messages($messages);

```

and you can access the validator from any controller as IoC
```php

$this->ValidateHelper($request, $fields, $message);

// or inverse
$this->HelperValidate($request, $fields, $message);

```

###Available Validation Rules

`required` : check if field required

`isset` : check if field is sent

`min` : minimal length

`max` : maximum length

`between` : length bettween 2 values usage(between:1:10)

`exact-length` : check if the length the same

`equals` : is value equal to your value usage(equals:helloWorld)

`equal-one` :  is value equals to one of 2 values usage(equal-one:hello:hi)

`length-one` : is length equals to 2 numbers usage(length-one:5,8)

`gt` : greater than

`gte` : greater than or equal to

`lt` : less than

`lte` : less than or equal to

`range` : check is number withen the range usage(range:5:10)

`numeric` : is value a number or float

`integer` : is value integer

`float` : is value float

`string` : is value string

`alpha` : value should have only alphabet characters

`alpha-numeric` : value accept alphabet and numbers

`email` : is valid email

`ip` : is valid ip address

`url` : is valid web link 



## License
Copyright (c) 2013 - 2017, Purecis, Inc. All rights reserved.

This Module is part of codeHive framework and its open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
