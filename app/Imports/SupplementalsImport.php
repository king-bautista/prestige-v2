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

class SupplementalsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['name']) {
                $brand = Category::updateOrCreate(
                    [
                        'name' => $row['name']
                    ],
                    [
                        'parent_id' => ($row['parent_name']) ? $this->getParentCategoryId($row['parent_name']) : null,
                        'supplemental_category_id' => ($row['supplemental_category_name']) ? $this->getSupplementalCategoryId($row['supplemental_category_name']) : null,
                        'descriptions' => $row['descriptions'],
                        'class_name' => $row['class_name'],
                        'category_type' => $row['category_type'],
                        'active' => $row['active'],
                    ]
                );
            }
        }
    }

    public function getParentCategoryId($category = '')
    {
        if($category) {
            $category_id = Category::where('name', 'like', '%'.rtrim(ltrim($category)).'%')->where('category_type', 1)->whereNULL('parent_id')->first();
            if($category_id)
                return $category_id['id'];
            return 0;
        }

        return 0;
    }

    public function getSupplementalCategoryId($category = '')
    {
        if($category) {
            $category_id = Category::where('name', 'like', '%'.rtrim(ltrim($category)).'%')->where('category_type', 2)->whereNull('parent_id')->first();
            if($category_id)
                return $category_id['id'];
            return 0;
        }

        return 0;
    }
}