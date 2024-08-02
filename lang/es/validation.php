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

    'accepted'             => ':attribute debe ser aceptado.',
    'accepted_if'          => ':attribute debe ser aceptado cuando :other es :value.',
    'active_url'           => ':attribute no es una URL válida.',
    'after'                => ':attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute solo debe contener letras.',
    'alpha_dash'           => ':attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => ':attribute solo debe contener letras y números.',
    'array'                => ':attribute debe ser una matriz.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => ':attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'array'   => ':attribute debe tener entre :min y :max elementos.',
        'file'    => ':attribute debe estar entre :min y :max kilobytes.',
        'numeric' => ':attribute debe estar entre :min y :max.',
        'string'  => ':attribute debe estar entre :min y :max caracteres.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => ':confirmación del atributo no coincide.',
    'current_password'     => 'La contraseña es incorrecta.',
    'date'                 => ':attribute no es una fecha válida.',
    'date_equals'          => ':attribute debe ser una fecha igual a :date.',
    'date_format'          => ':attribute no coincide con el formato :format.',
    'declined'             => ':attribute debe ser rechazado.',
    'declined_if'          => ':attribute debe rechazarse cuando :other es :value.',
    'different'            => ':attribute y :other deben ser diferentes.',
    'digits'               => ':attribute debe ser :digits digits.',
    'digits_between'       => ':attribute debe estar entre :min y :max dígitos.',
    'dimensions'           => ':attribute tiene dimensiones de imágen no válidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'doesnt_end_with'      => ':attribute no puede terminar con uno de los siguientes: :values.',
    'doesnt_start_with'    => ':attribute no puede comenzar con uno de los siguientes: :values.',
    'email'                => ':attribute debe ser una dirección de correo electrónico válida.',
    'ends_with'            => ':attribute debe terminar con uno de los siguientes: :values.',
    'enum'                 => ':attribute seleccionado no es válido.',
    'exists'               => ':attribute seleccionado no es válido.',
    'file'                 => ':attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute debe tener un valor.',
    'gt'                   => [
        'array'   => ':attribute debe tener más de :elements de valor.',
        'file'    => ':attribute debe ser mayor que :value en kilobytes.',
        'numeric' => ':attribute debe ser mayor que :value.',
        'string'  => ':attribute debe ser mayor que :caracteres de valor.',
    ],
    'gte'                  => [
        'array'   => ':attribute debe tener :elements de valor o más.',
        'file'    => ':attribute debe ser mayor o igual que :value en kilobytes.',
        'numeric' => ':attribute debe ser mayor o igual que :value.',
        'string'  => ':attribute debe ser mayor o igual que :value caracteres.',
    ],
    'image'                => ':attribute debe ser una imágen.',
    'in'                   => ':attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => ':attribute debe ser un número entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'ipv4'                 => ':attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => ':attribute debe ser una dirección IPv6 válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'lt'                   => [
        'array'   => ':attribute debe tener menos de :elements de valor.',
        'file'    => ':attribute debe ser menor que :value kilobytes.',
        'numeric' => ':attribute debe ser menor que :value.',
        'string'  => ':attribute debe ser menor que :caracteres de valor.',
    ],
    'lte'                  => [
        'array'   => ':attribute no debe tener más de :elements de valor.',
        'file'    => ':attribute debe ser menor o igual que :value en kilobytes.',
        'numeric' => ':attribute debe ser menor o igual que :value.',
        'string'  => ':attribute debe ser menor o igual que :caracteres de valor.',
    ],
    'mac_address'          => ':attribute debe ser una dirección MAC válida.',
    'max'                  => [
        'array'   => ':attribute no debe tener más de :máx. elementos.',
        'file'    => ':attribute no debe ser mayor que :max kilobytes.',
        'numeric' => ':attribute no debe ser mayor que :max.',
        'string'  => ':attribute no debe ser mayor que :max caracteres.',
    ],
    'max_digits'           => ':attribute no debe tener más de :máx dígitos.',
    'mimes'                => ':attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => ':attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'array'   => ':attribute debe tener al menos :min elementos.',
        'file'    => ':attribute debe tener al menos :min kilobytes.',
        'numeric' => ':attribute debe ser al menos :min.',
        'string'  => ':attribute debe tener al menos :min caracteres.',
    ],
    'min_digits'           => ':attribute debe tener al menos :min dígitos.',
    'multiple_of'          => ':attribute debe ser un múltiplo de :value.',
    'not_in'               => ':attribute seleccionado no es válido.',
    'not_regex'            => ':formato del atributo no es válido.',
    'numeric'              => ':attribute debe ser un número.',
    'password'             => [
        'letters'       => ':attribute debe contener al menos una letra.',
        'mixed'         => ':attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => ':attribute debe contener al menos un número.',
        'symbols'       => ':attribute debe contener al menos un símbolo.',
        'uncompromised' => ':attribute dado ha aparecido en una fuga de datos. Elija un :attribute diferente.',
    ],
    'present'              => ':campo de atributo debe estar presente.',
    'prohibited'           => 'El campo :attribute está prohibido.',
    'prohibited_if'        => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo :attribute está prohibido a menos que :other esté en :values.',
    'prohibits'            => 'El campo :attribute prohíbe que :other esté presente.',
    'regex'                => ':formato de atributo no es válido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_array_keys'  => 'El campo :attribute debe contener entradas para: :values.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo :attribute es obligatorio cuando se acepta :other.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values ​​está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values ​​no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los :values está presente.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'array'   => ':attribute debe contener :elements de tamaño.',
        'file'    => ':attribute debe ser :tamaño en kilobytes.',
        'numeric' => ':attribute debe ser :tamaño.',
        'string'  => ':attribute debe ser :caracteres de tamaño.',
    ],
    'starts_with'          => ':attribute debe comenzar con uno de los siguientes: :values.',
    'string'               => ':attribute debe ser una cadena.',
    'timezone'             => ':attribute debe ser una zona horaria válida.',
    'unique'               => ':attribute ya ha sido tomado.',
    'uploaded'             => ':attribute no se pudo cargar.',
    'url'                  => ':attribute debe ser una URL válida.',
    'uuid'                 => ':attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
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
    | with something more reader friendly such as 'E-Mail Address' instead
    | of 'email'. This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name'            => 'Nombre',
        'company'         => 'Empresa',
        'whatsapp'        => 'Teléfono Whatsapp',
        'client_id'       => 'Cliente',
        'client_name'     => 'Nombre',
        'client_company'  => 'Empresa',
        'client_whatsapp' => 'Teléfono Whatsapp',
        'mobile_phone'    => 'Teléfono Móvil',
        'phone_number'    => 'Numero de Teléfono',
        'fixed_phone'     => 'Teléfono Fijo',
        'notes'           => 'Notas',
        'title'           => 'Titulo',
        'description'     => 'Descripción',
        'address'         => 'Dirección',
        'make'            => 'Marca',
        'model'           => 'Modelo',
        'plate'           => 'Matrícula',
        'vehicle_id'      => 'Vehiculo',
        'vehicle_make'    => 'Marca',
        'vehicle_model'   => 'Modelo',
        'vehicle_plate'   => 'Matrícula',
        'vehicle_price'   => 'Precio',
        'date_collect'    => 'Fecha Retira',
        'date_return'     => 'Fecha Retorno',
        'time_collect'    => 'Hora Retira',
        'time_return'     => 'Hora Retorno',
        'days_to_charge'  => 'Dias',
    ],

];
