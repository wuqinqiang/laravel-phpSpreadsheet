<?php

namespace Remember\LaravelPhpSpreadsheet;

use http\Exception\InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LaravelPhpSpreadsheet
{
    protected $spreadsheet;

    public function __construct(Spreadsheet $spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
    }

    public function hasParam(string $param)
    {

    }

    public function init()
    {
        $this->spreadsheet->getActiveSheet();
    }


    public function makeSheet(string $param, array $data)
    {
        if (false === $this->init($param)) {
            throw new InvalidArgumentException('参数不在那里面');
        }
        //设置默认全局垂直水平居中
        $spreadsheet->getDefaultStyle()->applyFromArray(self::alignmentConfig());
        $sheet = $spreadsheet->getActiveSheet();
        //标题
        $sheet->setTitle('通仙订单');
        $sheet->setCellValue('A' . 1, '订单编号');
        $sheet->setCellValue('B' . 1, '商品名称');
        $sheet->setCellValue('C' . 1, '商品规格');
        $sheet->setCellValue('D' . 1, '实付金额');
        $sheet->setCellValue('E' . 1, '买家信息');
        $sheet->setCellValue('F' . 1, '买家留言');
        $sheet->setCellValue('G' . 1, '下单时间');
        $sheet->setCellValue('H' . 1, '收货人信息');
        $sheet->setCellValue('I' . 1, '订单状态');
        //设置第一行(标题)字体样式
        $sheet = self::setFontCell($sheet, "A1:I1");
        //设置表格的宽度
        $sheet = self::setWidth($sheet);
        //默认全部都水平垂直居中
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
    public static function alignmentConfig(): array
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ];
    }
}
