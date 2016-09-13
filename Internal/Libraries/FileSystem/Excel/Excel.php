<?php namespace ZN\FileSystem;

use CallController;
use ZN\FileSystem\Exception\FileNotFoundException;

class InternalExcel extends CallController implements ExcelInterface
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Telif Hakkı: Copyright (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------
    // Array To XLS
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array  $data
    // @param string $file
    //
    //--------------------------------------------------------------------------------------------------------
    public function arrayToXLS(Array $data, String $file = 'excel.xls')
    {
        $file = suffix($file, '.xls');

        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Type: application/vnd.ms-excel;");
        header("Pragma: no-cache");
        header("Expires: 0");

        if( ! empty($this->rows) )
        {
            $data = $this->rows;
            $this->rows = NULL;
        }

        $output = fopen("php://output", 'w');

        foreach( $data as $column )
        {
            fputcsv($output, $column, "\t");
        }

        fclose($output);
    }

    //--------------------------------------------------------------------------------------------------------
    // CSV To Array
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $file
    //
    //--------------------------------------------------------------------------------------------------------
    public function CSVToArray(String $file) : Array
    {
        $file = suffix($file, '.csv');

        if( ! is_file($file) )
        {
            throw new FileNotFoundException($file);
        }

        $row  = 1;
        $rows = [];

        if( ( $resource = fopen($file, "r") ) !== false )
        {
            while( ($data = fgetcsv($resource, 1000, ",")) !== false )
            {
                $num = count($data);

                $row++;
                for( $c = 0; $c < $num; $c++ )
                {
                    $rows[] = explode(';', $data[$c]);
                }
            }

            fclose($resource);
         }

         return $rows;
    }
}
