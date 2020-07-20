<?php

return [
    'coupe' => [
        'code'        => 'coupe',
        'title'       => 'Coupe',
        'description' => 'COUPE Shipping',
        'active'      => true,
        'type'        => 'per_unit',
        'class'       => 'DFM\Shipping\Carriers\Coupe',
    ],

    'leleu' => [
        'code'        => 'leleu',
        'title'       => 'Leleu',
        'description' => 'Leleu Shipping',
        'active'      => true,
        'type'        => 'per_order',
        'class'       => 'DFM\Shipping\Carriers\Leleu',
    ],
];
