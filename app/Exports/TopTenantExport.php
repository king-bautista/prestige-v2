<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TopTenantExport implements FromCollection, WithHeadings
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
            'Brand Name',
            'Category',
            'Tenant Count',
            '% Total Over Category',
            '% Total Over Tenant',
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
                'brand_name' => $report->brand_name,
                'main_category_name' => $report->main_category_name, 
                'tenant_count' => $report->tenant_count,
                'category_percentage' => $report->category_percentage,
                'tenant_percentage' => $report->tenant_percentage
            ];
        }

        return collect($responseData);
    }
}
