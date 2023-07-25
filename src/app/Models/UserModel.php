<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Database\Factories\UserModelFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Lumen\Auth\Authorizable;

class UserModel extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
        'active',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    protected $casts
        = [
            'type' => UserTypeEnum::class,
            'document_id' => 'integer',
            'active' => 'boolean',
        ];

    protected static function newFactory(): Factory
    {
        return UserModelFactory::new();
    }

    public function document(): HasOne
    {
        return $this->hasOne(DocumentModel::class, 'user_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(WalletModel::class, 'user_id');
    }
}