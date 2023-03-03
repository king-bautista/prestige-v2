<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classification_id',
        'parent_id',
        'name',
        'email',
        'contact_number',
        'address',
        'tin',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'companies';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveBrands($brands)
    {
        if(count($brands) > 0) {
            CompanyBrands::where('company_id', $this->id)->delete();

            foreach ($brands as $index => $data) {
                CompanyBrands::updateOrCreate(
                    [
                       'company_id' => $this->id,
                       'brand_id' => $data['id'],
                    ],
                );
            }
        }
    }
}
