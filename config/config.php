<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'startRow' => 2,
    'startLine' => 1,
    //Generate the cell format
    'columns' => [
        //demo
//        'student' => [
//            'fileName' => 'demo123'.time(),
//            'title' => 'demo1',
//            'lineName' => [
//                '姓名', '年龄', '地址',
//            ],
//
//        ],
//        'teacher' => [
//            'fileName' => 'demo456'.time(),
//            'title' => 'demo2',
//            'lineName' => [
//                '姓名', '年龄', '爱好',
//            ],
//        ]
    ],
    'style' => [
        'format' => [
            //Default horizontal vertical center
            'alignment' => [
                /**
                 * you can set: left right center centerContinuous justify fill
                 */
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ],
        //default cell width
        'width' => 15,
        'extra-width' => [
            'A' => 20,
            'B' => 25,
        ],
        //style cell border
        'border' => [
            'A1:B1' => [
                'borders' => [
                    'outline' => [
                        // Border style
                        // borderStyle you can set:
                        // dashDot dashDotDot dashed dotted double hair medium';
                        // 'mediumDashDot mediumDashDotDot mediumDashed slantDashDot thick thin';
                        'borderStyle' => 'thick',
                        'color' => ['argb' => '######'],
                    ],
                ],

            ],

        ]
    ],

];