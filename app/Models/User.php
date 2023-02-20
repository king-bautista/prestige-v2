<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'email_verified_at',
        'password',
        'api_token',
        'salt',
        'login_attempt',
        'is_blocked',
        'active',
        'activation_token',
        'created_by',
        'updated_by',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'salt',
        'remember_token',
        'login_attempt',
        'is_blocked',
        'is_active',
        'activation_token',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static function getSalt($email)
    {
        $user_user = static::where('email', '=', $email)->first();
        return $user_user->salt;
    }

    public function saveMeta($meta_data)
    {
        foreach ($meta_data as $key => $data) {
            UserMeta::updateOrCreate(
                [
                   'user_id' => $this->id,
                   'meta_key' => $key
                ],
                [
                   'meta_value' => $data,
                ],
            );
        }
    }

    public function saveRoles($roles)
    {
        UserRoles::where('user_id', $this->id)->delete();
        foreach ($roles as $key => $role) {
            UserRoles::updateOrCreate(
                [
                   'user_id' => $this->id,
                   'role_id' => $role['id']
                ]
            );
        }
    }
}
