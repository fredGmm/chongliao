<?php

namespace app\helpers\excel;

use \Yii;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Worksheet;
use \PHPExcel_Cell;
use \PHPExcel_Exception;
use \PHPExcel_Reader_Exception;
use \PHPExcel_Writer_Exception;
use yii\db\Query;

class ExcelWriter
{

    /**
     * @var PHPExcel $objPHPExcel
     */
    public $objPHPExcel;

    /**
     * @var PHPExcel_Worksheet $activeSheet
     */
    public $activeSheet;

    /**
     * @var array $columnIndex
     */
    public $columnIndex;


    public $excelHeader;

    public $lastTimestamp = null;

    /**
     * ExcelHandle constructor.
     * @param $excel_header
     * @throws PHPExcel_Exception
     */
    public function __construct($excel_header = [])
    {
        $this->objPHPExcel = new PHPExcel();
        $this->excelHeader = $excel_header;
        $this->columnIndex = array_flip(array_keys($excel_header));

        $this->activeSheet = $this->objPHPExcel->setActiveSheetIndex(0);
    }

    /**
     */
    public function writeHeader()
    {
        $sheet = $this->activeSheet;

        $excel_header = $this->excelHeader;

        $i = 0;
        foreach ($excel_header as $head) {
            $sheet->setCellValueExplicitByColumnAndRow($i++, 1, $head);
        }
    }

    /**
     * @param $excel_data
     */
    public function writeExcel($excel_data)
    {
        $sheet = $this->activeSheet;
        $columnIndex = $this->columnIndex;

        $headers = $this->excelHeader;
        $rowKey = $sheet->getHighestDataRow();
        foreach ($excel_data as $rowData) {
            $rowKey++;
            foreach ($headers as $column => $header) {
                $columnKey = $columnIndex[$column] ?? null;
                $dataValue = self::getObjectValue($rowData, $column);
                if ($columnKey !== null && $dataValue !== null) {
                    $sheet->setCellValueExplicitByColumnAndRow($columnKey, $rowKey, $dataValue);
                }
            }
        }
    }

    /**
     * @param Query|\yii\elasticsearch\Query $excel_query
     * @param null|callable $callback
     * @param int $timeout
     */
    public function writeExcelQuery($excel_query, $callback = null, $timeout = 1)
    {
        $sheet = $this->activeSheet;
        $columnIndex = $this->columnIndex;

        $headers = $this->excelHeader;
        $rowKey = $sheet->getHighestDataRow();
        foreach ($excel_query->each() as $rowData) {
            $rowKey++;
            foreach ($headers as $column => $header) {
                $columnKey = $columnIndex[$column] ?? null;
                $dataValue = self::getObjectValue($rowData, $column);
                if ($columnKey !== null && $dataValue !== null) {
                    $sheet->setCellValueExplicitByColumnAndRow($columnKey, $rowKey, $dataValue);
                }
            }

            if (time() - $this->lastTimestamp >= $timeout && is_callable($callback)) {
                call_user_func($callback, $rowKey);
                $this->lastTimestamp = time();
            }
        }

        if (is_callable($callback)) {
            call_user_func($callback, $rowKey);
            $this->lastTimestamp = time();
        }
    }

    public static function getObjectValue($data, $columnName)
    {
        // 含有点，递归取值
        $dotPos = strpos($columnName, '.');
        $part1 = substr($columnName, 0, $dotPos);
        Yii::info('start');
        Yii::info($part1);

        Yii::info($dotPos);

        if ($dotPos && isset($data->$part1)) {
            Yii::info($data->$part1);

            $part2 = substr($columnName, $dotPos + 1);
            Yii::info($part2);
            $dataValue = self::getObjectValue($data->$part1, $part2);
            Yii::info($data->$part1);
        } elseif (isset($data->$columnName)) {
            if (is_array($data->$columnName)) {
                $dataValue = implode(',', $data->$columnName);
            } elseif (is_string($data->$columnName) || is_numeric($data->$columnName)) {
                $dataValue = $data->$columnName;
            } else {
                $dataValue = '字段类型异常';
            }
        } elseif (isset($data[$columnName])) {
            if (is_array($data[$columnName])) {
                $dataValue = implode(',', $data[$columnName]);
            } elseif (is_string($data[$columnName]) || is_numeric($data[$columnName])) {
                $dataValue = $data[$columnName];
            } else {
                $dataValue = '字段类型异常2';
            }
        } else {
            $columnName = strtolower(preg_replace('/([A-Z])/', '_$1', $columnName));
            if (isset($data->$columnName)) {
                $dataValue = $data->$columnName;
            } else {
                $dataValue = null;
            }
        }
        return $dataValue;
    }

    /**
     * @param $file_name
     * @param string $type
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     */
    public function saveFile($file_name, $type = 'Excel2007')
    {
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $type);
        $objWriter->save($file_name);
    }

    /**
     * @param $file_name
     * @param string $type
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     */
    public function saveHttp($file_name, $type = 'Excel2007')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $type);
        $objWriter->save('php://output');

    }

}
