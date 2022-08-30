<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Brand extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'descriptions',
        'logo',
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
    protected $table = 'brands';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function saveSupplementals($supplementals)
    {

        if($supplementals) {

            BrandSupplemental::where('brand_id', $this->id)->delete();

            $supplenmetals_ids =  explode(',',$supplementals);
            foreach ($supplenmetals_ids as $index => $data) {
                BrandSupplemental::updateOrCreate(
                    [
                       'brand_id' => $this->id,
                       'supplemental_id' => $data,
                    ],
                );
            }
        }
    }

    public function saveTags($tags)
    {
        if($tags) {

            BrandTag::where('brand_id', $this->id)->delete();

            $tag_ids =  explode(',',$tags);
            foreach ($tag_ids as $index => $data) {
                BrandTag::updateOrCreate(
                    [
                       'brand_id' => $this->id,
                       'tag_id' => $data,
                    ],
                );
            }
        }
    }

}
