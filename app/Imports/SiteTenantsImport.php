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

use App\Models\SiteTenant;
use App\Models\Brand;
use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;

class SiteTenantsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            dd($row['store_name']);
            if($row['store_name']) {
                $brand_id = ($row['store_name']) ? $this->getBrandId($row['store_name']) : 0;
                $site_id = ($row['mall_name']) ? $this->getSiteId($row['mall_name']) : 0;
                $building_id = ($row['building_name']) ? $this->getBuildingId($site_id, $row['building_name']) : 0;
                $level_id = ($row['level_name']) ? $this->getBuildingLevelId($site_id, $building_id, $row['level_name']) : 0;

                $brand = SiteTenant::updateOrCreate(
                    [
                        'brand_id' => $brand_id,
                        'site_id' => $site_id,
                        'site_building_id' => $building_id,
                        'site_building_level_id' => $level_id
                    ]
                );
            }
	    }
    }

    public function getBrandId($brand)
    {
        if($brand) {
            $brand_id = Brand::where('name', '=', rtrim(ltrim($brand)))->first();
            if($brand_id)
                return $brand_id['id'];
            return 0;
        }

        return 0;
    }

    public function getSiteId($site)
    {
        if($site) {
            $site_id = Site::where('name', 'like', '%'.rtrim(ltrim($site)).'%')->first();
            if($site_id)
                return $site_id['id'];
            return 0;
        }

        return 0;
    }

    public function getBuildingId($site_id, $building)
    {
        if($building) {
            $building_id = SiteBuilding::where('name', 'like', '%'.rtrim(ltrim($building)).'%')
            ->where('site_id', $site_id)
            ->first();
            if($building_id)
                return $building_id['id'];
            return 0;
        }

        return 0;
    }

    public function getBuildingLevelId($site_id, $building_id, $level_name)
    {
        if($site_id && $level_name) {
            $level_name_id = SiteBuildingLevel::where('name', 'like', '%'.rtrim(ltrim($level_name)).'%')
            ->where('site_id', $site_id)
            ->where('site_building_id', $building_id)
            ->first();

            if($level_name_id)
                return $level_name_id['id'];
            return 0;
        }

        return 0;
    }
}
