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
use App\Models\Category;
use App\Models\Site;
use App\Models\Company;
use App\Models\CompanyCategory;

class IllustrationsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $count_category = $this->getCategoryIdSubCategoryId($row['category_id'], $row['sub_category_id']);
            if ($count_category > 0) {
                $module = CompanyCategory::updateOrCreate(
                    [
                        'category_id' => $row['category_id'],
                        'sub_category_id' => $row['sub_category_id'],
                        'kiosk_image_primary' => substr(parse_url($row['kiosk_image_primary'])['path'], 1),
                        'kiosk_image_top' => substr(parse_url($row['kiosk_image_top'])['path'], 1),
                    ],
                    [
                        'company_id' => $this->checkCompanyId($row['company_id']),
                        'site_id' => $this->checkSiteId($row['site_id']),
                        'label' => ($row['label']) ? $row['label'] : null,
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ],
                );
            }
        }
    }
    public function getCategoryIdSubCategoryId($category_id, $sub_category_id = '')
    {
        $category = Category::where('id', $category_id)->get();
        return (($category[0]['category_type'] == 1) && empty($category[0]['supplemental_category_id'])) ? 1 : 0;
    }

    public function checkCompanyId($company_id)
    {
        if($company_id) {
            $id = Company::where('id', $company_id)->first();
            if($id)
                return $id['id'];
            return null;
        }
        return null;
    }

    public function checkSiteId($site_id)
    {
        if($site_id) {
            $id = Site::where('id', $site_id)->first();
            if($id)
                return $id['id'];
            return null;
        }
        return null;
    }
}
