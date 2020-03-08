# Laravel中快速生成 xls 文件

[![Latest Version on Packagist](https://img.shields.io/packagist/v/remember/laravel-phpspreadsheet.svg?style=flat-square)](https://packagist.org/packages/remember/laravel-phpspreadsheet)
[![Build Status](https://img.shields.io/travis/remember/laravel-phpspreadsheet/master.svg?style=flat-square)](https://travis-ci.org/remember/laravel-phpspreadsheet)
[![Quality Score](https://img.shields.io/scrutinizer/g/remember/laravel-phpspreadsheet.svg?style=flat-square)](https://scrutinizer-ci.com/g/remember/laravel-phpspreadsheet)
[![Total Downloads](https://img.shxields.io/packagist/dt/remember/laravel-phpspreadsheet.svg?style=flat-square)](https://packagist.org/packages/remember/laravel-phpspreadsheet)

The reason for the development of this extension is that the business will often generate data in a specific XLSX format export, according to the daily development of some requirements features.integration of general functions, to achieve the requirements through the minimum configuration, do not need to see the corresponding PhpSpreadsheet package documents.However, for some complex requirements, you still need to check the phpspreadsheet documentation.


### environment
```php
php >7.1
Laravel  6.*
```

## Installation
```bash
composer require remember/laravel-spreadsheet
```
 **If there is a lack of extension, please install the extension first**

Publish configuration
```bash
php artisan vendor:publish --provider="Remember\LaravelPhpSpreadsheet\LaravelPhpSpreadsheetServiceProvider"
```

#### default config
```php

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
//                'name', 'age', 'address',
//            ],
//
//        ],
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
```
## Usage
 if you want  the browser to download the data and save it as an Excel file
```php
$data = [
            ['wuqinqiang', 23, 'HangZhou'],
            ['curry', 25, 'ShangHai'],
            ['Tom', 30, 'ShenZhen']
        ];
app('laravel-phpSpreadsheet')->downloadFile('student', $data);
```
If you want to save the file,then return file path 

```php
$path=app('laravel-phpSpreadsheet')->saveFile('student', $data);
return $path;
```
If your data is taken from the database and the data is not easily populated into the specified column.You can just generate one shelf,then populate it yourself.
                                                                                                 
```php
$users=User::all();
$sheet=app('laravel-phpSpreadsheet')->init('student');
$count = 2;
collect($users)->map(function($user) use($sheet,&$count){
  $sheet->setCellValue('A' . $count, $user->name);
  .......
  .......  balabala
  $count++;
})
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email wuqinqiang050@gmail.com instead of using the issue tracker.

## Credits

- [wuqinqiang](https://github.com/wuqinqiang)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
