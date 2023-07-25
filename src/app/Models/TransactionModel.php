<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable 
        = [
            'token',
            'status',
            'amount',
            'payer_id',
            'payee_id',
        ];

    protected $casts
        = [
            'status' => TransactionStatusEnum::class,
            'payer_id' => 'integer',
            'payee_id' => 'integer',
        ];
}