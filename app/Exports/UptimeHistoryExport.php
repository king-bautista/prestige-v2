<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UptimeHistoryExport implements FromCollection, WithHeadings
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
            'Screen Name',
            'Date',
            'Total Hours Up',
            'Opening Hour',
            'Closing Hour',
            'Max hours up',
            '% Uptime'
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
                'name' => $report->name, 
                'up_time_date' => $report->up_time_date,
                'total_hours' => $report->total_hours,
                'opening_hour' => $report->opening_hour,
                'closing_hour' => $report->closing_hour,
                'hours_up' => $report->hours_up,
                'percentage_uptime' => $report->percentage_uptime,
            ];
        }

        return collect($responseData);
    }
}
