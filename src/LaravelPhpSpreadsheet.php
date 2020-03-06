<?php

namespace Remember\LaravelPhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaravelPhpSpreadsheet
{
    protected $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    /**
     * @param string $param
     * @return bool
     */
    public function hasParam(string $param): bool
    {
        return array_key_exists($param, config('laravel-phpSpreadsheet.columns'));
    }

    /**
     * @param $param
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * 初始化一些参数
     */
    public function init($param)
    {
        //设置默认全局垂直水平居中
        $this->spreadsheet->getDefaultStyle()->applyFromArray($this->alignmentConfig());
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle(config("laravel-phpSpreadsheet.columns.$param.title"));

        $linNames = config("laravel-phpSpreadsheet.columns.$param.lineName");
        $line = 1;

        collect($linNames)->map(function ($name) use (&$sheet, &$line) {
            $sheet->setCellValueByColumnAndRow($line, 1, $name);
            // $sheet->getColumnDimensionByColumn()->setAutoSize();
            $line++;
        });

        return $sheet;
    }


    public function saveFile($param, array $data)
    {
        $this->makeSheet($param, $data);
        $writer = new Xlsx($this->spreadsheet);
        $file_name = date('Y-m-d', time()) . rand(1000, 9999);
        $file_name = '../' . $file_name . ".xlsx";
        $writer->save($file_name);

    }

    public function downloadFile($param, array $data)
    {
        $this->makeSheet($param, $data);
        $fileName = '01simple.xlsx';

        $file_name = date('Y-m-d', time()) . rand(1000, 9999);
//第二种直接页面上显示下载
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
//注意createWriter($spreadsheet, 'Xls') 第二个参数首字母必须大写
        $writer->save('php://output');
        exit();
    }


    public function makeSheet(string $param, array $data)
    {
        if (false === $this->hasParam($param)) {
            echo 1111;
        };
        $sheet = $this->init($param);
        $row = 2;
        $line = 1;
        collect($data)->map(function ($res) use (&$line, &$row, &$sheet) {
            $sheet->setCellValueByColumnAndRow($line, $row, $res['name']);
        });

        return $sheet;
    }

    /**
     * 设置字体区间的字体
     */
    public static function setFontCell($sheet, string $cell)
    {
        $sheet->getStyle($cell)->getFont()
            ->setBold(true)->setName('Arial');
        return $sheet;
    }

    /**
     * 设置宽度 默认 8
     * 有些单元格要长一点额外设置
     */
    public static function setWidth($sheet)
    {
        //需要额外定长的宽
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(40);
        return $sheet;
    }

    /**
     * @return array
     * 水平垂直居中配置
     */
    public function alignmentConfig(): array
    {
        return [
            config('laravel-phpSpreadsheet.alignment')
        ];
    }
}
