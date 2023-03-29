<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IsHelpfulExport implements FromCollection, WithHeadings
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
            'Is Helpful',
            'Reason',
            'Other Reason'
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
                'site_name' => $report['site_name'], 
                'helpful' => $report['helpful'], 
                'reason' => $report['reason'],
                'reason_other' => $report['reason_other']
            ];
        }

        return collect($responseData);
    }
}
