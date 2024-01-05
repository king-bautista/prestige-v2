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
use App\Models\Landmark;


class LandmarksImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['landmark'] && ($this->site($row['site_id'])> 0)) {
                $landmark = Landmark::updateOrCreate(
                    [
                        'site_id' => $row['site_id'],
                        'landmark' => $row['landmark'],
                        'image_url' =>  substr(parse_url($row['image_url'])['path'], 1),
                        'image_thumbnail_url' =>  substr(parse_url($row['image_thumbnail_url'])['path'], 1),
                    ],
                    [
                        'descriptions' => $row['descriptions'],
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ],
                );
            }
        }
    }
    public function site($site_id)
    {
        return Site::where('id', $site_id)
            ->count();
    }
}
