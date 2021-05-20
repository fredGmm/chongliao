<?php

namespace app\helpers\excel;

use \Yii;
use \Exception;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Worksheet;
use \PHPExcel_Cell;
use \PHPExcel_Exception;
use \PHPExcel_Reader_Exception;

class ExcelReader
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
     * @var array
     */
    public $fieldMap = [];

    /**
     * ExcelHandle constructor.
     * @param $filename
     * @param $fieldMap
     * @throws Exception
     */
    public function __construct($filename, $fieldMap = [])
    {
        $this->objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->fieldMap = $fieldMap;
        $this->openFile($filename);
        $this->getSheet();
    }

    /**
     * @param $filename
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws Exception
     */
    public function openFile($filename)
    {
        try {
            if (strtolower(substr($filename, -5)) == '.xlsx') {
                $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Excel2007');
            } elseif (strtolower(substr($filename, -4)) == '.xls') {
                $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Excel5');
            } elseif (strtolower(substr($filename, -4)) == '.csv') {
                $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('CSV');
            } else {
                throw new Exception('文件格式错误');
            }
        } catch (Exception $e) {
            throw new Exception('文件已损坏');
        }
        try {
            $objPHPExcel = $objReader->load($filename);
        } catch (PHPExcel_Reader_Exception $e) {
            throw new Exception('文件读取错误');
        }
        $this->objPHPExcel = $objPHPExcel;
        return $objPHPExcel;
    }

    /**
     * @param int $index
     * @return PHPExcel_Worksheet
     * @throws Exception
     */
    public function getSheet($index = 0)
    {
        $objPHPExcel = $this->objPHPExcel;
        try {
            $sheet = $objPHPExcel->getSheet($index);
        } catch (Exception $e) {
            throw new Exception('文件打开错误');
        }
        $this->activeSheet = $sheet;
        return $sheet;
    }

    /**
     * @return array
     */
    public function readHeader()
    {
        $sheet = $this->activeSheet;

        $rowIterator = $sheet->getRowIterator();
        $firstRow = $rowIterator->current();

        $cellIterator = $firstRow->getCellIterator();
        $header = [];
        foreach ($cellIterator as $cell) {
            /* @var $cell PHPExcel_Cell */
            $header[$cell->getColumn()] = trim($cell->getValue());
        }

        return $header;
    }


    public function readBody($limit = null)
    {
        $sheet = $this->activeSheet;

        // 行迭代器初始化
        $endRow = $limit ? $limit : null;
        $headerCount = 1;
        $rowIterator = $sheet->getRowIterator( 1 + $headerCount, $endRow + $headerCount);

        $data = [];
        foreach ($rowIterator as $key => $row) {
            $cellIterator = $row->getCellIterator();

            $row = [];
            foreach ($cellIterator as $cell) {
                /* @var $cell PHPExcel_Cell */
                $row[$cell->getColumn()] = trim($cell->getValue());
            }

            // key 为键(行号)
//            $row['key'] = $key;
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @return ExcelReaderIterator
     */
    public function readBodyIterator()
    {
        $sheet = $this->activeSheet;

        // 行迭代器初始化
        $rowIterator = $sheet->getRowIterator();
        $iterator = new ExcelReaderIterator($rowIterator, $this->fieldMap);

        $iterator->rewind();
        $iterator->next();

        return $iterator;
    }

    /**
     * @return string
     */
    public function getHighestDataRow()
    {
        $sheet = $this->activeSheet;

        return $sheet->getHighestDataRow();
    }

}
