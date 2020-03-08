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
     * @return bool
     * @throws InvalidArgumentException
     * check the necessary configuration parameters
     */
    public function checkConfig($param): bool
    {
        if (false === $this->hasParam($param)) {
            throw new InvalidArgumentException("the $param is not in columns");
        }
        $parameters = config("laravel-phpSpreadsheet.columns.$param");
        if (!isset($parameters['fileName']) || !isset($parameters['title']) || !isset($parameters['lineName'])) {
            throw new InvalidArgumentException("please check $param  configuration item ");
        }
        if (!is_array($parameters['lineName'])) {
            throw new InvalidArgumentException('lineName must to be an array');
        }
        return true;
    }


    public function makeSheet(string $param, array $data)
    {
        $this->checkConfig($param);
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
        $sheet->getDefaultColumnDimension()->setWidth(config("laravel-phpSpreadsheet.style.width") ?? 10);
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
        if ($cells && count($cells) > 0) {
            foreach ($cells as $key => $cell) {
                $sheet->getStyle($key)->applyFromArray($cell);
            }
        }
        return $sheet;
    }


    /**
     * save the file then return file url
     */
    public function saveFile($param, array $data)
    {
        $this->makeSheet($param, $data);
        $writer = new Xlsx($this->spreadsheet);
        $file_name = $this->getFileName($param);
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
        $file_name = $this->getFileName($param) . ".xlsx";
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

    /**
     * set some column width
     */
    public function setExtraWidth($sheet)
    {
        $extras = config('laravel-phpSpreadsheet.style.extra-width');
        if ($extras && count($extras) > 0) {
            foreach ($extras as $key => $extra) {
                if (strlen($key) == 1 && preg_match("/^[A-Z]+$/", $key)) {
                    $sheet->getColumnDimension($key)->setWidth($extra);
                }
            }
        }
        return $sheet;
    }

    /**
     * @return array
     * Horizontal and vertical are position
     */
    public function alignmentConfig(): array
    {
        return config('laravel-phpSpreadsheet.style.format') ?? [];
    }


    /**
     * @return int
     * get start line
     */
    public function getStartLine(): int
    {
        return config("laravel-phpSpreadsheet.startLine") > 0 ? config("laravel-phpSpreadsheet.startLine") : 1;
    }

    /**
     * @param $params
     * @return string
     * get file name from config
     */
    public function getFileName($param): string
    {
        return config("laravel-phpSpreadsheet.columns.$param.fileName") . ".xlsx";
    }

    /**
     * @return int
     * get start column
     */
    public function getStartRow(): int
    {
        return config("laravel-phpSpreadsheet.startRow") > 0 ? config("laravel-phpSpreadsheet.startRow") : 1;
    }


    /**
     * @return array
     * Styling cell borders 样式单元格边框
     */
    public function getDefaultStyle(): array
    {
        return config('laravel-phpSpreadsheet.style.border') ?? [];
    }

}
