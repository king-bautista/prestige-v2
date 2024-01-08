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

use App\Models\Site;

class SitesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['name']) {
                $site = Site::updateOrCreate(
                    [
                        'name' => $row['name'],
                        'descriptions' => $row['descriptions'],
                    ],
                    [
                        'site_logo' =>  substr(parse_url($row['site_logo'])['path'], 1),
                        'site_banner' => substr(parse_url($row['site_banner'])['path'], 1),
                        'site_background' => substr(parse_url($row['site_background'])['path'], 1),
                        'site_background_portrait' => substr(parse_url($row['site_background_portrait'])['path'], 1),
                        'is_default' => ($row['is_default'] == 1) ? 1 : 0,
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ]
                );

                $meta_value = [
                    'company_id' => ($row['company_id']) ? $row['company_id'] : null,
                    'facebook' => ($row['facebook']) ? $row['facebook'] : null,
                    'instagram' => ($row['instagram']) ? $row['instagram'] : null,
                    'twitter' => ($row['twitter']) ? $row['twitter'] : null,
                    'website' => ($row['website']) ? $row['website'] : null,
                    'premiere' => $this->checkBolean($row['premiere']),
                    'multilanguage' => $this->checkBolean($row['multilanguage']),
                    'site_code' => $row['site_code']
                ];
                
                if (empty($site->serial_number))
                    $site->serial_number = 'ST-' . Str::padLeft($site->id, 5, '0');
                $site->save();
                $site->saveMeta($meta_value);
            }
        }
    }

    public function checkBolean($bolean)
    {
        switch (strval($bolean)) {
            case "true":
                return true;
                break;
            case "1":
                return true;
                break;
            case "false":
                return false;
                break;
            case "0":
                return false;
                break;
            default:
                return false;
                break;
        }
    }
}
