<?php
namespace App\Services;

class CsvTestService
{
    public function getCsv()
    {
        $list = [
            ['aaa', 'bbb', 'ccc', 'dddd'],
            ['123', '456', '789'],
            ['"aaa"', '"bbb"']
        ];

        $file = new \SplFileObject(storage_path('csv/file.csv'), 'w');

        foreach ($list as $fields) {
            $file->fputcsv($fields);
        }

        $headers = [
            'Content-Type' => 'text/plain',
            'content-Disposition' => 'attachment; filename="'.$file.'"',
        ];

        return response()
            //->view('', $list, 200)
            ->download($file, 'hoge.csv', $headers);
    }
}
