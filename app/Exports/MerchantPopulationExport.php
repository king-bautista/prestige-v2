<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MerchantPopulationExport implements FromCollection, WithHeadings
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
            'Category',
            'Tenant Count',
            'Percentage Share'
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
                'category_parent_name' => $report['category_parent_name'], 
                'tenant_count' => $report['tenant_count'], 
                'percentage_share' => $report['percentage_share']
            ];
        }

        return collect($responseData);
    }
}
