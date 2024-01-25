<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class PlaylistExport implements FromCollection, WithHeadings
{
    public $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }    

    /**
    * Headings
    */
    public function headings(): array
    {
        return [
            'ID',
            'Parent Category',
            'Category Name',
            'Brand Name	',
            'Company Name',
            'Start Date',
            'End Date',
            'Duration',
            'Date Appoved',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $responseData = [];
        foreach ($this->reports as $report)
        {
            $responseData[] = [
                'content_serial_number' => $report['content_serial_number'], 
                'parent_category_name' => $report['parent_category_name'], 
                'category_name' => $report['category_name'],
                'brand_name' => $report['brand_name'],
                'company_name' => $report['company_name'],
                'start_date' => $report['start_date'],
                'end_date' => $report['end_date'],
                'duration' => $report['duration'],
                'updated_at' => $report['updated_at']
            ];
        }

        return collect($responseData);
    }
}
