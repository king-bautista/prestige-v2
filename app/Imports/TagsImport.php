<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Tag;

class TagsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if($row['name']) {
                $tags = Tag::updateOrCreate(
                    [
                        'name' => ucfirst($row['name'])
                    ],
                    [
                        'active' => 1
                    ],

                );
            }
	    }
    }
}
