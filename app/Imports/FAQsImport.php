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

use App\Models\FAQ;

class FAQsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            
           if ($row['question']) {
                $module = FAQ::updateOrCreate(
                    [
                        'question' => $row['question'],
                        'answer' => $row['answer'],
                    ],
                    [
                        'active' => ($row['active'] == 1)? 1:0,
                    ],
                );
            }
        }
    }
}
