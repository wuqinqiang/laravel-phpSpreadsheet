<?php

namespace Remember\LaravelPhpSpreadsheet;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaravelPhpSpreadsheet
{
    protected $spreadsheet;

    public function __construct(Spreadsheet $spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
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
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * initialize cell style ,setTitle ,default width
     */
    public function init($param)
    {
        $this->spreadsheet->getDefaultStyle()->applyFromArray($this->alignmentConfig());
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setStyle($sheet);
        $sheet->setTitle(config("laravel-phpSpreadsheet.columns.$param.title"));
        $sheet->getDefaultColumnDimension()->setWidth(config("laravel-phpSpreadsheet.style.width"));
        $this->setExtraWidth($sheet);
        $startRow = $this->getStartRow();
        $startLine = $this->getStartLine();
        $lineNames = config("laravel-phpSpreadsheet.columns.$param.lineName");
        collect($lineNames)->map(function ($name) use (&$sheet, &$startRow, &$startLine) {
            $sheet->setCellValueByColumnAndRow($startLine, $startRow, $name);
            $startLine++;
        });
        return $sheet;
    }


    /**
     * @param $sheet
     * set default style
     */
    public function setStyle($sheet)
    {
        $cells = $this->getDefaultStyle();
        foreach ($cells as $key => $cell) {
            $sheet->getStyle($key)->applyFromArray($cell);
        }
    }


    /**
     * save the file then return file url
     */
    public function saveFile($param, array $data)
    {
        $this->makeSheet($param, $data);
        $writer = new Xlsx($this->spreadsheet);
        $file_name = config("laravel-phpSpreadsheet.columns.$param.fileName") . ".xlsx";
        $file = config('filesystems.disks.public.root') . '/' . $file_name;
        $writer->save($file);
        $this->disconnect();
        return Storage::disk('public')->url($file_name);
    }

    /**
     * download file
     */
    public function downloadFile($param, array $data)
    {
        $this->makeSheet($param, $data);

        $file_name = date('Y-m-d', time()) . rand(1000, 9999);
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $this->disconnect();
        exit();
    }


    /**
     * 清除
     */
    public function disconnect()
    {
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }


    public function makeSheet(string $param, array $data)
    {
        if (false === $this->hasParam($param)) {
            throw new InvalidArgumentException("the $param is not in columns");
        };
        $sheet = $this->init($param);
        $row = $this->getStartRow() + 1;
        collect($data)->map(function ($res) use (&$row, &$sheet) {
            for ($i = 0; $i < count($res); $i++) {
                $sheet->setCellValueByColumnAndRow($this->getStartLine() + $i, $row, $res[$i]);
            }
            $row++;
        });

        return $sheet;
    }

    /**
     * set some column width
     */
    public function setExtraWidth($sheet)
    {
        $extras = config('laravel-phpSpreadsheet.style.extra-width');
        foreach ($extras as $key => $extra) {
            $sheet->getColumnDimension($key)->setWidth($extra);
        }
        return $sheet;
    }

    /**
     * @return array
     * Horizontal and vertical are position
     */
    public function alignmentConfig(): array
    {
        return config('laravel-phpSpreadsheet.style.format');
    }


    public function getStartLine(): int
    {
        return config("laravel-phpSpreadsheet.startLine") > 0 ? config("laravel-phpSpreadsheet.startLine") : 1;
    }

    public function getStartRow(): int
    {
        return config("laravel-phpSpreadsheet.startRow") > 0 ? config("laravel-phpSpreadsheet.startRow") : 1;
    }

    public function getDefaultStyle(): array
    {
        return config('laravel-phpSpreadsheet.style.border');
    }

}
