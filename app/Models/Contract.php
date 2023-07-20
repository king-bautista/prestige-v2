<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'company_id',
        'name',
        'reference_code',
        'remarks',
        'is_indefinite',
        'is_exclusive',
        'display_duration',
        'slots_per_loop',
        'exposure_per_day',
        'start_date',
        'end_date',
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
    protected $table = 'contracts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveBrands($brands)
    {
        if(count($brands) > 0) {
            ContractBrand::where('contract_id', $this->id)->delete();

            foreach ($brands as $data) {
                ContractBrand::updateOrCreate(
                    [
                       'contract_id' => $this->id,
                       'brand_id' => $data['id'],
                    ],
                );
            }
        }
    }

    public function saveScreens($screens)
    {
        ContractScreen::where('contract_id', $this->id)->delete();

        if(count(array_keys($screens)) > 1) {
            ContractScreen::updateOrCreate(
                [
                   'contract_id' => $this->id,
                   'site_screen_id' => $screens['id'],
                   'site_id' => $screens['site_id'],
                   'product_application' => $screens['product_application'],
                ],
            );

            return true;
        }

        foreach ($screens as $data) {
            ContractScreen::updateOrCreate(
                [
                'contract_id' => $this->id,
                'site_screen_id' => $data['id'],
                'site_id' => $data['site_id'],
                'product_application' => $data['product_application'],
                ],
            );
        }
    }
}
