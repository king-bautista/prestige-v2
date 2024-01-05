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

class CategoriesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if ($row['name'] && ($row['category_type'] == 1)) {
                $brand = Category::updateOrCreate(
                    [
                        'name' => $row['name']
                    ],
                    [
                        //'parent_id' => ($row['parent_category']) ? $this->getParentCategoryId($row['parent_category']) : null,
                        'parent_id' => ($row['parent_id']) ? $this->getParentCategoryId($row['parent_id']) : null,
                        'descriptions' => $row['descriptions'],
                        'class_name' => $row['class_name'],
                        'category_type' => $row['category_type'],
                        'active' => ($row['active'] == 1) ? 1 : 0,
                    ]
                );
            }
        }
    }

    public function getParentCategoryId($parent_id = '')
    {
        if ($parent_id) {
            $category_id = Category::where('id', $parent_id)->first();
            if ($category_id)
                return $category_id['id'];
            return 0;
        }

        return 0;
    }
}
