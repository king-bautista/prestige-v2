<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

use App\Models\Event;

class EventsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {   
        foreach ($rows as $row) {
            
             //echo '<pre>'; print_r($data); echo '</pre>';
            if ($row['event_name']) {
                $module = Event::updateOrCreate(
                    [
                        'site_id' => $row['site_id'],
                        'event_name' => $row['event_name'],
                        'location' => $row['location'],
                        'event_date' => $row['event_date'],
                        'image_url' => $row['image_url'],
                    ],
                    [
                        'start_date' => $row['start_date'],
                        'end_date' => $row['end_data'],
                        'active' => ($row['active'] == 1)? 1:0,
                    ],
                );
            }
        }
    }

    public function link($link = '')
    {
        if ($link != '#') {
            return str_replace((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]", "", $link);
        }
        return '#';
    }
}
