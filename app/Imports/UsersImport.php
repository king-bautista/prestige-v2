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

use App\Models\User;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $data = [
                'email' => $row['email'],
                'full_name' => $row['full_name'],
                'password' => $row['password'],
                'admin_role_id' => $this->roles($row['admin_role_id']), 

            ];
             echo '<pre>'; print_r($data); echo '</pre>';
            // if ($row['name']) {
            //     $module = User::updateOrCreate(
            //         [
            //             'name' => $row['name'],
            //             'role' => $row['role'],
            //             'link' => $this->link($row['link']),
            //         ],
            //         [
            //             'parent_id' => $row['parent_id'],
            //             'class_name' => $row['class_name'],
            //             'active' => $row['active'],
            //         ],
            //     );
            // }
        }
    }

    public function roles($roles)
    {
        $admin_role = [];
        foreach ($roles as $role) {
            $admin_role[] = $role->id;
        }
        return implode(",",$admin_role);
    }
}
