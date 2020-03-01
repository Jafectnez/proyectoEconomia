<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute debe ser aceptado.',
    'active_url' => ':attribute no es un URL valido.',
    'after' => ':attribute debe estar en una fecha posterior a :date.',
    'after_or_equal' => ':attribute debe estar en una fecha posterior o igual a :date.',
    'alpha' => ':attribute solo puede contener letras.',
    'alpha_dash' => ':attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => ':attribute solo puede contener letras y números.',
    'array' => ':attribute debe ser un arreglo.',
    'before' => ':attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => ':attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => ':attribute debe estar entre :min y :max.',
        'file' => ':attribute debe estar entre :min y :max Kb.',
        'string' => ':attribute debe estar entre :min y :max caracteres.',
        'array' => ':attribute debe tener entre :min y :max items.',
    ],
    'boolean' => ':attribute debe ser verdadero o falso.',
    'confirmed' => 'Confirmacion de :attribute no se encuentra.',
    'date' => ':attribute no es una fecha valida.',
    'date_equals' => ':attribute debe ser una fecha igual a :date.',
    'date_format' => ':attribute no esta en el formato :format.',
    'different' => ':attribute y :other deben ser diferentes.',
    'digits' => ':attribute debe ser digitos :digits .',
    'digits_between' => ':attribute debe estar entre :min y :max digitos.',
    'dimensions' => ':attribute tiene invalidas dimensiones de imagen.',
    'distinct' => ':attribute tiene un dato duplicado.',
    'email' => ':attribute debe ser un correo electrónico valido.',
    'ends_with' => ':attribute debe terminar con: :values',
    'exists' => 'El atributo seleccionado: :attribute es invalido.',
    'file' => ':attribute debe ser un archivo.',
    'filled' => ':attribute debe tener un valor.',
    'gt' => [
        'numeric' => ':attribute debe ser mayor que :value.',
        'file' => ':attribute debe ser mayor que :value Kb.',
        'string' => ':attribute debe ser mayor que :value caracteres.',
        'array' => ':attribute debe tener más de :value items.',
    ],
    'gte' => [
        'numeric' => ':attribute debe ser mayor o igual que :value.',
        'file' => ':attribute debe ser mayor o igual que :value Kb.',
        'string' => ':attribute debe ser mayor o igual que :value caracteres.',
        'array' => ':attribute debe tener :value items o más.',
    ],
    'image' => ':attribute debe ser una imagen.',
    'in' => 'El atributo :attribute es invalido.',
    'in_array' => ':attribute no existen en :other.',
    'integer' => ':attribute debe ser un int.',
    'ip' => ':attribute debe ser una direccion IP valida.',
    'ipv4' => ':attribute debe ser una direccion IPv4 valida.',
    'ipv6' => ':attribute debe ser una direccion IPv6 valida.',
    'json' => ':attribute debe ser una cadena JSON (JSON string) valida.',
    'lt' => [
        'numeric' => ':attribute debe ser menor que :value.',
        'file' => ':attribute debe ser menor que :value Kb.',
        'string' => ':attribute debe ser menor que :value caracteres.',
        'array' => ':attribute debe tener menos de :value items.',
    ],
    'lte' => [
        'numeric' => ':attribute debe ser menor o igual que :value.',
        'file' => ':attribute debe ser menor o igual que :value Kb.',
        'string' => ':attribute debe ser menor o igual que :value caracteres.',
        'array' => ':attribute no debe exceder :value items.',
    ],
    'max' => [
        'numeric' => ':attribute no debe ser exceder :max.',
        'file' => ':attribute no debe ser exceder :max Kb.',
        'string' => ':attribute no debe ser exceder :max caracteres.',
        'array' => ':attribute no debe exceder :max items.',
    ],
    'mimes' => ':attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => ':attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => ':attribute debe tener al menos :min.',
        'file' => ':attribute debe tener al menos :min Kb.',
        'string' => ':attribute debe tener al menos :min caracteres.',
        'array' => ':attribute must have at least :min items.',
    ],
    'not_in' => 'El atributo :attribute es invalido.',
    'not_regex' => ':attribute tiene formato invalido.',
    'numeric' => ':attribute debe ser un número.',
    'present' => ':attribute debe estar presente.',
    'regex' => ':attribute tiene formato invalido.',
    'required' => 'Campo :attribute es obligatorio.',
    'required_if' => 'Campo :attribute obligatorio cuando :other es :value.',
    'required_unless' => 'Campo :attribute es obligatorio a menos que :other esta en :values.',
    'required_with' => 'Campo :attribute es obligatorio cuando :values esta presente.',
    'required_with_all' => 'Campo :attribute es obligatorio cuando :values estan presentes.',
    'required_without' => 'Campo :attribute es obligatorio cuando :values no esta presente.',
    'required_without_all' => 'Campo :attribute es obligatorio cuando ninguno de :values estan presentes.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'numeric' => ':attribute debe ser de :size.',
        'file' => ':attribute debe ser de :size Kb.',
        'string' => ':attribute debe ser de :size caracteres.',
        'array' => ':attribute debe contener :size items.',
    ],
    'starts_with' => ':attribute debe iniciar con: :values',
    'string' => ':attribute debe ser un string.',
    'timezone' => ':attribute debe ser una zona valida.',
    'unique' => ':attribute ya se ha tomado.',
    'uploaded' => ':attribute no se pudo cargar.',
    'url' => ':attribute tiene un formato invalido.',
    'uuid' => ':attribute debe ser un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
