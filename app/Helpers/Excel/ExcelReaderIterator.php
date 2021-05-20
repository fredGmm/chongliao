<?php

namespace app\helpers\excel;

use Iterator;
use PHPExcel_Cell;
use PHPExcel_Worksheet_RowIterator;

class ExcelReaderIterator implements Iterator
{
    /**
     * @var PHPExcel_Worksheet_RowIterator $excelRowIterator
     */
    public $excelRowIterator;

    /**
     * @var array $fieldMap
     */
    public $fieldMap = [];


    /**
     * ExcelReaderIterator constructor.
     * @param PHPExcel_Worksheet_RowIterator $excelRowIterator
     * @param array $fieldMap
     */
    public function __construct($excelRowIterator, $fieldMap = [])
    {
        $this->excelRowIterator = $excelRowIterator;
        $this->fieldMap = $fieldMap;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $currentRow = $this->excelRowIterator->current();
        $row = [];
        $rowIndex = $currentRow->getRowIndex();
        foreach ($currentRow->getCellIterator() as $cell) {
            /* @var $cell PHPExcel_Cell */
            $key = $cell->getColumn();
            $value = trim($cell->getValue());
            if (!empty($this->fieldMap)) {
                $newKey = $this->fieldMap[$key] ?? '';
                if ($newKey) {
                    $row[$newKey] = $value;
                }
            } else {
                $row[$key] = $value;
            }
        }
        $row['key'] = $rowIndex - 1;

        return $row;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->excelRowIterator->next();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->excelRowIterator->key();
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->excelRowIterator->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->excelRowIterator->rewind();
        // 把第一行略去
        $this->next();
    }
}
