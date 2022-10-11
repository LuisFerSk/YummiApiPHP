<?php

require_once "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Writer\Xlsx};

class Excel
{
    static public function normalizeQueryData($queryData)
    {
        $normalizedData = [];

        $array = [];

        foreach ($queryData[0] as $key => $value) {
            array_push($array, $key);
        }

        array_push($normalizedData, $array);

        foreach ($queryData as $data) {
            $array = [];
            foreach ($data as $value) {
                array_push($array, $value);
            }
            array_push($normalizedData, $array);
        }

        return $normalizedData;
    }
    static public function generateExcel($nameFile, $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($nameFile);

        $sheet->fromArray($data, null, 'A1');

        $writer = new Xlsx($spreadsheet);
        $writer->save($nameFile . '.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nameFile . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
