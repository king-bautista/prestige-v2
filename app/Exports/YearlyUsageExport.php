<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class YearlyUsageExport implements FromCollection, WithHeadings
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
            'Site Name',
            'Total',
            'Average'
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
                'site_name' => $report->site_name, 
                'total_count' => $report->total_count,
                'total_average' => $report->total_average
            ];
        }

        return collect($responseData);
    }
}
