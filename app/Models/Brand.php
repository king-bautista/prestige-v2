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
        'thumbnail',
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
        BrandSupplemental::where('brand_id', $this->id)->delete();
        if($supplementals) {
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
        BrandTag::where('brand_id', $this->id)->delete();
        if($tags) {
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

    public function setNameAttribute($name)
    {
        $set_name =  preg_replace('/\//','^', $name);
        $this->attributes['name'] = preg_replace('/\\\\/', '*', $set_name); 
    }
    
    public function getNameAttribute($name)
    {
        $get_name = preg_replace('/\^/','/', $name);
        return preg_replace('/\*/','\\', $get_name);
    }
}
