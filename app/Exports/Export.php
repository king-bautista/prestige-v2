<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Export implements FromCollection, WithHeadings
{
    public $headers;
    public $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
        $this->headers = array_keys($reports[0]);   
    }          
    /**
    * Headings
    */
    public function headings(): array
    {
        $reponseData = [];
        foreach($this->headers as $header){
            $reponseData[] = ucwords(str_replace('_', ' ', $header));
        }
        
        return $reponseData;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->reports);
    }
}
