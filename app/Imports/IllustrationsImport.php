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

use App\Models\CompanyCategory;

class IllustrationsImport implements ToCollection, WithHeadingRow
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
            //  echo '<pre>'; print_r($data); echo '</pre>';
            if ($row['name']) {
                $module = CompanyCategory   ::updateOrCreate(
                    [
                        'name' => $row['name'],
                        'role' => $row['role'],
                        'link' => $this->link($row['link']),
                    ],
                    [
                        'parent_id' => $row['parent_id'],
                        'class_name' => $row['class_name'],
                        'active' => $row['active'],
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
