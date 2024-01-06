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

use App\Models\CustomerCare;

class CustomerCaresImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // $data = [
            //     'name' => $row['name'],
            //     'role' => $row['role'],
            //     'link' => $this->link($row['link'])

            // ];
              //echo '<pre>'; print_r($Row); echo '</pre>';
            if ($row['first_name'] && $row['last_name']) {
                $customer_care = CustomerCare::updateOrCreate(
                    [
                        'status_id' => $row['status_id'],
                        'concern_id' => $row['concern_id'],
                        'user_id' => $row['user_id'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'ticket_subject' => $row['ticket_subject'],
                        'ticket_description' => $row['ticket_description'],
                        'assigned_to_id' => $row['assigned_to_id'],
                        'internal_remark' => $row['internal_remark'],
                        'external_remark' => $row['external_remark'],
                        'image' => $row['image'],
                    ],
                    [
                        'active' => ($row['active'] == 1)? 1:0,
                    ],
                );
                $insert_ticket_id = CustomerCare::find($customer_care->id);
                $customer_care_id = 'TID-' . date("y") . sprintf('%05d', $customer_care->id);

                $ticket_id = ['ticket_id' => $customer_care_id];
                $insert_ticket_id->update($ticket_id);
    
                
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
