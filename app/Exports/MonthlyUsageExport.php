<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonthlyUsageExport implements FromCollection, WithHeadings
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
            'Merchant Usage',
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
            'Total',
            'Monthly Ave.'
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
                'page' => $report->page, 
                'jan_count' => $report->jan_count, 
                'feb_count' => $report->feb_count, 
                'mar_count' => $report->mar_count, 
                'apr_count' => $report->apr_count, 
                'may_count' => $report->may_count, 
                'jun_count' => $report->jun_count, 
                'jul_count' => $report->jul_count,
                'aug_count' => $report->aug_count,
                'sep_count' => $report->sep_count,
                'oct_count' => $report->oct_count,
                'nov_count' => $report->nov_count,
                'dec_count' => $report->dec_count,
                'total_count' => $report->total_count,
                'ave_count' => $report->ave_count
            ];
        }

        return collect($responseData);
    }
}
