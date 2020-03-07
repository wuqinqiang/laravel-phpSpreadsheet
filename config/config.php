<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'startRow' => 2,
    'startLine' => 1,
    'columns' => [
        'student' => [
            'title' => '今天又是元气满满的一天',
            'lineName' => [
                '姓名', '年龄', '地址',
            ],

        ],
        'teacher' => [
            'title' => '今天又是元气满满的一天',
            'lineName' => [
                '姓名', '年龄', '爱好',
            ],
        ]
    ],
    //默认基础样式
    'style' => [
        //格式
        'format' => [
            'alignment' => [
                //默认水平居中
                'horizontal' => 'center',
                //默认垂直居中
                'vertical' => 'center',
            ],
        ],
        'width' => 20,
        'border' => [
            'A1:B1' => [
                'borders' => [
                    'outline' => [
                        //// Border style
                        // 'none';
                        //  'dashDot';
                        //  'dashDotDot';
                        //  'dashed';
                        //   'dotted';
                        //   'double';
                        //   'hair';
                        //   'medium';
                        //   'mediumDashDot';
                        //   'mediumDashDotDot';
                        //   'mediumDashed';
                        //   'slantDashDot';
                        //   'thick';
                        //   'thin';
                        'borderStyle' => 'thick',
                        'color' => ['argb' => '######'],
                    ],
                ],

            ],

        ]
    ],

];