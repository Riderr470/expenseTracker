<?php

return [
    'widgets' => [

        'bar_chart' => [
            'label' => 'Bar Chart',

            'default' => [
                'data_source' => 'orders',
                'x_field' => 'date',
                'y_field' => 'total',
            ],

            'fields' => [
                'data_source',
                'x_field',
                'y_field',
            ],
        ],

        'pie_chart' => [
            'label' => 'Pie Chart',

            'default' => [
                'data_source' => 'orders',
                'label_field' => 'category',
                'value_field' => 'total',
            ],

            'fields' => [
                'data_source',
                'label_field',
                'value_field',
            ],
        ],

    ],
];