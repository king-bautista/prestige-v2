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

use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;

class FloorsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $site_id = session()->get('site_id');
        foreach ($rows as $row) {
            
            //if ($this->floor($site_id, $row['site_building_id']) > 0) {
                $floor = SiteBuildingLevel::updateOrCreate(
                    [
                        'site_id' => $row['site_id'],
                        'site_building_id' => $row['site_building_id'],
                        'name' => $row['name'],
                    ],
                    [
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ]
                );
            //}
        }
    }

    public function floor($site_id, $site_building_id)
    {
        return SiteBuilding::where('site_id', $site_id)
            ->where('id', $site_building_id)
            ->count();
    }
}
