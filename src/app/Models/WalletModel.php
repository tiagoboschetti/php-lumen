<?php

namespace App\Models;

use Database\Factories\WalletModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable 
        = [
            'available_balance',
            'user_id',
        ];

    protected $casts
        = [
            'user_id' => 'integer',
        ];


    protected static function newFactory(): Factory
    {
        return WalletModelFactory::new();
    }
    // relationships
    public function owner(): HasOne
    {
        return $this->hasOne(UserModel::class);
    }
}