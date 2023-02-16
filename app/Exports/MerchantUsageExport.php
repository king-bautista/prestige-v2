<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MerchantUsageExport implements FromCollection, WithHeadings
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
            'Tenant Name',
            'Tenant Category',
            'Search',
            'Category',
            'Banner Ad',
            'Total',
            '% Total Over Category',
            '% Total Over Tenant'
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
                'category_name' => $report->category_name, 
                'search_count' => $report->search_count, 
                'tenant_count' => $report->tenant_count, 
                'banner_count' => $report->banner_count, 
                'total_count' => $report->total_count, 
                'category_percentage' => $report->category_percentage, 
                'tenant_percentage' => $report->tenant_percentage
            ];
        }

        return collect($responseData);
    }
}
